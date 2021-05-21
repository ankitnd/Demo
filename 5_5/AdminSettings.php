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

		if ($_SESSION['logtype'] == "Admin") {
            $display['department_all'] = $this->cm->view_all_data("department");
            $display['branch_all'] = $this->cm->view_all_data("branch");
            $display['country_all'] = $this->cm->view_all("country");
            $display['state_all'] = $this->cm->view_all("state");
            $display['city_all'] = $this->cm->view_all("cities");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        } else {
            $display['branch_all'] = $this->cm->view_all("branch");
            $display['department_all'] = $this->cm->view_all("department");
            $display['country_all'] = $this->cm->view_all("country");
            $display['state_all'] = $this->cm->view_all("state");
            $display['city_all'] = $this->cm->view_all("cities");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        }
        $display['all_gst_daynamic_field'] = $this->cm->view_all("gst_daynamic_field");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");

		$this->load->view('erp/erpheader', $update);
		$this->load->view('admin/createbranch',$display);
	}

	public function ajax_branch_submit()
	{
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
			if (@$_FILES['branch_logo']['name'] != "") {
				$config['allowed_types'] = "*";
				$config['upload_path'] = FCPATH . "dist/branchlogo/";
				$new_name = time() . @$_FILES["branch_logo"]['name'];
				$config['file_name'] = $new_name;
				$this->load->library('upload');
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
					$display['msgp'] = "image not uploaded";
				}
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

		$this->load->view('erp/erpheader', $update);
		$this->load->view('admin/createdepartment');
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

}

?>