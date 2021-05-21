<?php
class AdminSettings extends CI_controller
{
	function __construct()
	{
		@parent::__construct();
		$this->load->model("Dbcommon", "cm");
        $this->load->model("AdminSettingsModel", "admin");
		$this->load->model("Quickadmissionprocess", "admi");
		$this->load->library("pagination");
		$this->load->library('email');
		$this->load->library('pdf');
		$this->load->helper('cookie');
		$this->load->library('upload');
        $this->load->library('session');
	}

	public function createbranch()
	{
		$display['receipt'] = $this->cm->view_all("receipt_permission");
		if (!empty($this->input->post('filter_branch_data'))) {
			$filter = $this->input->post();

			$display["branch_all"] = $this->admin->fetch_branch_reco("branch", $filter);
			$display['filter_branch'] = @$filter['filter_branch'];
			$display['filter_mobile_one'] = @$filter['mobile_one'];
			$display['filter_admin_id'] = @$filter['admin_id'];
			$display['filter_bank_name'] = @$filter['bank_name'];
		} else {
			$display['branch_all'] = $this->admin->fetch_branch_reco("branch");
		}

		
		$display['department_all'] = $this->cm->view_all("department");
		$display['country_all'] = $this->cm->view_all("country");
		$display['state_all'] = $this->cm->view_all("state");
		$display['city_all'] = $this->cm->view_all("cities");
		$display['user_all'] = $this->cm->Role_all_admin("user");
		$display['all_gst_daynamic_field'] = $this->cm->view_all("gst_daynamic_field");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");

		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");
		$update['f_module'] = $this->cm->view_all("f_module");
		$update['m_module'] = $this->cm->view_all("m_module");
		$update['l_module'] = $this->cm->view_all("l_module");
		$update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
		$update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
		$update['course_completed'] = $this->cm->course_completed_student("admission_courses");
		$update['count_course_notifive'] = $this->cm->count_course_notification("admission_courses");
		$update['course_data'] = $this->cm->view_all("course");
		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");

		$this->load->view('erp/erpheader', $update);
		$this->load->view('admin/createbranch',$display);
	}

	public function ajax_branch_submit() {
		if ($this->input->post('submit')) {
			$data = $this->input->post();
	
			date_default_timezone_set('Asia/Kolkata');
			$data['created_date'] = date('d-m-Y h:i:s');
			$data['created_by'] = $_SESSION['user_name'];
			unset($data['submit']);
			@$data['logtype'] = "Branch";
            @$data['session'] = implode(',',$data['session']);
            if ($_SESSION['logtype'] == "Super Admin") {
                @$data['admin_id'] = $data['admin_id'];
                @$data['country_id'] = $data['country_id'];
                @$data['state_id'] = $data['state_id'];
                @$data['city_id'] = $data['city_id'];
            } else {
                @$data['admin_id'] = $_SESSION['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                @$data['country_id'] = $data['country_id'];
                @$data['state_id'] = $all->state_id;
                @$data['city_id'] = $all->city_id;
            }

			$config['allowed_types'] = "*";
			$config['upload_path'] = FCPATH . "dist/branchlogo/";
			$new_name = time() . @$_FILES["branch_logo"]['name'];
			$config['file_name'] = $new_name;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('branch_logo')) {
				$imagedata = $this->upload->data();
				$data['branch_logo'] = $imagedata['file_name'];
				$config['image_library'] = 'gd2';
				$config['source_image'] = './dist/branchlogo/' . $imagedata['file_name'];
				$config['new_image'] = './dist/branchlogo/';
				$config['maintain_ratio'] = TRUE;
				$config['width']    = 640;
				$config['height']   = 480;

				$this->load->library('image_lib', $config);

				if (!$this->image_lib->resize()) {
					echo $this->image_lib->display_errors();
				} else {
					// echo "success"; 
				}
			} else {
				$error = array('error' => $this->upload->display_errors());
				$display['msgp'] = "image not uploaded";
			}

			if ($this->input->post('branch_id')) {
				$id = $this->input->post('branch_id');
				unset($data['branch_id']);
				$query = $this->admin->update_record('branch', $data, 'branch_id', $id);
				if ($query) {
					$recp["all_record"] = array('status' => 2, "msg" => "HI! This Record Successfully Updated");
					echo json_encode($recp); // echo "1";
				} else {
					$recp["all_record"] = array('status' => 3, "msg" => "Something Wrong");
					echo json_encode($recp); // echo "2";
				}
			} else {
				unset($data['branch_id']); 
				$query = $this->admin->import_record('branch', $data);
				if ($query) {
					$recp["all_record"] = array('status' => 1, "msg" => "HI! This Record Successfully Inserted");
					echo json_encode($recp); // echo "1";
				} else {
					$recp["all_record"] = array('status' => 3, "msg" => "Something Wrong");
					echo json_encode($recp); // echo "2";
				}
			}
		}
	}

