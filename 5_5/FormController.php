<?php
class FormController extends CI_Controller {
    public $newRecord;
    public $newfollowupRecord;
    public $activeRecord;
    public $postponeRecord;
    public $confirmRecord;
    public $discardRecord;
    public $job_pending;
    public $job_active;
    public $job_deactive;
    public $all_jobs;
    public $wpf;
    function __construct() {
        @parent::__construct();
        $this->load->model("Task", "tm");
        $this->load->helper('loggs');
        //$this->load->helper('urldata');
        $this->load->model("Dbcommon", "cm");
        $this->load->library("excel");
        if (@$_SESSION['logtype'] == 'Manager' || @$_SESSION['logtype'] == "Super Admin") {
            $curDate = date('m-d-Y');
            $this->newRecord = $this->cm->get_new_record_all_student('application_job', '0', 'application_status');
            $this->newfollowupRecord = $this->cm->get_new_record_all_student('application_job', '5', 'application_status', 'next_followup_date', $curDate);
            $this->activeRecord = $this->cm->get_new_record_all_student('application_job', '1', 'application_status');
            $this->activefollowupRecord = $this->cm->get_new_record_all_student('application_job', '6', 'application_status', 'next_followup_date', $curDate);
            $this->postponeRecord = $this->cm->get_new_record_all_student('application_job', '3', 'application_status');
            $this->confirmRecord = $this->cm->get_new_record_all_student('application_job', '2', 'application_status');
            $this->wpf = $this->cm->get_new_record_all_student('application_job', '7', 'application_status');
            $this->discardRecord = $this->cm->get_new_record_all_student('application_job', '4', 'application_status');
        } else {
            $userName = $_SESSION['Admin']['username'];
            $this->newRecord = $this->cm->get_new_record('application_job', '0', 'application_status', 'assign_faculty', $userName);
            $this->activeRecord = $this->cm->get_new_record('application_job', '1', 'application_status', 'assign_faculty', $userName);
            $this->postponeRecord = $this->cm->get_new_record('application_job', '3', 'application_status', 'assign_faculty', $userName);
            $this->confirmRecord = $this->cm->get_new_record('application_job', '2', 'application_status', 'assign_faculty', $userName);
            $this->discardRecord = $this->cm->get_new_record('application_job', '4', 'application_status', 'assign_faculty', $userName);
        }
        if (@$_SESSION['logtype'] == 'Manager' || @$_SESSION['logtype'] == "Super Admin") {
            $this->job_pending = $this->cm->get_new_record_all('job_post', '1', 'job_status');
            $this->job_active = $this->cm->get_new_record_all('job_post', '0', 'job_status');
            $this->job_deactive = $this->cm->get_new_record_all('job_post', '2', 'job_status');
            $this->all_jobs = $this->cm->get_new_record_all('job_post');
        } 
        else {
            $this->job_pending = $this->cm->get_new_record_all('job_post', '1', 'job_status');
            $this->job_active = $this->cm->get_new_record_all('job_post', '0', 'job_status');
            $this->job_deactive = $this->cm->get_new_record_all('job_post', '2', 'job_status');
            $this->all_jobs = $this->cm->get_new_record_all('job_post');
        }
    }
    public function jobApplication() {
        if (empty($this->session->userdata('postponed_after'))) {
            $stPo1 = 3;
            $curDate = date('m-d-Y');
            $display_postponed = $this->cm->view_all_job_application("application_job", 'application_status', $stPo1, 'next_followup_date', $curDate);
            // echo "<pre>";
            // print_r($display_postponed);
            // exit;
            date_default_timezone_set('Asia/Kolkata');
            $currentTime = date('Y-m-d h:i:sA');
            foreach ($display_postponed as $key => $val) {
                if ($val->next_followup_date == $curDate) {
                    $data_re = array('applications_id' => $val->application_number, 'remarks' => "Automatically go Active Basket", 'remarks_by' => @$_SESSION['user_name'], 'labels' => "active", 're_date' => $currentTime);
                    $this->cm->Insert_remark_record('job_remarks', $data_re);
                    $status_change = array('application_status' => 1);
                    $this->cm->update_data("application_job", $status_change, "application_id", $val->application_id);
                }
            }
            // exit;
            $this->session->set_userdata('postponed_after', "complete");
        }
        if (!empty($this->input->post('star_submit'))) {
            $star_data = $this->input->post();
            $total = 0;
            for ($i = 0;$i < sizeof($star_data['rating_no']);$i++) {
                $total = $total + $star_data['rating_no'][$i];
            }
            $no = count($star_data['rating_no']) * 10;
            $per = ($total / $no) * 100;
            if ($per >= 0 && $per <= 20) {
                $star = 1;
            } else if ($per >= 21 && $per <= 40) {
                $star = 2;
            } else if ($per >= 41 && $per <= 60) {
                $star = 3;
            } else if ($per >= 61 && $per <= 80) {
                $star = 4;
            } else if ($per >= 81 && $per <= 100) {
                $star = 5;
            }
            // echo "<pre>";
            $rating_no = implode(',', $star_data['rating_no']);
            $rating_remarks = implode(',', $star_data['rating_remarks']);
            $application_id = $this->input->post('star_application_id');
            // exit;
            // $cr_star_id =  $this->input->post('cr_star_id');
            $star_data = array('star' => $star, 'star_remarks' => $rating_remarks, 'star_no' => $rating_no);
            // print_r($star_data);
            // exit;
            $this->cm->student_star_rating('application_job', 'application_id', $application_id, $star_data);
            $this->session->set_flashdata('star_msg', 'give Star Successfully');
        }
        if ($this->input->get('action') == "notifive_status") {
            $id = $this->input->get('id');
            if ($this->input->get('notifive_status') == 0) {
                $st['notifive_status'] = 1;
            } else {
                $st['notifive_status'] = 1;
            }
            $re = $this->cm->update_data("application_job", $st, "application_id", $id);
            if ($re) {
                redirect('FormController/jobApplication');
            }
        }
        if (!empty($this->input->get('status'))) {
            $sts = $this->input->get('status');
            $followup = !empty($this->input->get('followup')) ? $this->input->get('followup') : '';
            $display['appli_sts'] = $sts;
            $display['followup_status'] = $followup;
        } else {
            $display['appli_sts'] = "inactive";
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "discard") {
                if ($this->input->get('discard') == 0) {
                    $st['discard'] = 1;
                } else {
                    $st['discard'] = 0;
                }
                $re = $this->cm->update_data("application_job", $st, "application_id", $id);
                if ($re) {
                    redirect('FormController/jobApplication');
                }
            } else if ($this->input->get('action') == "view") {
                $display['remarks_record'] = $this->cm->get_remarks_id_wise('job_remarks', 'applications_id', $id);
                $display['select_application'] = $this->cm->select_data("application_job", "application_id", $id);
            }
        }
        if (!empty($this->input->post('submit_job'))) {
            $data = $this->input->post();
            $data['position_for_apply'];
            $data['name'] = $data['fname'] . " " . $data['mname'] . " " . $data['lname'];
            $data['prefer_job_location'] = implode(",", $data['prefer_job_location']);
            $data['position_for_apply'] = implode(",", $data['position_for_apply']);
            //add remarks in
            $id = $data['application_id'];
            $user_name = $data['user_name_re'];
            $st = $data['application_status'];
            $rem = $data['faculty_remarks'];
            $da = date('d/m/Y H:i:s');
            if ($st == 0) {
                $st1 = "Inactive";
            } else if ($st == '5') {
                $st1 = "newfollowup";
            } else if ($st == 1) {
                $st1 = "Active";
            } else if ($st == 6) {
                $st1 = "activefollowup";
            } else if ($st == 2) {
                $st1 = "Confirm";
            } else if ($st == 3) {
                $st1 = "Postpone";
            } else if($st == 7){
                $st1 ="wpf";
            }
            $record1 = array('applications_id' => $id, 'remarks' => $rem, 'remarks_by' => $user_name, 'labels' => $st1, 're_date' => $da);
            $this->cm->Insert_remark_record('job_remarks', $record1);
            unset($data['submit_job']);
            unset($data['fname']);
            unset($data['mname']);
            unset($data['lname']);
            unset($data['application_id']);
            unset($data['faculty_remarks']);
            unset($data['user_name_re']);
            $re = $this->cm->update_data("application_job", $data, "application_id", $id);
            if ($re) {
                redirect('FormController/jobApplication');
            }
        }
        if (!empty($this->input->post('filter_profile'))) {
            $filter = $this->input->post();
            $display['application_job_all'] = $this->cm->jobapplication_filter("application_job", $filter);
            $display['filter_Next_Followup_Date_from'] = @$filter['filter_Next_Followup_Date_from'];
            $display['filter_Next_Followup_Date_to'] = @$filter['filter_Next_Followup_Date_to'];
            $display['filter_branch'] = @$data['filter_branch'];
            $display['filter_fname'] = @$filter['filter_fname'];
            $display['filter_grId'] = @$data['filter_grId'];
            $display['filter_email'] = @$filter['filter_email'];
            $display['filter_mobile'] = @$filter['filter_mobile'];
            $display['filter_faculty'] = @$data['filter_faculty'];
            $display['filter_demoDate_start'] = @$data['filter_demoDate_start'];
            $display['filter_demoDate_end'] = @$data['filter_demoDate_end'];
            $display['filter_position_for_apply'] = @$data['filter_position_for_apply'];
            $display['filter_prefer_job_location'] = @$data['filter_prefer_job_location'];
            $display['filter_applicationId'] = @$data['filter_applicationId'];
            $display['filter_oe_type'] = @$data['filter_oe_type'];
            $display['filter_on'] = "dfgf";
        } else if (!empty($this->input->get('filter_student_experience'))) {
            $filter_exp = $this->input->get();
            $display['application_job_all'] = $this->cm->jobapplication_filter_exp('application_job', $filter_exp);
        } else {
            $userName = $_SESSION['Admin']['username'];
            if ($userName == 'Pradip Malaviya' || @$_SESSION['user_name'] == 'Hitesh Desai') {
                $status = $this->input->get('status');
                if ($status == "inactive") {
                    $st1 = 0;
                    $display['application_job_all'] = $this->cm->view_all_job_application("application_job", 'application_status', $st1);
                } else if ($status == 'newfollowup') {
                    $st1 = 5;
                    $curDate = date('m-d-Y');
                    $display['application_job_all'] = $this->cm->view_all_job_application("application_job", 'application_status', $st1, 'next_followup_date', $curDate);
                } else if ($status == "active") {
                    $st1 = 1;
                    $display['application_job_all'] = $this->cm->view_all_job_application("application_job", 'application_status', $st1);
                } else if ($status == 'activefollowup') {
                    $st1 = 6;
                    $curDate = date('m-d-Y');
                    $display['application_job_all'] = $this->cm->view_all_job_application("application_job", 'application_status', $st1, 'next_followup_date', $curDate);
                } else if ($status == "confirm") {
                    $st1 = 2;
                    $display['application_job_all'] = $this->cm->view_all_job_application("application_job", 'application_status', $st1);
                } else if ($status == "postpone") {
                    $st1 = 3;
                    $display['application_job_all'] = $this->cm->view_all_job_application("application_job", 'application_status', $st1);
                } else {
                    $display['application_job_all'] = $this->cm->view_all_job_application("application_job", 'application_status', 0);
                }
            } else {
                $status = $this->input->get('status');
                $display['application_all_remarks'] = $this->cm->view_get_all_remarks_job("job_remarks");
                // $display['application_job_all'] = $this->cm->view_all_job_application("application_job");
                $display['application_job_all'] = $this->cm->view_all_job_application_assign_faculty("application_job", "assign_faculty", $userName);
            }
            // echo "<pre>";
            // print_r($display['application_job_all']);
            // exit;
            
        }
        // echo "<pre>";
        // 	print_r($display['application_job_all']);
        // 	exit;
        $display['notification_all'] = $this->cm->application_notification('application_job');
        $display['company_list'] = $this->cm->view_all_compnay('company_detail');
        $display['faculty_all'] = $this->cm->view_all_faculty("faculty");
        $display['area_all'] = $this->cm->view_all("area");
        $display['jobtype_all'] = $this->cm->view_all("jobtype");
        $display['branch_all'] = $this->cm->view_all("branch");
        $display['whatsapp_tem_data'] = $this->cm->view_all('whatsapp_template');
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['newRecord'] = $this->newRecord;
        $update['newfollowupRecord'] = $this->newfollowupRecord;
        $update['activeRecord'] = $this->activeRecord;
        $update['activefollowupRecord'] = $this->activefollowupRecord;
        $update['postponeRecord'] = $this->postponeRecord;
        $update['confirmRecord'] = $this->confirmRecord;
        $update['wpf'] = $this->wpf;
        $update['discardRecord'] = $this->discardRecord;
        $this->load->view('header', $update);
        $this->load->view('application_job', $display);
    }

    public function getStudentsRecordWpf(){
            $application_number =  $this->input->post('application_id');
            $record = $this->cm->get_all_record_data_student('application_job','application_number',$application_number);
            echo json_encode($record);

    }


    public function profile() {
        if (!empty($this->input->post('filter_profile'))) {
            $filter = $this->input->post();
            $display['all_application_data'] = $this->cm->jobapplication_filter("application_job", $filter);
            $display['filter_fname'] = @$filter['filter_fname'];
            $display['filter_branch'] = @$filter['filter_branch'];
            $display['filter_position_for_apply'] = @$filter['filter_position_for_apply'];
            $display['filter_applicationId'] = @$filter['filter_applicationId'];
            $display['filter_email'] = @$filter['filter_email'];
            $display['filter_mobile'] = @$filter['filter_mobile'];
            $display['filter_gr_id'] = @$filter['filter_gr_id'];
            $display['filter_faculty'] = @$filter['filter_faculty'];
            $display['filter_on'] = "dfgf";
        }
        $display['faculty_all'] = $this->cm->view_all_faculty("faculty");
        $display['area_all'] = $this->cm->view_all("area");
        $display['jobtype_all'] = $this->cm->view_all("jobtype");
        $display['branch_all'] = $this->cm->view_all("branch");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['newRecord'] = $this->newRecord;
        $update['activeRecord'] = $this->activeRecord;
        $update['postponeRecord'] = $this->postponeRecord;
        $update['confirmRecord'] = $this->confirmRecord;
        $update['discardRecord'] = $this->discardRecord;
        $this->load->view('header', $update);
        $this->load->view('job_profile', $display);
    }
    public function managementForm() {
        logdata("ManagementForm page open");
        // @$user_permission =  explode(",",$_SESSION['user_permission']);
        // if(in_array("Single Course=1",$user_permission) || $_SESSION['logtype']=="Super Admin") {
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("form_list", "form_id", $id);
                if ($re) {
                    logdata("managementForm " . $id . " Delete");
                    redirect('FormController/managementForm');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_form'] = $this->cm->select_data("form_list", "form_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $data['permission'] = implode(",", $data['logall']);
            $data['admin_id'] = $_SESSION['admin_id'];
            unset($data['update_id']);
            unset($data['submit']);
            unset($data['logall']);
            if ($_FILES['form_file']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/signsheet/";
                $new_name = time() . $_FILES["form_file"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('form_file')) {
                    $imagedata = $this->upload->data();
                    $data['form_file'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $ccrr = $this->cm->select_data("form_list", "form_id", $id);
                if ($_FILES['form_file']['name'] != "") {
                    $filess = FCPATH . "dist/signsheet/" . $ccrr->form_file;
                    @unlink($filess);
                }
                $re = $this->cm->update_data("form_list", $data, "form_id", $id);
            } else {
                $re = $this->cm->insert_data("form_list", $data);
            }
            if (@$re) {
                redirect('FormController/managementForm');
            }
        }
        $display['form_all'] = $this->cm->view_all("form_list");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['log_all'] = $this->cm->filter_data("logtype", "parent_id", "0");
        } else {
            $display['log_all'] = $this->tm->view_all("logtype");
        }
        // echo "<pre>";
        // print_r($display['form_all']);
        //$update['log_all'] = $this->tm->view_all("logtype");
        $this->load->view('header', $update);
        $this->load->view('management_form', $display);
    }
    public function records_remarks() {
        $faculty12 = $this->input->post('faculty_assign');
        $app_id = $this->input->post('application_id');
        $faculty_assign = isset($faculty12) ? $faculty12 : "";
        $remarks_id = $this->input->post('remarks_id');
        $user_name_re = $this->input->post('user_name_re');
        $remarks_label = $this->input->post('remarks_label');
        $rem_date = $this->input->post('date1');
        $next_followup_date = !empty($this->input->post('next_followup_date')) ? $this->input->post('next_followup_date') : '';
        $record = array('applications_id' => $app_id, 'remarks' => $remarks_id, 'remarks_by' => $user_name_re, 'labels' => $remarks_label, 're_date' => $rem_date, 'faculty_assign' => $faculty_assign);
        // $data =  array('');
        if ($remarks_label == 'Discart') {
            $data = array('application_status' => 4, 'next_followup_date' => $next_followup_date);
            $this->cm->update_data("application_job", $data, "application_number", $app_id);
        } else if ($remarks_label == 'New') {
            $data = array('application_status' => 0);
            $this->cm->update_data("application_job", $data, "application_number", $app_id);
        } else if ($remarks_label == 'New_Followup') {
            $data = array('application_status' => 5);
            $this->cm->update_data("application_job", $data, "application_number", $app_id);
        } else if ($remarks_label == 'Active') {
            $data = array('application_status' => 1);
            $this->cm->update_data("application_job", $data, "application_number", $app_id);
        } else if ($remarks_label == 'Active_Followup') {
            $data = array('application_status' => 6);
            $this->cm->update_data("application_job", $data, "application_number", $app_id);
        } else if ($remarks_label == 'Postpone') {
            $data = array('application_status' => 3);
            $this->cm->update_data("application_job", $data, "application_number", $app_id);
        } else if ($remarks_label == 'Confirm') {
            $data = array('application_status' => 2);
            $this->cm->update_data("application_job", $data, "application_number", $app_id);
        } else if($remarks_label =='wpf'){
            $data =  array('application_status' => 7);
            $this->cm->update_data("application_job", $data, "application_number", $app_id);
        }
        // $num =  $this->cm->check_remarks_alra('job_remarks',$remarks_id);
        // if(@$num==0)
        // {
        $follow_date = array('next_followup_date' => $next_followup_date);
        $this->cm->update_data("application_job", $follow_date, "application_number", $app_id);
        $assign_array = array('assign_faculty' => $faculty_assign);
        $this->cm->Insert_remark_record('job_remarks', $record);
        $this->cm->Update_remark_record('application_job', $assign_array, 'application_number', $app_id);
        $this->session->set_flashdata('remark_add', "Remarks Add Successfully");
        $display['remarks_job'] = "Remarks Add Successfully";
        // }
        // else
        // {
        // 	// $this->session->set_flashdata('remark_add',"Remarks Already Added");
        // 	$display['remarks_job'] = "Remarks Already Added";
        // }
        $display['remarks_record'] = $this->cm->get_remarks_id_wise('job_remarks', 'applications_id', $app_id);
        $this->load->view('ajax_remarks_record', $display);
    }
    public function records_remarks_confirms() {
        echo "<pre>";
        print_r($this->input->post());
    }
    public function get_pop_student_record() {
         $app_id = $this->input->post('id');
        $data['st_record'] = $this->cm->get_app_wise_record('application_job', $app_id, 'application_id');
         @$appl_no = $data['st_record']->application_number;
        $data['student_remakrs'] = $this->cm->get_remarks_student_wise('job_remarks', 'applications_id', $appl_no);

        $data['upd_faculty'] = $this->cm->view_all("faculty");
        $data['upd_position_applied'] = $this->cm->view_all("job_position_category");
        $data['upd_position_subcategory'] = $this->cm->view_all("job_subcategory");
        $this->load->view('ajax_get_popup', $data);
    }
    public function remarks_search() {
        $data = $this->input->post();
        $result['student_remakrs'] = $this->cm->remark_searching_record('job_remarks', $data['remark_search'], $data['application_no']);
        $this->load->view('ajax_remarks_searching', $result);
    }
    // all jobs function
    public function give_student_rating() {
        $data = $this->input->post();
        $star = $this->input->post('cr_star_id');
        $application_id = $this->input->post('star_company_id');
        $star_data = array('star' => $star);
        $this->cm->student_give_rating('application_job', 'application_id', $application_id, $star_data);
        redirect('FormController/jobApplication');
    }
    public function viewall_company_detail($status = '', $id = '') {
        if ($this->input->post('star_submit')) {
            $star = $this->input->post('cr_star_id');
            $company_id = $this->input->post('star_company_id');
            $star_data = array('rating' => $star);
            $this->cm->change_rating('company_detail', 'company_id', $company_id, $star_data);
        }
        $status_data_company = $status;
        if ($this->input->post('remarks') != '') {
            $status = $this->input->post('status');
            $id = $this->input->post('cr_company_id');
            if ($status != '' && $id != '') {
                $data = $this->input->post();
                $st_data = $this->input->post('status_data_change');
                if ($st_data == 1) {
                    $s_reco = "Active";
                } else if ($st_data == 2) {
                    $s_reco = "Deactive";
                    $allJobs = array('job_status' => $st_data);
                    $this->cm->change_all_job_status('job_post', 'company_id', $id, $allJobs);
                } else if ($st_data == 3) {
                    $s_reco = "Dumped";
                    $allJobs = array('job_status' => $st_data);
                } elseif ($st_data == 0) {
                    $s_reco = "Pending";
                    $allJobs = array('job_status' => $st_data);
                    $this->cm->change_all_job_status('job_post', 'company_id', $id, $allJobs);
                }
                $status_data = array('status' => $this->input->post('status_data_change'));
                $record = array('remarks' => $data['remarks'], 'cr_company_id' => $data['cr_company_id'], 'remark_by' => $data['remarks_by'], 'status' => $data['status']);
                $this->cm->add_remarks_company('company_remarks', $record);
                $all_record_company = $this->cm->get_company_record('company_detail', $data['cr_company_id'], 'company_id');
                $email_record = $all_record_company->email_id;
                $employer_name = $all_record_company->employer_name;
                $config = array('protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
                'smtp_host' => 'smtp.gmail.com', 'smtp_port' => 465, 'smtp_user' => 'placement.rnwmultimedia@gmail.com', 'smtp_pass' => 'rnw#tpo2020', 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
                'mailtype' => 'html', //plaintext 'text' mails or 'html'
                'smtp_timeout' => '4', //in seconds
                'charset' => 'UTF-8', 'wordwrap' => TRUE);
                // $this->load->config('email');
                $this->load->library('email');
                $this->email->initialize($config);
                $from = "placement.rnwmultimedia@gmail.com";
                $to = $email_record;
                $subject = "Status Change";
                if (@$st_reco == "Active") {
                    $message = '<div style="width: 600px; margin: 0 auto; font-family: Roboto; border: 1px solid #666; padding: 63px 12px; box-sizing: border-box;">
		<table width="100%"><tr><td class="logo_td"><div class="logo" style="width: 300px; margin: 0 auto;"><img src="https://demo.rnwmultimedia.com/placement/images/logo.png" width="100%"></div></td></tr><tr><td><h2 style="text-align: center; font-size: 20px;"><p style="margin: 0;">Training & Placement Policy</p><p style="margin: 0;">Red & White Group of Institute</p></h2></td></tr><tr><td><h1 style="font-weight: 400; margin: 0;">Hello, ' . $employer_name . ' <br> </h1></td></tr><tr><td align="center"><p style="font-size: 18px;">This is Red & White Group of Institute Your Status will be change to <span  style="display: inline-block; background: green; color: #fff; padding: 4px 7px; border-radius: 3px;">' . $s_reco . '</span><b>Go To Below Link To Post Job & Find Employee</b></p></td></tr><tr><td align="center"><a href="http://demo.rnwmultimedia.com/placement" style="display: inline-block; text-decoration: none; background: #e31e25; color: #fff; padding: 8px 14px; border-radius: 3px; text-transform: uppercase;">Click To Login</a></td></tr></table></div>';
                } else {
                    $message = '<div style="width: 600px; margin: 0 auto; font-family: Roboto; border: 1px solid #666; padding: 63px 12px; box-sizing: border-box;">
		<table width="100%"><tr><td class="logo_td"><div class="logo" style="width: 300px; margin: 0 auto;"><img src="https://demo.rnwmultimedia.com/placement/images/logo.png" width="100%"></div></td></tr><tr><td><h2 style="text-align: center; font-size: 20px;"><p style="margin: 0;">Training & Placement Policy</p><p style="margin: 0;">Red & White Group of Institute</p></h2></td></tr><tr><td><h1 style="font-weight: 400; margin: 0;">Hello, ' . $employer_name . ' <br> </h1></td></tr><tr><td align="center"><p style="font-size: 18px;">This is Red & White Group of Institute Your Status will be change to <span  style="display: inline-block; background: #F39C12; color: #fff; padding: 4px 7px; border-radius: 3px;">' . $s_reco . '</span><b>Contact RNW Placement Department</b></p></td></tr><tr><td align="center"><a href="http://demo.rnwmultimedia.com/placement" style="display: inline-block; text-decoration: none; background: #e31e25; color: #fff; padding: 8px 14px; border-radius: 3px; text-transform: uppercase;">Click To Login</a></td></tr></table></div>';
                }
                //         $message = '<p style="text-align: center;"><strong>Training & Placement Policy for Red & White Group of Institute</strong></p>
                // <p style="text-align: center;"><span style="font-weight: 400;">Hello, '.$employer_name.'</span></p>
                // <p style="text-align: center;"><span style="font-weight: 400;">       </span> <span style="font-weight: 400;">This is </span><strong>Red & White Group of Institute</strong><span style="font-weight: 400;"> Your Status will be change to <span style="padding:5px; color:white; background-color:green; border-radius: 5px;">'.$s_reco.'</span></span><br><strong>Go To Below Link To Post Job & Find Employee </strong><br><span style="font-weight: 400;"> <a href="http://demo.rnwmultimedia.com/placement" style="color:black; padding:10px; font-size:18px;">Click</a></span></p>';
                $this->email->set_newline("\r\n");
                $this->email->from($from);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->send();
                $re = $this->cm->status_wise_record_company('company_detail', 'company_id', $id, $status_data);
                if ($re) {
                    $this->session->set_flashdata('msg_alert', "Change Status Successfully");
                }
            }
        }
        if ($this->input->get('status') != '') {
            $status_data_company = $this->input->get('status');
            if ($this->input->post('search') != '') {
                $search_record = $this->input->post();
                $data['recruiter'] = $this->cm->search_by_field_company('company_detail', $search_record, $status_data_company);
                $data['filter_record'] = $search_record;
            } else {
                $data['recruiter'] = $this->cm->get_company_record_bystatus('company_detail', 'status', $status_data_company);
            }
        } else {
            if ($this->input->post('search') != '') {
                $search_record = $this->input->post();
                $data['recruiter'] = $this->cm->search_by_field_company('company_detail', $search_record, $status_data_company);
                $data['filter_record'] = $search_record;
            } else {
                $data['recruiter'] = $this->cm->get_company_record_bystatus('company_detail', 'status', 'all');
            }
        }
        $data['total_company'] = $this->cm->count_total_company('company_detail');
        $data['total_pending_company'] = $this->cm->count_total_status_company('company_detail', 'status', 0);
        $data['total_active_company'] = $this->cm->count_total_status_company('company_detail', 'status', 1);
        $data['total_deactive_company'] = $this->cm->count_total_status_company('company_detail', 'status', 2);
        $data['total_dumped_company'] = $this->cm->count_total_status_company('company_detail', 'status', 3);
        $update['all_jobs'] = $this->cm->view_all("job_post");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('jobs/view_all_company', $data);
    }
    public function Deactive_recruiter($id) {
        $rec = array('job_status' => 0);
        $re = $this->cm->change_admin_status_record('job_post', $rec, $id, 'jobpost_id');
        if ($re) {
            $this->session->set_flashdata('msg_alert', 'Change Status of company');
            redirect('FormController/view_all_jobs');
        } else {
            $this->session->set_flashdata('msg_alert', 'Something Wrong');
            redirect('FormController/view_all_jobs');
        }
    }
    public function Any_remarks_to_comapny() {
        $record = array('de_jobpost_id' => $this->input->post('jobpost_id'), 'de_company_id' => $this->input->post('company_id'), 'de_reason_remark' => $this->input->post('remarks'), 'de_by_name' => $_SESSION['user_name'], 'de_created_date' => date('d/m/Y H:i:s'));
        $re = $this->cm->add_job_category_position('job_deactive_remarks', $record);
        if ($re) {
            $company_id = $this->input->post('company_id');
            $jobpost_id = $this->input->post('jobpost_id');
            $remarks_data_r = $this->input->post('remarks');
            $all_record_company = $this->cm->get_company_record('company_detail', $company_id, 'company_id');
            $all_record_jobs_all = $this->cm->get_company_record('job_post', $jobpost_id, 'jobpost_id');
            $jjjj_co_name = $all_record_company->company_name;
            $jjjj_job_name = $all_record_jobs_all->job_name;
            $jjjj_position = $all_record_jobs_all->position;
            $email_record = $all_record_company->email_id;
            // $email_record = "piyush0101nakarani@gmail.com";
            $config = array('protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
            'smtp_host' => 'smtp.gmail.com', 'smtp_port' => 465, 'smtp_user' => 'placement.rnwmultimedia@gmail.com', 'smtp_pass' => 'rnw#tpo2020', 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
            'mailtype' => 'html', //plaintext 'text' mails or 'html'
            'smtp_timeout' => '4', //in seconds
            'charset' => 'UTF-8', 'wordwrap' => TRUE);
            // $this->load->config('email');
            $this->load->library('email');
            $this->email->initialize($config);
            $from = "placement.rnwmultimedia@gmail.com";
            $to = $email_record;
            $subject = "Placement Department Request";
            $message = '<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>T&P</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f1f1;
        }
        
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
        
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        .footer-icon td{
            width: 30px;
            height: 30px;
        }
        .footer-icon {
            text-align: center;
        }
        .footer-icon a{
            display: inline-block;
            width: 30px;
            height: 30px;
            vertical-align: middle;
        }
        .Notice_info{
            padding-left: 15px !important;
        }
        .Notice_info li{
            margin-bottom: 0px;
        }
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        
        img {
            -ms-interpolation-mode: bicubic;
        }
        
        a {
            text-decoration: none;
        }
    </style>
    <style>
        .primary {
            background: #0b527e;
        }
        
        .bg_white {
            background: #ffffff;
        }
        
        .bg_light {
            background: #fafafa;
        }
        
        .bg_black {
            background: #000000;
        }
        
        .bg_dark {
            background: rgba(0, 0, 0, .8);
        }

        .bg_gray{
            background: #e8e8e8;
        }
        
        .email-section {
            padding: 2.5em;
        }
        
        .worning {
            display: inline-block;
            padding: 3px 26px;
            background: rgba(0, 128, 0, 0.5);
            color: #fff;
        }
        .cirecil{
            width: 186px;
            height: 186px;
            border-radius: 100%;
            overflow: hidden;
            display: inline-block;
            margin: 0 auto;
            border: 5px solid #fff;
            box-shadow: 0 0 6px 0 rgba(0,0,0,.2);
        }
        ol{
            margin: 0;
            padding: 0;
        }
        p{

        }
        
        .btn {
            padding: 5px 15px;
            display: inline-block;
        }
        
        .btn.btn-primary {
            border-radius: 5px;
            background: #0d0cb5;
            color: #ffffff;
        }
        
        .btn.btn-white {
            border-radius: 5px;
            background: #ffffff;
            color: #000000;
        }
        
        .btn.btn-white-outline {
            border-radius: 5px;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
        }
        
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Poppins", sans-serif;
            color: #000000;
            margin-top: 0;
        }
        
        body {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(0, 0, 0, .4);
        }
        
        a {
            color: #0d0cb5;
        }
        
        .services {
            background: rgba(0, 0, 0, .03);
        }
        
        .text-services {
            padding: 10px 10px 0;
            text-align: center;
        }
        
        .text-services h3 {
            font-size: 16px;
            font-weight: 600;
        }
        .services-list p{
            color: rgba(0, 0, 0, .4);
        }
        .services-list {
            padding: 12px;
            box-sizing: border-box;
            width: 100%;
            float: left;
            text-align: center;
        }
        .services-list h5{
            margin-bottom: 0;
        }
        
        .services-list img {
            float: left;
        }
        
        .services-list .text {
            width: calc(100% - 60px);
            float: right;
        }
        
        .services-list h3 {
            margin-top: 0;
            margin-bottom: 0;
            color: #212529;
        }
        
        .services-list p {
            margin: 0;
        }
        
        .footer {
            color: rgba(255, 255, 255, .5);
        }
        
        .footer .heading {
            color: #ffffff;
            font-size: 20px;
        }
        
        .footer ul {
            margin: 0;
            padding: 0;
        }
        
        .footer ul li {
            list-style: none;
            margin-bottom: 10px;
        }
        
        .footer ul li a {
            color: rgba(255, 255, 255, 1);
        }
        
        @media screen and (max-width: 500px) {
            .icon {
                text-align: left;
            }
            .text-services {
                padding-left: 0;
                padding-right: 20px;
                text-align: left;
            }
        }
    </style>

</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly;">
    <center style="width: 100%; background-color: #f1f1f1;">
        <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        </div>
        <div style="max-width: 600px; margin: 0 auto;" class="email-container">
            
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                <tr>
                    <td valign="middle" class="primary footer email-section" style="padding-bottom: 0; padding-top: 0;">
                        <table>
                            <tbody>
                                <tr>
                                    <td valign="top" width="33.333%" style="padding-top: 20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 5px; padding-right: 5px;">
                                                        <h3 class="heading" style="margin-bottom: 10px; color: #fff;">Placement Department Request</h3>
                                                        <table width="100%">
                                                            <tr>
                                                                <td><p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">Company Name : <a style="color: #fff;" href="tel:9327506324">' . $jjjj_co_name . ' </a></p>
                                                                </td>
                                                            </tr>
                                                            <tr>    
                                                                <td>
                                                                    <p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">Job Name : <a style="color: #fff;" href="tel:7650050042"> ' . $jjjj_job_name . '</a></p>
                                                                </td>
                                                            </tr>
    <tr>
        <td>
            <p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">Job Position : <a style="color: #fff;" href="tel:7650050042">' . $jjjj_position . '</a></p>
        </td>
    </tr>
                                                            

                                                            
      <tr>
        <td>
            <p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">Response : <a style="color:#fff; " href="#" >' . $remarks_data_r . '</a></p>
        </td>
    </tr>

     <tr align="center">
        <td>
            <p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">: <a style="color:#fff;" href="https://demo.rnwmultimedia.com/placement/" >Go To Link</a></p>
        </td>
    </tr>

                                                        </table>
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                   <td valign="middle" class=" footer email-section" style="text-align: center; padding-top: 0; padding-bottom: 0; background:#e31e25;;">
                       <table>
                           <tbody>
                               <tr>
                                   <td valign="top" width="100%">
                                       <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                           <tbody>
                                               <tr>
                                                   <td style="text-align: left; padding-right: 8px; text-align: center; color: white; font-size: 12px;">
                                                       <p style="margin-top: 10px;">© Copyright 2020. Red & White Placement Department. All Rights Reserved.</p>
                                                   </td>
                                               </tr>
                                           </tbody>
                                       </table>
                                   </td>

                               </tr>
                           </tbody>
                       </table>
                   </td>
                </tr>

             
            </table>

        </div>
    </center>
</body>
</html>';
            $this->email->set_newline("\r\n");
            $this->email->from($from);
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
            echo "1";
        } else {
            echo "0";
        }
    }
    public function active_recruiter() {
        $data = $this->input->post();
        $record = array('de_jobpost_id' => $this->input->post('jobpost_id'), 'de_company_id' => $this->input->post('company_id'), 'de_reason_remark' => $this->input->post('remarks'), 'de_by_name' => $_SESSION['user_name'], 'de_created_date' => date('d/m/Y H:i:s'));
        $this->cm->add_job_category_position('job_deactive_remarks', $record);
        $id = $this->input->post('jobpost_id');
        $rec = array('job_status' => $this->input->post('job_status'));
        $re = $this->cm->change_admin_status_record('job_post', $rec, $id, 'jobpost_id');
        //email coding
        $status_job = $this->input->post('job_status');
        $company_id = $this->input->post('company_id');
        $jobpost_id = $this->input->post('jobpost_id');
        $all_record_company = $this->cm->get_company_record('company_detail', $company_id, 'company_id');
        $all_record_jobs_all = $this->cm->get_company_record('job_post', $jobpost_id, 'jobpost_id');
        // print_r($all_record_company);
        // print_r($all_record_jobs_all);
        $jjjj_co_name = $all_record_company->company_name;
        $jjjj_job_name = $all_record_jobs_all->job_name;
        $jjjj_position = $all_record_jobs_all->position;
        $jjj_comments = $this->input->post('remarks');
        // exit;
        $email_record = $all_record_company->email_id;
        // $email_record = "piyush0101nakarani@gmail.com";
        // print_r($email_record);
        // exit;
        $config = array('protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
        'smtp_host' => 'smtp.gmail.com', 'smtp_port' => 465, 'smtp_user' => 'placement.rnwmultimedia@gmail.com', 'smtp_pass' => 'rnw#tpo2020', 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
        'mailtype' => 'html', //plaintext 'text' mails or 'html'
        'smtp_timeout' => '4', //in seconds
        'charset' => 'UTF-8', 'wordwrap' => TRUE);
        // $this->load->config('email');
        $this->load->library('email');
        $this->email->initialize($config);
        $from = "placement.rnwmultimedia@gmail.com";
        $to = $email_record;
        $subject = "Change Job Status";
        $message = '<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>T&P</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f1f1;
        }
        
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
        
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        .footer-icon td{
            width: 30px;
            height: 30px;
        }
        .footer-icon {
            text-align: center;
        }
        .footer-icon a{
            display: inline-block;
            width: 30px;
            height: 30px;
            vertical-align: middle;
        }
        .Notice_info{
            padding-left: 15px !important;
        }
        .Notice_info li{
            margin-bottom: 0px;
        }
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        
        img {
            -ms-interpolation-mode: bicubic;
        }
        
        a {
            text-decoration: none;
        }
    </style>
    <style>
        .primary {
            background: #0b527e;
        }
        
        .bg_white {
            background: #ffffff;
        }
        
        .bg_light {
            background: #fafafa;
        }
        
        .bg_black {
            background: #000000;
        }
        
        .bg_dark {
            background: rgba(0, 0, 0, .8);
        }

        .bg_gray{
            background: #e8e8e8;
        }
        
        .email-section {
            padding: 2.5em;
        }
        
        .worning {
            display: inline-block;
            padding: 3px 26px;
            background: rgba(0, 128, 0, 0.5);
            color: #fff;
        }
        .cirecil{
            width: 186px;
            height: 186px;
            border-radius: 100%;
            overflow: hidden;
            display: inline-block;
            margin: 0 auto;
            border: 5px solid #fff;
            box-shadow: 0 0 6px 0 rgba(0,0,0,.2);
        }
        ol{
            margin: 0;
            padding: 0;
        }
        p{

        }
        
        .btn {
            padding: 5px 15px;
            display: inline-block;
        }
        
        .btn.btn-primary {
            border-radius: 5px;
            background: #0d0cb5;
            color: #ffffff;
        }
        
        .btn.btn-white {
            border-radius: 5px;
            background: #ffffff;
            color: #000000;
        }
        
        .btn.btn-white-outline {
            border-radius: 5px;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
        }
        
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Poppins", sans-serif;
            color: #000000;
            margin-top: 0;
        }
        
        body {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(0, 0, 0, .4);
        }
        
        a {
            color: #0d0cb5;
        }
        
        .services {
            background: rgba(0, 0, 0, .03);
        }
        
        .text-services {
            padding: 10px 10px 0;
            text-align: center;
        }
        
        .text-services h3 {
            font-size: 16px;
            font-weight: 600;
        }
        .services-list p{
            color: rgba(0, 0, 0, .4);
        }
        .services-list {
            padding: 12px;
            box-sizing: border-box;
            width: 100%;
            float: left;
            text-align: center;
        }
        .services-list h5{
            margin-bottom: 0;
        }
        
        .services-list img {
            float: left;
        }
        
        .services-list .text {
            width: calc(100% - 60px);
            float: right;
        }
        
        .services-list h3 {
            margin-top: 0;
            margin-bottom: 0;
            color: #212529;
        }
        
        .services-list p {
            margin: 0;
        }
        
        .footer {
            color: rgba(255, 255, 255, .5);
        }
        
        .footer .heading {
            color: #ffffff;
            font-size: 20px;
        }
        
        .footer ul {
            margin: 0;
            padding: 0;
        }
        
        .footer ul li {
            list-style: none;
            margin-bottom: 10px;
        }
        
        .footer ul li a {
            color: rgba(255, 255, 255, 1);
        }
        
        @media screen and (max-width: 500px) {
            .icon {
                text-align: left;
            }
            .text-services {
                padding-left: 0;
                padding-right: 20px;
                text-align: left;
            }
        }
    </style>

