<?php
class AddmissionController extends CI_controller {
    function __construct() {
        @parent::__construct();
        $this->load->model("Dbcommon", "cm");
        $this->load->model("Quickadmissionprocess", "admi");
        $this->load->library("pagination");
        $this->load->library('email');
        $this->load->library('pdf');
        date_default_timezone_set("Asia/Calcutta");
        $today = date('m/d/Y');
        $this->db->where('hold_ending_date', $today);
        $this->db->from('admission_process');
        $data = $this->db->get();
        $admdata = $data->result();
        for ($i = 0;$i < sizeof($admdata);$i++) {
            $admission_id = @$admdata[$i]->admission_id;
            $admission_status = "Enrolled";
            $record = array('admission_status' => $admission_status);
            $this->admi->update_admission_status_record('admission_process', $record, 'admission_id', $admission_id);
        }
    }
    public function seat_assign() {
        date_default_timezone_set("Asia/Calcutta");
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("seat_assign", "seat_id", $id);
                if ($re) {
                    redirect('AddmissionController/seat_assign');
                }
            } else if ($this->input->get('action') == "seat_delete") {
                $re = $this->cm->delete_data("seat_batch", "seat_batch_id", $id);
                if ($re) {
                    redirect('AddmissionController/seat_assign');
                }
            } else if ($this->input->get('action') == "seat_month_delete") {
                $re = $this->cm->delete_data("seat_month", "seat_month_id", $id);
                if ($re) {
                    redirect('AddmissionController/seat_assign');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_seat_assign'] = $this->cm->select_data("seat_assign", "seat_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            if (isset($data['seat_id']) && !empty($data['seat_id'])) {
                $bdata = $this->cm->filter_data("seat_batch", "seat_id", $data['seat_id']);
                $this->cm->view_all("seat_batch");
                $b = 0;
                foreach ($bdata as $k => $v) {
                    foreach ($data['seat_year'] as $key => $value) {
                        if ($value == $v->batch_year) {
                            $b = 1;
                            break;
                        }
                    }
                }
                if ($b == 0) {
                    foreach ($data['seat_year'] as $key => $value) {
                        $s_data['batch_year'] = $value;
                        $s_data['seat_no'] = $data['seat_no'][$key];
                        $s_data['seat_id'] = $data['seat_id'];
                        $all = $this->cm->select_data("seat_course", "seat_course_id", $data['seat_course_id']);
                        $s_data['batch_name'] = $all->seat_course_name;
                        $s_data['batch_code'] = $all->seat_course_name . "-" . $value;
                        $this->cm->insert_data("seat_batch", $s_data);
                    }
                } else {
                    $display['bmsg'] = "added...........";
                }
            } else {
                $asigndata = $this->cm->view_all("seat_assign");
                $t = 0;
                foreach ($asigndata as $key => $value) {
                    if ($value->seat_branch_id == $data['seat_branch_id'] && $value->department_id == $data['department_ids'] && $value->seat_course_id == $data['seat_course_id']) {
                        $t = 1;
                        break;
                    }
                }
                // echo "<pre>";
                // echo $t;
                // print_r($this->cm->view_all("seat_assign"));
                // print_r($data);
                // die;
                if ($t == 0) {
                    $ins_data['seat_branch_id'] = $data['seat_branch_id'];
                    $ins_data['department_id'] = $data['department_ids'];
                    $ins_data['seat_course_id'] = $data['seat_course_id'];
                    if (!empty($this->input->post('update_id'))) {
                        $id = $this->input->post('update_id');
                        $re = $this->cm->update_data("seat_assign", $ins_data, "seat_id", $id);
                    } else {
                        $re = $this->cm->seat_insert_data("seat_assign", $ins_data);
                        //echo mysqli_insert_id(mysqli_connect("localhost","root","","mzfrxmujjb"));
                        //echo $re->seat_id;
                        foreach ($data['seat_year'] as $key => $value) {
                            $s_data['batch_year'] = $value;
                            $s_data['seat_no'] = $data['seat_no'][$key];
                            $s_data['seat_id'] = $re;
                            $all = $this->cm->select_data("seat_course", "seat_course_id", $data['seat_course_id']);
                            $s_data['batch_name'] = $all->seat_course_name;
                            $s_data['batch_code'] = $all->seat_course_name . "-" . $value;
                            // echo "<pre>";
                            // print_r($s_data);
                            // die;
                            $this->cm->insert_data("seat_batch", $s_data);
                        }
                    }
                    if ($re) {
                        redirect('AddmissionController/seat_assign');
                    }
                } else {
                    $display['msg'] = "added...........";
                }
            }
        }
        date_default_timezone_set('Asia/kolkata');
        $display['all_eduzilla_coutingseat'] = $this->cm->admission_seat("eduzilladata");
        $display['seat_assign_all'] = $this->cm->view_all("seat_assign");
        $display['seat_betch_all'] = $this->cm->view_all_order("seat_batch");
        $display['department_all'] = $this->cm->view_all("department");
        $display['seat_branch_all'] = $this->cm->view_all("seat_branch");
        foreach ($display['seat_betch_all'] as $key => $value) {
            $seat_data = $this->cm->select_data("seat_assign", "seat_id", $value->seat_id);
            $mdata = $this->cm->select_data("seat_branch", "seat_branch_id", $seat_data->seat_branch_id);
            // $display['seat_betch_all'][$key]->total_done11= $this->cm->fetch_data_sea($value->batch_name,"admission_date",$value->batch_year,"admission_code",$mdata->seat_branch_code);
            $display['seat_betch_all'][$key]->seat_branch_code = $mdata->seat_branch_code;
            $ddata = $this->cm->select_data("department", "department_id", $seat_data->department_id);
            if ($ddata->department_name == "COLLEGE") {
                // echo $value->batch_code;
                // die;
                $addata = $this->cm->fetch_data_sea($value->batch_name, "course", $value->batch_year);
                $c = 0;
                foreach ($addata as $k => $v) {
                    if ($v->admission_status == "Terminated" || $v->admission_status == "Cancelled") {
                        $c++;
                    }
                }
                $display['seat_betch_all'][$key]->total_cancle = $c;
                $display['seat_betch_all'][$key]->total_done = count($this->cm->fetch_data_sea($value->batch_name, "course", $value->batch_year));
                $display['seat_betch_all'][$key]->total_done11 = $this->cm->fetch_data_sea($value->batch_name, "course", $value->batch_year);
            } else {
                $addata = $this->cm->fetch_data_sea_new($value->batch_name, "admission_date", $value->batch_year, "admission_code", $mdata->seat_branch_code);
                $c = 0;
                foreach ($addata as $k => $v) {
                    if ($v->admission_status == "Terminated" || $v->admission_status == "Cancelled") {
                        $c++;
                    }
                }
                $display['seat_betch_all'][$key]->total_cancle = $c;
                $display['seat_betch_all'][$key]->total_done = count($this->cm->fetch_data_sea_new($value->batch_name, "admission_date", $value->batch_year, "admission_code", $mdata->seat_branch_code));
                $display['seat_betch_all'][$key]->total_done11 = $this->cm->fetch_data_sea_new($value->batch_name, "admission_date", $value->batch_year, "admission_code", $mdata->seat_branch_code);
            }
            // count($this->cm->fetch_data_sea($value->batch_name,"admission_date",$value->batch_year,"admission_code",$mdata->seat_branch_code))
            
        }
        foreach ($display['seat_betch_all'] as $m => $n) {
            $display['seat_betch_all'][$m]->month = $this->cm->filter_data("seat_month", "month_seat_batch_id", $n->seat_batch_id);
        }
        foreach ($display['seat_betch_all'] as $key => $value) {
            foreach ($value->month as $k => $v) {
                $seat_t = 1;
                $cancle_t = 1;
                $value->month[$k]->total_month_cancle_seat = 0;
                $value->month[$k]->total_month_seat = 0;
                foreach ($value->total_done11 as $m => $n) {
                    $d = date('m', strtotime($n->admission_date));
                    if ($d == $v->month_name) {
                        $value->month[$k]->total_month_seat = $seat_t++;
                        if ($n->admission_status == "Terminated" || $n->admission_status == "Cancelled") {
                            //$cancle_t++;
                            $value->month[$k]->total_month_cancle_seat = $cancle_t++;
                        }
                    }
                }
                if (isset($value->month[$k]->total_month_seat) || isset($value->month[$k]->total_month_cancle_seat)) {
                    if (isset($value->month[$k]->total_month_seat)) {
                        $jdata['month_book_seat'] = $value->month[$k]->total_month_seat;
                    }
                    if (isset($value->month[$k]->total_month_cancle_seat)) {
                        $jdata['month_cancle_seat'] = $value->month[$k]->total_month_cancle_seat;
                    }
                    $jdata['month_pending_seat'] = $v->month_seat - ($v->month_book_seat - $v->month_cancle_seat);
                    if (isset($v->seat_month_id)) {
                        $this->cm->update_data("seat_month", $jdata, "seat_month_id", $v->seat_month_id);
                    }
                }
            }
        }
        foreach ($display['seat_assign_all'] as $key => $value) {
            //$seat_data = $this->cm->select_data("seat_assign","seat_id",$value->seat_id);
            $ssdata = $this->cm->select_data("seat_branch", "seat_branch_id", $value->seat_branch_id);
            $batchdata = $this->cm->select_data("seat_course", "seat_course_id", $value->seat_course_id);
            // $display['seat_betch_all'][$key]->total_done11= $this->cm->fetch_data_sea($value->batch_name,"admission_date",$value->batch_year,"admission_code",$mdata->seat_branch_code);
            $display['seat_assign_all'][$key]->seat_branch_code = $ssdata->seat_branch_code;
            $display['seat_assign_all'][$key]->seat_branch_name = $ssdata->seat_branch_name;
            $display['seat_assign_all'][$key]->seat_course_name = $batchdata->seat_course_name;
            $ddata = $this->cm->select_data("department", "department_id", $value->department_id);
            // count($this->cm->fetch_data_sea($value->batch_name,"admission_date",$value->batch_year,"admission_code",$mdata->seat_branch_code))
            $display['seat_assign_all'][$key]->department_name = $ddata->department_name;
        }
        // echo "<pre>";
        // print_r($display['seat_assign_all']);
        // die;
        // $edata = ;
        foreach ($display['seat_betch_all'] as $u => $v) {
            $s = 0;
            foreach ($v->month as $key => $value) {
                $s = $s + $value->month_seat;
            }
            $display['seat_betch_all'][$u]->assign_sheet = $s;
        }
        $display['seat_course_all'] = $this->cm->view_all("seat_course");
        $update['upd_branch'] = $this->cm->view_all("seat_branch");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('seat_assign', $display);
    }
    public function month_seat_assign() {
        $data = $this->input->post();
        $sdata = $this->cm->filter_data("seat_month", "month_seat_batch_id", $data['month_seat_batch_id']);
        $t = 0;
        foreach ($sdata as $k => $v) {
            if ($data['month_name'] == $v->month_name) {
                $t = 1;
                break;
            }
        }
        if ($t == 0) {
            $mdata['month_seat_batch_id'] = $data['month_seat_batch_id'];
            $mdata['month_seat_id'] = $data['month_seat_id'];
            $mdata['month_name'] = $data['month_name'];
            $mdata['month_seat'] = $data['month_seat'];
            $this->cm->insert_data("seat_month", $mdata);
        } else {
            $d['mmsg'] = "added...........";
        }
        return redirect("AddmissionController/seat_assign", $d);
    }
    public function show_seat($id, $s) {
        if ($s == 0) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }
        $this->cm->update_data("seat_month", $data, "seat_month_id", $id);
        return redirect("AddmissionController/seat_assign");
    }
    public function fetch_seat_department() {
        $data = $this->cm->select_data("seat_branch", "seat_branch_id", $this->input->post('branch_ids'));
        echo $this->cm->fetch_seat_department($data->department_ids);
    }
    public function fetch_seat_course() {
        if ($this->input->post('department_ids')) {
            echo $this->cm->fetch_seat_course($this->input->post('department_ids'));
        }
    }
    public function fetch_no_of_year() {
        $no['n'] = $this->input->post('year_no');
        return $this->load->view('no_of_year', $no);
    }
    public function seat_branch() {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                if ($this->input->get('status') == 0) {
                    $st['branch_status'] = 1;
                } else {
                    $st['branch_status'] = 0;
                }
                $re = $this->cm->update_data("seat_branch", $st, "seat_branch_id", $id);
                if ($re) {
                    redirect('AddmissionController/seat_branch');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_seat_branch'] = $this->cm->select_data("seat_branch", "seat_branch_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['update_id']);
            unset($data['submit']);
            $data['logtype'] = "Branch";
            @$data['department_ids'] = implode(",", @$data['depart_ids']);
            unset($data['depart_ids']);
            $data['timestamp'] = (date('Y-m-d H:i:s'));
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("seat_branch", $data, "seat_branch_id", $id);
            } else {
                $re = $this->cm->insert_data("seat_branch", $data);
            }
            if ($re) {
                redirect('AddmissionController/seat_branch');
            }
        }
        $display['seat_branch_all'] = $this->cm->view_all("seat_branch");
        $display['department_all'] = $this->cm->view_all("department");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('seat_branch', $display);
    }
    // public function seat_department()
    // {
    // 	 if($_SESSION['logtype']!="Super Admin") { redirect('welcome');  }
    // 	if(!empty($this->input->get('action')) && !empty($this->input->get('id')))
    // 	{
    // 		$id=$this->input->get('id');
    // 		if($this->input->get('action')=="delete")
    // 		{
    // 		    if($this->input->get('status')==0)
    // 		    {
    // 		        $st['seat_department_status']=1;
    // 		    }
    // 		    else
    // 		    {
    // 		        $st['seat_department_status']=0;
    // 		    }
    // 			$re = $this->cm->update_data("seat_department",$st,"seat_department_id",$id);
    // 			if($re)
    // 			{
    // 				redirect('AddmissionController/seat_department');
    // 			}
    // 		}
    // 		else if($this->input->get('action')=="edit")
    // 		{
    // 			$display['select_seat_department']=$this->cm->select_data("seat_department","seat_department_id",$id);
    // 		}
    // 	}
    // 	if(!empty($this->input->post('submit')))
    // 	{
    // 		$data = $this->input->post();
    // 		unset($data['update_id']);
    // 		unset($data['submit']);
    // 		@$data['seat_branch_id'] = implode(",",@$data['s_branch_ids']);
    // 		unset($data['s_branch_ids']);
    // 		if(!empty($this->input->post('update_id')))
    // 		{
    // 			$id = $this->input->post('update_id');
    // 			$re = $this->cm->update_data("seat_department",$data,"seat_department_id",$id);
    // 		}
    // 		else
    // 		{
    // 			//  echo "<pre>";
    // 			// print_r($data);
    // 			// die();
    // 			$re = $this->cm->insert_data("seat_department",$data);
    // 		}
    // 		if($re)
    // 		{
    // 			redirect('AddmissionController/seat_department');
    // 		}
    // 	}
    // 	$display['seat_branch_all'] = $this->cm->view_all("seat_branch");
    // 	$display['seat_department_all'] = $this->cm->view_all("seat_department");
    // 	$update['upd_faculty'] = $this->cm->view_all("faculty");
    // 	$update['upd_branch'] = $this->cm->view_all("branch");
    // 	$update['upd_see'] = $this->cm->check_update("demo");
    // 	$this->load->view('header',$update);
    // 	$this->load->view('seat_department',$display);
    // }
    public function seat_course() {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                if ($this->input->get('status') == 0) {
                    $st['course_status'] = 1;
                } else {
                    $st['course_status'] = 0;
                }
                $re = $this->cm->update_data("seat_course", $st, "seat_course_id", $id);
                if ($re) {
                    redirect('AddmissionController/seat_course');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_seat_course'] = $this->cm->select_data("seat_course", "seat_course_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['update_id']);
            unset($data['submit']);
            @$data['department_ids'] = implode(",", @$data['depart_ids']);
            unset($data['depart_ids']);
            $data['timestamp'] = (date('Y-m-d H:i:s'));
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("seat_course", $data, "seat_course_id", $id);
            } else {
                $re = $this->cm->insert_data("seat_course", $data);
            }
            if ($re) {
                redirect('AddmissionController/seat_course');
            }
        }
        $display['seat_course_all'] = $this->cm->view_all("seat_course");
        $display['department_all'] = $this->cm->view_all("department");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('seat_course', $display);
    }
    public function admission_update() {
        $id = $this->input->get('id');
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            date_default_timezone_set("Asia/Calcutta");
            unset($data['id']);
            unset($data['submit']);
            @$ins_data['mobile_no'] = $data['mobile_no'];
            @$ins_data['alternate_mobile_no'] = $data['alternate_mobile_no'];
            @$ins_data['email'] = $data['email'];
            @$ins_data['source_id'] = $data['source_id'];
            @$ins_data['first_name'] = $data['first_name'];
            @$ins_data['surname'] = $data['surname'];
            @$ins_data['admission_branch'] = $data['admission_branch'];
            @$ins_data['addby'] = $data['addby'];
            @$ins_data['branch_id'] = $data['branch_id'];
            @$ins_data['year'] = $data['no_year'];
            if (empty($ins_data['year'])) {
                $ins_data['year'] = "2020";
            }
            @$ins_data['department_id'] = implode(",", @$data['department_id']);
            if (empty($ins_data['department_id'])) {
                $ins_data['department_id'] = "";
            }
            $ins_data['type'] = ($data['quick_course_package']) ? : "";
            @$ins_data['course_id'] = implode(",", @$data['course_or_single']);
            if (empty($ins_data['course_id'])) {
                $ins_data['course_id'] = "";
            }
            @$ins_data['package_id'] = implode(",", @$data['course_or_package']);
            if (empty($ins_data['package_id'])) {
                $ins_data['package_id'] = "";
            }
            @$ins_data['faculty_id'] = $data['faculty_id'];
            @$ins_data['batch_id'] = $data['batch_id'];
            @$ins_data['tuation_fees'] = $data['tuation_fees'];
            @$ins_data['registration_fees'] = $data['registration_fees'];
            @$ins_data['state_id'] = $data['state_id'];
            @$ins_data['city_id'] = $data['city_id'];
            @$ins_data['area_id'] = $data['area_id'];
            @$ins_data['present_address'] = $data['present_address'];
            @$ins_data['permanent_address'] = $data['permanent_address'];
            @$ins_data['pin_code'] = $data['pin_code'];
            @$ins_data['father_name'] = $data['father_name'];
            @$ins_data['father_email'] = $data['father_email'];
            @$ins_data['father_occupation'] = $data['father_occupation'];
            @$ins_data['father_income'] = $data['father_income'];
            @$ins_data['mother_name'] = $data['mother_name'];
            @$ins_data['mother_email'] = $data['mother_email'];
            @$ins_data['mother_mobile_no'] = $data['mother_mobile_no'];
            @$ins_data['mother_occupation'] = $data['mother_occupation'];
            @$ins_data['mother_income'] = $data['mother_income'];
            @$ins_data['admission_msg_email'] = $data['admission_msg_email'];
            if (empty($ins_data['admission_msg_email'])) {
                $ins_data['admission_msg_email'] = "";
            }
            @$ins_data['admission_msg_mobile'] = $data['admission_msg_mobile'];
            if (empty($ins_data['admission_msg_mobile'])) {
                $ins_data['admission_msg_mobile'] = "";
            }
            @$ins_data['school_collage_name'] = $data['school_collage_name'];
            @$ins_data['country_id'] = $data['country_id'];
            @$ins_data['school_clg_state'] = $data['school_clg_state'];
            @$ins_data['school_clg_city'] = $data['school_clg_city'];
            @$ins_data['school_clg_area'] = $data['school_clg_area'];
            @$ins_data['school_collage_type'] = $data['school_collage_type'];
        }
        if (!empty($this->input->post('id'))) {
            $id = $this->input->post('id');
            $re = $this->admi->upd_admission("admission_process", $ins_data, "admission_id", $id);
        } else if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
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
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('header_test', $update);
        $this->load->view('erp/admission_update', $display);
    }
    public function basic_info() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
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
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('header_test', $update);
        $this->load->view('erp/basic_info', $display);
    }
    public function admlogins() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
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
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('header_test', $update);
        $this->load->view('erp/admlogins', $display);
    }
    public function admcourses() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
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
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['admission_courses'] = $this->admi->get_shining_sheet("admission_courses", "admission_id", $id);
        $this->load->view('header_test', $update);
        $this->load->view('erp/admcourses', $display);
    }
    public function ajax_shiningsheet_data() {
        $course_id = $this->input->post('course_id');
        $admission_courses_id = $this->input->post('admission_courses_id');
        $data['admission_course'] = $this->admi->get_shining_sheet('admission_courses', 'admission_courses_id', $admission_courses_id);
        $data['shining_sheet'] = $this->admi->get_shining_sheet('shining_sheet', 'course_id', $course_id);
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $data['list_course'] = $this->cm->view_all("course");
        $data['assign_student'] = $this->cm->view_all("assign_student");
        $this->load->view('erp/ajax_shiningsheet_data', $data);
    }
    public function ajax_shiningsheet_data_all_student_wise() {
        $course_id = $this->input->post('course_id');
        $faculty_id = $this->input->post('faculty_id');
        $batches_id = $this->input->post('batches_id');
        $data['shining_sheet'] = $this->admi->get_shining_sheet('shining_sheet', 'course_id', $course_id);
        $data['match_course'] = $this->admi->shining_sheet_courses_match('course', 'course_id', $course_id);
        $data['match_faculty'] = $this->admi->shining_sheet_faculty_match('faculty', 'faculty_id', $faculty_id);
        $data['fetch_betch'] = $this->cm->select_data('batches', 'batches_id', $batches_id);
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $data['list_course'] = $this->cm->view_all("course");
        $data['assign_student'] = $this->cm->view_all("assign_student");
        $this->load->view('erp/ajax_shiningsheet_data_student_wise', $data);
    }
    public function GeneratePdf() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $data['admission_course'] = $this->cm->select_data("admission_courses", "admission_courses_id", $id);
            $data['shining_sheet'] = $this->cm->select_data("shining_sheet", "course_id", $id);
        }
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $data['list_course'] = $this->cm->view_all("course");
        $data['assign_student'] = $this->cm->view_all("assign_student");
        $this->load->view('erp/dummypdf', $data);
        $html = $this->output->get_output();
        // Load pdf library
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        // Output the generated PDF (1 = download and 0 = preview)
        $this->pdf->stream("html_contents.pdf", array("Attachment" => 0));
    }
    public function assign_student() {
        $shining_sheet_id = $this->input->post('shining_sheet_id');
        $admission_id = $this->input->post('admission_id');
        $gr_id = $this->input->post('gr_id');
        $course_orpackage_id = $this->input->post('course_orpackage_id');
        // $encodedUrl = urlencode($html_url);
        $s_id = strtr(base64_encode($this->input->post('shining_sheet_id')), '+/=', '._-');
        $a_id = strtr(base64_encode($this->input->post('admission_id')), '+/=', '._-');
        $g_id = strtr(base64_encode($this->input->post('gr_id')), '+/=', '._-');
        $cp_id = strtr(base64_encode($this->input->post('course_orpackage_id')), '+/=', '._-');
        $all_record_admission = $this->admi->get_admission_record('admission_process', 'admission_id', $admission_id);
        foreach ($all_record_admission as $key => $value) {
        }
        if (isset($value)) {
            $email_record = $value->email;
            $emailconfigration = $this->admi->email_configration_daynamic('email_settings');
            foreach ($emailconfigration as $key) {
                if ($key->email_type == "1") {
                    $Protocol = $key->encryption;
                    $HostName = $key->host;
                    $SmtpPortNo = $key->port;
                    $SmtpUser = $key->email;
                    $SmtpPass = $key->password;
                    $config = array('protocol' => $Protocol, 'smtp_host' => $HostName, 'smtp_port' => $SmtpPortNo, 'smtp_user' => $SmtpUser, 'smtp_pass' => $SmtpPass, 'mailtype' => 'html', 'charset' => 'utf-8');
                    $this->load->library('email');
                    $this->email->initialize($config);
                }
                if ($key->email_type == "2") {
                    $fromemail = $key->email;
                    $from = $fromemail;
                }
            }
            $to = $email_record;
            $subject = "Shining Sheet Topic";
            $message = "https://demo.rnwmultimedia.com/assign_student/student_assign.php?shining_sheet_id=$s_id&admission_id=$a_id&gr_id=$g_id&course_orpackage_id=$cp_id";
            $this->email->set_newline("\r\n");
            $this->email->from($from);
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
        }
    }
    public function assigned_multistudent() {
        $shining_sheet_id = $this->input->post('shining_sheet_id');
        $batches_id = $this->input->post('batches_id');
        $adm_data = $this->admi->get_adm_batches_all("admission_courses", "batch_id", $batches_id);
        foreach ($adm_data as $val) {
            $s_id = strtr(base64_encode($this->input->post('shining_sheet_id')), '+/=', '._-');
            $a_id = strtr(base64_encode($val->admission_id), '+/=', '._-');
            $g_id = strtr(base64_encode($val->gr_id), '+/=', '._-');
            $cp_id = strtr(base64_encode($val->courses_id), '+/=', '._-');
            $emailconfigration = $this->cm->view_all('email_settings');
            foreach ($emailconfigration as $key) {
                if ($key->email_type == "1") {
                    $Protocol = $key->encryption;
                    $HostName = $key->host;
                    $SmtpPortNo = $key->port;
                    $SmtpUser = $key->email;
                    $SmtpPass = $key->password;
                    $config = array('protocol' => $Protocol, 'smtp_host' => $HostName, 'smtp_port' => $SmtpPortNo, 'smtp_user' => $SmtpUser, 'smtp_pass' => $SmtpPass, 'mailtype' => 'html', 'charset' => 'utf-8');
                    $this->load->library('email');
                    $this->email->initialize($config);
                }
                if ($key->email_type == "2") {
                    $fromemail = $key->email;
                    $from = $fromemail;
                }
            }
            $from = $from;
            $to = $val->email;
            $subject = "Shining Sheet Topic";
            $message = "https://demo.rnwmultimedia.com/assign_student/student_assign.php?shining_sheet_id=$s_id&admission_id=$a_id&gr_id=$g_id&course_orpackage_id=$cp_id";
            $this->email->set_newline("\r\n");
            $this->email->from($from);
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send()) {
                $this->session->set_flashdata("email_sent", "Email sent successfully.");
            } else {
                $this->session->set_flashdata("email_sent_error", "Error in sending Email.");
            }
        }
    }
    public function admremarks() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
            $display['list_remark'] = $this->admi->get_remarks_id_wise("admission_remarks", "admission_id", $id);
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
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
        $this->load->view('header_test', $update);
        $this->load->view('erp/admremarks', $display);
    }
    public function admission_view() {
        if (!empty($this->input->post('filter_admission'))) {
            $filter = $this->input->post();
            $display['list_all_admission'] = $this->admi->admission_view_all('admission_process', $filter);
            $display['all_list_admission'] = $this->admi->view_all_admission_course('admission_process');
            $data = array();
            if (isset($display['all_list_admission'])) {
                foreach ($display['all_list_admission'] as $key => $val) {
                    if ($val->type == 'single') {
                        $data[] = $this->admi->get_all_record_grid_wise('admission_process', 'admission_id', $val->admission_id, $val->type);
                    } else {
                        $data[] = $this->admi->get_all_record_grid_wise('admission_process', 'admission_id', $val->admission_id, $val->type);
                    }
                }
            }
            $alldata = array();
            for ($i = 0;$i < sizeof($data);$i++) {
                $record = array();
                $k = 0;
                for ($j = 0;$j < sizeof($data);$j++) {
                    if ($data[$i]->gr_id == $data[$j]->gr_id) {
                        if ($data[$i]->type == 'single') {
                            $record = @$data[$i]->course_name;
                        } else if ($data[$i]->type == 'package') {
                            $record = @$data[$i]->package_name;
                        }
                    }
                }
                $alldata[$data[$i]->gr_id][$data[$i]->admission_id] = $record;
            }
            for ($i = 0;$i < sizeof($display['list_all_admission']);$i++) {
                foreach ($alldata as $k => $v) {
                    if (@$display['list_all_admission'][$i]->gr_id == $k) {
                        $display['list_all_admission'][$i]->list_multi_course_admission = $v;
                    }
                }
            }
            $pa = 0;
            foreach ($display['all_list_admission'] as $keys => $vals) {
                $this->db->select_sum('paid_amount');
                $this->db->from('admission_installment');
                $this->db->where('admission_id', $vals->admission_id);
                $this->db->count_all();
                $query = $this->db->get();
                $total_ammount[$pa] = $query->result();
                $pa++;
            }
            for ($p = 0;$p < sizeof($display['list_all_admission']);$p++) {
                $display['list_all_admission'][$p]->paidcount = $total_ammount[$p];
            }
            $display['filter_startDate'] = @$filter['filter_startDate'];
            $display['filter_endDate'] = @$filter['filter_endDate'];
            $display['filter_fname'] = @$filter['filter_fname'];
            $display['filter_lname'] = @$filter['filter_lname'];
            $display['filter_gr_id'] = @$filter['filter_gr_id'];
            $display['filter_email'] = @$filter['filter_email'];
            $display['filter_mobile'] = @$filter['filter_mobile'];
            $display['filter_source'] = @$filter['filter_source'];
            $display['filter_branch'] = @$filter['filter_branch'];
            $display['filter_course'] = @$filter['filter_course'];
            $display['filter_package'] = @$filter['filter_package'];
            $display['filter_on'] = "dfgf";
        } else {
            $status_all = $this->admi->view_all_admission_record('admission_process');
            if (@$this->input->get('status') && $this->input->get('status') != 'All') {
                $chek = $this->input->get('status');
                $records = $this->admi->get_admission_record_by_status_wise('admission_process', 'admission_status', $chek);
                foreach ($status_all as $value) {
                    $all_status[] = $value->admission_status;
                }
                $count = 0;
                foreach ($all_status as $val) {
                    $query = $this->db->query("SELECT * FROM admission_process Where `admission_status`='$val'");
                    $num_of_row[$val] = $query->num_rows();
                }
                $display['list_all_admission'] = $records;
            } else {
                foreach ($status_all as $value) {
                    $all_status[] = $value->admission_status;
                }
                $count = 0;
                foreach ($all_status as $val) {
                    $query = $this->db->query("SELECT * FROM admission_process Where `admission_status`='$val'");
                    $num_of_row[$val] = $query->num_rows();
                }
                $display['list_all_admission'] = $this->admi->admission_view_all('admission_process');
            }
            $display['all_list_admission'] = $this->admi->view_all_admission_course('admission_process');
            $data = array();
            if (isset($display['all_list_admission'])) {
                foreach ($display['all_list_admission'] as $key => $val) {
                    if ($val->type == 'single') {
                        $data[] = $this->admi->get_all_record_grid_wise('admission_process', 'admission_id', $val->admission_id, $val->type);
                    } else {
                        $data[] = $this->admi->get_all_record_grid_wise('admission_process', 'admission_id', $val->admission_id, $val->type);
                    }
                }
            }
            // echo "<pre>";
            // print_r($data);
            // exit;
            $alldata = array();
            for ($i = 0;$i < sizeof($data);$i++) {
                $record = array();
                $k = 0;
                for ($j = 0;$j < sizeof($data);$j++) {
                    if ($data[$i]->gr_id == $data[$j]->gr_id) {
                        if ($data[$i]->type == 'single') {
                            $record = @$data[$i]->course_name;
                        } else if ($data[$i]->type == 'package') {
                            $record = @$data[$i]->package_name;
                        }
                    }
                }
                // print_r($record);
                $alldata[$data[$i]->gr_id][$data[$i]->admission_id] = $record;
            }
            // echo "<pre>";
            // print_r($alldata);
            // exit;
            // $display['list_all_admission']['list_multi_course_admission'] = $alldata;
            for ($i = 0;$i < sizeof($display['list_all_admission']);$i++) {
                foreach ($alldata as $k => $v) {
                    if (@$display['list_all_admission'][$i]->gr_id == $k) {
                        $display['list_all_admission'][$i]->list_multi_course_admission = $v;
                    }
                }
            }
            $pa = 0;
            foreach ($display['all_list_admission'] as $keys => $vals) {
                $this->db->select_sum('paid_amount');
                $this->db->from('admission_installment');
                $this->db->where('admission_id', $vals->admission_id);
                $this->db->count_all();
                $query = $this->db->get();
                $total_ammount[$pa] = $query->result();
                $pa++;
            }
            for ($p = 0;$p < sizeof($display['list_all_admission']);$p++) {
                $display['list_all_admission'][$p]->paidcount = $total_ammount[$p];
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_completed'] = $this->cm->course_completed_student("admission_courses");
        $update['count_course_notifive'] = $this->cm->count_course_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_batch'] = $this->cm->view_all("batch_list");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_remarks'] = $this->cm->view_all("admission_remarks");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $display['sms_template_list'] = $this->cm->view_all("sms_template");
        $display['list_email_template'] = $this->cm->view_all("email_template_category");
        $display['status_wise'] = $num_of_row;
        //$display['status_wise_record'] = $records;
        // echo "<pre>";
        // print_r($display['demo_data']);
        // die;
        if (!empty($this->input->post('test'))) {
            $this->load->view('erp/ajax_admission_view', $display);
        } else {
            $this->load->view('header_test', $update);
            $this->load->view('erp/admission_view', $display);
        }
    }
    public function GetRecord() {
        $mobile_no = $this->input->post('mobile_no');
        $data = $this->admi->fetch_admission($mobile_no);
        $data_demo = $this->admi->fetch_data_demo($mobile_no);
        $data_lead = $this->admi->fetch_data($mobile_no);
        if (@$data_lead->lead_list_id != '') {
            $data = $this->admi->fetch_data($mobile_no);
        } else if (@$data->admission_id != '') {
            $data = $this->admi->fetch_admission($mobile_no);
        } else if (@$data_demo->demo_id != '') {
            $data = $this->admi->fetch_data_demo($mobile_no);
        }
        echo json_encode(array('record' => $data));
    }
    public function GetRecord_alternate_number_wise() {
        $alternate_mobile_no = $this->input->post('alternate_mobile_no');
        if (!empty($alternate_mobile_no)) {
            $data = $this->admi->fetch_data_alternet_no_wise($alternate_mobile_no);
            echo json_encode(array('record' => $data));
        } else {
            $data = null;
            echo json_encode($data);
        }
    }
    public function Getrecord_email_wise() {
        $email = $this->input->post('email');
        $data = $this->admi->fetch_data_Email_wise_admission($email);
        $data_lead = $this->admi->fetch_data_Email_wise($email);
        if (@$data->admission_id != '') {
            $data = $this->admi->fetch_data_Email_wise_admission($email);
        } else if (@$data_lead->lead_list_id != '') {
            $data = $this->admi->fetch_data_Email_wise($email);
        }
        echo json_encode(array('record' => $data));
    }
    public function getrecord_package() {
        $package_id = $this->input->post('packageId');
        print_r($package_id);
        exit;
        $pids = implode(',', $package_id);
        $data = $this->cm->select_data('admission_documents_permission', 'package_id', $pids);
        echo json_encode(array('record' => $data));
    }
    public function getrecord_single_course() {
        $course_id = $this->input->post('course_id');
        $cids = implode(',', $course_id);
        $data = $this->cm->select_data('admission_documents_permission', 'course_id', $cids);
        echo json_encode(array('record' => $data));
    }
    public function Getrecode_multiple() {
        $mobileNo = $this->input->post('mobile_no');
        if (!empty($mobile_no)) {
            $data = $this->admi->fetchdata_multiple('lead_list', 'mobile_no', $mobileNo);
        } else {
            $data = array('');
        }
        $alternate_mobile_no = $this->input->post('alternate_mobile_no');
        if (!empty($alternate_mobile_no)) {
            $data_aler = $this->admi->fetchdata_multiple('lead_list', 'alternate_mobile_no', $alternate_mobile_no);
        } else {
            $data_aler = array('');
        }
        $email = $this->input->post('email');
        if (!empty($email)) {
            $data_email = $this->admi->fetchdata_multiple('lead_list', 'email', $email);
        } else {
            $data_email = array('');
        }
        $array_mobile = (array)$data;
        $array_alternate_mobile = (array)$data_aler;
        $array_email = (array)$data_email;
        $result['multiple_data'] = array_merge($array_mobile, $array_alternate_mobile, $array_email);
        echo json_encode($result);
    }
    public function GetRecord_shining_sheet() {
        $course_id = $this->input->post('course_id');
        $data = $this->admi->fetch_data_shining_sheet($course_id);
        $record = array();
        for ($i = 0;$i < sizeof($data);$i++) {
            $record[] = $data[$i]->shining_sheet_id;
        }
        $record = implode(',', $record);
        echo json_encode(array('record' => $record));
    }
    public function firstProcess() {
        date_default_timezone_set("Asia/Calcutta");
        $data = $this->input->post();
        $addby = $_SESSION['user_name'];
        $admission_date = date('d-M-Y h:i A');
        $year = date('Y');
        $q_amdnumber = $this->db->query("SELECT MAX(admission_number) AS admission_number FROM admission_process ORDER BY admission_id DESC LIMIT 1");
        $a_number = $q_amdnumber->result_array();
        $admis_number = $a_number[0]['admission_number'];
        $no = number_format($admis_number);
        $admission_number = $no + 1;
        if (empty($data['lead_list_id'])) {
            $lead_list_id = $data['lead_list_id'] = "";
        } else {
            $lead_list_id = $data['lead_list_id'];
        }
        if (empty($data['demo_id'])) {
            $demo_id = $data['demo_id'] = "";
        } else {
            $demo_id = $data['demo_id'];
        }
        if (empty($data['shining_sheet_id'])) {
            $shining_sheet_id = $data['shining_sheet_id'] = "";
        } else {
            $shining_sheet_id = $data['shining_sheet_id'];
        }
        if (empty($data['quick_course_package'])) {
            $type = $data['quick_course_package'] = "";
        } else {
            $type = $data['quick_course_package'];
        }
        if (empty($data['course_or_single'])) {
            $co = $data['course_or_single'] = "";
        } else {
            $co = implode(',', $data['course_or_single']);
        }
        if (empty($data['course_or_package'])) {
            $co_p = $data['course_or_package'] = "";
        } else {
            $co_p = implode(',', $data['course_or_package']);
        }
        if (empty($data['batch_id'])) {
            $batch = $data['batch_id'] = "";
        } else {
            $batch = $data['batch_id'];
        }
        if (empty($data['no_of_installment'])) {
            $no_of_installment = $data['no_of_installment'] = "";
        } else {
            $no_of_installment = $data['no_of_installment'];
        }
        if (empty($data['mobile_no'])) {
            $mobile_no = $data['mobile_no'] = "";
        } else {
            $mobile_no = $data['mobile_no'];
        }
        if (empty($data['stating_course_id'])) {
            $stating_course_id = $data['stating_course_id'] = "";
        } else {
            $stating_course_id = $data['stating_course_id'];
        }
        if (empty($data['cheque_dd_no'])) {
            $cheque_dd_no = $data['cheque_dd_no'] = "";
        } else {
            $cheque_dd_no = $data['cheque_dd_no'];
        }
        if (empty($data['cheque_dd_date'])) {
            $cheque_dd_date = $data['cheque_dd_date'] = "";
        } else {
            $cheque_dd_date = $data['cheque_dd_date'];
        }
        if (empty($data['bank_name'])) {
            $bank_name = $data['bank_name'] = "";
        } else {
            $bank_name = $data['bank_name'];
        }
        if (empty($data['bank_branch_name'])) {
            $bank_branch_name = $data['bank_branch_name'] = "";
        } else {
            $bank_branch_name = $data['bank_branch_name'];
        }
        if (empty($data['cheque_status'])) {
            $cheque_status = $data['cheque_status'] = "";
        } else {
            $cheque_status = $data['cheque_status'];
        }
        if (empty($data['transaction_no'])) {
            $transaction_no = $data['transaction_no'] = "";
        } else {
            $transaction_no = $data['transaction_no'];
        }
        if (empty($data['transaction_date'])) {
            $transaction_date = $data['transaction_date'] = "";
        } else {
            $transaction_date = $data['transaction_date'];
        }
        if (($data['quick_course_package'] == "single")) {
            $c = $this->cm->select_data('course', 'course_id', $co);
            $depart = $c->department_id;
            $subdepart = $c->subdepart_ids;
        } else {
            $p = $this->cm->select_data('package', 'package_id', $co_p);
            $depart = $p->department_id;
            $subdepart = $p->subdepart_ids;
        }
        $check_record = $this->cm->select_data('admission_process', 'mobile_no', $mobile_no);
        if ($check_record == '') {
            $query = $this->db->query("SELECT MAX(gr_id) AS gr_id FROM admission_process ORDER BY admission_id DESC LIMIT 1");
            $q2 = $query->result();
            $g_id = 0;
            if (count($q2) > 0) {
                if (!empty($q2[0]->gr_id)) {
                    $g_id = 1 + $q2[0]->gr_id;
                } else {
                    $g_id = 101;
                }
            } else {
                $g_id = 101;
            }
        } else {
            $g_id = $check_record->gr_id;
        }
        $b_all = $this->cm->select_data('branch', 'branch_id', $data['branch_id']);
        $dm = date('Y-m');
        $date_month = substr($dm, 2);
        if ($data['branch_id'] == 1) {
            $rn1q = $this->db->query("SELECT MAX(rnw1_no) AS rnw1_no FROM admission_process ORDER BY admission_id DESC LIMIT 1");
            $rn1 = $rn1q->result_array();
            $r1 = $rn1[0]['rnw1_no'];
            $num = number_format($r1);
            $rnw = $num + 1;
        } else if ($data['branch_id'] == 5) {
            $rn2q = $this->db->query("SELECT MAX(rnw2_no) AS rnw2_no FROM admission_process ORDER BY admission_id DESC LIMIT 1");
            $rn2 = $rn2q->result_array();
            $r2 = $rn2[0]['rnw2_no'];
            $num = number_format($r2);
            $rnw = $num + 1;
        } else if ($data['branch_id'] == 8) {
            $rn3q = $this->db->query("SELECT MAX(rnw3_no) AS rnw3_no FROM admission_process ORDER BY admission_id DESC LIMIT 1");
            $rn3 = $rn3q->result_array();
            $r3 = $rn3[0]['rnw3_no'];
            $num = number_format($r3);
            $rnw = $num + 1;
        } else if ($data['branch_id'] == 3) {
            $rn4q = $this->db->query("SELECT MAX(rnw4_no) AS rnw4_no FROM admission_process ORDER BY admission_id DESC LIMIT 1");
            $rn4 = $rn4q->result_array();
            $r4 = $rn4[0]['rnw4_no'];
            $num = number_format($r4);
            $rnw = $num + 1;
        } else if ($data['branch_id'] == 9) {
            $rn1bq = $this->db->query("SELECT MAX(RW1B) AS RW1B FROM admission_process ORDER BY admission_id DESC LIMIT 1");
            $rn1b = $rn1bq->result_array();
            $r1b = $rn1b[0]['RW1B'];
            $num = number_format($r1b);
            $rnw = $num + 1;
        } else if ($data['branch_id'] == 10) {
            $rn1mmq = $this->db->query("SELECT MAX(rw1mm) AS rw1mm FROM admission_process ORDER BY admission_id DESC LIMIT 1");
            $rn1mm = $rn1mmq->result_array();
            $r1mm = $rn1mm[0]['rw1mm'];
            $num = number_format($r1mm);
            $rnw = $num + 1;
        } else if ($data['branch_id'] == 11) {
            $rnclgq = $this->db->query("SELECT MAX(clg) AS clg FROM admission_process ORDER BY admission_id DESC LIMIT 1");
            $rnclg = $rnclgq->result_array();
            $rclg = $rnclg[0]['clg'];
            $num = number_format($rclg);
            $rnw = $num + 1;
        }
        if ($data['branch_id'] == 1) {
            $rnw1_no = $rnw;
        } else {
            $rnw1_no = 0;
        }
        if ($data['branch_id'] == 5) {
            $rnw2_no = $rnw;
        } else {
            $rnw2_no = 0;
        }
        if ($data['branch_id'] == 8) {
            $rnw3_no = $rnw;
        } else {
            $rnw3_no = 0;
        }
        if ($data['branch_id'] == 3) {
            $rnw4_no = $rnw;
        } else {
            $rnw4_no = 0;
        }
        if ($data['branch_id'] == 9) {
            $RW1B = $rnw;
        } else {
            $RW1B = 0;
        }
        if ($data['branch_id'] == 10) {
            $rw1mm = $rnw;
        } else {
            $rw1mm = 0;
        }
        if ($data['branch_id'] == 11) {
            $clg = $rnw;
        } else {
            $clg = 0;
        }
        $enrollment_number = $b_all->branch_code . "-" . $date_month . "-" . $rnw;
        if (empty($batch)) {
            $admission_status = "Pending";
        } else {
            $admission_status = "Enrolled";
        }
        $record = array('lead_list_id' => $lead_list_id, 'demo_id' => $demo_id, 'shining_sheet_id' => $shining_sheet_id, 'gr_id' => $g_id, 'admission_number' => $admission_number, 'enrollment_number' => $enrollment_number, 'admission_date' => $admission_date, 'mobile_no' => $data['mobile_no'], 'alternate_mobile_no' => $data['alternate_mobile_no'], 'email' => $data['email'], 'source_id' => $data['source_id'], 'first_name' => $data['first_name'], 'surname' => $data['surname'], 'addby' => $addby, 'branch_id' => $data['branch_id'], 'year' => $year, 'department_id' => $depart, 'subdepartment_id' => $subdepart, 'type' => $type, 'course_id' => $co, 'package_id' => $co_p, 'stating_course_id' => $stating_course_id, 'batch_id' => $batch, 'tuation_fees' => $data['tuation_fees'], 'registration_fees' => $data['registration_fees'], 'no_of_installment' => $data['no_of_installment'], 'admission_status' => $admission_status, 'rnw1_no' => $rnw1_no, 'rnw2_no' => $rnw2_no, 'rnw3_no' => $rnw3_no, 'rnw4_no' => $rnw4_no, 'RW1B' => $RW1B, 'rw1mm' => $rw1mm, 'clg' => $clg);
        $respose = $this->admi->insert_endlast_ins_data('admission_process', $record);
        if (empty($respose->package_id)) {
            $adm_id = $respose->admission_id;
            $g_id = $respose->gr_id;
            $branch = $respose->branch_id;
            $admi_c = $respose->course_id;
            $co = $respose->course_id;
            $batch = $respose->batch_id;
            $s_date = $respose->admission_date;
            $sur_name = $respose->surname;
            $fistname = $respose->first_name;
            $email = $respose->email;
            $admission_courses_status = "Ongoing";
            $c_data['admission_id'] = $adm_id;
            $c_data['gr_id'] = $g_id;
            $c_data['branch_id'] = $branch;
            $c_data['courses_id'] = $admi_c;
            $c_data['batch_id'] = $batch;
            $c_data['course_orpackage_id'] = $co;
            $c_data['stating_date'] = $s_date;
            $c_data['surname'] = $sur_name;
            $c_data['email'] = $email;
            $c_data['first_name'] = $fistname;
            $c_data['admission_courses_status'] = $admission_courses_status;
            $this->db->insert('admission_courses', $c_data);
        } else {
            $ids = $respose->admission_id;
            $gr = $respose->gr_id;
            $bids = $respose->branch_id;
            $batids = $respose->batch_id;
            $adm_course = $respose->package_id;
            $date = $respose->admission_date;
            $surn = $respose->surname;
            $fistn = $respose->first_name;
            $emai = $respose->email;
            $admission_courses_status = "Ongoing";
            $record['p_all'] = $this->admi->match_package('package', 'package_id', $respose->package_id);
            if ($data['branch_id'] == 11 || $data['branch_id'] == 9) {
                $c_data['courses_id'] = $record['p_all']->package_id;
                $c_data['admission_id'] = $ids;
                $c_data['gr_id'] = $gr;
                $c_data['branch_id'] = $bids;
                $c_data['batch_id'] = $batids;
                $c_data['course_orpackage_id'] = $adm_course;
                $c_data['stating_date'] = $date;
                $c_data['surname'] = $surn;
                $c_data['first_name'] = $fistn;
                $c_data['email'] = $emai;
                $c_data['admission_courses_status'] = $admission_courses_status;
                $this->db->insert('admission_courses', $c_data);
            } else {
                $course_ids = explode(',', $record['p_all']->course_ids);
                for ($i = 0;$i < sizeof($course_ids);$i++) {
                    $courses[] = $this->admi->match_course('course', 'course_id', $course_ids[$i]);
                }
                foreach ($courses as $key => $value) {
                    if ($stating_course_id == $value->course_id) {
                        $c_data['admission_courses_status'] = "Ongoing";
                    } else {
                        $c_data['admission_courses_status'] = "Pending";
                    }
                    $c_data['courses_id'] = $value->course_id;
                    $c_data['admission_id'] = $ids;
                    $c_data['gr_id'] = $gr;
                    $c_data['branch_id'] = $bids;
                    $c_data['course_orpackage_id'] = $adm_course;
                    $c_data['stating_date'] = $date;
                    $c_data['surname'] = $surn;
                    $c_data['first_name'] = $fistn;
                    $c_data['email'] = $emai;
                    $this->db->insert('admission_courses', $c_data);
                }
            }
        }
        $instalment_data['admission_id'] = $respose->admission_id;
        $instalment_data['registration_fees'] = $data['registration_fees'];
        $instalment_data['installment_date'] = $installment_date_fisrt;
        $instalment_data['installment_no'] = "1";
        $instalment_data['due_amount'] = $data['registration_fees'];
        $instalment_data['paid_amount'] = $data['registration_fees'];
        $instalment_data['payment_type'] = "Regular Payment";
        $instalment_data['payment_mode'] = $data['payment_mode'];
        $instalment_data['cheque_dd_no'] = $cheque_dd_no;
        $instalment_data['cheque_dd_date'] = $cheque_dd_date;
        $instalment_data['bank_name'] = $bank_name;
        $instalment_data['bank_branch_name'] = $bank_branch_name;
        $instalment_data['cheque_status'] = $cheque_status;
        $instalment_data['transaction_no'] = $transaction_no;
        $instalment_data['transaction_date'] = $transaction_date;
        $this->db->insert('admission_installment', $instalment_data);
        $record['allrecord'] = $respose;
        echo json_encode($record);
    }
    public function Admissionprocess() {
        $id = $this->input->get('id');
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            date_default_timezone_set("Asia/Calcutta");
            unset($data['update_id']);
            unset($data['submit']);
            if (empty($data['installment_date_fisrt'])) {
                $installment_date_fisrt = $data['installment_date_fisrt'] = "";
            } else {
                $installment_date_fisrt = $data['installment_date_fisrt'];
            }
            if (empty($data['due_amount_first'])) {
                $due_amount_first = $data['due_amount_first'] = "";
            } else {
                $due_amount_first = $data['due_amount_first'];
            }
            if (empty($data['paid_amount_first'])) {
                $paid_amount_first = $data['paid_amount_first'] = "";
            } else {
                $paid_amount_first = $data['paid_amount_first'];
            }
            if (empty($data['due_amount'])) {
                $d_amount = $data['due_amount'] = "";
            } else {
                $d_amount = $data['due_amount'];
            }
            if (empty($data['paid_amount'])) {
                $p_amount = $data['paid_amount'] = "";
            } else {
                $p_amount = $data['paid_amount'];
            }
            @$ins_data['state_id'] = $data['state_id'];
            @$ins_data['city_id'] = $data['city_id'];
            @$ins_data['area_id'] = $data['area_id'];
            @$ins_data['present_address'] = $data['present_address'];
            @$ins_data['permanent_address'] = $data['permanent_address'];
            @$ins_data['pin_code'] = $data['pin_code'];
            @$ins_data['father_name'] = $data['father_name'];
            @$ins_data['father_email'] = $data['father_email'];
            @$ins_data['father_mobile_no'] = $data['father_mobile_no'];
            @$ins_data['father_occupation'] = $data['father_occupation'];
            @$ins_data['father_income'] = $data['father_income'];
            @$ins_data['mother_name'] = $data['mother_name'];
            @$ins_data['mother_email'] = $data['mother_email'];
            @$ins_data['mother_mobile_no'] = $data['mother_mobile_no'];
            @$ins_data['mother_occupation'] = $data['mother_occupation'];
            @$ins_data['mother_income'] = $data['mother_income'];
            if (empty($data['admission_msg_email'])) {
                $ins_data['admission_msg_email'] = "";
            } else {
                @$ins_data['admission_msg_email'] = $data['admission_msg_email'];
                $email_record = $data['father_email'];
                $emailconfigration = $this->admi->email_configration_daynamic('email_settings');
                foreach ($emailconfigration as $key) {
                    if ($key->email_type == "1") {
                        $Protocol = $key->encryption;
                        $HostName = $key->host;
                        $SmtpPortNo = $key->port;
                        $SmtpUser = $key->email;
                        $SmtpPass = $key->password;
                        $config = array('protocol' => $Protocol, 'smtp_host' => $HostName, 'smtp_port' => $SmtpPortNo, 'smtp_user' => $SmtpUser, 'smtp_pass' => $SmtpPass, 'mailtype' => 'html', 'charset' => 'utf-8');
                        $this->load->library('email');
                        $this->email->initialize($config);
                    }
                    if ($key->email_type == "2") {
                        $fromemail = $key->email;
                        $from = $fromemail;
                    }
                }
                $to = $data['email'];
                $subject = $data['subject'];
                $message = $data['message'];
                $this->email->set_newline("\r\n");
                $this->email->from($from);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->send();
            }
            @$ins_data['admission_msg_mobile'] = $data['admission_msg_mobile'];
            if (empty($ins_data['admission_msg_mobile'])) {
                $ins_data['admission_msg_mobile'] = "";
            }
            @$ins_data['school_collage_name'] = $data['school_collage_name'];
            @$ins_data['country_id'] = $data['country_id'];
            @$ins_data['school_clg_state'] = $data['school_clg_state'];
            @$ins_data['school_clg_city'] = $data['school_clg_city'];
            @$ins_data['school_clg_area'] = $data['school_clg_area'];
            @$ins_data['school_collage_type'] = $data['school_collage_type'];
            $instalment_data['admission_id'] = $data['admission_id'];
            $instalment_data['registration_fees'] = $data['registration_fees'];
            $instalment_data['installment_date'] = $installment_date_fisrt;
            $instalment_data['due_amount'] = $due_amount_first;
            $instalment_data['paid_amount'] = $paid_amount_first;
            $this->db->insert('admission_installment', $instalment_data);
            $i = 0;
            foreach ($data['installment_date'] as $key => $value) {
                $intalment_record['installment_date'] = $value;
                $intalment_record['due_amount'] = $d_amount[$i];
                $intalment_record['paid_amount'] = $p_amount[$i];
                $intalment_record['admission_id'] = $data['admission_id'];
                $intalment_record['registration_fees'] = $data['registration_fees'];
                $i++;
                $this->db->insert('admission_installment', $intalment_record);
            }
            $import_data['admission_id'] = $data['admission_id'];
            $import_data['gr_id'] = $data['gr_id'];
            $import_data['enrollment_number'] = $data['enrollment_number'];
            if ($_FILES['photos']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/admissiondocuments/";
                $new_name = time() . $_FILES["photos"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('photos')) {
                    $imagedata = $this->upload->data();
                    $import_data['photos'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            if ($_FILES['10th_marksheet']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/admissiondocuments/";
                $new_name = time() . $_FILES["10th_marksheet"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('10th_marksheet')) {
                    $imagedata = $this->upload->data();
                    $import_data['10th_marksheet'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            if ($_FILES['12th_marksheet']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/admissiondocuments/";
                $new_name = time() . $_FILES["12th_marksheet"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('12th_marksheet')) {
                    $imagedata = $this->upload->data();
                    $import_data['12th_marksheet'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            if ($_FILES['leaving_certy']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/admissiondocuments/";
                $new_name = time() . $_FILES["leaving_certy"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('leaving_certy')) {
                    $imagedata = $this->upload->data();
                    $import_data['leaving_certy'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            if ($_FILES['treal_certy']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/admissiondocuments/";
                $new_name = time() . $_FILES["treal_certy"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('treal_certy')) {
                    $imagedata = $this->upload->data();
                    $import_data['treal_certy'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            if ($_FILES['light_bill']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/admissiondocuments/";
                $new_name = time() . $_FILES["light_bill"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('light_bill')) {
                    $imagedata = $this->upload->data();
                    $import_data['light_bill'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            if ($_FILES['aadhar_card']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/admissiondocuments/";
                $new_name = time() . $_FILES["aadhar_card"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('aadhar_card')) {
                    $imagedata = $this->upload->data();
                    $import_data['aadhar_card'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            $this->db->insert('admission_documents', $import_data);
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->admi->update_data("admission_process", $ins_data, "admission_id", $id);
            }
            if ($re) {
                $this->session->set_flashdata('msg_alert', 'Your Admission Is Done');
                redirect('AddmissionController/Admissionprocess');
            } else {
                $this->session->set_flashdata('msg_alert', 'Something Wrong');
                redirect('AddmissionController/Admissionprocess');
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_country'] = $this->cm->view_all("country");
        $display['list_state'] = $this->cm->view_all("state");
        $display['list_city'] = $this->cm->view_all("cities");
        $display['list_area'] = $this->cm->view_all("area");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header_test', $update);
        $this->load->view('erp/Admissionprocess', $display);
    }
    public function ins_remarks() {
        $data = $this->admi->ins_remarks();
        echo json_encode($data);
    }
    public function upd_basic_info() {
        $data = $this->admi->upd_basic_info();
        echo json_encode($data);
    }
    public function admission_uplode_img() {
        $admission_id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $admission_id);
        echo json_encode(array('record' => $data));
    }
    public function do_upload() {
        $config['upload_path'] = "./dist/admimages/";
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        if ($this->upload->do_upload("file")) {
            $data = array('upload_data' => $this->upload->data());
            $admission_id = $this->input->post('admission_id');
            $image = $data['upload_data']['file_name'];
            $result = $this->admi->save_upload($admission_id, $image);
            echo json_decode($result);
        }
    }
    public function fetch_single_course() {
        $data = $this->input->post();
        $b_id = $data['branch_id'];
        $course = $this->admi->view_all('course');
        foreach ($course as $co) {
            $flag = 0;
            $dnbi = explode(',', $co['branch_id']);
            if (in_array($b_id, $dnbi)) {
                $flag = 1;
            }
            if ($flag == 1) {
?>
				<?php ?>
				<option value="<?php echo $co['course_id']; ?>"><?php echo $co['course_name']; ?></option>
			<?php
            }
        }
    }
    public function fetch_package_course() {
        $data = $this->input->post();
        $b_id = $data['branch_id'];
        $package = $this->admi->view_all('package');
        foreach ($package as $pac) {
            $flag = 0;
            $dnbi = explode(',', $pac['branch_id']);
            if (in_array($b_id, $dnbi)) {
                $flag = 1;
            }
            if ($flag == 1) {
?>

				<option value="<?php echo $pac['package_id']; ?>"><?php echo $pac['package_name']; ?></option>

				<?php
            }
        }
    }
    public function filter_branch_wise_faculty() {
        $data = $this->input->post();
        for ($i = 0;$i < sizeof($data);$i++) {
            $branch = $data['branch_id'][$i];
            $faculty = $this->admi->view_all('faculty');
            foreach ($faculty as $dn) {
                $flag = 0;
                $dnbi = explode(',', $dn['branch_ids']);
                if (in_array($branch, $dnbi)) {
                    $flag = 1;
                }
                if ($flag == 1) {
?>

					<?php if ($dn['status'] == 0) { ?>

						<option value="<?php echo $dn['faculty_id']; ?>"><?php echo $dn['name']; ?></option>

				<?php
                    }
                }
            }
        }
    }
    public function fetch_batch_data() {
        $id = $this->input->post('stating_course_id');
        $branch_id = $this->input->post('branch_id');
        $batch = $this->admi->get_batches_all('batches', 'course_id', $id);
        for ($i = 0;$i < sizeof($batch);$i++) {
            if ($batch[$i]->branch_id == $branch_id) { ?>

				<option value="<?php echo $batch[$i]->batches_id; ?>"><?php echo $batch[$i]->batch_name; ?></option>

			<?php
            }
        }
    }
    public function fetch_course_wise_batch() {
        $data = $this->input->post();
        $batch = $this->admi->get_batches_all('batches', 'course_id', $data['course_start_id'][0]);
        for ($i = 0;$i < sizeof($batch);$i++) {
            if ($data['branch_id'] == $batch[$i]->branch_id) { ?>

				<option value="<?php echo $batch[$i]->batches_id; ?>"><?php echo $batch[$i]->batch_name; ?></option>

			<?php
            }
        }
    }
    public function fetch_stating_singlecourse() {
        $course_ids = $this->input->post('course_id');
        for ($i = 0;$i < sizeof($course_ids);$i++) {
            $courses[] = $this->admi->match_course('course', 'course_id', $course_ids[$i]);
        }
        // print_r($courses);
        $data = array();
        if (count($courses) > 0) {
            for ($i = 0;$i < count($courses);$i++) {
                $data[$courses[$i]->course_id] = $courses[$i]->course_name;
            }
        } else {
            $data = '';
        }
        echo json_encode($data);
    }
    public function fetch_stating_course() {
        $id = $this->input->post('package_id');
        $pids = implode(',', $id);
        $pdatas = $this->cm->select_data('package', 'package_id', $pids);
        $course_ids = explode(',', $pdatas->course_ids);
        for ($i = 0;$i < sizeof($course_ids);$i++) {
            $courses[] = $this->admi->match_course('course', 'course_id', $course_ids[$i]);
        }
        // print_r($courses);
        $data = array();
        if (count($courses) > 0) {
            for ($i = 0;$i < count($courses);$i++) {
                $data[$courses[$i]->course_id] = $courses[$i]->course_name;
            }
        } else {
            $data = '';
        }
        echo json_encode($data);
    }
    public function Get_faculty() {
        $id = $this->input->post('branch_id');
        $fdata = $this->admi->get_batches_all('faculty', 'branch_ids', $id);
        for ($i = 0;$i < sizeof($fdata);$i++) {
?>
			<option value="<?php echo $fdata[$i]->faculty_id; ?>"><?php echo $fdata[$i]->name; ?></option>

			<?php
        }
    }
    public function receipt() {
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("receipt_template", "receipt_id", $id);
                if ($re) {
                    logdata('receipt_id id= ' . $id . " Deleted");
                    redirect('AddmissionController/receipt');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_receipt'] = $this->cm->select_data("receipt_template", "receipt_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['update_id']);
            unset($data['submit']);
            @$ins_data['branch_id'] = $data['branch_id'];
            @$ins_data['department_id'] = $data['department_id'];
            @$ins_data['subdepartment_id'] = $data['subdepartment_id'];
            @$ins_data['other'] = $data['other'];
            if (empty($ins_data['other'])) {
                $ins_data['other'] = "";
            }
            @$ins_data['logo'] = $data['logo'];
            @$ins_data['course'] = $data['course'];
            @$ins_data['package'] = $data['package'];
            @$ins_data['receipt_no'] = $data['receipt_no'];
            @$ins_data['receipt_date'] = $data['receipt_date'];
            @$ins_data['gr_id'] = $data['gr_id'];
            @$ins_data['registration_no'] = $data['registration_no'];
            @$ins_data['name'] = $data['name'];
            @$ins_data['the_sum_of'] = $data['the_sum_of'];
            @$ins_data['pay_now'] = $data['pay_now'];
            @$ins_data['remarks'] = $data['remarks'];
            @$ins_data['received_by_name'] = $data['received_by_name'];
            @$ins_data['receipt_type'] = implode(",", @$data['receipt_type']);
            if (empty($ins_data['receipt_type'])) {
                $ins_data['receipt_type'] = "";
            }
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("receipt_template", $ins_data, "receipt_id", $id);
            } else {
                $re = $this->cm->insert_data("receipt_template", $ins_data);
            }
            if ($re) {
                redirect('AddmissionController/receipt');
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_subdepartment'] = $this->cm->view_all("subdepartment");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_receipt'] = $this->cm->view_all("receipt_template");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header_test', $update);
        $this->load->view('erp/receipt', $display);
    }
    public function documents() {
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("admission_documents_permission", "documents_id", $id);
                if ($re) {
                    //logdata('documents_id id= '.$id." Deleted");
                    redirect('AddmissionController/documents');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_documents'] = $this->cm->select_data("admission_documents_permission", "documents_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['update_id']);
            unset($data['submit']);
            @$ins_data['branch_id'] = $data['branch_id'];
            @$ins_data['department_id'] = $data['department_id'];
            $ins_data['type'] = ($data['type']) ? : "";
            // @$ins_data['course_id'] = $data['course_or_single'];
            // if (empty($ins_data['course_id'])) {
            // 	$ins_data['course_id'] = "";
            // }
            // @$ins_data['package_id'] = $data['course_or_package'];
            // if (empty($ins_data['package_id'])) {
            // 	$ins_data['package_id'] = "";
            // }
            // @$ins_data['condition_course_package'] = $data['condition_course_package'];
            @$ins_data['photos'] = $data['photos'];
            @$ins_data['tenth_marksheet'] = $data['tenth_marksheet'];
            @$ins_data['twelth_marksheet'] = $data['twelth_marksheet'];
            @$ins_data['leaving_certy'] = $data['leaving_certy'];
            @$ins_data['treal_certy'] = $data['treal_certy'];
            @$ins_data['light_bill'] = $data['light_bill'];
            @$ins_data['aadhar_card'] = $data['aadhar_card'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("admission_documents_permission", $ins_data, "documents_id", $id);
                if ($re) {
                    $this->session->set_flashdata('msg_upd', '<b style="color: green">Record Updated successfully</b>');
                    redirect('AddmissionController/documents');
                }
            } else {
                $re = $this->cm->insert_data("admission_documents_permission", $ins_data);
                if ($re) {
                    $this->session->set_userdata("msg_ins", '<b style="color: green">Record Inserted successfully</b>');
                    redirect('AddmissionController/documents');
                }
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_subdepartment'] = $this->cm->view_all("subdepartment");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_documents'] = $this->cm->view_all("admission_documents_permission");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header_test', $update);
        $this->load->view('erp/documents', $display);
    }
    public function getDocumentListPackage() {
        // print_r($this->input->post());
        $package_id = $this->input->post('packageCourse');
        $pack_id = $package_id[0];
        $branch_id = $this->input->post('branch_id');
        $record = $this->cm->match_department('package', 'package_id', $pack_id);
        $department_id = $record->department_id;
        $reData = array('branch_id' => $branch_id, 'department_id' => $department_id, 'singleD' => 'package');
        $returnDataDocument = $this->cm->getDocumentListRecord('admission_documents_permission', $reData);
        echo json_encode($returnDataDocument);
    }
    public function getDocumentList() {
        $data = $this->input->post();
        $branch_data = $data['branch_id'];
        $singleCourseId = $data['singleCourse'][0];
        $record = $this->cm->match_department('course', 'course_id', $singleCourseId);
        $department_id = $record->department_id;
        $reData = array('branch_id' => $branch_data, 'department_id' => $department_id, 'singleD' => 'single');
        $returnDataDocument = $this->cm->getDocumentListRecord('admission_documents_permission', $reData);
        echo json_encode($returnDataDocument);
    }
    public function search_data() {
        $key = $this->input->post('s_key');
        $q = "SELECT *  

	   		FROM admission_process

	   		INNER JOIN branch 

	   		ON admission_process.branch_id=branch.branch_id

	   		WHERE first_name LIKE '%$key%' OR surname LIKE '%$key%' OR gr_id LIKE '%$key%' OR mobile_no LIKE '%	 $key%'";
        $q1 = $this->db->query($q);
        $q2 = $q1->result();
        $data['list_all_admission'] = $q2;
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_admission', $data);
    }
    public function default_search() {
        if (!empty($this->input->post('filter_admission'))) {
            $filter = $this->input->post();
            $display['all_admission'] = $this->admi->view_all_admission("admission_process", $filter);
            $display['filter_startDate'] = @$filter['filter_startDate'];
            $display['filter_endDate'] = @$filter['filter_endDate'];
            $display['filter_fname'] = @$filter['filter_fname'];
            $display['filter_lname'] = @$filter['filter_lname'];
            $display['filter_email'] = @$filter['filter_email'];
            $display['filter_mobile'] = @$filter['filter_mobile'];
            $display['filter_source'] = @$filter['filter_source'];
            $display['filter_course'] = @$filter['filter_course'];
            $display['filter_branch'] = @$filter['filter_branch'];
            $display['filter_batch'] = @$filter['filter_batch'];
            $display['filter_enquiry_assignedUser'] = @$filter['filter_enquiry_assignedUser'];
            $display['filter_follwup_interestRating'] = @$filter['filter_follwup_interestRating'];
            $display['filter_Next_Followup_Date_from'] = @$filter['filter_Next_Followup_Date_from'];
            $display['filter_Next_Followup_Date_to'] = @$filter['filter_Next_Followup_Date_to'];
            $display['filter_gr_id'] = @$filter['filter_gr_id'];
            $display['filter_department'] = @$filter['filter_department'];
            $display['filter_Interest_Level'] = @$filter['filter_Interest_Level'];
            $display['filter_Student_Response'] = @$filter['filter_Student_Response'];
            $display['filter_package'] = @$filter['filter_package'];
            $display['filter_on'] = "dfgf";
        }
        // else
        // {
        //     $display['all_admission'] = $this->admi->view_all_admission("admission_process");
        // }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_batch'] = $this->cm->view_all("batch_list");
        $display['list_source'] = $this->cm->view_all("source");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $display['list_installment'] = $this->cm->view_all("admission_installment");
        // echo "<pre>";
        // print_r($display['demo_data']);
        // die;
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header_test', $update);
        $this->load->view('erp/default_search', $display);
    }
    public function GetInstallmentDetails() {
        $packageData = $this->input->post('packageId');
        $record = array();
        for ($i = 0;$i < count($packageData);$i++) {
            $pId = $packageData[$i];
            $record[] = $this->cm->get_packageName_installment('package', 'package_id', $pId);
        }
        $total = 0;
        for ($i = 0;$i < sizeof($record);$i++) {
            $total = $total + $record[$i]->package_fees;
        }
        $record['get_install'] = $total;
        echo json_encode($record);
    }
    public function GetInstallmentDetailssinglecourse() {
        $courseData = $this->input->post('courseId');
        $record = array();
        for ($i = 0;$i < count($courseData);$i++) {
            $cId = $courseData[$i];
            $record[] = $this->admi->get_courseSingle_installment('course', 'course_id', $cId);
        }
        // print_r($record[0]->course_fees);
        $total = 0;
        for ($i = 0;$i < sizeof($record);$i++) {
            $total = $total + $record[$i]->course_fees;
        }
        $data['get_install'] = $total;
        echo json_encode($data);
    }
    public function GetInstall_with_registration() {
        $record['data'] = array('tution_fee' => $this->input->post('tution_fee'), 'registration_fee' => $this->input->post('reg_fees'), 'no_of_installment' => $this->input->post('no_of_install'));
        $this->load->view('erp/get_installment_record', $record);
    }
    public function erp_getinstallment_withregistration() {
        $record['data'] = array('tution_fee' => $this->input->post('tution_fee'), 'registration_fee' => $this->input->post('reg_fees'), 'no_of_installment' => $this->input->post('no_of_install'));
        $this->load->view('erp/erpadmission_installment', $record);
    }
    public function upd_shining_sheet_data() {
        $data = $this->admi->upd_shining_sheet();
        echo json_encode($data);
    }
    public function get_adm_record() {
        $admission_id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_admission_basic_details('admission_process', 'admission_id', $admission_id);
        echo json_encode(array('record' => $data));
    }
    public function get_admission_email_record() {
        $admission_id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $admission_id);
        echo json_encode(array('record' => $data));
    }
    public function get_admission_sms_record() {
        $admission_id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $admission_id);
        echo json_encode(array('record' => $data));
    }
    public function get_sms_template_record() {
        $sms_id = $this->input->post('sms_template_id');
        $data['records'] = $this->cm->get_sms_template_record('sms_template', 'category', $sms_id);
        echo json_encode(array('reco' => $data));
    }
    public function send_sms_record() {
        $data = $this->input->post();
        $record = $this->sendSMS($data['primary_sms'], $data['sms_textarea']);
        if ($record) {
            $userdata = $this->session->userdata('Admin');
            $r_record['user_id'] = $userdata['id'];
            $r_record['user_role'] = $userdata['name'];
            $r_record['type'] = "SMS";
            $r_record['comment'] = $data['sms_textarea'];
            $r_record['admission_id'] = $data['sms_recepient_id'];
            $r_record['subject'] = $data['sms_template'];
            unset($data['sms_textarea']);
            unset($data['email']);
            date_default_timezone_set("Asia/Calcutta");
            $r_record['timing_sender'] = date('m/d/Y h:i A');
            $r_record['status'] = "success";
            $this->admi->quick_admission_data('admission_followup_history', $r_record);
            $this->admi->quick_admission_data('admission_followup_history_response', $r_record);
            echo "sent";
        }
    }
    public function sendSMS($mo, $msg) {
        $mobile = "91" . $mo;
        $msg = urlencode($msg);
        //echo $msg;
        $url = "http://dndsms.vishawebsolutions.com/api/mt/SendSMS?user=Hitesh-RNW&password=Hitz9898&senderid=RNWEDU&channel=Trans&DCS=8&flashsms=0&number=$mobile&text=$msg&route=15";
        /* init the resource */
        $ch = curl_init();
        curl_setopt_array($ch, array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0));
        /* get response */
        $output = curl_exec($ch);
        /* Print error if any */
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }
        curl_close($ch);
        return 1;
    }
    public function get_email_template_record() {
        if (!empty($this->input->post('template_id'))) {
            $id = $this->input->post('template_id');
            $data['record'] = $this->cm->get_template_rec('email_template_category', 'id', $id);
            echo json_encode(array('reco' => $data));
        }
    }
    public function send_email() {
        $data = $this->input->post();
        $emailconfigration = $this->admi->email_configration_daynamic('email_settings');
        foreach ($emailconfigration as $key) {
            if ($key->email_type == "1") {
                $Protocol = $key->encryption;
                $HostName = $key->host;
                $SmtpPortNo = $key->port;
                $SmtpUser = $key->email;
                $SmtpPass = $key->password;
                $config = array('protocol' => $Protocol, 'smtp_host' => $HostName, 'smtp_port' => $SmtpPortNo, 'smtp_user' => $SmtpUser, 'smtp_pass' => $SmtpPass, 'mailtype' => 'html', 'charset' => 'utf-8');
                $this->load->library('email');
                $this->email->initialize($config);
            }
            if ($key->email_type == "2") {
                $fromemail = $key->email;
                $from = $fromemail;
            }
        }
        $to = $data['email'];
        $subject = $data['subject'];
        $message = $data['message'];
        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            $userdata = $this->session->userdata('Admin');
            // print_r($data['id']);
            $data['user_role'] = $userdata['name'];
            $data['user_id'] = $userdata['id'];
            $data['type'] = "email";
            $data['comment'] = $data['message'];
            unset($data['message']);
            unset($data['email']);
            date_default_timezone_set("Asia/Calcutta");
            $data['timing_sender'] = date('m/d/Y h:i A');
            $data['status'] = "success";
            $this->admi->quick_admission_data('admission_followup_history', $data);
            $this->admi->quick_admission_data('admission_followup_history_response', $data);
            echo "sent";
        } else {
            echo $this->email->print_debugger();
        }
    }
    public function get_admission_Student_detail() {
        $id = $this->input->post('admission_id');
        $data['adm_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $id);
        $data['list_remark'] = $this->admi->get_remarks_id_wise("admission_remarks", "admission_id", $id);
        $data['admission_courses'] = $this->admi->get_shining_sheet("admission_courses", "admission_id", $id);
        $data['adm_instalment'] = $this->admi->get_instalment("admission_installment", "admission_id", $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $this->load->view('erp/ajax_admission_student_record', $data);
    }
    public function get_courses_data() {
        $id = $this->input->post('admission_id');
        $data['adm_record'] = $this->admi->get_adm_cp_record_list('admission_process', 'admission_id', $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_admission_courses', $data);
    }
    public function upgarede_course_without_fees_modification() {
        $id = $this->input->post('admission_id');
        $data['adm_record'] = $this->admi->get_adm_cp_record_list('admission_process', 'admission_id', $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_withoutcoursefees_modification', $data);
    }
    public function pass_data_course() {
        $data = $this->input->post();
        $course_single = $data['course_single'];
        // exit;
        if ($course_single == 'single') {
            // echo "single";
            $record['c_all'] = $this->admi->match_course('course', 'course_id', $data['course_orsingle']);
            $c_id = $record['c_all']->course_id;
            $c_name = $record['c_all']->course_name;
            $c_fees = $record['c_all']->course_fees;
            $c_record = array('course_id' => $c_id, 'course_name' => $c_name, 'course_package' => "single", 'course_fees' => $c_fees);
            // $c_record = array('course_package'=>"single");
            echo json_encode($c_record);
        } else {
            // echo "package";
            $record['p_all'] = $this->admi->match_package('package', 'package_id', $data['course_orpackage']);
            $p_id = $record['p_all']->package_id;
            $p_name = $record['p_all']->package_name;
            $p_fees = $record['p_all']->package_fees;
            $p_record = array('course_id' => $p_id, 'course_name' => $p_name, 'course_package' => "package", 'package_fees' => $p_fees);
            // $p_record = array('course_package'=>"package");
            echo json_encode($p_record);
        }
    }
    public function get_adm() {
        $id = $this->input->post('admission_id');
        $data['adm_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_admission_edit', $data);
    }
    public function ajax_admission_payment() {
        $id = $this->input->post('admission_id');
        $data['adm_instalment'] = $this->admi->get_instalment("admission_installment", "admission_id", $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_payment', $data);
    }
    public function ajax_admission_cp() {
        $id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $id);
        $data['admission_courses'] = $this->admi->get_shining_sheet("admission_courses", "admission_id", $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['list_dropdown_adm'] = $this->cm->view_all("dropdown_adm");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_cp_tbl_data', $data);
    }
    public function ajax_admission_remarks() {
        $id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $id);
        $data['all_remarks'] = $this->admi->get_adm_id_wise_remark("admission_remarks", "admission_id", $id);
        $data['all_admission_remarsk'] = $this->admi->get_all_remarks("admission_remarks", "admission_id", $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajx_admission_remarks', $data);
    }
    public function ajax_Cancellation_admission() {
        $id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['list_dropdown_adm'] = $this->cm->view_all("dropdown_adm");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_Cancellation_admission', $data);
    }
    public function ajx_Terminated_admission() {
        $id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['list_dropdown_adm'] = $this->cm->view_all("dropdown_adm");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajx_Terminated_admission', $data);
    }
    public function Course_as_completed() {
        $data = $this->input->post();
        date_default_timezone_set("Asia/Calcutta");
        $remarks_time = date('h:i A');
        if (empty($data['course_completed_adm_id'])) {
            $admission_id = $data['course_completed_adm_id'] = "";
        } else {
            $admission_id = $data['course_completed_adm_id'];
        }
        if (empty($data['course_completed_date'])) {
            $course_completed_date = $data['course_completed_date'] = "";
        } else {
            $course_completed_date = $data['course_completed_date'];
        }
        if (empty($data['dropdown_adm_id'])) {
            $dropdown_adm_id = $data['dropdown_adm_id'] = "";
        } else {
            $dropdown_adm_id = $data['dropdown_adm_id'];
        }
        if (empty($data['admission_remrak'])) {
            $admission_remrak = $data['admission_remrak'] = "";
        } else {
            $admission_remrak = $data['admission_remrak'];
        }
        if (empty($data['addby'])) {
            $addby = $data['addby'] = "";
        } else {
            $addby = $data['addby'];
        }
        if (empty($data['admission_courses_id'])) {
            $admission_courses_id = $data['admission_courses_id'] = "";
        } else {
            $admission_courses_id = $data['admission_courses_id'];
        }
        if (empty($data['admission_courses_status'])) {
            $admission_courses_status = $data['admission_courses_status'] = "";
        } else {
            $admission_courses_status = $data['admission_courses_status'];
        }
        $record = array('admission_id' => $admission_id, 'admission_remrak' => $admission_remrak, 'remarks_date' => $course_completed_date, 'dropdown_adm_id' => $dropdown_adm_id, 'remarks_time' => $remarks_time, 'addby' => $addby);
        $this->admi->save_data('admission_remarks', $record);
        $this->db->set('admission_courses_status', $admission_courses_status);
        $this->db->set('course_completed_date', $course_completed_date);
        $this->db->where('admission_courses_id', $admission_courses_id);
        $result = $this->db->update('admission_courses');
        if ($result) {
            $recp["all_record"] = array('status' => 1, "msg" => "HI! Your Course Is Now Completed.");
            echo json_encode($recp); // echo "1";
            
        } else {
            $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp); // echo "2";
            
        }
    }
    public function admission_canceled() {
        $data = $this->input->post();
        date_default_timezone_set("Asia/Calcutta");
        $remarks_time = date('h:i A');
        if (empty($data['admission_id'])) {
            $admission_id = $data['admission_id'] = "";
        } else {
            $admission_id = $data['admission_id'];
        }
        if (empty($data['cancellation_admission_date'])) {
            $cancellation_admission_date = $data['cancellation_admission_date'] = "";
        } else {
            $cancellation_admission_date = $data['cancellation_admission_date'];
        }
        if (empty($data['dropdown_adm_id'])) {
            $dropdown_adm_id = $data['dropdown_adm_id'] = "";
        } else {
            $dropdown_adm_id = $data['dropdown_adm_id'];
        }
        if (empty($data['admission_remrak'])) {
            $admission_remrak = $data['admission_remrak'] = "";
        } else {
            $admission_remrak = $data['admission_remrak'];
        }
        if (empty($data['addby'])) {
            $addby = $data['addby'] = "";
        } else {
            $addby = $data['addby'];
        }
        $this->db->select_sum('paid_amount');
        $this->db->from('admission_installment');
        $this->db->where('admission_id', $admission_id);
        $this->db->count_all();
        $query = $this->db->get();
        $querydata = $query->result();
        $paying_amount = array();
        for ($i = 0;$i < sizeof($querydata);$i++) {
            $paying_amount = $querydata[$i]->paid_amount;
        }
        $value = "0";
        $paid_amount = $paying_amount + $value;
        $adm_data = $this->cm->select_data('admission_process', 'admission_id', $admission_id);
        if ($paid_amount == $adm_data->tuation_fees) {
            $record = array('admission_id' => $admission_id, 'admission_remrak' => $admission_remrak, 'remarks_date' => $cancellation_admission_date, 'dropdown_adm_id' => $dropdown_adm_id, 'remarks_time' => $remarks_time, 'addby' => $addby);
            $this->admi->save_data('admission_remarks', $record);
            $admission_status = "Canceled";
            $this->db->set('admission_status', $admission_status);
            $this->db->set('cancellation_admission_date', $cancellation_admission_date);
            $this->db->set('dropdown_adm_id', $dropdown_adm_id);
            $this->db->where('admission_id', $admission_id);
            $result = $this->db->update('admission_process');
            if ($result) {
                $recp["all_record"] = array('status' => 1, "msg" => "HI! Finally Your Admission Is Cancelled.");
                echo json_encode($recp); // echo "1";
                
            } else {
                $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
                echo json_encode($recp); // echo "2";
                
            }
        } else {
            $fees_not_pay = "Not Match";
            if ($fees_not_pay == "Not Match") {
                $recp["all_record"] = array('status' => 3, "msg" => "Your Admission will Be Canceled After You Complete Your Fees.");
                echo json_encode($recp); // echo "1";
                
            } else {
                $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
                echo json_encode($recp); // echo "2";
                
            }
        }
    }
    public function admission_Terminate() {
        $data = $this->input->post();
        date_default_timezone_set("Asia/Calcutta");
        $remarks_time = date('h:i A');
        if (empty($data['admission_id'])) {
            $admission_id = $data['admission_id'] = "";
        } else {
            $admission_id = $data['admission_id'];
        }
        if (empty($data['terminate_date'])) {
            $terminate_date = $data['terminate_date'] = "";
        } else {
            $terminate_date = $data['terminate_date'];
        }
        if (empty($data['dropdown_adm_id'])) {
            $dropdown_adm_id = $data['dropdown_adm_id'] = "";
        } else {
            $dropdown_adm_id = $data['dropdown_adm_id'];
        }
        if (empty($data['admission_remrak'])) {
            $admission_remrak = $data['admission_remrak'] = "";
        } else {
            $admission_remrak = $data['admission_remrak'];
        }
        if (empty($data['addby'])) {
            $addby = $data['addby'] = "";
        } else {
            $addby = $data['addby'];
        }
        $record = array('admission_id' => $admission_id, 'admission_remrak' => $admission_remrak, 'remarks_date' => $terminate_date, 'dropdown_adm_id' => $dropdown_adm_id, 'remarks_time' => $remarks_time, 'addby' => $addby);
        $this->admi->save_data('admission_remarks', $record);
        $admission_status = "Terminated";
        $this->db->set('admission_status', $admission_status);
        $this->db->set('terminate_date', $terminate_date);
        $this->db->set('dropdown_adm_id', $dropdown_adm_id);
        $this->db->where('admission_id', $admission_id);
        $result = $this->db->update('admission_process');
        if ($result) {
            $recp["all_record"] = array('status' => 1, "msg" => "HI! Finally Your Admission Is Terminated.");
            echo json_encode($recp); // echo "1";
            
        } else {
            $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp); // echo "2";
            
        }
    }
    public function admission_completed() {
        $data = $this->input->post();
        date_default_timezone_set("Asia/Calcutta");
        $remarks_time = date('h:i A');
        if (empty($data['admission_id'])) {
            $admission_id = $data['admission_id'] = "";
        } else {
            $admission_id = $data['admission_id'];
        }
        if (empty($data['admission_completed_date'])) {
            $admission_completed_date = $data['admission_completed_date'] = "";
        } else {
            $admission_completed_date = $data['admission_completed_date'];
        }
        if (empty($data['dropdown_adm_id'])) {
            $dropdown_adm_id = $data['dropdown_adm_id'] = "";
        } else {
            $dropdown_adm_id = $data['dropdown_adm_id'];
        }
        if (empty($data['admission_remrak'])) {
            $admission_remrak = $data['admission_remrak'] = "";
        } else {
            $admission_remrak = $data['admission_remrak'];
        }
        if (empty($data['addby'])) {
            $addby = $data['addby'] = "";
        } else {
            $addby = $data['addby'];
        }
        $record = array('admission_id' => $admission_id, 'admission_remrak' => $admission_remrak, 'remarks_date' => $admission_completed_date, 'dropdown_adm_id' => $dropdown_adm_id, 'remarks_time' => $remarks_time, 'addby' => $addby);
        $this->admi->save_data('admission_remarks', $record);
        $data_record['adm'] = $this->admi->match_admdata('admission_process', 'admission_id', $admission_id);
        $adm_id = $data_record['adm']->admission_id;
        $admission_status = "Completed";
        $this->db->set('admission_status', $admission_status);
        $this->db->where('admission_id', $adm_id);
        $result = $this->db->update('admission_process');
        if ($result) {
            $recp["all_record"] = array('status' => 1, "msg" => "HI! Your Admission Is Now Completed.");
            echo json_encode($recp); // echo "1";
            
        } else {
            $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp); // echo "2";
            
        }
    }
    public function ajax_admission_hold() {
        $id = $this->input->post('admission_id');
        $data['adm_get_record'] = $this->admi->get_adm_record_list('admission_process', 'admission_id', $id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['list_dropdown_adm'] = $this->cm->view_all("dropdown_adm");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_admission_hold', $data);
    }
    public function admission_Hold() {
        $data = $this->input->post();
        date_default_timezone_set("Asia/Calcutta");
        $remarks_time = date('h:i A');
        if (empty($data['admission_id'])) {
            $admission_id = $data['admission_id'] = "";
        } else {
            $admission_id = $data['admission_id'];
        }
        if (empty($data['hold_stating_date'])) {
            $hold_stating_date = $data['hold_stating_date'] = "";
        } else {
            $hold_stating_date = $data['hold_stating_date'];
        }
        if (empty($data['hold_ending_date'])) {
            $hold_ending_date = $data['hold_ending_date'] = "";
        } else {
            $hold_ending_date = $data['hold_ending_date'];
        }
        if (empty($data['dropdown_adm_id'])) {
            $dropdown_adm_id = $data['dropdown_adm_id'] = "";
        } else {
            $dropdown_adm_id = $data['dropdown_adm_id'];
        }
        if (empty($data['admission_remrak'])) {
            $admission_remrak = $data['admission_remrak'] = "";
        } else {
            $admission_remrak = $data['admission_remrak'];
        }
        if (empty($data['hold_addby'])) {
            $addby = $data['hold_addby'] = "";
        } else {
            $addby = $data['hold_addby'];
        }
        $record = array('admission_id' => $admission_id, 'admission_remrak' => $admission_remrak, 'remarks_date' => $hold_stating_date, 'remarks_time' => $remarks_time, 'hold_ending_date' => $hold_ending_date, 'dropdown_adm_id' => $dropdown_adm_id, 'addby' => $addby);
        $this->admi->save_data('admission_remarks', $record);
        $admission_status = "Hold";
        $this->db->set('admission_status', $admission_status);
        $this->db->set('hold_stating_date', $hold_stating_date);
        $this->db->set('hold_ending_date', $hold_ending_date);
        $this->db->set('dropdown_adm_id', $dropdown_adm_id);
        $this->db->set('hold_addby', $addby);
        $this->db->where('admission_id', $admission_id);
        $result = $this->db->update('admission_process');
        if ($result) {
            $recp["all_record"] = array('status' => 1, "msg" => "HI Student! Your Admission Has Been Withhold For A Short Period Of Time.");
            echo json_encode($recp); // echo "1";
            
        } else {
            $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp); // echo "2";
            
        }
    }
    public function admission_upgrade() {
        $data = $this->input->post();
        $name = $_SESSION['user_name'];
        if ($_SESSION['logtype'] == "Super Admin") {
            $data['user_data'] = $this->admi->get_adm_record_list('admin', 'name', $name);
            foreach ($data['user_data'] as $key => $value) {
                $record = $this->admi->get_adm_record_list('branch', 'branch_id', $value);
            }
        } else {
            $user = $this->admi->get_adm_record_list('user', 'name', $name);
            $record = $this->admi->get_adm_record_list('branch', 'branch_id', $user->branch_ids);
        }
        $branch_id = $record->branch_id;
        $year = date('Y');
        if (empty($data['admission_id'])) {
            $admission_id = $data['admission_id'] = "";
        } else {
            $admission_id = $data['admission_id'];
        }
        if (empty($branch_id)) {
            $branch_id = $branch_id = "";
        } else {
            $branch_id = $branch_id;
        }
        if (empty($year)) {
            $year = $year = "";
        } else {
            $year = $year;
        }
        if (empty($data['course_orpackage'])) {
            $package_id = $data['course_orpackage'] = "";
        } else {
            $package_id = $data['course_orpackage'];
        }
        if (empty($data['course_orsingle'])) {
            $course_id = $data['course_orsingle'] = "";
        } else {
            $course_id = $data['course_orsingle'];
        }
        if (empty($data['tuation_fees'])) {
            $tuation_fees = $data['tuation_fees'] = "";
        } else {
            $tuation_fees = $data['tuation_fees'];
        }
        if (empty($data['registration_fees'])) {
            $registration_fees = $data['registration_fees'] = "";
        } else {
            $registration_fees = $data['registration_fees'];
        }
        if (empty($data['no_of_installment'])) {
            $no_of_installment = $data['no_of_installment'] = "";
        } else {
            $no_of_installment = $data['no_of_installment'];
        }
        if (empty($data['installment_date_first'])) {
            $installment_date_first = $data['installment_date_first'] = "";
        } else {
            $installment_date_first = $data['installment_date_first'];
        }
        if (empty($data['due_amount_first'])) {
            $due_amount_first = $data['due_amount_first'] = "";
        } else {
            $due_amount_first = $data['due_amount_first'];
        }
        if (empty($data['paid_amount_first'])) {
            $paid_amount_first = $data['paid_amount_first'] = "";
        } else {
            $paid_amount_first = $data['paid_amount_first'];
        }
        if (empty($data['installment_date'])) {
            $installment_date = $data['installment_date'] = "";
        } else {
            $installment_date = $data['installment_date'];
        }
        if (empty($data['due_amount'])) {
            $d_amount = $data['due_amount'] = "";
        } else {
            $d_amount = $data['due_amount'];
        }
        if (empty($data['paid_amount'])) {
            $p_amount = $data['paid_amount'] = "";
        } else {
            $p_amount = $data['paid_amount'];
        }
        $instalment_data['admission_id'] = $admission_id;
        $instalment_data['registration_fees'] = $registration_fees;
        $instalment_data['installment_date'] = $installment_date_first;
        $instalment_data['due_amount'] = $due_amount_first;
        $instalment_data['paid_amount'] = $paid_amount_first;
        $this->db->insert('admission_installment', $instalment_data);
        $i = 0;
        foreach ($data['installment_date'] as $key => $value) {
            $intalment_record['installment_date'] = $value;
            $intalment_record['due_amount'] = $d_amount[$i];
            $intalment_record['paid_amount'] = $p_amount[$i];
            $intalment_record['admission_id'] = $admission_id;
            $intalment_record['registration_fees'] = $registration_fees;
            $i++;
            $this->db->insert('admission_installment', $intalment_record);
        }
        $this->db->set('branch_id', $branch_id);
        $this->db->set('year', $year);
        $this->db->set('package_id', $package_id);
        $this->db->set('course_id', $course_id);
        $this->db->set('no_of_installment', $no_of_installment);
        $this->db->set('tuation_fees', $tuation_fees);
        $this->db->set('registration_fees', $registration_fees);
        $this->db->where('admission_id', $admission_id);
        $result = $this->db->update('admission_process');
        if ($result) {
        $recp["all_record"] = array('status' => 1, "msg" => "Successfully Upgraded");
        echo json_encode($recp); // echo "1";
        } else {
            $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong!");
            echo json_encode($recp); // echo "2";
            
        }
    }
    public function without_fees_modification_course() {
        $data = $this->input->post();
        if (empty($data['course_id'])) {
            $course_id = $data['course_id'] = "";
        } else {
            $course_id = $data['course_id'];
            $co = $this->cm->select_data('course', 'course_id', $course_id);
            $department_id = $co->department_id;
            $subdepartment_id = $co->subdepart_ids;
        }
        if (empty($data['package_id'])) {
            $package_id = $data['package_id'] = "";
        } else {
            $package_id = $data['package_id'];
            $pa = $this->cm->select_data('package', 'package_id', $package_id);
            $department_id = $pa->department_id;
            $subdepartment_id = $pa->subdepart_ids;
        }
        if (!empty($data['admission_id'])) {
            $old_record['re'] = $this->admi->get_old_admission_record('admission_process', 'admission_id', $data['admission_id']);
            if (@$old_record['re']->department_id == @$department_id) {
                $up_department_id = $department_id . "&#nochange";
            } else {
                $up_department_id = $department_id . "&#change";
            }
            if (@$old_record['re']->subdepartment_id == @$subdepartment_id) {
                $up_subdepartment_id = $subdepartment_id . "&#nochange";
            } else {
                $up_subdepartment_id = $subdepartment_id . "&#change";
            }
            if (@$old_record['re']->type == @$data['type']) {
                $up_type = $data['type'] . "&#nochange";
            } else {
                $up_type = $data['type'] . "&#change";
            }
            if (@$old_record['re']->course_id == @$course_id) {
                $up_course_id = $course_id . "&#nochange";
            } else {
                $up_course_id = $course_id . "&#change";
            }
            if (@$old_record['re']->package_id == @$package_id) {
                $up_package_id = $package_id . "&#nochange";
            } else {
                $up_package_id = $package_id . "&#change";
            }
            $up_created_bye = $_SESSION['user_name'];
            date_default_timezone_set("Asia/Calcutta");
            $admission_upd_date = date('m/d/Y h:i A');
            $created_bye = $_SESSION['user_name'];
            $record = array('department_id' => $department_id, 'subdepartment_id' => $subdepartment_id, 'type' => $data['type'], 'course_id' => $course_id, 'package_id' => $package_id);
            $re = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
            if ($re) {
                $history_record = array('admission_id' => $data['admission_id'], 'department_id' => $up_department_id, 'subdepartment_id' => $up_subdepartment_id, 'type' => $up_type, 'course_id' => $up_course_id, 'package_id' => $up_package_id, 'created_bye' => $up_created_bye, 'admission_upd_date' => @$admission_upd_date);
                $this->admi->quick_adm_data('history_adm_without_fees', $history_record);
            }
            if ($st = 1) {
                $recp["all_record"] = array('status' => 1, "msg" => "Successfully Upgrade Course");
                echo json_encode($recp); // echo "1";
                
            } else {
                $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
                echo json_encode($recp); // echo "2";
                
            }
        } else {
            $check_ep = $this->admi->check_record_alerady('admission_process', $record);
            if ($check_ep == 0) {
                $re = $this->admi->quick_adm_data('admission_process', $record);
                if ($re) {
                    $history_record = array('admission_id' => @$re, 'department_id' => $department_id . "&#nochange", 'subdepartment_id' => $subdepartment_id . "&#nochange", 'type' => $data['type'] . "&#nochange", 'course_id' => $course_id . "&#nochange", 'package_id' => $package_id . "&#nochange", 'created_bye' => $up_created_bye . "&#nochange", 'admission_upd_date' => @$admission_upd_date);
                    $this->admi->quick_adm_data('history_adm_without_fees', $history_record);
                }
                $st = 2;
                echo $st;
            } else {
                $st = 0;
                echo $st;
            }
        }
    }
    public function get_instalment_upgrate_adm() {
        // echo "<pre>";
        // print_r($this->input->post());
        $record['data'] = array('tution_fee' => $this->input->post('tution_fee'), 'registration_fee' => $this->input->post('reg_fees'), 'no_of_installment' => $this->input->post('no_of_install'));
        $this->load->view('erp/get_upgrade_course_installment_record', $record);
        // die;
        
    }
    public function get_multi_adm_course() {
        $admission_id = $this->input->post('multic');
        $data['adm_get_record'] = $this->admi->get_adm_list('admission_process', 'admission_id', $admission_id);
        if ($data['adm_get_record']->type == 'single') {
            $data_record['adm_get_record'] = $this->admi->get_adm_list_single_check_record('admission_process', 'admission_id', $admission_id);
        } else {
            $data_record['adm_get_record'] = $this->admi->get_adm_list_package_check_record('admission_process', 'admission_id', $admission_id);
        }
        echo json_encode(array('record' => $data_record));
    }
    public function upd_admission_basic() {
        $data = $this->input->post();
        if (empty($data['admission_id'])) {
            $admission_id = $data['admission_id'] = "";
        } else {
            $admission_id = $data['admission_id'];
        }
        if (empty($data['first_name'])) {
            $first_name = $data['first_name'] = "";
        } else {
            $first_name = $data['first_name'];
        }
        if (empty($data['surname'])) {
            $surname = $data['surname'] = "";
        } else {
            $surname = $data['surname'];
        }
        if (empty($data['email'])) {
            $email = $data['email'] = "";
        } else {
            $email = $data['email'];
        }
        if (empty($data['mobile_no'])) {
            $mobile_no = $data['mobile_no'] = "";
        } else {
            $mobile_no = $data['mobile_no'];
        }
        if (empty($data['branch_id'])) {
            $branch_id = $data['branch_id'] = "";
        } else {
            $branch_id = $data['branch_id'];
        }
        if (@$data['submit'] == 'Upd Admission') {
            $admission_id = '';
        } else {
            $admission_id = $data['admission_id'];
        }
        $record = array('admission_id' => $admission_id, 'first_name' => $first_name, 'surname' => $surname, 'email' => $email, 'mobile_no' => $mobile_no, 'branch_id' => $branch_id);
        if (!empty($admission_id)) {
            $old_record['re'] = $this->admi->get_old_admission_record('admission_process', 'admission_id', $admission_id);
            if (@$old_record['re']->first_name == @$data['first_name']) {
                $up_first_name = $data['first_name'] . "&#nochange";
            } else {
                $up_first_name = $data['first_name'] . "&#change";
            }
            if (@$old_record['re']->surname == @$data['surname']) {
                $up_surname = $data['surname'] . "&#nochange";
            } else {
                $up_surname = $data['surname'] . "&#change";
            }
            if (@$old_record['re']->email == @$data['email']) {
                $up_email = $data['email'] . "&#nochange";
            } else {
                $up_email = $data['email'] . "&#change";
            }
            if (@$old_record['re']->mobile_no == @$data['mobile_no']) {
                $up_mobile_no = $data['mobile_no'] . "&#nochange";
            } else {
                $up_mobile_no = $data['mobile_no'] . "&#change";
            }
            if (@$old_record['re']->branch_id == @$data['branch_id']) {
                $up_branch_id = $data['branch_id'] . "&#nochange";
            } else {
                $up_branch_id = $data['branch_id'] . "&#change";
            }
            date_default_timezone_set("Asia/Calcutta");
            $admission_upd_date = date('m/d/Y h:i A');
            $re = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $admission_id);
            if ($re) {
                $history_record = array('admission_id' => $admission_id, 'first_name' => $up_first_name, 'surname' => $up_surname, 'email' => $up_email, 'mobile_no' => $up_mobile_no, 'branch_id' => $up_branch_id, 'admission_upd_date' => @$admission_upd_date);
                $this->admi->quick_adm_data('history_adm_basicdetails', $history_record);
            }
            $st = 1;
            echo $st;
        } else {
            $check_ep = $this->admi->check_record_alerady('admission_process', $record);
            if ($check_ep == 0) {
                $re = $this->admi->quick_adm_data('admission_process', $record);
                if ($re) {
                    $history_record = array('admission_id' => @$re, 'first_name' => $first_name . "&#nochange", 'surname' => $surname . "&#nochange", 'email' => $email . "&#nochange", 'mobile_no' => $mobile_no . "&#nochange", 'branch_id' => $branch_id . "&#nochange", 'admission_upd_date' => @$admission_upd_date);
                    $this->admi->quick_adm_data('history_adm_basicdetails', $history_record);
                }
                $st = 2;
                echo $st;
            } else {
                $st = 0;
                echo $st;
            }
        }
    }
    public function upd_admission_cp() {
        $data = $this->input->post();
        if (empty($data['admission_id'])) {
            $admission_id = $data['admission_id'] = "";
        } else {
            $admission_id = $data['admission_id'];
        }
        if (empty($data['type'])) {
            $type = $data['type'] = "";
        } else {
            $type = $data['type'];
        }
        if (empty($data['course_id'])) {
            $course_id = $data['course_id'] = "";
        } else {
            $course_id = implode(',', $data['course_id']);
            $co = $this->cm->select_data('course', 'course_id', $course_id);
            $year = $co->session;
            $department_id = $co->department_id;
            $subdepartment_id = $co->subdepart_ids;
        }
        if (empty($data['package_id'])) {
            $package_id = $data['package_id'] = "";
        } else {
            $package_id = implode(',', $data['package_id']);
            $pa = $this->cm->select_data('package', 'package_id', $package_id);
            $year = $pa->session;
            $department_id = $pa->department_id;
            $subdepartment_id = $pa->subdepart_ids;
        }
        if (empty($data['faculty_id'])) {
            $faculty_id = $data['faculty_id'] = "";
        } else {
            $faculty_id = $data['faculty_id'];
        }
        if (empty($data['batch_id'])) {
            $batch_id = $data['batch_id'] = "";
        } else {
            $batch_id = $data['batch_id'];
        }
        if (@$data['submit'] == 'Upd Admission') {
            $admission_id = '';
        } else {
            $admission_id = $data['admission_id'];
        }
        $record = array('admission_id' => $admission_id, 'branch_id' => $data['branch_ids'], 'year' => $year, 'department_id' => $department_id, 'subdepartment_id' => $subdepartment_id, 'type' => $type, 'course_id' => $course_id, 'package_id' => $package_id, 'faculty_id' => $faculty_id, 'batch_id' => $batch_id);
        if (!empty($admission_id)) {
            $old_record['re'] = $this->admi->get_old_admission_record('admission_process', 'admission_id', $admission_id);
            if (@$old_record['re']->branch_id == @$data['branch_ids']) {
                $up_admission_branch = $data['branch_ids'] . "&#nochange";
            } else {
                $up_admission_branch = $data['branch_ids'] . "&#change";
            }
            if (@$old_record['re']->year == @$year) {
                $up_year = $year . "&#nochange";
            } else {
                $up_year = $year . "&#change";
            }
            if (@$old_record['re']->department_id == $department_id) {
                $up_department_id = $department_id . "&#nochange";
            } else {
                $up_department_id = $department_id . "&#change";
            }
            if (@$old_record['re']->subdepartment_id == $subdepartment_id) {
                $up_subdepartment_id = $subdepartment_id . "&#nochange";
            } else {
                $up_subdepartment_id = $subdepartment_id . "&#change";
            }
            if (@$old_record['re']->type == @$data['type']) {
                $up_type = $data['type'] . "&#nochange";
            } else {
                $up_type = $data['type'] . "&#change";
            }
            if (empty($data['course_id'])) {
                $courses = $data['course_id'] = "";
            } else {
                $courses = implode(',', $data['course_id']);
            }
            if (@$old_record['re']->course_id == $courses) {
                $up_course_id = $courses . "&#nochange";
            } else {
                $up_course_id = $courses . "&#change";
            }
            if (empty($data['package_id'])) {
                $package = $data['package_id'] = "";
            } else {
                $package = implode(',', $data['package_id']);
            }
            if (@$old_record['re']->package_id == $package) {
                $up_package_id = $package . "&#nochange";
            } else {
                $up_package_id = $package . "&#change";
            }
            if (@$old_record['re']->faculty_id == @$data['faculty_id']) {
                $up_faculty_id = $data['faculty_id'] . "&#nochange";
            } else {
                $up_faculty_id = $data['faculty_id'] . "&#change";
            }
            if (@$old_record['re']->batch_id == @$data['batch_id']) {
                $up_batch_id = $data['batch_id'] . "&#nochange";
            } else {
                $up_batch_id = $data['batch_id'] . "&#change";
            }
            date_default_timezone_set("Asia/Calcutta");
            $admission_upd_date = date('m/d/Y h:i A');
            $re = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $admission_id);
            if ($re) {
                $history_record = array('admission_id' => $admission_id, 'branch_id' => $up_admission_branch, 'year' => $up_year, 'department_id' => $up_department_id, 'subdepartment_id' => $up_subdepartment_id, 'type' => $up_type, 'course_id' => $up_course_id, 'package_id' => $up_package_id, 'faculty_id' => $up_faculty_id, 'batch_id' => $up_batch_id, 'admission_upd_date' => @$admission_upd_date);
                $this->admi->quick_adm_data('history_adm_cp', $history_record);
            }
            $st = 1;
            echo $st;
        } else {
            $check_ep = $this->admi->check_record_alerady('admission_process', $record);
            if ($check_ep == 0) {
                $re = $this->admi->quick_adm_data('admission_process', $record);
                if ($re) {
                    $history_record = array('admission_id' => @$re, 'branch_id' => $data['branch_ids'] . "&#nochange", 'year' => $year . "&#nochange", 'department_id' => $department_id . "&#nochange", 'subdepartment_id' => $subdepartment_id . "&#nochange", 'type' => $type . "&#nochange", 'course_id' => $course_id . "&#nochange", 'package_id' => $package_id . "&#nochange", 'faculty_id' => $faculty_id . "&#nochange", 'batch_id' => $batch_id . "&#nochange", 'admission_upd_date' => @$admission_upd_date);
                    $this->admi->quick_adm_data('history_adm_cp', $history_record);
                }
                $st = 2;
                echo $st;
            } else {
                $st = 0;
                echo $st;
            }
        }
    }
    public function upd_adm_details() {
        $data = $this->input->post();
        if (empty($data['adm_update_id'])) {
            $adm_update_id = $data['adm_update_id'] = "";
        } else {
            $adm_update_id = $data['adm_update_id'];
        }
        if (empty($data['state_id'])) {
            $state_id = $data['state_id'] = "";
        } else {
            $state_id = $data['state_id'];
        }
        if (empty($data['city_id'])) {
            $city_id = $data['city_id'] = "";
        } else {
            $city_id = $data['city_id'];
        }
        if (empty($data['area_id'])) {
            $area_id = $data['area_id'] = "";
        } else {
            $area_id = $data['area_id'];
        }
        if (empty($data['pin_code'])) {
            $pin_code = $data['pin_code'] = "";
        } else {
            $pin_code = $data['pin_code'];
        }
        if (empty($data['present_address'])) {
            $present_address = $data['present_address'] = "";
        } else {
            $present_address = $data['present_address'];
        }
        if (empty($data['permanent_address'])) {
            $permanent_address = $data['permanent_address'] = "";
        } else {
            $permanent_address = $data['permanent_address'];
        }
        if (empty($data['father_name'])) {
            $father_name = $data['father_name'] = "";
        } else {
            $father_name = $data['father_name'];
        }
        if (empty($data['father_email'])) {
            $father_email = $data['father_email'] = "";
        } else {
            $father_email = $data['father_email'];
        }
        if (empty($data['father_mobile_no'])) {
            $father_mobile_no = $data['father_mobile_no'] = "";
        } else {
            $father_mobile_no = $data['father_mobile_no'];
        }
        if (empty($data['father_occupation'])) {
            $father_occupation = $data['father_occupation'] = "";
        } else {
            $father_occupation = $data['father_occupation'];
        }
        if (empty($data['father_income'])) {
            $father_income = $data['father_income'] = "";
        } else {
            $father_income = $data['father_income'];
        }
        if (empty($data['mother_name'])) {
            $mother_name = $data['mother_name'] = "";
        } else {
            $mother_name = $data['mother_name'];
        }
        if (empty($data['mother_email'])) {
            $mother_email = $data['mother_email'] = "";
        } else {
            $mother_email = $data['mother_email'];
        }
        if (empty($data['mother_mobile_no'])) {
            $mother_mobile_no = $data['mother_mobile_no'] = "";
        } else {
            $mother_mobile_no = $data['mother_mobile_no'];
        }
        if (empty($data['mother_occupation'])) {
            $mother_occupation = $data['mother_occupation'] = "";
        } else {
            $mother_occupation = $data['mother_occupation'];
        }
        if (empty($data['mother_income'])) {
            $mother_income = $data['mother_income'] = "";
        } else {
            $mother_income = $data['mother_income'];
        }
        if (empty($data['admission_msg_email'])) {
            $admission_msg_email = $data['admission_msg_email'] = "";
        } else {
            $admission_msg_email = $data['admission_msg_email'];
        }
        if (empty($data['admission_msg_mobile'])) {
            $admission_msg_mobile = $data['admission_msg_mobile'] = "";
        } else {
            $admission_msg_mobile = $data['admission_msg_mobile'];
        }
        if (empty($data['school_collage_name'])) {
            $school_collage_name = $data['school_collage_name'] = "";
        } else {
            $school_collage_name = $data['school_collage_name'];
        }
        if (empty($data['country_id'])) {
            $country_id = $data['country_id'] = "";
        } else {
            $country_id = $data['country_id'];
        }
        if (empty($data['school_clg_state'])) {
            $school_clg_state = $data['school_clg_state'] = "";
        } else {
            $school_clg_state = $data['school_clg_state'];
        }
        if (empty($data['school_clg_city'])) {
            $school_clg_city = $data['school_clg_city'] = "";
        } else {
            $school_clg_city = $data['school_clg_city'];
        }
        if (empty($data['school_clg_area'])) {
            $school_clg_area = $data['school_clg_area'] = "";
        } else {
            $school_clg_area = $data['school_clg_area'];
        }
        if (empty($data['school_collage_type'])) {
            $school_clg_area = $data['school_clg_area'] = "";
        } else {
            $school_clg_area = $data['school_clg_area'];
        }
        if (empty($data['school_collage_type'])) {
            $school_collage_type = $data['school_collage_type'] = "";
        } else {
            $school_collage_type = $data['school_collage_type'];
        }
        if (@$data['submit'] == 'Add Admission') {
            $adm_update_id = '';
        } else {
            $adm_update_id = $data['adm_update_id'];
        }
        $record = array('admission_id' => $adm_update_id, 'state_id' => $state_id, 'city_id' => $city_id, 'area_id' => $area_id, 'pin_code' => $pin_code, 'present_address' => $present_address, 'permanent_address' => $permanent_address, 'father_name' => $father_name, 'father_email' => $father_email, 'father_mobile_no' => $father_mobile_no, 'father_occupation' => $father_occupation, 'father_income' => $father_income, 'mother_name' => $mother_name, 'mother_email' => $mother_email, 'mother_mobile_no' => $mother_mobile_no, 'mother_occupation' => $mother_occupation, 'mother_income' => $mother_income, 'admission_msg_email' => $admission_msg_email, 'admission_msg_mobile' => $admission_msg_mobile, 'school_collage_name' => $school_collage_name, 'country_id' => $country_id, 'school_clg_state' => $school_clg_state, 'school_clg_city' => $school_clg_city, 'school_clg_area' => $school_clg_area, 'school_collage_type' => $school_collage_type);
        if (!empty($adm_update_id)) {
            $old_record['re'] = $this->admi->get_old_admission_record('admission_process', 'admission_id', $adm_update_id);
            if (@$old_record['re']->state_id == @$data['state_id']) {
                $up_state_id = $data['state_id'] . "&#nochange";
            } else {
                $up_state_id = $data['state_id'] . "&#change";
            }
            if (@$old_record['re']->city_id == @$data['city_id']) {
                $up_city_id = $data['city_id'] . "&#nochange";
            } else {
                $up_city_id = $data['city_id'] . "&#change";
            }
            if (@$old_record['re']->area_id == @$data['area_id']) {
                $up_area_id = $data['area_id'] . "&#nochange";
            } else {
                $up_area_id = $data['area_id'] . "&#change";
            }
            if (@$old_record['re']->pin_code == @$data['pin_code']) {
                $up_pin_code = $data['pin_code'] . "&#nochange";
            } else {
                $up_pin_code = $data['pin_code'] . "&#change";
            }
            if (@$old_record['re']->present_address == @$data['present_address']) {
                $up_present_address = $data['present_address'] . "&#nochange";
            } else {
                $up_present_address = $data['present_address'] . "&#change";
            }
            if (@$old_record['re']->permanent_address == @$data['permanent_address']) {
                $up_permanent_address = $data['permanent_address'] . "&#nochange";
            } else {
                $up_permanent_address = $data['permanent_address'] . "&#change";
            }
            if (@$old_record['re']->father_name == @$data['father_name']) {
                $up_father_name = $data['father_name'] . "&#nochange";
            } else {
                $up_father_name = $data['father_name'] . "&#change";
            }
            if (@$old_record['re']->father_email == @$data['father_email']) {
                $up_father_email = $data['father_email'] . "&#nochange";
            } else {
                $up_father_email = $data['father_email'] . "&#change";
            }
            if (@$old_record['re']->father_mobile_no == @$data['father_mobile_no']) {
                @$up_father_mobile_no = @$data['father_mobile_no'] . "&#nochange";
            } else {
                @$up_father_mobile_no = @$data['father_mobile_no'] . "&#change";
            }
            if (@$old_record['re']->father_occupation == @$data['father_occupation']) {
                $up_father_occupation = $data['father_occupation'] . "&#nochange";
            } else {
                $up_father_occupation = $data['father_occupation'] . "&#change";
            }
            if (@$old_record['re']->father_income == @$data['father_income']) {
                $up_father_income = @$data['father_income'] . "&#nochange";
            } else {
                $up_father_income = @$data['father_income'] . "&#change";
            }
            if (@$old_record['re']->mother_name == @$data['mother_name']) {
                $up_mother_name = $data['mother_name'] . "&#nochange";
            } else {
                $up_mother_name = $data['mother_name'] . "&#change";
            }
            if (@$old_record['re']->mother_email == @$data['mother_email']) {
                $up_mother_email = $data['mother_email'] . "&#nochange";
            } else {
                $up_mother_email = $data['mother_email'] . "&#change";
            }
            if (@$old_record['re']->mother_mobile_no == @$data['mother_mobile_no']) {
                $up_mother_mobile_no = @$data['mother_mobile_no'] . "&#nochange";
            } else {
                $up_mother_mobile_no = @$data['mother_mobile_no'] . "&#change";
            }
            if (@$old_record['re']->mother_occupation == @$data['mother_occupation']) {
                $up_mother_occupation = $data['mother_occupation'] . "&#nochange";
            } else {
                $up_mother_occupation = $data['mother_occupation'] . "&#change";
            }
            if (@$old_record['re']->mother_income == @$data['mother_income']) {
                $up_mother_income = $data['mother_income'] . "&#nochange";
            } else {
                $up_mother_income = $data['mother_income'] . "&#change";
            }
            if (@$old_record['re']->admission_msg_email == @$data['admission_msg_email']) {
                $up_admission_msg_email = @$data['admission_msg_email'] . "&#nochange";
            } else {
                $up_admission_msg_email = @$data['admission_msg_email'] . "&#change";
            }
            if (@$old_record['re']->admission_msg_mobile == @$data['admission_msg_mobile']) {
                $up_admission_msg_mobile = $data['admission_msg_mobile'] . "&#nochange";
            } else {
                $up_admission_msg_mobile = $data['admission_msg_mobile'] . "&#change";
            }
            if (@$old_record['re']->school_collage_name == @$data['school_collage_name']) {
                $up_school_collage_name = $data['school_collage_name'] . "&#nochange";
            } else {
                $up_school_collage_name = $data['school_collage_name'] . "&#change";
            }
            if (@$old_record['re']->country_id == @$data['country_id']) {
                $up_country_id = $data['country_id'] . "&#nochange";
            } else {
                $up_country_id = $data['country_id'] . "&#change";
            }
            if (@$old_record['re']->school_clg_state == @$data['school_clg_state']) {
                $up_school_clg_state = $data['school_clg_state'] . "&#nochange";
            } else {
                @$up_school_clg_state = @$data['school_clg_state'] . "&#change";
            }
            if (@$old_record['re']->school_clg_city == @$data['school_clg_city']) {
                $up_school_clg_city = $data['school_clg_city'] . "&#nochange";
            } else {
                $up_school_clg_city = @$data['school_clg_city'] . "&#change";
            }
            if (@$old_record['re']->school_clg_area == @$data['school_clg_area']) {
                $up_school_clg_area = $data['school_clg_area'] . "&#nochange";
            } else {
                $up_school_clg_area = $data['school_clg_area'] . "&#change";
            }
            if (@$old_record['re']->school_collage_type == @$data['school_collage_type']) {
                $up_school_collage_type = $data['school_collage_type'] . "&#nochange";
            } else {
                $up_school_collage_type = $data['school_collage_type'] . "&#change";
            }
            date_default_timezone_set("Asia/Calcutta");
            $admission_upd_date = date('m/d/Y h:i A');
            $re = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $adm_update_id);
            if ($re) {
                // $history_record['next_followup_date'] = $up_next_followup_date;
                $history_record = array('admission_id' => $adm_update_id, 'state_id' => $up_state_id, 'city_id' => $up_city_id, 'area_id' => $up_area_id, 'pin_code' => $up_pin_code, 'present_address' => $up_present_address, 'permanent_address' => $up_permanent_address, 'father_name' => $up_father_name, 'father_email' => $up_father_email, 'father_mobile_no' => $up_father_mobile_no, 'father_occupation' => $up_father_occupation, 'father_income' => $up_father_income, 'mother_name' => $up_mother_name, 'mother_email' => $up_mother_email, 'mother_mobile_no' => $up_mother_mobile_no, 'mother_occupation' => $up_mother_occupation, 'mother_income' => $up_mother_income, 'admission_msg_email' => $up_admission_msg_email, 'admission_msg_mobile' => $up_admission_msg_mobile, 'school_collage_name' => $up_school_collage_name, 'country_id' => $up_country_id, 'school_clg_state' => $up_school_clg_state, 'school_clg_city' => $up_school_clg_city, 'school_clg_area' => $up_school_clg_area, 'school_collage_type' => $up_school_collage_type, 'admission_upd_date' => @$admission_upd_date);
                $this->admi->quick_adm_data('admission_history', $history_record);
            }
            $st = 1;
            echo $st;
        } else {
            $check_ep = $this->admi->check_record_alerady('admission_process', $record);
            if ($check_ep == 0) {
                $re = $this->admi->quick_adm_data('admission_process', $record);
                if ($re) {
                    $history_record = array('admission_id' => @$re, 'state_id' => $state_id . "&#nochange", 'city_id' => $city_id . "&#nochange", 'area_id' => $area_id . "&#nochange", 'pin_code' => $pin_code . "&#nochange", 'present_address' => $present_address . "&#nochange", 'permanent_address' => $permanent_address . "&#nochange", 'father_name' => $father_name . "&#nochange", 'father_email' => $father_email . "&#nochange", 'father_mobile_no' => $father_mobile_no . "&#nochange", 'father_occupation' => $father_occupation . "&#nochange", 'father_income' => $father_income . "&#nochange", 'mother_name' => $mother_name . "&#nochange", 'mother_email' => $mother_email . "&#nochange", 'mother_mobile_no' => $mother_mobile_no . "&#nochange", 'mother_occupation' => $mother_occupation . "&#nochange", 'mother_income' => $mother_income . "&#nochange", 'admission_msg_email' => $admission_msg_email . "&#nochange", 'admission_msg_mobile' => $admission_msg_mobile . "&#nochange", 'school_collage_name' => $school_collage_name . "&#nochange", 'country_id' => $country_id . "&#nochange", 'school_clg_state' => $school_clg_state . "&#nochange", 'school_clg_city' => $school_clg_city . "&#nochange", 'school_clg_area' => $school_clg_area . "&#nochange", 'school_collage_type' => @$school_collage_type . "&#nochange", 'admission_upd_date' => @$admission_upd_date);
                    $this->admi->quick_adm_data('admission_history', $history_record);
                }
                $st = 2;
                echo $st;
            } else {
                $st = 0;
                echo $st;
            }
        }
    }
    public function ajax_get_histroy_record() {
        $adm_id = $this->input->post('admission_id');
        $data['history'] = $this->admi->get_history_admission('admission_history', 'admission_id', $adm_id);
        $data['history_basic_details'] = $this->admi->get_history_dbasic_admission('history_adm_basicdetails', 'admission_id', $adm_id);
        $data['history_cp'] = $this->admi->get_history_cp_admission('history_adm_cp', 'admission_id', $adm_id);
        $data['history_without_fees'] = $this->admi->get_history_without_fees_modification('history_adm_without_fees', 'admission_id', $adm_id);
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_subdepartment'] = $this->cm->view_all("subdepartment");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_course'] = $this->cm->view_all("course");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_admission_history', $data);
    }
    public function get_course_package_single() {
        $course = $this->input->post('course');
        if ($course == 'single') {
            $record['list_course'] = $this->cm->view_all("course");
            $record['course'] = "course";
            $this->load->view('erp/ajax_course', $record);
        } else {
            $record['list_course'] = $this->cm->view_all("package");
            $record['course'] = "package";
            $this->load->view('erp/ajax_package', $record);
        }
    }
    public function Assessment() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
            $display['admission_data'] = $this->admi->get_admission_record("admission_process", "gr_id", $display['admission']->gr_id);
            if (isset($display['admission_data'])) {
                foreach ($display['admission_data'] as $key => $val) {
                    if ($val->type == 'single') {
                        $data[] = $this->admi->get_all_record_grid_wise('admission_process', 'gr_id', $val->gr_id, $val->type);
                    } else {
                        $data[] = $this->admi->get_all_record_grid_wise('admission_process', 'gr_id', $val->gr_id, $val->type);
                    }
                }
            }
            $alldata = array();
            for ($i = 0;$i < sizeof($data);$i++) {
                $record = array();
                $k = 0;
                for ($j = 0;$j < sizeof($data);$j++) {
                    if ($data[$i]->gr_id == $data[$j]->gr_id) {
                        if ($data[$i]->type == 'single') {
                            $record = @$data[$i]->course_name;
                        } else if ($data[$i]->type == 'package') {
                            $record = @$data[$i]->package_name;
                        }
                    }
                }
                $alldata[$data[$i]->gr_id][$data[$i]->admission_id] = $record;
            }
            foreach ($alldata as $k => $v) {
                if (@$display['admission']->gr_id == $k) {
                    $display['admission']->list_multi_course_admission = $v;
                }
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
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
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['previous_adm_data'] = $this->admi->previous_data("admission_assessment_form", "admission_id", $id);
        $display['tbl_data'] = $this->admi->assessment_pdf_data("admission_assessment_form", "admission_id", $id);
        $this->load->view('header_test', $update);
        $this->load->view('erp/Assessment', $display);
    }
    public function Assessment_data() {
        $data = $this->input->post();
        date_default_timezone_set("Asia/Calcutta");
        $date = date('m/d/Y');
        if (empty($data['admission_id'])) {
            $admission_id = $data['admission_id'] = "";
        } else {
            $admission_id = $data['admission_id'];
        }
        if (empty($data['student_name'])) {
            $student_name = $data['student_name'] = "";
        } else {
            $student_name = $data['student_name'];
        }
        if (empty($data['course_or_package_id'])) {
            $course_or_package_id = $data['course_or_package_id'] = "";
        } else {
            $course_or_package_id = $data['course_or_package_id'];
        }
        if (empty($data['faculty_id'])) {
            $faculty_id = $data['faculty_id'] = "";
        } else {
            $faculty_id = $data['faculty_id'];
        }
        if (empty($data['gr_id'])) {
            $gr_id = $data['gr_id'] = "";
        } else {
            $gr_id = $data['gr_id'];
        }
        if (empty($data['uniform'])) {
            $uniform = $data['uniform'] = "";
        } else {
            $uniform = implode(",", @$data['uniform']);
        }
        if (empty($data['discipline'])) {
            $discipline = $data['discipline'] = "";
        } else {
            $discipline = implode(",", @$data['discipline']);
        }
        if (empty($data['total_work_days'])) {
            $total_work_days = $data['total_work_days'] = "";
        } else {
            $total_work_days = $data['total_work_days'];
        }
        if (empty($data['total_present_days'])) {
            $total_present_days = $data['total_present_days'] = "";
        } else {
            $total_present_days = $data['total_present_days'];
        }
        if (empty($data['total_attendance_percentage'])) {
            $total_attendance_percentage = $data['total_attendance_percentage'] = "";
        } else {
            $total_attendance_percentage = $data['total_attendance_percentage'];
        }
        if (empty($data['faculty_behavior_mark'])) {
            $faculty_behavior_mark = $data['faculty_behavior_mark'] = "";
        } else {
            $faculty_behavior_mark = $data['faculty_behavior_mark'];
        }
        if (empty($data['student_behavior_mark'])) {
            $student_behavior_mark = $data['student_behavior_mark'] = "";
        } else {
            $student_behavior_mark = $data['student_behavior_mark'];
        }
        if (empty($data['total_project'])) {
            $total_project = $data['total_project'] = "";
        } else {
            $total_project = $data['total_project'];
        }
        if (empty($data['submited'])) {
            $submited = $data['submited'] = "";
        } else {
            $submited = $data['submited'];
        }
        if (empty($data['submited_percentage'])) {
            $submited_percentage = $data['submited_percentage'] = "";
        } else {
            $submited_percentage = $data['submited_percentage'];
        }
        if (empty($data['on_time'])) {
            $on_time = $data['on_time'] = "";
        } else {
            $on_time = $data['on_time'];
        }
        if (empty($data['on_time_percentage'])) {
            $on_time_percentage = $data['on_time_percentage'] = "";
        } else {
            $on_time_percentage = $data['on_time_percentage'];
        }
        if (empty($data['quality'])) {
            $quality = $data['quality'] = "";
        } else {
            $quality = $data['quality'];
        }
        if (empty($data['total'])) {
            $total = $data['total'] = "";
        } else {
            $total = $data['total'];
        }
        if (empty($data['participated'])) {
            $participated = $data['participated'] = "";
        } else {
            $participated = $data['participated'];
        }
        if (empty($data['participated_percentage'])) {
            $participated_percentage = $data['participated_percentage'] = "";
        } else {
            $participated_percentage = $data['participated_percentage'];
        }
        if (empty($data['remarks'])) {
            $remarks = $data['remarks'] = "";
        } else {
            $remarks = $data['remarks'];
        }
        $record = array('admission_id' => $admission_id, 'gr_id' => $gr_id, 'student_name' => $student_name, 'course_or_package_id' => $course_or_package_id, 'faculty_id' => $faculty_id, 'uniform' => $uniform, 'discipline' => $discipline, 'student_behavior_mark' => $student_behavior_mark, 'faculty_behavior_mark' => $faculty_behavior_mark, 'total_work_days' => $total_work_days, 'total_present_days' => $total_present_days, 'total_attendance_percentage' => $total_attendance_percentage, 'total_project' => $total_project, 'submited' => $submited, 'submited_percentage' => $submited_percentage, 'on_time' => $on_time, 'on_time_percentage' => $on_time_percentage, 'quality' => $quality, 'total_activity' => $total, 'participated' => $participated, 'participated_percentage' => $participated_percentage, 'remarks' => $remarks, 'date' => $date);
        $result = $this->admi->save_data('admission_assessment_form', $record);
        if ($result) {
			$recp["all_record"] = array('status' => 1, "msg" => "HI! This Record Inserted.");
			echo json_encode($recp); // echo "1";
		} else {
			$recp["all_record"] = array('status' => 3, "msg" => "Something Wrong");
			echo json_encode($recp); // echo "2";
		}
    }
    public function Assessment_pdf() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
            $display['admission_data'] = $this->admi->get_admission_record("admission_process", "gr_id", $display['admission']->gr_id);
            if (isset($display['admission_data'])) {
                foreach ($display['admission_data'] as $key => $val) {
                    if ($val->type == 'single') {
                        $data[] = $this->admi->get_all_record_grid_wise('admission_process', 'gr_id', $val->gr_id, $val->type);
                    } else {
                        $data[] = $this->admi->get_all_record_grid_wise('admission_process', 'gr_id', $val->gr_id, $val->type);
                    }
                }
            }
            $alldata = array();
            for ($i = 0;$i < sizeof($data);$i++) {
                $record = array();
                $k = 0;
                for ($j = 0;$j < sizeof($data);$j++) {
                    if ($data[$i]->gr_id == $data[$j]->gr_id) {
                        if ($data[$i]->type == 'single') {
                            $record = @$data[$i]->course_name;
                        } else if ($data[$i]->type == 'package') {
                            $record = @$data[$i]->package_name;
                        }
                    }
                }
                $alldata[$data[$i]->gr_id][$data[$i]->admission_id] = $record;
            }
            foreach ($alldata as $k => $v) {
                if (@$display['admission']->gr_id == $k) {
                    $display['admission']->list_multi_course_admission = $v;
                }
            }
        }
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
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['assessment_pdf_data'] = $this->admi->assessment_pdf_data("admission_assessment_form", "admission_id", $id);
        $this->load->view('erp/Assessment_pdf', $display);
        $html = $this->output->get_output();
        // Load pdf library
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        // Output the generated PDF (1 = download and 0 = preview)
        $this->pdf->stream("html_contents.pdf", array("Attachment" => 0));
    }
    public function icard_form() {
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("id_card", "idcard_id", $id);
                if ($re) {
                    //logdata('documents_id id= '.$id." Deleted");
                    redirect('AddmissionController/icard_form');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_idcard'] = $this->cm->select_data("id_card", "idcard_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['update_id']);
            unset($data['submit']);
            $config['allowed_types'] = "*";
            $config['upload_path'] = FCPATH . "dist/erplogo/";
            $new_name = time() . $_FILES["logo"]['name'];
            $config['file_name'] = $new_name;
            $this->load->library('upload');
            $this->upload->initialize($config);
            if ($this->upload->do_upload('logo')) {
                $imagedata = $this->upload->data();
                $ins_data['logo'] = $imagedata['file_name'];
            } else {
                $display['msgp'] = "image not uploaded";
            }
            @$ins_data['department_id'] = $data['department_id'];
            @$ins_data['branch'] = $data['branch'];
            @$ins_data['batch'] = $data['batch'];
            @$ins_data['course'] = $data['course'];
            @$ins_data['photos'] = $data['photos'];
            @$ins_data['surname'] = $data['surname'];
            @$ins_data['name'] = $data['name'];
            @$ins_data['admission_date'] = $data['admission_date'];
            @$ins_data['year'] = $data['year'];
            @$ins_data['gr_id'] = $data['gr_id'];
            // echo "<pre>";
            // print_r($ins_data);
            // die();
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                // $ll = $this->cm->select_data("id_card","idcard_id",$id);
                // if($_FILES['logo']['name']!="")
                // {
                // 	$filess=FCPATH."dist/erplogo/".$ll->logo;
                // 	@unlink($filess);
                // }
                $re = $this->cm->update_data("id_card", $ins_data, "idcard_id", $id);
            } else {
                $re = $this->cm->insert_data("id_card", $ins_data);
            }
            if ($re) {
                redirect('AddmissionController/icard_form');
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_subdepartment'] = $this->cm->view_all("subdepartment");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_batch'] = $this->cm->view_all("batch_list");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['all_idcard_data'] = $this->cm->view_all("id_card");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header_test', $update);
        $this->load->view('erp/icard_form', $display);
    }
    public function idcards() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
        }
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
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/idcards', $display);
        $html = $this->output->get_output();
        // Load pdf library
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        // Output the generated PDF (1 = download and 0 = preview)
        $this->pdf->stream("html_contents.pdf", array("Attachment" => 0));
    }
    public function admprocess_permition() {
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("adm_permission", "adm_permission_id", $id);
                if ($re) {
                    //logdata('documents_id id= '.$id." Deleted");
                    redirect('AddmissionController/admprocess_permition');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_adm_permission'] = $this->cm->select_data("adm_permission", "adm_permission_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['update_id']);
            unset($data['submit']);
            @$ins_data['branch_id'] = $data['branch_id'];
            @$ins_data['department_id'] = $data['department_id'];
            @$ins_data['logtype_name'] = $data['logtype_name'];
            @$ins_data['mobile_no'] = $data['mobile_no'];
            @$ins_data['alternet_no'] = $data['alternet_no'];
            @$ins_data['email'] = $data['email'];
            @$ins_data['source'] = $data['source'];
            @$ins_data['first_name'] = $data['first_name'];
            @$ins_data['surname'] = $data['surname'];
            @$ins_data['branch_name'] = $data['branch_name'];
            @$ins_data['assigned_to'] = $data['assigned_to'];
            @$ins_data['admission_branch'] = $data['admission_branch'];
            @$ins_data['session'] = $data['session'];
            @$ins_data['department'] = $data['department'];
            @$ins_data['course_category'] = $data['course_category'];
            @$ins_data['course'] = $data['course'];
            @$ins_data['package'] = $data['package'];
            @$ins_data['faculty'] = $data['faculty'];
            @$ins_data['batch'] = $data['batch'];
            @$ins_data['tuition_fees'] = $data['tuition_fees'];
            @$ins_data['registration_fees'] = $data['registration_fees'];
            // echo "<pre>";
            // print_r($ins_data);
            // die();
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("adm_permission", $ins_data, "adm_permission_id", $id);
            } else {
                $re = $this->cm->insert_data("adm_permission", $ins_data);
            }
            if ($re) {
                redirect('AddmissionController/admprocess_permition');
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
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
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['all_adm_permission'] = $this->cm->view_all("adm_permission");
        $display['all_logtype'] = $this->cm->view_all("logtype");
        $this->load->view('header_test', $update);
        $this->load->view('erp/admprocess_permition', $display);
    }
    public function unfillup_fealds() {
        if (!empty($this->input->post('filter_admission'))) {
            $filter = $this->input->post();
            $display['Admission_record'] = $this->admi->admission_unfillup_fields_all("admission_process", $filter);
            $display['filter_startDate'] = @$filter['filter_startDate'];
            $display['filter_endDate'] = @$filter['filter_endDate'];
            $display['filter_fname'] = @$filter['filter_fname'];
            $display['filter_lname'] = @$filter['filter_lname'];
            $display['filter_gr_id'] = @$filter['filter_gr_id'];
            $display['filter_email'] = @$filter['filter_email'];
            $display['filter_mobile'] = @$filter['filter_mobile'];
            $display['filter_source'] = @$filter['filter_source'];
            $display['filter_branch'] = @$filter['filter_branch'];
            $display['filter_course'] = @$filter['filter_course'];
            $display['filter_package'] = @$filter['filter_package'];
            $display['filter_on'] = "dfgf";
        } else {
            $display['Admission_record'] = $this->admi->admission_unfillup_fields_all("admission_process");
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_batch'] = $this->cm->view_all("batch_list");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_remarks'] = $this->cm->view_all("admission_remarks");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['list_country'] = $this->cm->view_all("country");
        $display['list_state'] = $this->cm->view_all("state");
        $display['list_city'] = $this->cm->view_all("cities");
        $display['list_area'] = $this->cm->view_all("area");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $display['sms_template_list'] = $this->cm->view_all("sms_template");
        $display['list_email_template'] = $this->cm->view_all("email_template_category");
        //$display['Admission_record'] = $this->cm->view_all("admission_process");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header_test', $update);
        $this->load->view('erp/unfillup_fealds', $display);
    }
    public function batches() {
        if (!empty($this->input->post('filter_batches'))) {
            $filter = $this->input->post();
            $display["list_all_batches"] = $this->admi->batches_view_all("batches", $filter);
            $display['filter_fname'] = @$filter['filter_fname'];
            $display['filter_lname'] = @$filter['filter_lname'];
            $display['filter_branch'] = @$filter['filter_branch'];
            $display['filter_faculty'] = @$filter['filter_faculty'];
            $display['filter_course'] = @$filter['filter_course'];
            $display['filter_on'] = "dfgf";
        } else {
            $display['list_all_batches'] = $this->admi->batches_view_all('batches');
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_batch'] = $this->cm->view_all("batch_list");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_remarks'] = $this->cm->view_all("admission_remarks");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['list_country'] = $this->cm->view_all("country");
        $display['list_state'] = $this->cm->view_all("state");
        $display['list_city'] = $this->cm->view_all("cities");
        $display['list_area'] = $this->cm->view_all("area");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $display['sms_template_list'] = $this->cm->view_all("sms_template");
        $display['list_email_template'] = $this->cm->view_all("email_template_category");
        $display['Admission_record'] = $this->cm->view_all("admission_process");
        $this->load->view('header_test', $update);
        $this->load->view('erp/batches', $display);
    }
    public function Get_courses() {
        $data = $this->input->post();
        $f_id = implode(',', $data['faculty_id']);
        $faculty = $this->admi->faculty_wise_course_get('faculty', 'faculty_id', $f_id);
        $c_ids = array();
        $j = 0;
        for ($i = 0;$i < sizeof($faculty);$i++) {
            $c_ids = $faculty[$i]->course_ids;
            $j++;
        }
        $cids = explode(",", $c_ids);
        $course = $this->admi->view_all('course');
        foreach ($course as $co) {
            if (in_array($co['course_id'], $cids)) {
?>

				<option value="<?php echo $co['course_id']; ?>"><?php echo $co['course_name']; ?></option>

			<?php
            }
        }
    }
    public function Get_courses_faculty() {
        $data = $this->input->post();
        $faculty = $this->admi->faculty_wise_course_get('faculty', 'faculty_id', $data['faculty_id']);
        $c_ids = array();
        $j = 0;
        for ($i = 0;$i < sizeof($faculty);$i++) {
            $c_ids = $faculty[$i]->course_ids;
            $j++;
        }
        $cids = explode(",", $c_ids);
        $course = $this->admi->view_all('course');
        foreach ($course as $co) {
            if (in_array($co['course_id'], $cids)) {
?>

				<option value="<?php echo $co['course_id']; ?>"><?php echo $co['course_name']; ?></option>

<?php
            }
        }
    }
    public function Batch_add() {
        $data = $this->input->post();
        $record = array('batch_name' => $data['batch_name'], 'batch_code' => $data['batch_code'], 'branch_id' => $data['branch_id'], 'faculty_id' => $data['faculty_id'], 'course_id' => $data['course_id'], 'created_bye' => $data['created_bye']);
        $result = $this->cm->save_data('batches', $record);
        if ($result) {
            $recp["all_record"] = array('status' => 1, "msg" => "Successfully Inserted Batch");
            echo json_encode($recp); // echo "1";
            
        } else {
            $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp); // echo "2";
            
        }
    }
    public function view_batches() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "tps") {
            $display['get_batch'] = $this->admi->get_adm_batches_all("admission_courses", "batch_id", $id);
            $display['batches'] = $this->cm->select_data("batches", "batches_id", $id);
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_batch'] = $this->cm->view_all("batch_list");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_remarks'] = $this->cm->view_all("admission_remarks");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['list_country'] = $this->cm->view_all("country");
        $display['list_state'] = $this->cm->view_all("state");
        $display['list_city'] = $this->cm->view_all("cities");
        $display['list_area'] = $this->cm->view_all("area");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['reason_list'] = $this->cm->view_all("cancel_reason_list");
        $display['sms_template_list'] = $this->cm->view_all("sms_template");
        $display['list_email_template'] = $this->cm->view_all("email_template_category");
        $display['Admission_record'] = $this->cm->view_all("admission_process");
        $display['list_all_batches'] = $this->cm->view_all("batches");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header_test', $update);
        $this->load->view('erp/view_batches', $display);
    }
    public function fetch_data_admission_process() {
        $admission_id = $this->input->post('admission_id');
        if (!empty($admission_id)) {
            $data = $this->admi->Fetch_admission_id_wise_record($admission_id);
            echo json_encode(array('record' => $data));
        } else {
            $data = null;
            echo json_encode($data);
        }
    }
    public function upd_adm_state() {
        $data = $this->input->post();
        $record = array('state_id' => $data['state_id']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_city() {
        $data = $this->input->post();
        $record = array('city_id' => $data['city_id']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_area() {
        $data = $this->input->post();
        $record = array('area_id' => $data['area_id']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_pin() {
        $data = $this->input->post();
        $record = array('pin_code' => $data['pin_code']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_presentaddress() {
        $data = $this->input->post();
        $record = array('present_address' => $data['present_address']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_fathername() {
        $data = $this->input->post();
        $record = array('father_name' => $data['father_name']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_fatheremail() {
        $data = $this->input->post();
        $record = array('father_email' => $data['father_email']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_fathermobileno() {
        $data = $this->input->post();
        $record = array('father_mobile_no' => $data['father_mobile_no']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_fatheroccupation() {
        $data = $this->input->post();
        $record = array('father_occupation' => $data['father_occupation']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_fatherincome() {
        $data = $this->input->post();
        $record = array('father_income' => $data['father_income']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_mothername() {
        $data = $this->input->post();
        $record = array('mother_name' => $data['mother_name']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_motheremail() {
        $data = $this->input->post();
        $record = array('mother_email' => $data['mother_email']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_mothermobileno() {
        $data = $this->input->post();
        $record = array('mother_mobile_no' => $data['mother_mobile_no']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_motheroccupation() {
        $data = $this->input->post();
        $record = array('mother_occupation' => $data['mother_occupation']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_motherincome() {
        $data = $this->input->post();
        $record = array('mother_income' => $data['mother_income']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_school_college_type() {
        $data = $this->input->post();
        $record = array('school_collage_type' => $data['school_collage_type']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_school_college_name() {
        $data = $this->input->post();
        $record = array('school_collage_name' => $data['school_collage_name']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_country() {
        $data = $this->input->post();
        $record = array('country_id' => $data['country_id']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_school_clg_state() {
        $data = $this->input->post();
        $record = array('school_clg_state' => $data['school_clg_state']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_school_clg_city() {
        $data = $this->input->post();
        $record = array('school_clg_city' => $data['school_clg_city']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function upd_adm_school_clg_area() {
        $data = $this->input->post();
        $record = array('school_clg_area' => $data['school_clg_area']);
        $result = $this->admi->update_admission_record('admission_process', $record, 'admission_id', $data['admission_id']);
        echo json_encode($result);
    }
    public function live_search() {
        $search = $this->input->post('search');
        $query = $this->admi->getdata_livesearch($search);
        echo json_encode($query);
    }
    public function ajaxPro() {
        $query = $this->input->get('query');
        $this->db->like('first_name', $query);
        $data = $this->db->get("admission_process")->result();
        echo json_encode($data);
    }
    public function Send_Otp() {
        $data = $this->input->post();
        $mobile = $data['mobile_no'];
        $otp = rand(100000, 999999);
        $_SESSION['session_otp'] = $otp;
        $msg = $otp;
        $ins_data['otp_number'] = $msg;
        $this->db->insert('admission_otp', $ins_data);
        $url = "http://dndsms.vishawebsolutions.com/api/mt/SendSMS?user=Hitesh-RNW&password=Hitz9898&senderid=RNWEDU&channel=Trans&DCS=8&flashsms=0&number=$mobile&text=$msg&route=15";
        /* init the resource */
        $ch = curl_init();
        curl_setopt_array($ch, array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0));
        /* get response */
        $output = curl_exec($ch);
        if ($output) {
            $recp["all_record"] = array('status' => 1, "msg" => "Successfully Send OTP");
            echo json_encode($recp);
        }
        /* Print error if any */
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }
        curl_close($ch);
        return 1;
    }
    public function verify_otp() {
        $data = $this->input->post();
        $otp = $data['mobileOtp'];
        $record = $this->admi->match_opt('admission_otp', 'otp_number', $otp);
        if ($otp == $record->otp_number) {
            $recp["all_record"] = array('status' => 1, "msg" => "Successfully Verify OTP");
            echo json_encode($recp);
        } else {
            $recp["all_record"] = array('status' => 1, "msg" => "Not Verify OTP");
            echo json_encode($recp);
        }
    }
    public function ajax_batch_history() {
        $batch_id = $this->input->post('batches_id');
        $data['history'] = $this->admi->get_history_batch('batches_history', 'batches_id', $batch_id);
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_batch_history', $data);
    }
    public function get_not_assigned_batch() {
        $batches_id = $this->input->post('batches_id');
        $course_id = $this->input->post('course_id');
        $data['not_assigned_batch_list'] = $this->admi->get_adm_batches_all('admission_courses', 'courses_id', $course_id);
        $data['match_data'] = $this->cm->select_data('batches', 'batches_id', $batches_id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_extra_batch', $data);
    }
    public function shining_sheet() {
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            if (!empty($this->input->post('addmore'))) {
                foreach ($this->input->post('addmore') as $key => $value) {
                    @$value['course_id'] = $data['course_id'];
                    $this->db->insert('shining_sheet', $value);
                }
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['batch_datas'] = $this->cm->batch_notification_data("admission_courses");
        $update['count_batch'] = $this->cm->count_batch_notification("admission_courses");
        $update['course_data'] = $this->cm->view_all("course");
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_shining_sheet'] = $this->cm->view_all("shining_sheet");
        $display['shining_sheet_data'] = $this->admi->shining_sheet_record("shining_sheet");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header_test', $update);
        $this->load->view('erp/shining_sheet', $display);
    }
    public function get_course_topics() {
        $course_id = $this->input->post('course_id');
        $data['course_topic_record'] = $this->admi->get_course_topic('shining_sheet', 'course_id', $course_id);
        $data['list_course'] = $this->cm->view_all("course");
        $this->load->view('erp/ajax_topic', $data);
    }
    public function get_sheet_ids_wise_data() {
        $id = $this->input->post('shining_sheet_id');
        $data = $this->admi->get_sheet_idswise('shining_sheet', 'shining_sheet_id', $id);
        echo json_encode(array('record' => $data));
    }
    public function edit_topic_course() {
        $data = $this->input->post();
        $record = array('name' => $data['name']);
        $result = $this->admi->edit_course_topic_data('shining_sheet', $record, 'shining_sheet_id', $data['shining_sheet_id']);
        if ($result) {
            $recp["all_record"] = array('status' => 1, "msg" => "This Topic Edited.");
            echo json_encode($recp); // echo "1";
            
        } else {
            $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp); // echo "2";
            
        }
    }
    public function remove_topic_course() {
        $id = $this->input->post('shining_sheet_id');
        $result = $this->admi->delete_topic('shining_sheet', 'shining_sheet_id', $id);
        if ($result) {
            $recp["all_record"] = array('status' => 1, "msg" => "This Topic Deleted.");
            echo json_encode($recp); // echo "1";
            
        } else {
            $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
            echo json_encode($recp); // echo "2";
            
        }
    }
    public function admission_receipt() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
            $display['instalment'] = $this->cm->select_data("admission_installment", "admission_id", $id);
            $branch = $this->cm->select_data('branch', 'branch_id', $display['admission']->branch_id);
            if ($display['admission']->branch_id == 1) {
                $rn1q = $this->db->query("SELECT MAX(RW1) AS RW1 FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn1 = $rn1q->result_array();
                $r1 = $rn1[0]['RW1'];
                $num = number_format($r1);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 5) {
                $rn2q = $this->db->query("SELECT MAX(RW2) AS RW2 FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn2 = $rn2q->result_array();
                $r2 = $rn2[0]['RW2'];
                $num = number_format($r2);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 8) {
                $rn3q = $this->db->query("SELECT MAX(RW3) AS RW3 FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn3 = $rn3q->result_array();
                $r3 = $rn3[0]['RW3'];
                $num = number_format($r3);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 3) {
                $rn4q = $this->db->query("SELECT MAX(RW4) AS RW4 FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn4 = $rn4q->result_array();
                $r4 = $rn4[0]['RW4'];
                $num = number_format($r4);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 9) {
                $rn1bq = $this->db->query("SELECT MAX(RW1B) AS RW1B FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn1b = $rn1bq->result_array();
                $r1b = $rn1b[0]['RW1B'];
                $num = number_format($r1b);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 10) {
                $rn1mmq = $this->db->query("SELECT MAX(rw1mm) AS rw1mm FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn1mm = $rn1mmq->result_array();
                $r1mm = $rn1mm[0]['rw1mm'];
                $num = number_format($r1mm);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 11) {
                $rnclgq = $this->db->query("SELECT MAX(clg) AS clg FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rnclg = $rnclgq->result_array();
                $rclg = $rnclg[0]['clg'];
                $num = number_format($rclg);
                $num = $num + 1;
            }
            if ($display['admission']->branch_id == 1) {
                $RW1 = $rnw;
                $record = array('RW1' => $RW1);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW1 = 0;
            }
            if ($display['admission']->branch_id == 5) {
                $RW2 = $rnw;
                $record = array('RW2' => $RW2);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW2 = 0;
            }
            if ($display['admission']->branch_id == 8) {
                $RW3 = $rnw;
                $record = array('RW3' => $RW3);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW3 = 0;
            }
            if ($display['admission']->branch_id == 3) {
                $RW4 = $rnw;
                $record = array('RW4' => $RW4);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW4 = 0;
            }
            if ($display['admission']->branch_id == 9) {
                $RW1B = $rnw;
                $record = array('RW1B' => $RW1B);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW1B = 0;
            }
            if ($display['admission']->branch_id == 10) {
                $rw1mm = $rnw;
                $record = array('rw1mm' => $rw1mm);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $rw1mm = 0;
            }
            if ($display['admission']->branch_id == 11) {
                $clg = $rnw;
                $record = array('clg' => $clg);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $clg = 0;
            }
            $receiptnumber = $branch->branch_code . "-" . $rnw;
            $display['receiptno'] = $receiptnumber;
        }
        // date_default_timezone_set("Asia/Calcutta");
        // $receipt_date =  date('d/m/Y');
        // $receipt_data['admission_id'] = $display['admission']->admission_id;
        // $receipt_data['branch_id'] = $display['admission']->branch_id;
        // $receipt_data['intallment_id'] = $display['instalment']->admission_installment_id;
        // $receipt_data['receipt_no'] = $receiptnumber;
        // $receipt_data['receipt_date'] = $receipt_date;
        // $receipt_data['gr_id'] = $display['admission']->gr_id;
        // $receipt_data['installment_no'] = $display['instalment']->installment_no;
        // $receipt_data['enrollment_no'] = $display['admission']->enrollment_number;
        // if(display['admission']->type=="single")
        // {
        // 	$receipt_date['course_id'] = $display['admission']->course_id;
        // 	$receipt_date['package_id'] = "";
        // }
        // else
        // {
        // 	$receipt_date['package_id'] = $display['admission']->package_id;
        // 	$receipt_date['course_id'] = "";
        // }
        // $receipt_data['pay_now'] = $display['instalment']->paid_amount;
        // $receipt_data['ramrk_by'] = $display['instalment']->payment_mode;
        // $receipt_data['received_by'] = $_SESSION['user_name'];
        // $this->db->insert('admissin_receipt',$receipt_data);
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_subdepartment'] = $this->cm->view_all("subdepartment");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_country'] = $this->cm->view_all("country");
        $display['list_state'] = $this->cm->view_all("state");
        $display['list_city'] = $this->cm->view_all("cities");
        $display['list_area'] = $this->cm->view_all("area");
        $display['list_batch'] = $this->cm->view_all("batch_list");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/admission_receipt', $display);
        $html = $this->output->get_output();
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("html_contents.pdf", array("Attachment" => 0));
    }
    public function admission_receiptGst() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['admission'] = $this->cm->select_data("admission_process", "admission_id", $id);
            $display['instalment'] = $this->cm->select_data("admission_installment", "admission_id", $id);
            $branch = $this->cm->select_data('branch', 'branch_id', $display['admission']->branch_id);
            $display['permission_branch_wise'] = $this->cm->select_data('branch', 'branch_id', $display['admission']->branch_id);
            $display['all_daynamic_record_gst'] = $this->admi->get_daynamic_fields('gst_daynamic_field', 'branch_id', $display['admission']->branch_id);
            if ($display['admission']->branch_id == 1) {
                $rn1q = $this->db->query("SELECT MAX(RW1) AS RW1 FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn1 = $rn1q->result_array();
                $r1 = $rn1[0]['RW1'];
                $num = number_format($r1);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 5) {
                $rn2q = $this->db->query("SELECT MAX(RW2) AS RW2 FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn2 = $rn2q->result_array();
                $r2 = $rn2[0]['RW2'];
                $num = number_format($r2);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 8) {
                $rn3q = $this->db->query("SELECT MAX(RW3) AS RW3 FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn3 = $rn3q->result_array();
                $r3 = $rn3[0]['RW3'];
                $num = number_format($r3);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 3) {
                $rn4q = $this->db->query("SELECT MAX(RW4) AS RW4 FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn4 = $rn4q->result_array();
                $r4 = $rn4[0]['RW4'];
                $num = number_format($r4);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 9) {
                $rn1bq = $this->db->query("SELECT MAX(RW1B) AS RW1B FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn1b = $rn1bq->result_array();
                $r1b = $rn1b[0]['RW1B'];
                $num = number_format($r1b);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 10) {
                $rn1mmq = $this->db->query("SELECT MAX(rw1mm) AS rw1mm FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rn1mm = $rn1mmq->result_array();
                $r1mm = $rn1mm[0]['rw1mm'];
                $num = number_format($r1mm);
                $rnw = $num + 1;
            } else if ($display['admission']->branch_id == 11) {
                $rnclgq = $this->db->query("SELECT MAX(clg) AS clg FROM admission_installment ORDER BY admission_installment_id DESC LIMIT 1");
                $rnclg = $rnclgq->result_array();
                $rclg = $rnclg[0]['clg'];
                $num = number_format($rclg);
                $num = $num + 1;
            }
            if ($display['admission']->branch_id == 1) {
                $RW1 = $rnw;
                $record = array('RW1' => $RW1);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW1 = 0;
            }
            if ($display['admission']->branch_id == 5) {
                $RW2 = $rnw;
                $record = array('RW2' => $RW2);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW2 = 0;
            }
            if ($display['admission']->branch_id == 8) {
                $RW3 = $rnw;
                $record = array('RW3' => $RW3);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW3 = 0;
            }
            if ($display['admission']->branch_id == 3) {
                $RW4 = $rnw;
                $record = array('RW4' => $RW4);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW4 = 0;
            }
            if ($display['admission']->branch_id == 9) {
                $RW1B = $rnw;
                $record = array('RW1B' => $RW1B);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $RW1B = 0;
            }
            if ($display['admission']->branch_id == 10) {
                $rw1mm = $rnw;
                $record = array('rw1mm' => $rw1mm);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $rw1mm = 0;
            }
            if ($display['admission']->branch_id == 11) {
                $clg = $rnw;
                $record = array('clg' => $clg);
                $this->admi->generate_receipt_record('admission_installment', $record, 'admission_installment_id', $display['instalment']->admission_installment_id);
            } else {
                $clg = 0;
            }
            $receiptnumber = $branch->branch_code . "-" . $rnw;
            $display['receiptno'] = $receiptnumber;
        }
        $display['upd_faculty'] = $this->cm->view_all("faculty");
        $display['upd_branch'] = $this->cm->view_all("branch");
        $display['upd_see'] = $this->cm->check_update("demo");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_department'] = $this->cm->view_all("department");
        $display['list_subdepartment'] = $this->cm->view_all("subdepartment");
        $display['list_branch'] = $this->cm->view_all("branch");
        $display['list_course'] = $this->cm->view_all("course");
        $display['list_package'] = $this->cm->view_all("package");
        $display['list_source'] = $this->cm->view_all("source");
        $display['list_country'] = $this->cm->view_all("country");
        $display['list_state'] = $this->cm->view_all("state");
        $display['list_city'] = $this->cm->view_all("cities");
        $display['list_area'] = $this->cm->view_all("area");
        $display['list_batch'] = $this->cm->view_all("batch_list");
        $display['hod_all'] = $this->cm->view_all_hod_demo("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/admission_receiptGst', $display);
        $html = $this->output->get_output();
        // Load pdf library
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        // Output the generated PDF (1 = download and 0 = preview)
        $this->pdf->stream("html_contents.pdf", array("Attachment" => 0));
        //header('Content-type: application/pdf');
        
    }
    public function intsalment_Unpaidpayment() {
        $id = $this->input->post('admission_installment_id');
        $data['adm_instalment'] = $this->cm->select_data("admission_installment", "admission_installment_id", $id);
        $data['admission_data'] = $this->cm->select_data("admission_process", "admission_id", $data['adm_instalment']->admission_id);
        $data['list_department'] = $this->cm->view_all("department");
        $data['list_branch'] = $this->cm->view_all("branch");
        $data['list_course'] = $this->cm->view_all("course");
        $data['list_package'] = $this->cm->view_all("package");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_batch'] = $this->cm->view_all("batch_list");
        $data['list_source'] = $this->cm->view_all("source");
        $data['list_country'] = $this->cm->view_all("country");
        $data['list_state'] = $this->cm->view_all("state");
        $data['list_city'] = $this->cm->view_all("cities");
        $data['list_area'] = $this->cm->view_all("area");
        $data['faculty_all'] = $this->cm->view_all("faculty");
        $this->load->view('erp/ajax_payment_upd', $data);
    }
    public function upd_intsallment() {
        date_default_timezone_set("Asia/Calcutta");
        $data = $this->input->post();
        $admIntsallment = $this->cm->select_data('admission_installment', 'admission_installment_id', $data['upd_intalment_id']);
        $admprocess_data = $this->cm->select_data('admission_process', 'admission_id', $admIntsallment->admission_id);
        if ($data['paying_amount'] == $admIntsallment->due_amount) {
            $commentdata['admission_id'] = $admprocess_data->admission_id;
            $commentdata['labels'] = "Fees";
            $commentdata['admission_remrak'] = $data['comments'];
            $commentdata['remarks_date'] = date('d/m/Y');
            $commentdata['remarks_time'] = date('h:i A');
            $commentdata['addby'] = $_SESSION['user_name'];
            $this->db->insert('admission_remarks', $commentdata);
            $p_amount = $data['paying_amount'];
            if (empty($data['send_sms_student'])) {
                $send_sms_student = $data['send_sms_student'] = "";
            } else {
                $send_sms_student = $data['send_sms_student'];
            }
            if (empty($data['send_sms_parents'])) {
                $send_sms_parents = $data['send_sms_parents'] = "";
            } else {
                $send_sms_parents = $data['send_sms_parents'];
            }
            $record = array('payment_type' => $data['payment_type'], 'payment_mode' => $data['payment_mode'], 'paid_amount' => $p_amount, 'send_sms_student' => $send_sms_student, 'send_email_student' => $data['send_email_student'], 'send_sms_parents' => $send_sms_parents, 'send_email_parents' => $data['send_email_parents']);
            $result = $this->admi->update_installment('admission_installment', $record, 'admission_installment_id', $data['upd_intalment_id']);
            if ($result) {
                $recp["all_record"] = array('status' => 1, "msg" => "You Have Completed The Installation Of Your Fees");
                echo json_encode($recp); // echo "1";
                
            } else {
                $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
                echo json_encode($recp); // echo "2";
                
            }
        } else {
            $splitadd_Installment = $admIntsallment->due_amount - $data['paying_amount'];
            $installmentdate = date('Y-m-d', strtotime("+30 days"));
            $splitaddInstallment_data['admission_id'] = $admIntsallment->admission_id;
            $splitaddInstallment_data['registration_fees'] = $admIntsallment->registration_fees;
            $splitaddInstallment_data['installment_date'] = $installmentdate;
            $splitaddInstallment_data['due_amount'] = $splitadd_Installment;
            $this->db->insert('admission_installment', $splitaddInstallment_data);
            $commentdata['admission_id'] = $admprocess_data->admission_id;
            $commentdata['labels'] = "Fees";
            $commentdata['admission_remrak'] = $data['comments'];
            $commentdata['remarks_date'] = date('d/m/Y');
            $commentdata['remarks_time'] = date('h:i A');
            $commentdata['addby'] = $_SESSION['user_name'];
            $this->db->insert('admission_remarks', $commentdata);
            $payment = $data['paying_amount'];
            if (empty($data['send_sms_student'])) {
                $send_sms_student = $data['send_sms_student'] = "";
            } else {
                $send_sms_student = $data['send_sms_student'];
            }
            if (empty($data['send_sms_parents'])) {
                $send_sms_parents = $data['send_sms_parents'] = "";
            } else {
                $send_sms_parents = $data['send_sms_parents'];
            }
            $record = array('payment_type' => $data['payment_type'], 'payment_mode' => $data['payment_mode'], 'due_amount' => $payment, 'paid_amount' => $payment, 'send_sms_student' => $send_sms_student, 'send_email_student' => $data['send_email_student'], 'send_sms_parents' => $send_sms_parents, 'send_email_parents' => $data['send_email_parents']);
            $result = $this->admi->update_installment('admission_installment', $record, 'admission_installment_id', $data['upd_intalment_id']);
            if ($result) {
                $recp["all_record"] = array('status' => 3, "msg" => "You Have Completed The Split Installation Of Your Fees");
                echo json_encode($recp); // echo "1";
                
            } else {
                $recp["all_record"] = array('status' => 2, "msg" => "Something Wrong");
                echo json_encode($recp); // echo "2";
                
            }
        }
    }
    public function createbranch() {
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
        $this->load->view('erp/erpcreatebranch');
    }
}