	public function get_record_branch() {
		$branch_id = $this->input->post('branch_id');
		$record['single_record'] = $this->admin->get_branch_reco('branch', 'branch_id', $branch_id);
		echo json_encode($record);
	}

	public function createdepartment()
	{
		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");
		$update['f_module'] = $this->cm->view_all("f_module");
		$update['m_module'] = $this->cm->view_all("m_module");
		$update['l_module'] = $this->cm->view_all("l_module");
		$update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
		$update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
		$update['course_completed'] = $this->cm->course_completed_student("admission_courses");
		$update['count_course_notifive'] = $this->cm->count_course_notification("admission_courses");
		$update['course_data'] = $this->cm->view_all("course");
		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");
		
		$display['department_all'] = $this->cm->view_all_data("department");
		$display['branch_all'] = $this->cm->view_all_data("branch");
		$display['state_all'] = $this->cm->view_all("state");
		$display['city_all'] = $this->cm->view_all("cities");
		$display['user_all'] = $this->cm->Role_all_admin("user");

		$this->load->view('erp/erpheader', $update);
		$this->load->view('admin/createdepartment', $display);
	}

	public function createsubdepartment()
	{
		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");
		$update['f_module'] = $this->cm->view_all("f_module");
		$update['m_module'] = $this->cm->view_all("m_module");
		$update['l_module'] = $this->cm->view_all("l_module");
		$update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
		$update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
		$update['course_completed'] = $this->cm->course_completed_student("admission_courses");
		$update['count_course_notifive'] = $this->cm->count_course_notification("admission_courses");
		$update['course_data'] = $this->cm->view_all("course");
		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");

		$this->load->view('erp/erpheader', $update);
		$this->load->view('admin/createsubdepartment');
	}

	public function createuser()
	{
		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");
		$update['f_module'] = $this->cm->view_all("f_module");
		$update['m_module'] = $this->cm->view_all("m_module");
		$update['l_module'] = $this->cm->view_all("l_module");
		$update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
		$update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
		$update['course_completed'] = $this->cm->course_completed_student("admission_courses");
		$update['count_course_notifive'] = $this->cm->count_course_notification("admission_courses");
		$update['course_data'] = $this->cm->view_all("course");
		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");

		$this->load->view('erp/erpheader', $update);
		$this->load->view('admin/createuser');
	}

    public function get_record_receipt() {
        $id = $this->input->post('branch_id');
        $data['single_record'] = $this->cm->get_receipt_permission_record('receipt_permission', 'branch_id', $id);
        echo json_encode(array('record' => $data));
    }