</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly;">
    <center style="width: 100%; background-color: #f1f1f1;">
        <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        </div>
        <div style="max-width: 600px; margin: 0 auto;" class="email-container">
            
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                <tr>
                    <td valign="middle" class="primary footer email-section" style="padding-bottom: 0; padding-top: 0;">
                        <table>
                            <tbody>
                                <tr>
                                    <td valign="top" width="33.333%" style="padding-top: 20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 5px; padding-right: 5px;">
                                                        <h3 class="heading" style="margin-bottom: 10px; color: #fff;">Change Below Job Status</h3>
                                                        <table width="100%">
                                                            <tr>
                                                                <td><p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">Company Name : <a style="color: #fff;" href="tel:9327506324">' . $jjjj_co_name . ' </a></p>
                                                                </td>
                                                            </tr>
                                                            <tr>    
                                                                <td>
                                                                    <p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">Job Name : <a style="color: #fff;" href="tel:7650050042"> ' . $jjjj_job_name . '</a></p>
                                                                </td>
                                                            </tr>
    <tr>
        <td>
            <p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">Job Position : <a style="color: #fff;" href="tel:7650050042">' . $jjjj_position . '</a></p>
        </td>
    </tr>
                                                            

                                                            
      <tr>
        <td>
            <p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">Response : <a style="color:#fff; " href="#" >' . $jjj_comments . '</a></p>
        </td>
    </tr>

     <tr align="center">
        <td>
            <p style="color: #dedede; margin-bottom: 5px; margin-top: 0px;">: <a style="color:#fff;" href="https://demo.rnwmultimedia.com/placement/" >Go To Link</a></p>
        </td>
    </tr>

                                                        </table>
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                   <td valign="middle" class=" footer email-section" style="text-align: center; padding-top: 0; padding-bottom: 0; background:#e31e25;;">
                       <table>
                           <tbody>
                               <tr>
                                   <td valign="top" width="100%">
                                       <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                           <tbody>
                                               <tr>
                                                   <td style="text-align: left; padding-right: 8px; text-align: center; color: white; font-size: 12px;">
                                                       <p style="margin-top: 10px;">© Copyright 2020. Red & White Placement Department. All Rights Reserved.</p>
                                                   </td>
                                               </tr>
                                           </tbody>
                                       </table>
                                   </td>

                               </tr>
                           </tbody>
                       </table>
                   </td>
                </tr>

             
            </table>

        </div>
    </center>