	public function Admission_upd_receipt_permission() {
        $data = $this->input->post();
        $receipt_permission_id = $data['receipt_permission_id'];
        date_default_timezone_set("Asia/Calcutta");
        $created_date = date('d-M-Y h:i A');
        $addby = $_SESSION['user_name'];
        $record = array('receipt_type' => $data['receipt_type'], 'logo' => $data['logo'], 'branch_title' => $data['branch_title'], 'address' => $data['address'], 'course' => $data['course'], 'receipt_no' => $data['receipt_no'], 'receipt_date' => $data['receipt_date'], 'gr_id' => $data['gr_id'], 'enrollment_no' => $data['enrollment_no'], 'gst_no' => $data['gst_no'], 'name' => $data['name'], 'pay_now' => $data['pay_now'], 'installment_no' => $data['installment_no'], 'tuition_fees' => $data['tuition_fees'], 'total_pay' => $data['total_pay'], 'the_sum_of' => $data['the_sum_of'], 'remarks' => $data['remarks'], 'addby' => $addby, 'created_date' => $created_date);
        $re = $this->cm->upd_receipt_permission('receipt_permission', $record, 'receipt_permission_id', $receipt_permission_id);
        if ($re) {
            $record = array('status' => 1, "msg" => "Successfully Updated Permission");
            $recp['all_record'] = $record;
            echo json_encode($recp);
        } else {
            $recp['all_record'] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp);
        }
    }

	public function delete_branch_record () {
		$id = $this->input->post('branch_id');
		$query = $this->admin->delete_branch('branch', 'branch_id', $id);
        if ($query) {
			$recp["all_record"] = array('status' => 1, "msg" => "HI! This Record Successfully Deleted");
			echo json_encode($recp); // echo "1";
		} else {
			$recp["all_record"] = array('status' => 3, "msg" => "Something Wrong");
			echo json_encode($recp); // echo "2";
		}
	}

	public function update_branch_status () {
		$data = $this->input->post();
		$reco = array('branch_status'=>$data['status']);
		
		$re = $this->admin->update_record("branch", $reco, "branch_id", $data['branch_id']);
		
		if ($re) {
			$recp["status"] = array('status' => 1, "msg" => "Branch status updated.");
			echo json_encode($recp);
		} else {
			$recp["status"] = array('status' => 1, "msg" => "Something Wrong");
			echo json_encode($recp);
		}
	}

	public function filter_branch_record () {
		if (!empty($this->input->post('filter_branch_data'))) {
			$filter = $this->input->post();

			$display["overdue_fees_list"] = $this->admin->fetch_filter_branch("branch", $filter);
			$display['filter_fname'] = @$filter['filter_fname'];
			$display['filter_lname'] = @$filter['filter_lname'];
			$display['filter_email'] = @$filter['filter_email'];
			$display['filter_mobile'] = @$filter['filter_mobile'];
			$display['filter_enrollnno'] = @$filter['filter_enrollnno'];
			$display['filter_branch'] = @$filter['filter_branch'];
			$display['filter_course'] = @$filter['filter_course'];
			$display['filter_package'] = @$filter['filter_package'];
			$display['filter_from_date'] = @$filter['filter_from_date'];
			$display['filter_to_date'] = @$filter['filter_to_date'];
			$display['filter_on'] = "dfgf";
		} else {
			$display['overdue_fees_list'] = $this->admi->fetch_overdue_fees("branch");
		}


		$update['upd_faculty'] = $this->cm->view_all("faculty");
		$update['upd_branch'] = $this->cm->view_all("branch");
		$update['upd_see'] = $this->cm->check_update("demo");
		$update['f_module'] = $this->cm->view_all("f_module");
		$update['m_module'] = $this->cm->view_all("m_module");
		$update['l_module'] = $this->cm->view_all("l_module");
		$update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
		$update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
		$update['course_completed'] = $this->cm->course_completed_student("admission_courses");
		$update['count_course_notifive'] = $this->cm->count_course_notification("admission_courses");
		$update['course_data'] = $this->cm->view_all("course");


		$display['upd_faculty'] = $this->cm->view_all("faculty");
		$display['upd_branch'] = $this->cm->view_all("branch");
		$display['upd_see'] = $this->cm->check_update("demo");
		$display['list_source'] = $this->cm->view_all("source");
		$display['list_department'] = $this->cm->view_all("department");
		$display['list_branch'] = $this->cm->view_all("branch");
		$display['list_course'] = $this->cm->view_all("course");
		$display['list_package'] = $this->cm->view_all("package");
		$display['list_source'] = $this->cm->view_all("source");
		$display['list_country'] = $this->cm->view_all("country");
		$display['list_state'] = $this->cm->view_all("state");
		$display['list_city'] = $this->cm->view_all("cities");
		$display['list_area'] = $this->cm->view_all("area");
		$display['list_batch'] = $this->cm->view_all("batch_list");
		$display['all_admission'] = $this->cm->view_all("admission_process");
		$display['hod_all'] = $this->cm->view_all_hod_demo("hod");
		$display['faculty_all'] = $this->cm->view_all("faculty");
		$display['admisison_process_data'] = $this->cm->view_all("admission_process");
		$display['overdue_fees_counting_list'] = $this->admi->overdue_fees_counting("admission_installment");

		$this->load->view('erp/erpheader', $update);
		$this->load->view('erp/overdue_fees', $display); 
	}

	public function Admission_receipt_permission() {
        $data = $this->input->post();
        date_default_timezone_set("Asia/Calcutta");
        $created_date = date('d-M-Y h:i A');
        $addby = $_SESSION['user_name'];
        for ($i = 0;$i < sizeof($data['branch_id']);$i++) {
            $branches = $this->admi->view_all('branch');
            foreach ($branches as $dn) {
                $flag = 0;
                $dnbi = explode(',', $data['branch_id'][$i]);
                if (in_array($dn['branch_id'], $dnbi)) {
                    $flag = 1;
                }

                if ($flag == 1) {
                    $record = array('branch_id' => $dn['branch_id'], 'receipt_type' => $data['receipt_type'], 'logo' => $data['logo'], 'branch_title' => $data['branch_title'], 'address' => $data['address'], 'course' => $data['course'], 'receipt_no' => $data['receipt_no'], 'receipt_date' => $data['receipt_date'], 'gr_id' => $data['gr_id'], 'enrollment_no' => $data['enrollment_no'], 'gst_no' => $data['gst_no'], 'name' => $data['name'], 'pay_now' => $data['pay_now'], 'installment_no' => $data['installment_no'], 'tuition_fees' => $data['tuition_fees'], 'total_pay' => $data['total_pay'], 'the_sum_of' => $data['the_sum_of'], 'remarks' => $data['remarks'], 'addby' => $addby, 'created_date' => $created_date);
                    $result = $this->admi->save_data('receipt_permission', $record);
                    if ($result) {
                        $status_check = 1;
                    } else {
                        $status_check = 0;
                    }
                }
            }
        }
        if ($status_check == 1) {
            $record = array('status' => 1, "msg" => "Successfully Posted Permission");
            $recp['all_record'] = $record;
            echo json_encode($recp);
        } else {
            $recp['all_record'] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp);
        }
    }
}

?>