</body>
</html>';
        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
        //email coding end
        if ($re) {
            $this->session->set_flashdata('msg_alert', 'Change Status of company');
            echo "1";
        } else {
            $this->session->set_flashdata('msg_alert', 'Something Wrong');
            // redirect('FormController/view_all_jobs');
            echo "0";
        }
    }
    public function pending_recruiter($id) {
        $rec = array('status' => 1);
        $re = $this->cm->change_admin_status_record('company_detail', $rec, $id, 'company_id');
        if ($re) {
            $this->session->set_flashdata('msg_alert', 'Change Status of company');
            redirect('FormController/view_all_jobs');
        } else {
            $this->session->set_flashdata('msg_alert', 'Something Wrong');
            redirect('FormController/view_all_jobs');
        }
    }
    public function active_recruiter_jobs($id, $status) {
        if ($status == 1) {
            $rec = array('status' => 1);
        } else {
            $rec = array('status' => 0);
        }
        $re = $this->cm->change_admin_status_record('student_applied_job', $rec, $id, 'applied_id');
        if ($re) {
            $this->session->set_flashdata('msg_alert', 'Change Status of Student Jobs');
            redirect('FormController/view_student_applied_jobs');
        } else {
            $this->session->set_flashdata('msg_alert', 'Something Wrong');
            redirect('FormController/view_student_applied_jobs');
        }
    }
    public function view_all_jobs() {
        if (!empty($this->input->post('filter_search'))) {
            $filter = $this->input->post();
            $display["recruiter"] = $this->cm->get_all_jobs_post("job_post", $filter);
            $display['filter_startDate'] = @$filter['filter_startDate'];
            $display['filter_endDate'] = @$filter['filter_endDate'];
            $display['filter_fname'] = @$filter['filter_fname'];
            $display['filter_lname'] = @$filter['filter_lname'];
            $display['filter_email'] = @$filter['filter_email'];
            $display['filter_mobile'] = @$filter['filter_mobile'];
            $display['filter_source'] = @$filter['filter_source'];
            $display['filter_branch'] = @$filter['filter_branch'];
            $display['filter_position_for_apply'] = @$filter['filter_position_for_apply'];
            $display['filter_prefer_job_location'] = @$filter['filter_prefer_job_location'];
            $display['filter_on'] = "dfgf";
        } else {
            if (!empty($this->input->get('job_status'))) {
                $j_status = $this->input->get('job_status');
                if ($j_status == 'pending') {
                    $j_data = '1';
                } else if ($j_status == 'active') {
                    $j_data = '0';
                } else if ($j_status == 'deactive') {
                    $j_data = '2';
                }
                $display['recruiter'] = $this->cm->get_all_jobs_post_statuswise('job_post', $j_data, 'job_status');
            } else {
                $display['recruiter'] = $this->cm->get_all_jobs_post('job_post');
            }
        }
        $display['area_all'] = $this->cm->view_all("area");
        $display['jobtype_all'] = $this->cm->view_all("jobtype");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['pending_job'] = $this->job_pending;
        $update['active_job'] = $this->job_active;
        $update['deactive_job'] = $this->job_deactive;
        $update['all_jobs'] = $this->all_jobs;
        $this->load->view('header', $update);
        $this->load->view('jobs/view_all_jobs', $display);
    }
    public function view_student_applied_jobs() {
        if (!empty($this->input->post('filter_appliy_student'))) {
            $filter = $this->input->post();
            $display['student_applied'] = $this->cm->applied_student_filter("student_applied_job", $filter);
            $display['filter_fname'] = @$filter['filter_fname'];
            $display['filter_company'] = @$filter['filter_company'];
            $display['filter_grId'] = @$data['filter_grId'];
            $display['filter_company_mobile_no'] = @$filter['filter_company_mobile_no'];
            $display['filter_mobile'] = @$filter['filter_mobile'];
            $display['filter_on'] = "dfgf";
        } else {
            $display['student_applied'] = $this->cm->student_applied_on_job('student_applied_job');
        }
        // echo "<pre>";
        // print_r($data);
        // die();
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        // echo "<pre>";
        // print_r($data);
        // exit;
        $this->load->view('header', $update);
        $this->load->view('jobs/student_applied_jobs', $display);
    }
    public function view_company_wise_job() {
        $id = $this->input->post('company_id');
        $data['recruiter'] = $this->cm->company_wise_job('job_post', 'company_id', $id);
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        // $this->load->view('header',$update);
        $this->load->view('jobs/ajax_company_wise_jobs', $data);
    }
    public function add_company_reason() {
        $data = $this->input->post();
        $record = array('remarks' => $data['remarks'], 'cr_company_id' => $data['cr_remarks_id'], 'remark_by' => $data['remarks_by'], 'status' => $data['status']);
        $re = $this->cm->add_remarks_company('company_remarks', $record);
        if ($re) {
            $this->session->set_flashdata('msg_alert', 'Successfully Changed Status');
            redirect('FormController/viewall_company_detail');
        } else {
            $this->session->set_flashdata('msg_alert', 'Something wrong');
            redirect('FormController/viewall_company_detail');
        }
        // echo "<pre>";
        // print_r($record);
        // exit;
        
    }
    public function get_remarks_company_data() {
        $company_id = $this->input->post('company_id');
        $record['all_remarks'] = $this->cm->get_company_remarks('company_remarks', 'cr_company_id', $company_id);
        $this->load->view('jobs/remarks_company_record', $record);
    }
    public function ajax_company_query_get() {
        $id = $this->input->post('company_id');
        $data['company'] = $this->cm->select_data("company_detail", "company_id", $id);
        $data['all_query'] = $this->cm->get_company_remarks('company_query', 'company_id', $id);
        $data['email_template'] = $this->cm->view_all('SendEmail_template');
        $this->load->view('jobs/ajax_query', $data);
    }
    public function get_emailsend_template_data_id_wise() {
        $SendEmail_template_id = $this->input->post('SendEmail_template_id');
        $data['email_template_record'] = $this->cm->get_email_temp_record('SendEmail_template', 'SendEmail_template_id', $SendEmail_template_id);
        echo json_encode(array('record' => $data));
    }
    public function studentConfirmationData() {
        $data = $this->input->post();
        $config['upload_path'] = FCPATH . 'images/placements_image/';
        $config['allowed_types'] = '*';
        $new_name = time() . $_FILES["upload_joining_letter_confirm"]['name'];
        $config['file_name'] = $new_name;
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('upload_joining_letter_confirm')) {
            $image_metadata = $this->upload->data();
        }
        // echo "<pre>";
        if (!empty($image_metadata['file_name'])) {
            $joining_letter = $image_metadata['file_name'];
            $data['joining_letter'] = $joining_letter;
        } else {
            $joining_letter = '';
            $data['joining_letter'] = $joining_letter;
        }
        $company_name_confirm = !empty($this->input->post('company_name_confirm')) ? $this->input->post('company_name_confirm') : '';
        $joining_date_confirm = !empty($this->input->post('joining_date_confirm')) ? $this->input->post('joining_date_confirm') : '';
        $starting_salary_confirm = !empty($this->input->post('starting_salary_confirm')) ? $this->input->post('starting_salary_confirm') : '';
        $bond_confirm = !empty($this->input->post('bond_confirm')) ? $this->input->post('bond_confirm') : '';
        $bond_year_month_confirm = !empty($this->input->post('bond_year_month_confirm')) ? $this->input->post('bond_year_month_confirm') : '';
        $application_id = $this->input->post('rem_application_status_confirm');
        $confirm_student = array('confirm_company_name' => $company_name_confirm, 'confirm_joining_date' => $joining_date_confirm, 'confirm_starting_salary_confirm' => $starting_salary_confirm, 'confirm_joining_letter' => $data['joining_letter'], 'confirm_bond' => $bond_confirm, 'confirm_bond_year_month' => $bond_year_month_confirm);
        $this->cm->application_job_record_update('application_job', 'application_number', $application_id, $confirm_student);
        date_default_timezone_set('Asia/Kolkata');
        $currentTime = date('Y-m-d h:i:sA');
        $reConfirm = $confirm_student_remarks = array('applications_id' => $application_id, 'remarks' => $this->input->post('student_remarks_confirm'), 're_date' => $currentTime, 'remarks_by' => $this->input->post('user_name_re'), 'labels' => $this->input->post('change_status_popup_confirm'));
        if ($reConfirm) {
            $re = $this->cm->application_job_remarks_record('job_remarks', $confirm_student_remarks);
            if ($re) {
                $data = array('application_status' => 2);
                $this->cm->update_data("application_job", $data, "application_number", $application_id);
                $this->session->set_flashdata('msg_alert', 'Successfully Changed Status With Remarks');
                redirect('FormController/jobApplication?status=confirm');
            } else {
                $this->session->set_flashdata('msg_alert', 'Something wrong');
                redirect('FormController/jobApplication?status=active');
            }
        } else {
            $this->session->set_flashdata('msg_alert', 'Something wrong');
            redirect('FormController/jobApplication?status=active');
        }
    }
    public function ins_query() {
        $data = $this->input->post();
        $all_record_email_template = $this->cm->get_company_record('SendEmail_template', $data['email_template_id'], 'SendEmail_template_id');
        $email_tamplate_name = $all_record_email_template->E_template_name;
        $all_record_company = $this->cm->get_company_record('company_detail', $data['company_id'], 'company_id');
        $email_record = $all_record_company->email_id;
        // print_r($email_record);
        // exit;
        $config = array('protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
        'smtp_host' => 'smtp.gmail.com', 'smtp_port' => 465, 'smtp_user' => 'placement.rnwmultimedia@gmail.com', 'smtp_pass' => 'rnw#tpo2020', 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
        'mailtype' => 'html', //plaintext 'text' mails or 'html'
        'smtp_timeout' => '4', //in seconds
        'charset' => 'UTF-8', 'wordwrap' => TRUE);
        // $this->load->config('email');
        $this->load->library('email');
        $this->email->initialize($config);
        $from = "placement.rnwmultimedia@gmail.com";
        $to = $email_record;
        $subject = $email_tamplate_name;
        $message = $data['message_text'];
        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
        $record = array('company_id' => $data['company_id'], 'email_template_id' => $data['email_template_id'], 'message_text' => $data['message_text'], 'date_time' => $data['date_time'], 'given_by' => $data['given_by']);
        $response = $this->cm->ins_query('company_query', $record);
        echo json_encode($response);
    }
    public function view_rapid_job_post() {
        if ($this->input->post('search')) {
            $record = $this->input->post();
            $data['rapid_job'] = $this->cm->get_filter_record('rapid_job_post', $record);
            $data['filter_record'] = $record;
        } else {
            $data['rapid_job'] = $this->cm->get_rapid_post_data('rapid_job_post');
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('jobs/view_rapid_post_job', $data);
    }
    public function add_record_jobPosition() {
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['submit']);
            if (!empty($this->input->post('job_position_id_r'))) {
                $id = $this->input->post('job_position_id_r');
                $record = array("job_position" => $this->input->post('job_position'), 'job_position_cat' => $this->input->post('job_position_cat'));
                $re = $this->cm->update_job_position_record('job_position_category', 'job_position_id', $id, $record);
                if ($re) {
                    $recp["all_record"] = array('status' => 2, "msg" => "Successfully Update Job Position");
                    echo json_encode($recp);
                }
            } else {
                unset($data['job_position_id_r']);
                $re = $this->cm->add_job_category_position('job_position_category', $data);
                if ($re) {
                    $recp["all_record"] = array('status' => 1, "msg" => "Successfully Insert Job Position");
                    echo json_encode($recp);
                }
            }
        }
    }
    public function update_job_positoin() {
        if (!empty($this->input->post('job_position_id'))) {
            $id = $this->input->post('job_position_id');
            $record['single_record'] = $this->cm->get_jobPositionRecord('job_position_category', 'job_position_id', $id);
            echo json_encode($record);
        }
    }
    public function update_job_category_position() {
        if (!empty($this->input->post('job_position_id'))) {
            $id = $this->input->post('job_position_id');
            $record['single_record'] = $this->cm->get_jobPositionRecord('job_main_category', 'job_category_id', $id);
            echo json_encode($record);
        }
    }
    public function delete_job() {
        if (!empty($this->input->post('job_post_id'))) {
            $id = $this->input->post('job_post_id');
            $re = $this->cm->delete_job_post('job_position_category', 'job_position_id', $id);
            if ($re) {
                $this->session->set_flashdata('msg', "Successfully Delete Job");
            }
        }
        redirect('FormController/job_position');
    }
    public function job_position() {
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $record['position'] = $this->cm->get_all_record('job_position_category');
        $record['jmCategory'] = $this->cm->get_all_record('job_main_category');
        $this->load->view('header', $update);
        $this->load->view('jobs/job_position', $record);
    }
    //main category
    public function job_main_category() {
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $record['position'] = $this->cm->get_all_record('job_main_category');
        $this->load->view('header', $update);
        $this->load->view('jobs/job_main_category', $record);
    }
    public function add_record_jobCategory() {
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['submit']);
            if (!empty($this->input->post('job_category_id_r'))) {
                $id = $this->input->post('job_category_id_r');
                $record = array("job_category_name" => $this->input->post('job_category_name'));
                $re = $this->cm->update_job_position_record('job_main_category', 'job_category_id', $id, $record);
                if ($re) {
                    $recp["all_record"] = array('status' => 2, "msg" => "Successfully Update Job Position");
                    echo json_encode($recp);
                }
            } else {
                unset($data['job_category_id_r']);
                $re = $this->cm->add_job_category_position('job_main_category', $data);
                if ($re) {
                    $recp["all_record"] = array('status' => 1, "msg" => "Successfully Insert Job Position");
                    echo json_encode($recp);
                }
            }
        }
    }
    public function delete_job_catgory() {
        if (!empty($this->input->post('job_post_id'))) {
            $id = $this->input->post('job_post_id');
            $re = $this->cm->delete_job_post('job_main_category', 'job_category_id', $id);
            if ($re) {
                $this->session->set_flashdata('msg', "Successfully Delete Job");
            }
        }
        redirect('FormController/job_main_category');
    }
    //job_subcategory
    public function update_job_subcategory() {
        if (!empty($this->input->post('job_position_id'))) {
            $id = $this->input->post('job_position_id');
            $record['single_record'] = $this->cm->get_jobPositionRecord('job_subcategory', 'job_subcategory_id', $id);
            echo json_encode($record);
        }
    }
    public function job_subcategory() {
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $record['position'] = $this->cm->get_all_record('job_subcategory');
        $record['jmCategory'] = $this->cm->get_all_record('job_main_category');
        $record['jmPosition'] = $this->cm->get_all_record('job_position_category');
        $this->load->view('header', $update);
        $this->load->view('jobs/job_subcategory', $record);
    }
    public function delete_job_subcategory() {
        if (!empty($this->input->post('job_post_id'))) {
            $id = $this->input->post('job_post_id');
            $re = $this->cm->delete_job_post('job_subcategory', 'job_subcategory_id', $id);
            if ($re) {
                $this->session->set_flashdata('msg', "Successfully Delete Job");
            }
        }
        redirect('FormController/job_subcategory');
    }
    public function add_record_subcategory() {
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['submit']);
            if (!empty($this->input->post('job_subcategory_id_r'))) {
                $id = $this->input->post('job_subcategory_id_r');
                $record = array("job_main_cat" => $this->input->post('job_main_cat'), 'job_position_cat' => $this->input->post('job_position_cat'), 'job_subcategory_name' => $this->input->post('job_subcategory_name'));
                $re = $this->cm->update_job_position_record('job_subcategory', 'job_subcategory_id', $id, $record);
                if ($re) {
                    $recp["all_record"] = array('status' => 2, "msg" => "Successfully Update Job Position");
                    echo json_encode($recp);
                }
            } else {
                unset($data['job_subcategory_id_r']);
                $re = $this->cm->add_job_category_position('job_subcategory', $data);
                if ($re) {
                    $recp["all_record"] = array('status' => 1, "msg" => "Successfully Insert Job Position");
                    echo json_encode($recp);
                }
            }
        }
    }
    public function get_position_main_sub() {
        $po_sub = $this->input->post('main_sub_cat');
        $record['jmPosition'] = $this->cm->get_poswise_sub('job_position_category', 'job_position_cat', $po_sub);
        $this->load->view('jobs/ajax_position_category', $record);
    }
    public function gradeStudentsQuestions() {
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('jobs/add_grade_questions');
    }
    public function add_questions_grade() {
        $record = array("first", "second", "third", "forth", "fifth", "sixth", "seventh");
        if (!empty($this->input->post('submit'))) {
            $j = 0;
            $data = $this->input->post();
            $count = count($data) - 1;
            $added_name = @$_SESSION['user_name'];
            for ($i = 0;$i < $count;$i = $i + 2) {
                $hea = $record[$j] . "_record";
                $perc = $record[$j] . "_per";
                $header = $data[$hea][0];
                $per = $data[$perc][0];
                $record_insert = count($data[$hea]) - 1;
                for ($k = 1;$k <= $record_insert;$k++) {
                    $store = array('header' => $header, 'question' => $data[$hea][$k], 'percentage' => $data[$perc][$k], 'added_by' => $added_name, 'total_weightege' => $per);
                    $this->cm->add_all_related_questions('grade_student_question', $store);
                }
                $j++;
            }
            $this->session->set_flashdata('msg_alert', 'successfully Questions added');
            $this->session->unset_userdata('type');
        }
        if (!empty($this->input->post('addTypes'))) {
            $type = $this->input->post('types');
            $this->session->set_userdata('type', $type);
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('jobs/add_grade_questions');
    }
    public function viewgradeStudentsQuestions($id = '') {
        if ($id != '') {
            $re = $this->cm->delete_record('grade_student_question', 'grade_student_id', $id);
            if ($re) {
                $this->session->set_flashdata('msg_alert', 'Delete Successfully');
                redirect('FormController/viewgradeStudentsQuestions');
            }
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $data['gradeStudent'] = $this->cm->view_all_by_student('grade_student_question');
        $rec = array();
        for ($i = 0;$i < sizeof($data['gradeStudent']);$i++) {
            $rec[] = $data['gradeStudent'][$i]->header;
        }
        $view_rec = array();
        if (count($rec) > 0) {
            for ($j = 0;$j < sizeof($rec);$j++) {
                $data['allgrade'][] = $this->cm->get_header_wise_record('grade_student_question', 'header', $rec[$j]);
            }
        } else {
            $data['allgrade'][] = array();
        }
        $this->load->view('header', $update);
        $this->load->view('jobs/view_grade_questions', @$data);
    }
    public function get_ajax_job_detail() {
        $jobpost_id = $this->input->post('jobpost_id');
        $data['record'] = $this->tm->get_job_detail_com('job_post', 'jobpost_id', $jobpost_id);
        $data['comment_reason'] = $this->tm->get_comment_reasons('job_deactive_remarks', 'de_jobpost_id', $jobpost_id);
        $this->load->view('jobs/ajax_job_details', $data);
    }
    public function ajax_company_detail_get() {
        $company_id = $this->input->post('company_id');
        $data['record'] = $this->tm->get_company_detail_ajax_wise('company_detail', 'company_id', $company_id);
        $this->load->view('jobs/ajax_company_details', $data);
    }
    // public function deletegradestudent($id)
    // {
    // 	$this->cm->delete_grade_record()
    // }
    public function get_ajax_job_show_history($id = '') {
        $job_id = $this->input->post('jobpost_id');
        $data['record'] = $this->cm->get_jobs_ajax_wise('job_post_history', 'j_job_id', $job_id);
        $this->load->view('jobs/ajax_jobs_detail_history', $data);
    }
    public function resume() {
        $id = $this->input->get('id');
        if ($this->input->get('action') == "edit") {
            $display['student_applied'] = $this->cm->select_data("student_applied_job", "applied_id", $id);
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('jobs/resume', $display);
    }
    public function ajax_notification() {
        $data = $this->cm->upd_status_notification();
        echo json_encode($data);
    }
    public function editjobApplication() {
        $allRecord = $this->input->post();
        $allRecord['gr_id'] = $allRecord['grid'];
        $allRecord['job_subcategory'] = $allRecord['position_subcategory'];
        $allRecord['name'] = $allRecord['fname'] . " " . $allRecord['mname'] . " " . $allRecord['lname'];
        $application_no = $allRecord['application_number'];
        unset($allRecord['grid']);
        unset($allRecord['position_subcategory']);
        unset($allRecord['fname']);
        unset($allRecord['mname']);
        unset($allRecord['lname']);
        unset($allRecord['application_number']);
        unset($allRecord['submit']);
        $qu = $this->cm->update_data('application_job', $allRecord, 'application_number', $application_no);
        if ($qu) {
            echo "1";
        } else {
            echo "0";
        }
    }
    public function lmsWhatsappTemplate() {
        $display['area_all'] = $this->cm->view_all("area");
        $display['jobtype_all'] = $this->cm->view_all("jobtype");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $display['template_data'] = $this->cm->view_all('whatsapp_template');
        $this->load->view('header', $update);
        $this->load->view('jobs/lms_whatsapp_template', $display);
    }
    public function add_template_whatsapp_data() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            date_default_timezone_set('Asia/Kolkata');
            $date = date('d-m-y h:i:s');
            $data['created_at'] = $date;
            unset($data['submit']);
            if ($this->input->post('whatsapp_template_id_dd')) {
                $id = $this->input->post('whatsapp_template_id_dd');
                unset($data['whatsapp_template_id_dd']);
                $query = $this->cm->edit_update_whatsapp_template('whatsapp_template', $data, 'whatsapp_template_id', $id);
                if ($query) {
                    $recp["all_record"] = array('status' => 2, "msg" => "Successfully Updated Template");
                    echo json_encode($recp); // echo "1";
                    
                } else {
                    $recp["all_record"] = array('status' => 3, "msg" => "Something Wrong");
                    echo json_encode($recp); // echo "2";
                    
                }
            } else {
                unset($data['whatsapp_template_id_dd']);
                $data['created_by'] = $_SESSION['user_name'];
                $query = $this->cm->insert_whatsapp_template('whatsapp_template', $data);
                if ($query) {
                    $recp["all_record"] = array('status' => 1, "msg" => "Successfully Insert Template");
                    echo json_encode($recp); // echo "1";
                    
                } else {
                    $recp["all_record"] = array('status' => 3, "msg" => "Something Wrong");
                    echo json_encode($recp); // echo "2";
                    
                }
            }
        }
    }
    public function delete_whatsapp_template() {
        $id = $this->input->post('whatsapp_template_id');
        $query = $this->cm->delete_whatsapp_template_data('whatsapp_template', 'whatsapp_template_id', $id);
        if ($query) {
            echo "1";
        } else {
            echo "2";
        }
    }
    public function update_whatsapp_template() {
        $id = $this->input->post('template_w_id');
        $record['single_record'] = $this->cm->get_template_whatsapp_data('whatsapp_template', 'whatsapp_template_id', $id);
        echo json_encode($record);
    }
    public function whatsapp_tem_me_get() {
        $templ_name = $this->input->post('templat_namm');
        $data = $this->cm->get_whatsapp_te_message('whatsapp_template', 'w_template_name', $templ_name);
        echo @$data->w_template_message;
    }
    public function send_mes_through_wht() {
        date_default_timezone_set('Asia/Kolkata');
        $currentTime = date('d-m-Y h:i:s A', time());
        $data = $this->input->post();
        $appli_no = $data['appl_number'];
        $hea = $data['template_name'];
        $rem = $data['template_message'];
        $remarks = $hea . "<br>" . $rem;
        $userName = $_SESSION['user_name'];
        $cu_date = $currentTime;
        $data = array('applications_id' => $appli_no, 'remarks' => $remarks, 're_date' => $cu_date, 'remarks_by' => $userName, 'labels' => "whatsapp Template");
        $da_re = $this->cm->insert_template_remarks_data('job_remarks', $data);
        if ($da_re) {
            $apData = $this->cm->fetch_all_records_application_no('application_job', 'application_number', $appli_no);
            $phone = $apData->number;
            echo json_encode(array('status' => 1, 'phone_no' => $phone));
        } else {
            echo json_encode(array('status' => 0));
        }
    }
    public function send_email_template() {
        $display['area_all'] = $this->cm->view_all("area");
        $display['jobtype_all'] = $this->cm->view_all("jobtype");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $display['template_data'] = $this->cm->view_all('SendEmail_template');
        $this->load->view('header', $update);
        $this->load->view('jobs/send_email_template', $display);
    }
    public function add_template_SendEmail_data() {
        if ($this->input->post('submit')) {
            $data = $this->input->post();
            date_default_timezone_set('Asia/Kolkata');
            $date = date('d-m-y h:i:s');
            $data['created_at'] = $date;
            unset($data['submit']);
            if ($this->input->post('SendEmail_template_id')) {
                $id = $this->input->post('SendEmail_template_id');
                unset($data['SendEmail_template_id']);
                $query = $this->cm->edit_update_SendEmail_template('SendEmail_template', $data, 'SendEmail_template_id', $id);
                if ($query) {
                    $recp["all_record"] = array('status' => 2, "msg" => "Successfully Updated Template");
                    echo json_encode($recp); // echo "1";
                    
                } else {
                    $recp["all_record"] = array('status' => 3, "msg" => "Something Wrong");
                    echo json_encode($recp); // echo "2";
                    
                }
            } else {
                unset($data['SendEmail_template_id']);
                $data['created_by'] = $_SESSION['user_name'];
                $query = $this->cm->insert_SendEmail_template('SendEmail_template', $data);
                if ($query) {
                    $recp["all_record"] = array('status' => 1, "msg" => "Successfully Insert Template");
                    echo json_encode($recp); // echo "1";
                    
                } else {
                    $recp["all_record"] = array('status' => 3, "msg" => "Something Wrong");
                    echo json_encode($recp); // echo "2";
                    
                }
            }
        }
    }
    public function update_email_template() {
        $id = $this->input->post('template_E_id');
        $record['single_record'] = $this->cm->get_template_email_data('SendEmail_template', 'SendEmail_template_id', $id);
        echo json_encode($record);
    }
    public function delete_email_template() {
        $id = $this->input->post('SendEmail_template_id');
        $query = $this->cm->delete_Email_template_data('SendEmail_template', 'SendEmail_template_id', $id);
        if ($query) {
            echo "1";
        } else {
            echo "2";
        }
    }
    public function excelJobApplication($status) {
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $table_columns = array("Grid","Student_Name","phone","Address","course","Branch","Photo","company name","Website","Recruiter Name","company Number","Company_address","joning_date","Confirm_date","starting Salary","Joning Letter","Bond","remark");
        $column = 0;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $student_data = $this->cm->fetch_data_student_status_wise_record('application_job', 'application_status', $status);
        $company_data = $this->cm->view_all('company_detail');
        $excel_row = 2;
        foreach ($student_data as $row){
            foreach($company_data as $cdata){
                if($cdata->company_name == $row->confirm_company_name){
                    $company_name = $cdata->company_name;
                    $website_lik  = $cdata->url;
                    $recruiter_name  = $cdata->employer_name;
                    $company_number  = $cdata->company_number;
                    $company_address  = $cdata->company_address;
                }
            }
            $company_name       = !empty($company_name)?$company_name :'';
            $website_lik        = !empty($website_lik)?$website_lik:'';
            $recruiter_name     = !empty($recruiter_name)?$recruiter_name:'';
            $company_number     = !empty($company_number)?$company_number:'';
            $company_address    = !empty($company_address)?$company_address:'';

            $photo = "https://demo.rnwmultimedia.com/placement/img/".$row->photo;
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->gr_id);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->number);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->course);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->branch_id);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $photo);
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $wesite_lik );
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $recruiter_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $company_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->confirm_joining_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->confirm_joining_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->confirm_starting_salary_confirm);
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $confirm_joining_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row->application_date);
            $excel_row++;
        }
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Employee Data.xls"');
        $object_writer->save('php://output');
    }
    public function excelcompanyDetail($status) {
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $table_columns = array("company name", "Company Number", "URL", "Employer Name", "Employer Designation", "Email Id", "Company Address", "status", "created At", "Date");
        $column = 0;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $company_data = $this->cm->fetch_data_company_status_wise_record('company_detail', 'status', $status);
        $excel_row = 2;
        foreach ($company_data as $row) {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->company_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->company_number);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->url);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->employer_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->employer_designation);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->email_id);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->company_address);
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->status);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->created_at);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->date);
            $excel_row++;
        }
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Company Detail.xls"');
        $object_writer->save('php://output');
    }


    public function editedWPF(){
        // print_r($this->input->post());
        $appli_number = $this->input->post('wpf_rem_application_status_confirm');

        $name = !empty($this->input->post('oe_name'))?$this->input->post('oe_name'):'';
        $wpnumber = !empty($this->input->post('oe_number'))?$this->input->post('oe_number'):'';
        $school_name = !empty($this->input->post('oe_school_name'))?$this->input->post('oe_school_name'):'';
        $oe_type = !empty($this->input->post('oe_type'))?$this->input->post('oe_type'):'';
        $oe_company_name = !empty($this->input->post('oe_company_name'))?$this->input->post('oe_company_name'):'';
        $oe_designation = !empty($this->input->post('oe_designation'))?$this->input->post('oe_designation'):'';
        $oe_no_of_employee = !empty($this->input->post('oe_no_of_employee'))?$this->input->post('oe_no_of_employee'):'';
        $oe_salary = !empty($this->input->post('oe_name'))?$this->input->post('oe_salary'):'';
        $category_data = !empty($this->input->post('category_data'))?$this->input->post('category_data'):'';
        if($category_data == ''){
            $category_data = !empty($this->input->post('oe_category_other'))?$this->input->post('oe_category_other'):'';
        }
        // $name = !empty($this->input->post('oe_name'))?$this->input->post('oe_name'):'';
        $record = array('name'=>$name,'number'=>$wpnumber,'school_name'=>$school_name,'owner_employee_type'=>$oe_type,'confirm_company_name'=>$oe_company_name,'designation'=>$oe_designation,'no_of_employee'=>$oe_no_of_employee,'confirm_starting_salary_confirm'=>$oe_salary,'application_status'=>'2','position_for_apply'=>$category_data);
        $this->cm->update_wpf_record('application_job','application_number',$appli_number, $record);
        $rmks = $this->input->post('oe_remarks');
        date_default_timezone_set('Asia/Kolkata');
        $currentTime = date('Y-m-d h:i:sA');
        $userName = $this->input->post('user_name_re');
        $remarks_data = array('applications_id'=>$appli_number,'remarks'=>$rmks,'re_date'=>$currentTime,'remarks_by'=>$userName,'labels'=>'confirm');

        $data_remarks_add = $this->cm->add_job_category_position('job_remarks',$remarks_data);
        if($data_remarks_add){
            echo "1";
        }
        else{
            echo "0";
        }
    }
}
?>