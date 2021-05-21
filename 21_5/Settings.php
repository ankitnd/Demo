<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Settings extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->model("Dbcommon", "cm");
        $this->load->model("Quickadmissionprocess", "admi");
        $this->load->model("Task", "tm");
        $this->load->helper('loggs');
        $this->load->library('session');
        //$this->load->helper('urldata');
        date_default_timezone_set('Asia/Kolkata');
    }
    public function admin()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("user", "user_id", $id);
                if ($re) {
                    redirect('settings/admin');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_user'] = $this->cm->select_data("user", "user_id", $id);
            }
            if ($this->input->get('action') == "status") {
                if ($this->input->get('status') == 0) {
                    $st['user_status'] = 1;
                } else {
                    $st['user_status'] = 0;
                }
                // echo $st['user_status'];
                // die;
                $re = $this->cm->update_data("user", $st, "user_id", $id);
                if ($re) {
                    redirect('settings/admin');
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            // echo "<pre>";
            // print_r($_POST);
            // die;
            $ins_data['logtype'] = $data['logtype'];
            $ins_data['state_id'] = $data['state_id'];
            $ins_data['city_id'] = $data['city_id'];
            $ins_data['name'] = $data['name'];
            $ins_data['email'] = $data['email'];
            $ins_data['password'] = $data['password'];
            $ins_data['company_name'] = $data['company_name'];
            $ins_data['company_code'] = "RNW" . rand(5000, 9000);
            $ins_data['f_permission'] = implode(",", $data['fp']);
            $ins_data['m_permission'] = implode(",", $data['m_all']);
            $ins_data['m_parent_id'] = $_SESSION['user_id'];
            // $perm = $this->cm->view_all("permission");
            // $permission="";
            // foreach($perm as $p)
            // {
            //     $p_name = preg_replace('/\s+/', '', $p->permission_name);
            //     if(empty($permission))
            //     {
            //          $permission = $this->input->post($p_name);
            //     }
            //     else
            //     {
            //        $permission =$permission.",".$this->input->post($p_name);
            //     }
            // }
            // $ins_data['permission'] = $permission;
            $l_module = $this->cm->view_all('l_module');
            // print_r($l_module);
            // print_r($data1);
            // $f_all = array();
            // foreach ($l_module as $key => $value) {
            // 	foreach ($data as $k => $v) {
            // 	 $k = str_replace("_", " ", $k);
            // 	if($value->name == $k)
            // 	{
            // 		$f_all[] = $v;
            // 	}
            // 	unset($data[$k]);
            // }
            // }
            $ins_data['permission'] = implode(",", $_POST['f_all']);
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("user", $ins_data, "user_id", $id);
            } else {
                $email = $this->input->post('email');
                $pwd = $this->input->post('password');
                $config = array(
                    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
                    'smtp_host' => 'smtp.gmail.com', 'smtp_port' => 465, 'smtp_user' => 'jalpachudasama1998@gmail.com', 'smtp_pass' => '9879825912', 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
                    'mailtype' => 'text', //plaintext 'text' mails or 'html'
                    'smtp_timeout' => '4', //in seconds
                    'charset' => 'UTF-8', 'wordwrap' => TRUE
                );
                // $this->load->config('email');
                $this->load->library('email');
                $this->email->initialize($config);
                $from = "jalpachudasama1998@gmail.com";
                $to = $email;
                $subject = "RED&WHITE GROUP OF INSTITUTE CREATE A NEW ADMIN";
                $message = "account id:- $email\n Password:- $pwd";
                $this->email->set_newline("\r\n");
                $this->email->from($from);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->send();
                // if () {
                //     echo 'Your Email has successfully been sent.';
                // } else {
                //     show_error($this->email->print_debugger());
                // }
                $re = $this->cm->seat_insert_data("user", $ins_data);
                //$jall['admin_id'] = $ss;
                //$re = $this->cm->update_data("user",$jall,"user_id",$ss);

            }
            if ($re) {
                redirect('settings/admin');
            }
        }
        if (!empty($this->input->get('logtype_action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('logtype_action') == "delete") {
                if ($this->input->get('status') == 0) {
                    $st['status'] = 1;
                } else {
                    $st['status'] = 0;
                }
                $re = $this->cm->update_data("logtype", $st, "logtype_id", $id);
                if ($re) {
                    redirect('settings/admin');
                }
            } else if ($this->input->get('logtype_action') == "edit") {
                $display['select_logtype'] = $this->cm->select_data("logtype", "logtype_id", $id);
            }
        }
        if (!empty($this->input->post('logtype_submit'))) {
            $data1 = $this->input->post();
            unset($data1['update_id']);
            unset($data1['logtype_submit']);
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("logtype", $data1, "logtype_id", $id);
            } else {
                $re = $this->cm->insert_data("logtype", $data1);
            }
            if ($re) {
                redirect('settings/admin');
            }
        }
        $display['department_all'] = $this->cm->view_all("department");
        $display['state_all'] = $this->cm->view_all("state");
        $display['city_all'] = $this->cm->view_all("cities");
        $display['branch_all'] = $this->cm->view_all("branch");
        $display['logtype_all'] = $this->cm->view_all("logtype");
        $display['permission_all'] = $this->cm->view_all("permission");
        $display['user_all'] = $this->cm->view_all_user("user");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('admin', $display);
    }
    public function branch()
    {
        logdata("branch page open");
        if ($_SESSION['logtype'] != "Super Admin" && $_SESSION['logtype'] != "Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("branch", "branch_id", $id);
                if ($re) {
                    logdata('branch id= ' . $id . " Deleted");
                    redirect('settings/branch');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_branch'] = $this->cm->select_data("branch", "branch_id", $id);
            }
            if ($this->input->get('action') == "status") {
                if ($this->input->get('status') == 0) {
                    $st['branch_status'] = 1;
                } else {
                    $st['branch_status'] = 0;
                }
                $re = $this->cm->update_data("branch", $st, "branch_id", $id);
                if ($re) {
                    logdata('branch id= ' . $id . " Status update " . $st['branch_status']);
                    redirect('settings/branch');
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            //$all = $this->cm->select_data("user","user_id",$data['admin_id']);
            unset($data['update_id']);
            unset($data['submit']);
            @$ins_data['logtype'] = "Branch";
            @$ins_data['session'] = implode(",", @$data['no_year']);
            if ($_SESSION['logtype'] == "Super Admin") {
                @$ins_data['admin_id'] = $data['admin_id'];
                @$ins_data['country_id'] = $data['country_id'];
                @$ins_data['state_id'] = $data['state_id'];
                @$ins_data['city_id'] = $data['city_id'];
            } else {
                @$ins_data['admin_id'] = $_SESSION['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                // print_r($_SESSION['admin_id']);
                // print_r($all);
                // die;
                @$ins_data['country_id'] = $data['country_id'];
                @$ins_data['state_id'] = $all->state_id;
                @$ins_data['city_id'] = $all->city_id;
            }
            @$ins_data['branch_name'] = $data['branch_name'];
            @$ins_data['branch_code'] = $data['branch_code'];
            @$ins_data['branch_title'] = $data['branch_title'];
            @$ins_data['email'] = $data['email'];
            @$ins_data['phone_landline_no'] = $data['phone_landline_no'];
            @$ins_data['mobile_one'] = $data['mobile_one'];
            @$ins_data['mobile_two'] = $data['mobile_two'];
            @$ins_data['website_name'] = $data['website_name'];
            @$ins_data['pan_no'] = $data['pan_no'];
            @$ins_data['CIN'] = $data['CIN'];
            @$ins_data['gst_no'] = $data['gst_no'];
            @$ins_data['bank_name'] = $data['bank_name'];
            @$ins_data['account_name'] = $data['account_name'];
            @$ins_data['account_no'] = $data['account_no'];
            @$ins_data['ifsc'] = $data['ifsc'];
            @$ins_data['account_type'] = $data['account_type'];
            @$ins_data['branch_address'] = $data['branch_address'];
            if ($_FILES['branch_logo']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/branchlogo/";
                $new_name = time() . $_FILES["branch_logo"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('branch_logo')) {
                    $imagedata = $this->upload->data();
                    @$ins_data['branch_logo'] = $imagedata['file_name'];
                } else {
                    $display['msgp'] = "image not uploaded";
                }
            }
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $bbb = $this->cm->select_data("branch", "branch_id", $id);
                if ($_FILES['branch_logo']['name'] != "") {
                    $filess = FCPATH . "dist/branchlogo/" . $bbb->branch_logo;
                    @unlink($filess);
                }
                $re = $this->cm->update_data("branch", $ins_data, "branch_id", $id);
                logdata('branch id=' . $id . ' ' . $ins_data['branch_name'] . " update");
            } else {
                if ($_SESSION['logtype'] == "Super Admin") {
                    $re = $this->cm->branch_insert("branch", $ins_data);
                    logdata($ins_data['branch_name'] . " Branch add");
                } else {
                    $re = $this->cm->seat_insert_data("branch", $ins_data);
                    // print_r($_SESSION['admin_id']);
                    // die;
                    $uall = $this->cm->select_data("user", "user_id", $_SESSION['admin_id']);
                    if (!empty($uall->branch_ids)) {
                        $bid = $uall->branch_ids;
                        $udata['branch_ids'] = $bid . "," . $re;
                    } else {
                        $udata['branch_ids'] = $re;
                    }
                    $this->cm->update_data("user", $udata, "user_id", $_SESSION['admin_id']);
                }
            }
            if ($re) {
                redirect('settings/branch');
            }
        }
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
        $this->load->view('header', $update);
        $this->load->view('branch', $display);
    }
    public function Admission_receipt_permission()
    {
        $data = $this->input->post();
        date_default_timezone_set("Asia/Calcutta");
        $created_date = date('d-M-Y h:i A');
        $addby = $_SESSION['user_name'];
        for ($i = 0; $i < sizeof($data['branch_id']); $i++) {
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
    public function Admission_upd_receipt_permission()
    {
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
    public function get_record_receipt()
    {
        $id = $this->input->post('branch_id');
        $data['single_record'] = $this->cm->get_receipt_permission_record('receipt_permission', 'branch_id', $id);
        echo json_encode(array('record' => $data));
    }
    public function department()
    {
        if ($_SESSION['logtype'] != "Super Admin" && $_SESSION['logtype'] != "Admin") {
            redirect('welcome');
        }
        logdata("Department page open");
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("department", "department_id", $id);
                if ($re) {
                    logdata("department id= " . $id . " Deleted");
                    redirect('settings/department');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_department'] = $this->cm->select_data("department", "department_id", $id);
            }
            if ($this->input->get('action') == "status") {
                if ($this->input->get('status') == 0) {
                    $st['depart_status'] = 1;
                } else {
                    $st['depart_status'] = 0;
                }
                $re = $this->cm->update_data("department", $st, "department_id", $id);
                if ($re) {
                    logdata("department id= " . $id . " Status update " . $st['depart_status']);
                    redirect('settings/department');
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $all = $this->cm->select_data("user", "user_id", $data['admin_id']);
            unset($data['update_id']);
            unset($data['submit']);
            if (empty($data['b_ids'])) {
                $branch_id = $data['b_ids'] = "";
            } else {
                $branch_id = implode(",", @$data['b_ids']);
            }
            if ($_SESSION['logtype'] == "Super Admin") {
                $ins_data['admin_id'] = $data['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $data['admin_id']);
                $ins_data['state_id'] = $all->state_id;
                $ins_data['city_id'] = $all->city_id;
            } else {
                $ins_data['admin_id'] = $_SESSION['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                // print_r($all);
                // die;
                $ins_data['state_id'] = $all->state_id;
                $ins_data['city_id'] = $all->city_id;
            }
            $ins_data['branch_id'] = $branch_id;
            $ins_data['department_name'] = $data['department_name'];
            if (empty($result) && empty($this->input->post('update_id'))) {
                if ($_SESSION['logtype'] == "Super Admin") {
                    $re = $this->cm->insert_data("department", $ins_data);
                    logdata("department name= " . $ins_data['department_name'] . " Insert");
                } else {
                    $re = $this->cm->seat_insert_data("department", $ins_data);
                    // print_r($_SESSION['admin_id']);
                    // die;
                    $uall = $this->cm->select_data("user", "user_id", $_SESSION['admin_id']);
                    if (!empty($uall->department_ids)) {
                        $did = $uall->department_ids;
                        $udata['department_ids'] = $did . "," . $re;
                    } else {
                        $udata['department_ids'] = $re;
                    }
                    $this->cm->update_data("user", $udata, "user_id", $_SESSION['admin_id']);
                }
            } else {
                $display['msg_alert'] = $ins_data['department_name'] . "  Department Name Already Exist";
            }
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("department", $ins_data, "department_id", $id);
                logdata("department id= " . $id . "department name= " . $ins_data['department_name'] . " Update");
            } else {
                $re = $this->cm->select_data("department", $ins_data, "department_id", $id);
            }
            if (@$re) {
                redirect('settings/department');
            }
        }
        if ($_SESSION['logtype'] == "Admin") {
            $display['department_all'] = $this->cm->view_all_data("department");
            $display['branch_all'] = $this->cm->view_all_data("branch");
            $display['state_all'] = $this->cm->view_all("state");
            $display['city_all'] = $this->cm->view_all("cities");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        } else {
            $display['department_all'] = $this->cm->view_all("department");
            $display['branch_all'] = $this->cm->view_all("branch");
            $display['state_all'] = $this->cm->view_all("state");
            $display['city_all'] = $this->cm->view_all("cities");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('department', $display);
    }
    function select_department()
    {
        $data = $this->input->post('department_id');
        $result['selectdata'] = $data;
        echo json_encode($result);
    }
    public function subdepartment()
    {
        if ($_SESSION['logtype'] != "Super Admin" && $_SESSION['logtype'] != "Admin") {
            redirect('welcome');
        }
        logdata("subdepartment page open");
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("subdepartment", "subdepartment_id", $id);
                if ($re) {
                    logdata('SubDepartment id= ' . $id . " Deleted");
                    redirect('settings/subdepartment');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_subdepartment'] = $this->cm->select_data("subdepartment", "subdepartment_id", $id);
            }
            if ($this->input->get('action') == "status") {
                if ($this->input->get('status') == 0) {
                    $st['depart_status'] = 1;
                } else {
                    $st['depart_status'] = 0;
                }
                $re = $this->cm->update_data("subdepartment", $st, "subdepartment_id", $id);
                if ($re) {
                    logdata('subdepartment_id id= ' . $id . " Status " . $st['depart_status'] . " updated");
                    redirect('settings/subdepartment');
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $all = $this->cm->select_data("user", "user_id", $data['admin_id']);
            unset($data['update_id']);
            unset($data['submit']);
            if ($_SESSION['logtype'] == "Super Admin") {
                $ins_data['admin_id'] = $data['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $data['admin_id']);
                $ins_data['state_id'] = $all->state_id;
                $ins_data['city_id'] = $all->city_id;
            } else {
                $ins_data['admin_id'] = $_SESSION['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                $ins_data['state_id'] = $all->state_id;
                $ins_data['city_id'] = $all->city_id;
            }
            if (empty($data['b_ids'])) {
                $branch_id = $data['b_ids'] = "";
            } else {
                $branch_id = implode(",", @$data['b_ids']);
            }
            $ins_data['branch_id'] = $branch_id;
            $ins_data['department_ids'] = $data['department_ids'];
            $ins_data['subdepartment_name'] = $data['subdepartment_name'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("subdepartment", $ins_data, "subdepartment_id", $id);
                logdata("subdepartment_id= " . $id . " subdepartment_name=" . $ins_data['subdepartment_name'] . " Update");
            } else {
                $result = $this->cm->select_data("subdepartment", $ins_data, "subdepartment_id", $id);
                if (empty($result)) {
                    if ($_SESSION['logtype'] == "Super Admin") {
                        $re = $this->cm->insert_data("subdepartment", $ins_data);
                    } else {
                        $re = $this->cm->seat_insert_data("subdepartment", $ins_data);
                        // print_r($_SESSION['admin_id']);
                        // die;
                        $uall = $this->cm->select_data("user", "user_id", $_SESSION['admin_id']);
                        if (!empty($uall->subdepartment_ids)) {
                            $subdid = $uall->subdepartment_ids;
                            $udata['subdepartment_ids'] = $subdid . "," . $re;
                        } else {
                            $udata['subdepartment_ids'] = $re;
                        }
                        $this->cm->update_data("user", $udata, "user_id", $_SESSION['admin_id']);
                    }
                    logdata("subdepartment_id= " . $id . " subdepartment_name=" . $ins_data['subdepartment_name'] . " add");
                } else {
                    $display['msg_alert'] = $ins_data['subdepartment_name'] . "  Department Name Already Exist";
                }
            }
            if (@$re) {
                redirect('settings/subdepartment');
            }
        }
        if ($_SESSION['logtype'] == "Admin") {
            $display['department_all'] = $this->cm->view_all_data("department");
            $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
            $display['branch_all'] = $this->cm->view_all_data("branch");
            $display['state_all'] = $this->cm->view_all("state");
            $display['city_all'] = $this->cm->view_all("cities");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        } else {
            $display['department_all'] = $this->cm->view_all("department");
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['state_all'] = $this->cm->view_all("state");
            $display['city_all'] = $this->cm->view_all("cities");
            $display['branch_all'] = $this->cm->view_all("branch");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('subdepartment', $display);
    }
    public function extradepartment()
    {
        if ($_SESSION['logtype'] != "Super Admin" && $_SESSION['logtype'] != "Admin") {
            redirect('welcome');
        }
        logdata("ExtraDepartment page open");
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("extradepartment", "extra_id", $id);
                if ($re) {
                    logdata('ExtraDepartment id= ' . $id . " Deleted");
                    redirect('settings/extradepartment');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_subdepartment'] = $this->cm->select_data("extradepartment", "extra_id", $id);
            }
            if ($this->input->get('action') == "status") {
                if ($this->input->get('status') == 0) {
                    $st['depart_status'] = 1;
                } else {
                    $st['depart_status'] = 0;
                }
                $re = $this->cm->update_data("extradepartment", $st, "extra_id", $id);
                if ($re) {
                    logdata('ExtraDepartment id= ' . $id . " Status " . $st['depart_status'] . " updated");
                    redirect('settings/extradepartment');
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            // echo "<pre>";
            // print_r($data);
            // die;
            $all = $this->cm->select_data("user", "user_id", $data['admin_id']);
            unset($data['update_id']);
            unset($data['submit']);
            if (!is_array($data['extradepartment_name'])) {
                $data['extradepartment_name'] = explode(",", $data['extradepartment_name']);
            }
            foreach ($data['extradepartment_name'] as $key => $value) {
                if ($_SESSION['logtype'] == "Super Admin") {
                    $ins_data['admin_id'] = $data['admin_id'];
                    $all = $this->cm->select_data("user", "admin_id", $data['admin_id']);
                    $ins_data['state_id'] = $all->state_id;
                    $ins_data['city_id'] = $all->city_id;
                } else {
                    $ins_data['admin_id'] = $_SESSION['admin_id'];
                    $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                    // print_r($all);
                    // die;
                    $ins_data['state_id'] = $all->state_id;
                    $ins_data['city_id'] = $all->city_id;
                }
                $ins_data['branch_id'] = $data['branch_id'];
                $ins_data['department_ids'] = $data['department_ids'];
                $ins_data['subdepartment_ids'] = $data['subdepartment_ids'];
                $ins_data['extradepartment_name'] = $value;
                if (!empty($this->input->post('update_id'))) {
                    $id = $this->input->post('update_id');
                    $re = $this->cm->update_data("extradepartment", $ins_data, "extra_id", $id);
                    logdata("extra_id= " . $id . " extradepartment_name=" . $ins_data['extradepartment_name'] . " Update");
                } else {
                    $result = $this->cm->select_data("extradepartment", $ins_data, "extra_id", $id);
                    if (empty($result)) {
                        if ($_SESSION['logtype'] == "Super Admin") {
                            $re = $this->cm->insert_data("extradepartment", $ins_data);
                        } else {
                            $re = $this->cm->seat_insert_data("extradepartment", $ins_data);
                            // print_r($_SESSION['admin_id']);
                            // die;
                            $uall = $this->cm->select_data("user", "user_id", $_SESSION['admin_id']);
                            if (!empty($uall->subdepartment_ids)) {
                                $subdid = $uall->subdepartment_ids;
                                $udata['subdepartment_ids'] = $subdid . "," . $re;
                            } else {
                                $udata['subdepartment_ids'] = $re;
                            }
                            $this->cm->update_data("user", $udata, "user_id", $_SESSION['admin_id']);
                        }
                        logdata("extra_id= " . $id . " extradepartment_name=" . $ins_data['extradepartment_name'] . " add");
                    } else {
                        $display['msg_alert'] = $ins_data['subdepartment_name'] . "  Department Name Already Exist";
                    }
                }
            }
            if (@$re) {
                redirect('settings/extradepartment');
            }
        }
        if ($_SESSION['logtype'] == "Admin") {
            $display['department_all'] = $this->cm->view_all_data("department");
            $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
            $display['extradepartment_all'] = $this->tm->view_all("extradepartment");
            $display['branch_all'] = $this->cm->view_all_data("branch");
            $display['state_all'] = $this->cm->view_all("state");
            $display['city_all'] = $this->cm->view_all("cities");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        } else {
            $display['department_all'] = $this->cm->view_all("department");
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['extradepartment_all'] = $this->tm->view_all("extradepartment");
            $display['state_all'] = $this->cm->view_all("state");
            $display['city_all'] = $this->cm->view_all("cities");
            $display['branch_all'] = $this->cm->view_all("branch");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        }
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('extra_department', $display);
    }
    function select_Subdepartment()
    {
        $data = $this->input->post('subdepartment_id');
        $result['selectdata'] = $data;
        echo json_encode($result);
    }
    public function course()
    {
        @$user_permission = explode(",", $_SESSION['user_permission']);
        logdata("Course page open");
        if (in_array("Single Course", $user_permission) || $_SESSION['logtype'] == "Super Admin" || $_SESSION['logtype'] == "Admin") {
            if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
                $id = $this->input->get('id');
                if ($this->input->get('action') == "delete") {
                    if ($this->input->get('status') == 0) {
                        $st['status'] = 1;
                    } else {
                        $st['status'] = 0;
                    }
                    $re = $this->cm->update_data("course", $st, "course_id", $id);
                    if ($re) {
                        logdata('course id= ' . $id . " Status " . $st['status'] . " updated");
                        redirect('settings/course');
                    }
                } else if ($this->input->get('action') == "edit") {
                    $display['select_course'] = $this->cm->select_data("course", "course_id", $id);
                }
            }
            if (!empty($this->input->post('submit'))) {
                $data = $this->input->post();
                unset($data['update_id']);
                unset($data['submit']);
                @$ins_data['session'] = implode(",", @$data['no_year']);
                $ins_data['admin_id'] = $_SESSION['admin_id'];
                @$ins_data['branch_id'] = implode(",", $data['b_ids']);
                if (empty($ins_data['branch_id'])) {
                    $ins_data['branch_id'] = "";
                }
                @$ins_data['department_id'] = implode(",", $data['depart_ids']);
                if (empty($ins_data['department_id'])) {
                    $ins_data['department_id'] = "";
                }
                @$ins_data['subdepart_ids'] = implode(",", $data['subdepart_ids']);
                if (empty($ins_data['subdepart_ids'])) {
                    $ins_data['subdepart_ids'] = "";
                }
                $ins_data['course_name'] = $data['course_name'];
                $ins_data['course_code'] = $data['course_code'];
                //$ins_data['course_detail'] = $data['course_detail'];
                $ins_data['course_fees'] = $data['course_fees'];
                $ins_data['installment'] = $data['installment'];
                $ins_data['course_duration'] = $data['course_duration'];
                $ins_data['jobg'] = $data['jobg'];
                if ($_FILES['csigning_sheet']['name'] != "") {
                    $config['allowed_types'] = "*";
                    $config['upload_path'] = FCPATH . "dist/signsheet/";
                    $new_name = time() . $_FILES["csigning_sheet"]['name'];
                    $config['file_name'] = $new_name;
                    $this->load->library('upload');
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('csigning_sheet')) {
                        $imagedata = $this->upload->data();
                        $ins_data['csigning_sheet'] = $imagedata['file_name'];
                    } else {
                        $display['msgp'] = "image not uploaded";
                    }
                }
                if (!empty($this->input->post('update_id'))) {
                    $id = $this->input->post('update_id');
                    $ccrr = $this->cm->select_data("course", "course_id", $id);
                    if ($_FILES['csigning_sheet']['name'] != "") {
                        $filess = FCPATH . "dist/signsheet/" . $ccrr->csigning_sheet;
                        @unlink($filess);
                    }
                    $re = $this->cm->update_data("course", $ins_data, "course_id", $id);
                    logdata("course id= " . $id . " course name=" . $ins_data['course_name'] . " Update");
                } else {
                    $result = $this->cm->select_data("course", "course_name", $ins_data['course_name']);
                    $result = $this->cm->select_data("course", "course_detail", $ins_data['course_detail']);
                    if (empty($result)) {
                        // echo "<pre>";
                        // print_r($ins_data);
                        // die();
                        $re = $this->cm->insert_data("course", $ins_data);
                        logdata("course name=" . $ins_data['course_name'] . " add");
                    } else {
                        $display['msg_alert'] = $ins_data['course_name'] . "  Course Name Already Exist";
                        $display['msg_alert'] = $ins_data['course_detail'] . "  Course Detail Already Exist";
                    }
                }
                if (@$re) {
                    redirect('settings/course');
                }
            }
            // if($_SESSION['logtype'] == "Super Admin")
            // {
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['department_all'] = $this->cm->view_all("department");
            $display['course_all'] = $this->cm->view_all("course");
            $display['branch_all'] = $this->cm->view_all("branch");
            $display['user_all'] = $this->cm->Role_all_admin("user");
            // }
            // else
            // {
            // $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
            // $display['department_all'] = $this->cm->view_all_data("department");
            // $display['course_all'] = $this->cm->view_all_data("course");
            // $display['branch_all'] = $this->cm->view_all_data("branch");
            // $display['user_all'] = $this->cm->Role_all_admin("user");
            // }
            $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['upd_see'] = $this->cm->check_update("demo");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $this->load->view('header', $update);
            $this->load->view('course', $display);
        }
    }
    ///custtom changes single course
    public function delete_fun($course_id)
    {
        $item = $this->cm->delete_item($course_id);
        logdata('course id= ' . $course_id . " Deleted");
        redirect(base_url('Settings/course'));
    }
    public function course_package()
    {
        @$user_permission = explode(",", $_SESSION['user_permission']);
        logdata("Package page open");
        if (in_array("Course Package", $user_permission) || $_SESSION['logtype'] == "Super Admin" || $_SESSION['logtype'] == "Admin") {
            if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
                $id = $this->input->get('id');
                if ($this->input->get('action') == "delete") {
                    if ($this->input->get('status') == 0) {
                        $st['status'] = 1;
                    } else {
                        $st['status'] = 0;
                    }
                    $re = $this->cm->update_data("package", $st, "package_id", $id);
                    if ($re) {
                        logdata('package id= ' . $id . " Status " . $st['status'] . " updated");
                        redirect('settings/course_package');
                    }
                } else if ($this->input->get('action') == "edit") {
                    $display['select_package'] = $this->cm->select_data("package", "package_id", $id);
                }
            }
            if (!empty($this->input->post('submit'))) {
                $data = $this->input->post();
                unset($data['update_id']);
                unset($data['submit']);
                @$ins_data['session'] = implode(",", @$data['no_year']);
                $ins_data['admin_id'] = $_SESSION['admin_id'];
                @$ins_data['branch_id'] = implode(",", $data['b_ids']);
                if (empty($ins_data['branch_id'])) {
                    $ins_data['branch_id'] = "";
                }
                @$ins_data['department_id'] = implode(",", $data['depart_ids']);
                if (empty($ins_data['department_id'])) {
                    $ins_data['department_id'] = "";
                }
                @$ins_data['subdepart_ids'] = implode(",", $data['subdepart_ids']);
                if (empty($ins_data['subdepart_ids'])) {
                    $ins_data['subdepart_ids'] = "";
                }
                $ins_data['package_name'] = $data['package_name'];
                // $ins_data['package_detail'] = $data['package_detail'];
                $ins_data['package_code'] = $data['package_code'];
                $ins_data['package_fees'] = $data['package_fees'];
                $ins_data['installment'] = $data['installment'];
                $ins_data['package_duration'] = $data['package_duration'];
                //$ins_data['jobg'] = $data['jobg'];
                if ($_FILES['psigning_sheet']['name'] != "") {
                    $config['allowed_types'] = "*";
                    $config['upload_path'] = FCPATH . "dist/signsheet/";
                    $new_name = time() . $_FILES["psigning_sheet"]['name'];
                    $config['file_name'] = $new_name;
                    $this->load->library('upload');
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('psigning_sheet')) {
                        $imagedata = $this->upload->data();
                        $ins_data['psigning_sheet'] = $imagedata['file_name'];
                    } else {
                        $display['msgp'] = "image not uploaded";
                    }
                }
                if (!empty($this->input->post('update_id'))) {
                    $id = $this->input->post('update_id');
                    $ccrr = $this->cm->select_data("package", "package_id", $id);
                    if ($_FILES['psigning_sheet']['name'] != "") {
                        $filess = FCPATH . "dist/signsheet/" . $ccrr->psigning_sheet;
                        @unlink($filess);
                    }
                    $re = $this->cm->update_data("package", $ins_data, "package_id", $id);
                    logdata("package id= " . $id . " package name=" . $ins_data['package_name'] . " Update");
                } else {
                    // echo "<pre>";
                    //       		    print_r($ins_data);
                    //       		    die();
                    $re = $this->cm->insert_data("package", $ins_data);
                    logdata("package name=" . $ins_data['package_name'] . " add");
                }
                if ($re) {
                    redirect('settings/course_package');
                }
            }
            // if($_SESSION['logtype'] == "Super Admin")
            // {
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['department_all'] = $this->cm->view_all("department");
            $display['course_all'] = $this->cm->view_all_course("course");
            $display['branch_all'] = $this->cm->view_all("branch");
            $display['user_all'] = $this->cm->Role_all_admin("user");
            $display['package_all'] = $this->cm->view_all("package");
            //$display['package_all'] = $this->cm->view_all_package("package");  old method chnage this
            // }
            // else
            // {
            // $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
            // $display['department_all'] = $this->cm->view_all_data("department");
            // $display['course_all'] = $this->cm->view_all_data("course");
            // $display['branch_all'] = $this->cm->view_all_data("branch");
            // $display['user_all'] = $this->cm->Role_all_admin("user");
            // $display['package_all'] = $this->cm->view_all_data("package");
            // }
            $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['upd_see'] = $this->cm->check_update("demo");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $this->load->view('header', $update);
            $this->load->view('course_package', $display);
        }
    }
    ///////////////////-------custom changes pakagecourse--------////////////////////
    public function pakageremove($package_id)
    {
        $item = $this->cm->removepakages($package_id);
        logdata('Package id= ' . $package_id . " Deleted");
        redirect(base_url('Settings/course_package'));
    }
    public function staff()
    {
        logdata("Faculty page open");
        @$user_permission = explode(",", $_SESSION['user_permission']);
        if (in_array("Faculty=1", $user_permission) || $_SESSION['logtype'] == "Super Admin" || $_SESSION['logtype'] == "Admin") {
            if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
                $id = $this->input->get('id');
                if ($this->input->get('action') == "delete") {
                    $re = $this->cm->delete_data("faculty", "faculty_id", $id);
                    if ($re) {
                        logdata('faculty id= ' . $id . " Deleted");
                        redirect('settings/staff');
                    }
                } else if ($this->input->get('action') == "edit") {
                    $display['select_faculty'] = $this->cm->select_data("faculty", "faculty_id", $id);
                    $b_all = $this->cm->filter_data("branch", "admin_id", $display['select_faculty']->admin_id);
                    $d_all = $this->cm->filter_data("department", "admin_id", $display['select_faculty']->admin_id);
                    $s_name = array();
                    $sd_name = array();
                    foreach ($b_all as $key => $value) {
                        if (!empty($value)) {
                            $data = $this->cm->filter_data("department", "branch_id", $value->branch_id);
                            $b = $this->cm->select_data("branch", "branch_id", $value->branch_id);
                            if (!empty($data)) {
                                $s_name[$b->branch_name] = $data;
                            }
                        }
                    }
                    $display['s_name'] = $s_name;
                    // echo "<pre>";
                    $i = 0;
                    foreach ($d_all as $key => $value) {
                        if (!empty($value)) {
                            $data = $this->cm->filter_data("subdepartment", "department_ids", $value->department_id);
                            // print_r($data);
                            $b = $this->cm->select_data("branch", "branch_id", $value->branch_id);
                            $d = $this->cm->select_data("department", "department_id", $value->department_id);
                            //print_r($b);
                            if (!empty($data)) {
                                @$sd_name[$b->branch_name][$d->department_name] = $data;
                            }
                        }
                    }
                    $display['sd_name'] = $sd_name;
                    $all = $this->cm->filter_data("extradepartment", "admin_id", $display['select_faculty']->admin_id);
                    $mall = array();
                    foreach ($all as $key => $value) {
                        $jall = $this->cm->select_data("subdepartment", "subdepartment_id", $value->subdepartment_ids);
                        $mall[$jall->subdepartment_name][$key] = $value;
                    }
                    $display['mall'] = $mall;
                } else if ($this->input->get('action') == "change_s") {
                    if ($this->input->get('status') == 0) {
                        $sdata['status'] = 1;
                        $re = $this->cm->update_data("faculty", $sdata, "faculty_id", $id);
                    } else {
                        $sdata['status'] = 0;
                        $re = $this->cm->update_data("faculty", $sdata, "faculty_id", $id);
                    }
                    if ($re) {
                        logdata('faculty id= ' . $id . " Status " . $sdata['status'] . " updated");
                        redirect('settings/staff');
                    }
                }
            }
            if (!empty($this->input->post('submit'))) {
                $data = $this->input->post();
                $ins_data['logtype'] = "Faculty";
                @$ins_data['branch_ids'] = implode(",", @$data['b_ids']);
                if (empty($ins_data['branch_ids'])) {
                    $ins_data['branch_ids'] = "";
                }
                @$ins_data['department_id'] = implode(",", @$data['depart_ids']); //$data['department_id'];
                if (empty($ins_data['department_id'])) {
                    $ins_data['department_id'] = "";
                }
                @$ins_data['subdepartment_id'] = implode(',', $data['subdepart_ids']);
                if (empty($ins_data['subdepartment_id'])) {
                    $ins_data['subdepartment_id'] = "";
                }
                if ($_SESSION['logtype'] == "Super Admin") {
                    $ins_data['admin_id'] = $_POST['admin_id'];
                } else {
                    $ins_data['admin_id'] = $_SESSION['admin_id'];
                }
                $pdata = $this->cm->select_data("hod", "name", $data['hod_id']);
                if (!empty($pdata->hod_id)) {
                    $hid = $pdata->hod_id;
                }
                @$ins_data['hod_ids'] = $data['hod_id'];
                @$ins_data['parent_name'] = $data['hod_id'];
                $ins_data['name'] = $data['name'];
                $ins_data['email'] = $data['email'];
                $ins_data['password'] = $data['password'];
                $ins_data['phone'] = $data['phone'];
                $ins_data['permission'] = implode(",", $data['f_all']);
                $ins_data['f_permission'] = implode(",", $data['fp']);
                $ins_data['m_permission'] = implode(",", $data['m_all']);
                if (!empty($this->input->post('update_id'))) {
                    $id = $this->input->post('update_id');
                    $re = $this->cm->update_data("faculty", $ins_data, "faculty_id", $id);
                    logdata("faculty id= " . $id . " faculty name=" . $ins_data['name'] . " Update");
                } else {
                    $re = $this->cm->insert_data("faculty", $ins_data);
                    logdata("faculty name=" . $ins_data['name'] . " add");
                }
                if ($re) {
                    redirect('settings/staff');
                }
            }
            if ($_SESSION['logtype'] == "Super Admin") {
                $display['department_all'] = $this->cm->view_all("department");
                $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
                $display['branch_all'] = $this->cm->view_all("branch");
                $display['faculty_all'] = $this->cm->view_all("faculty");
                $display['permission_all'] = $this->cm->view_all("permission");
                $display['f_module'] = $this->cm->view_all("f_module");
                $display['m_module'] = $this->cm->view_all("m_module");
                $display['l_module'] = $this->cm->view_all("l_module");
                $display['select_logtype'] = $this->tm->select_data_fact_permission("logtype", "logtype_name", "Faculty");
                $display['hod_all'] = $this->cm->view_all_hods("hod");
                $display['user_all'] = $this->cm->view_all_user("user");
            } else {
                $display['department_all'] = $this->cm->view_all("department");
                $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
                $display['branch_all'] = $this->cm->view_all("branch");
                $display['faculty_all'] = $this->tm->view_all("faculty");
                $display['permission_all'] = $this->cm->view_all("permission");
                $display['f_module'] = $this->cm->view_all("f_module");
                $display['m_module'] = $this->cm->view_all("m_module");
                $display['l_module'] = $this->cm->view_all("l_module");
                $display['select_logtype'] = $this->tm->select_data("logtype", "logtype_name", "Faculty");
                $display['hod_all'] = $this->cm->view_all("hod");
                $display['user_all'] = $this->cm->view_all_user("user");
            }
            $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
            $update['upd_hod'] = $this->cm->view_all("hod");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['upd_see'] = $this->cm->check_update("demo");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $this->load->view('header', $update);
            $this->load->view('staff', $display);
        }
    }
    public function student_finder_permission()
    {
        logdata("Faculty page open");
        @$user_permission = explode(",", $_SESSION['user_permission']);
        if (in_array("Faculty=1", $user_permission) || $_SESSION['logtype'] == "Super Admin" || $_SESSION['logtype'] == "Admin") {
            if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
                $id = $this->input->get('id');
                if ($this->input->get('action') == "change_s") {
                    if (@$this->input->get('faculty_sstt') == 'faculty') {
                        if ($this->input->get('status') == 1) {
                            $sdata['student_finder_app_status'] = 0;
                            $re = $this->cm->update_data("faculty", $sdata, "faculty_id", $id);
                        } else {
                            $sdata['student_finder_app_status'] = 1;
                            $re = $this->cm->update_data("faculty", $sdata, "faculty_id", $id);
                        }
                        if ($re) {
                            logdata('faculty id= ' . $id . " student_finder_app_Status " . $sdata['student_finder_app_status'] . " updated");
                            redirect('settings/student_finder_permission');
                        }
                    }
                    if (@$this->input->get('faculty_sstt') == 'user') {
                        if ($this->input->get('status') == 1) {
                            $sdata['student_finder_app_status'] = 0;
                            $re = $this->cm->update_data("user", $sdata, "user_id", $id);
                        } else {
                            $sdata['student_finder_app_status'] = 1;
                            $re = $this->cm->update_data("user", $sdata, "user_id", $id);
                        }
                        if ($re) {
                            logdata('user id= ' . $id . " student_finder_app_Status " . $sdata['student_finder_app_status'] . " updated");
                            redirect('settings/student_finder_permission');
                        }
                    }
                }
            }
            if (!empty($this->input->post('submit'))) {
                $data_re = $this->input->post();
                if (@$this->input->post('table_type_faculty') == 'faculty') {
                    $data_facu = $this->input->post('faculty_records');
                    for ($i = 0; $i < sizeof($data_facu); $i++) {
                        $sdata['student_finder_app_status'] = 1;
                        $re = $this->cm->update_data("faculty", $sdata, "faculty_id", $data_facu[$i]);
                    }
                }
                if ($re) {
                    redirect('settings/student_finder_permission');
                }
            }
            if ($_SESSION['logtype'] == "Super Admin") {
                $display['department_all'] = $this->cm->view_all("department");
                $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
                $display['branch_all'] = $this->cm->view_all("branch");
                $display['faculty_all'] = $this->cm->view_all("faculty");
                $display['users_all'] = $this->cm->view_all("user");
                $display['permission_all'] = $this->cm->view_all("permission");
                $display['f_module'] = $this->cm->view_all("f_module");
                $display['m_module'] = $this->cm->view_all("m_module");
                $display['l_module'] = $this->cm->view_all("l_module");
                $display['select_logtype'] = $this->tm->select_data_fact_permission("logtype", "logtype_name", "Faculty");
                $display['hod_all'] = $this->cm->view_all_hods("hod");
                $display['user_all'] = $this->cm->view_all_user("user");
            } else {
                $display['department_all'] = $this->cm->view_all("department");
                $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
                $display['branch_all'] = $this->cm->view_all("branch");
                $display['faculty_all'] = $this->tm->view_all("faculty");
                $display['users_all'] = $this->cm->view_all_users_apps("user");
                $display['permission_all'] = $this->cm->view_all("permission");
                $display['f_module'] = $this->cm->view_all("f_module");
                $display['m_module'] = $this->cm->view_all("m_module");
                $display['l_module'] = $this->cm->view_all("l_module");
                $display['select_logtype'] = $this->tm->select_data("logtype", "logtype_name", "Faculty");
                $display['hod_all'] = $this->cm->view_all_hods("hod");
                $display['user_all'] = $this->cm->view_all_user("user");
            }
            $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
            $update['upd_hod'] = $this->cm->view_all("hod");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['upd_see'] = $this->cm->check_update("demo");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $this->load->view('header', $update);
            $this->load->view('student_finder_permission', $display);
        }
    }
    public function setFacultyCourse()
    {
        $faculty_id = $this->input->post('faculty_id');
        $course_ids = $this->input->post('faculty_course');
        $package_ids = $this->input->post('faculty_package');
        $c_data = $this->cm->view_all("course");
        $p_data = $this->cm->view_all("package");
        $call = array();
        $pall = array();
        // echo "<pre>";
        // print_r($course_ids);
        // die;
        if (!empty($course_ids)) {
            foreach ($c_data as $k => $v) {
                foreach ($course_ids as $key => $value) {
                    if ($value == $v->course_id) {
                        $cd = $this->cm->select_data("course", "course_id", $v->course_id);
                        $call[] = $cd->course_name;
                    }
                }
            }
        }
        if (!empty($package_ids)) {
            foreach ($p_data as $k => $m) {
                foreach ($package_ids as $key => $j) {
                    if ($j == $m->package_id) {
                        $pd = $this->cm->select_data("package", "package_id", $m->package_id);
                        $pall[] = $pd->package_name;
                    }
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            if (is_array($course_ids)) {
                $data['course_ids'] = implode(",", $course_ids);
                logdata(implode(",", $call) . " Course update for Faculty page");
            } else {
                $data['course_ids'] = "";
            }
            if (is_array($package_ids)) {
                $data['package_ids'] = implode(",", $package_ids);
                logdata(implode(",", $pall) . " Package update for faculty page");
            } else {
                $data['package_ids'] = "";
            }
            $re = $this->cm->update_data("faculty", $data, "faculty_id", $faculty_id);
            if ($re) {
                $display['msg'] = "Course added Successfully";
            }
        }
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['select_logtype'] = $this->tm->select_data_fact_permission("logtype", "logtype_name", "hod");
        } else {
            $display['select_logtype'] = $this->tm->select_data("logtype", "logtype_name", "hod");
        }
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['department_all'] = $this->cm->view_all("department");
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['branch_all'] = $this->cm->view_all("branch");
            $display['faculty_all'] = $this->cm->view_all_facultys("faculty");
            $display['permission_all'] = $this->cm->view_all("permission");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        } else {
            $display['department_all'] = $this->cm->view_all_data("department");
            $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
            $display['branch_all'] = $this->cm->view_all_data("branch");
            $display['faculty_all'] = $this->cm->view_all_data("faculty");
            $display['permission_all'] = $this->cm->view_all("permission");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        }
        $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('staff', $display);
    }
    public function sethodCourse()
    {
        $hod_id = $this->input->post('hod_id');
        $course_ids = $this->input->post('hod_course');
        $package_ids = $this->input->post('hod_package');
        $c_data = $this->cm->view_all("course");
        $p_data = $this->cm->view_all("package");
        $call = array();
        $pall = array();
        // echo "<pre>";
        // print_r($course_ids);
        // die;
        if (!empty($course_ids)) {
            foreach ($c_data as $k => $v) {
                foreach ($course_ids as $key => $value) {
                    if ($value == $v->course_id) {
                        $cd = $this->cm->select_data("course", "course_id", $v->course_id);
                        $call[] = $cd->course_name;
                    }
                }
            }
        }
        if (!empty($package_ids)) {
            foreach ($p_data as $k => $m) {
                foreach ($package_ids as $key => $j) {
                    if ($j == $m->package_id) {
                        $pd = $this->cm->select_data("package", "package_id", $m->package_id);
                        $pall[] = $pd->package_name;
                    }
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            if (is_array($course_ids)) {
                $data['course_ids'] = implode(",", $course_ids);
                logdata(implode(",", $call) . " Course update for hod page");
            } else {
                $data['course_ids'] = "";
            }
            if (is_array($package_ids)) {
                $data['package_ids'] = implode(",", $package_ids);
                logdata(implode(",", $pall) . " Package update for hod page");
            } else {
                $data['package_ids'] = "";
            }
            $re = $this->cm->update_data("hod", $data, "hod_id", $hod_id);
            if ($re) {
                $display['msg'] = "Course added Successfully";
            }
        }
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['select_logtype'] = $this->tm->select_data_fact_permission("logtype", "logtype_name", "hod");
        } else {
            $display['select_logtype'] = $this->tm->select_data("logtype", "logtype_name", "hod");
        }
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $display['branch_all'] = $this->cm->view_all("branch");
        $display['hod_all'] = $this->cm->view_all_hods("hod");
        $display['permission_all'] = $this->cm->view_all("permission");
        $display['user_all'] = $this->cm->Role_all_admin("user");
        $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
        $update['upd_hod'] = $this->cm->view_all("hod");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('hod', $display);
    }
    public function aspectedtime()
    {
        if (!empty($this->input->post())) {
            $name = $this->input->post('filter_name');
            $display['select_faculty'] = $this->cm->select_data_by_filter_faculty("faculty", "name", $name);
            $_SESSION['user_name_store'] = $name;
        } else {
            $id = $this->session->userdata['user_id'];
            $display['select_faculty'] = $this->cm->select_data("faculty", "faculty_id", $id);
        }
        $display['all_time'] = $this->cm->view_all("time");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('aspectedtime', $display);
    }
    public function settime()
    {
        $data = $this->input->post();
        if (isset($data['submit'])) {
            $id = $this->session->userdata['user_id'];
            $all_time = $this->cm->view_all("time");
            $set['checkTime'] = "";
            foreach ($all_time as $time) {
                @$temp = $data['gettime' . $time->time_id] . "/" . $data['getnote' . $time->time_id];
                if (empty($set['checkTime'])) {
                    $set['checkTime'] = $temp;
                } else {
                    $set['checkTime'] = $set['checkTime'] . "|&|" . $temp;
                }
            }
            $re = $this->cm->update_data("faculty", $set, "faculty_id", $id);
            if ($re) {
                redirect('settings/aspectedtime');
            }
        }
    }
    public function profile()
    {
        $display['msgp'] = "";
        if (!empty($this->input->post('cpassword'))) {
            $data = $this->input->post();
            if (!empty($data['npass']) && !empty($data['cpass'])) {
                if ($data['npass'] == $data['cpass']) {
                    $datac['password'] = $data['npass'];
                    $id = $_SESSION['user_id'];
                    if ($_SESSION['logtype'] == "Super Admin") {
                        $re = $this->cm->update_data("admin", $datac, "id", $id);
                    } else if ($_SESSION['logtype'] == "Faculty") {
                        $re = $this->cm->update_data("faculty", $datac, "faculty_id", $id);
                    } else {
                        $re = $this->cm->update_data("user", $datac, "user_id", $id);
                    }
                    if ($re) {
                        $display['msgpc'] = "Password change successfully";
                    }
                } else {
                    $display['msgp'] = "New Password And Confirm Password does not Match";
                }
            } else {
                $display['msgp'] = "Please Enter New Password And Confirm Password";
            }
        }
        if (!empty($this->input->post('cimage'))) {
            if ($_FILES['image']['name'] != "") {
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "dist/img/";
                $new_name = time() . $_FILES["image"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image')) {
                    $imagedata = $this->upload->data();
                    $image = FCPATH . "dist/img/" . $_SESSION['user_image'];
                    @unlink($image);
                } else {
                    $display['msgp'] = "image not uploaded";
                }
                $dataimg['image'] = @$imagedata['file_name'];
                $id = $_SESSION['user_id'];
                if ($_SESSION['logtype'] == "Super Admin") {
                    $re = $this->cm->update_data("admin", $dataimg, "id", $id);
                    $select_user = $this->cm->select_data("admin", "id", $id);
                } else if ($_SESSION['logtype'] == "Faculty") {
                    $re = $this->cm->update_data("faculty", $dataimg, "faculty_id", $id);
                    $select_user = $this->cm->select_data("faculty", "faculty_id", $id);
                } else {
                    $re = $this->cm->update_data("user", $dataimg, "user_id", $id);
                    $select_user = $this->cm->select_data("user", "user_id", $id);
                }
                if ($re) {
                    $this->session->set_userdata("user_image", $select_user->image);
                }
            } else {
                $display['msgp'] = "Please select Image";
            }
        }
        if (!empty($this->input->post('f_details'))) {
            // echo "<pre>";
            // print_r($this->input->post());
            // die;
            $data = $this->input->post();
            $fdata['designation'] = $data['designation'];
            $fdata['email'] = $data['email'];
            $fdata['personal_no'] = $data['personal_no'];
            $u_id = $_SESSION['user_id'];
            if ($_SESSION['logtype'] == "Super Admin") {
                $fdata['mobile_no'] = $data['mobile_no'];
                $re = $this->cm->update_data("admin", $fdata, "id", $u_id);
                //$select_user=$this->cm->select_data("admin","id",$id);

            } else if ($_SESSION['logtype'] == "Faculty") {
                $fdata['phone'] = $data['mobile_no'];
                $re = $this->cm->update_data("faculty", $fdata, "faculty_id", $u_id);
                //$select_user=$this->cm->select_data("faculty","faculty_id",$id);

            } else if ($_SESSION['logtype'] == "hod") {
                $fdata['phone'] = $data['mobile_no'];
                $re = $this->cm->update_data("hod", $fdata, "hod_id", $u_id);
                //$select_user=$this->cm->select_data("user","user_id",$id);

            } else {
                $fdata['mobile_no'] = $data['mobile_no'];
                $re = $this->cm->update_data("user", $fdata, "user_id", $u_id);
            }
            if ($re) {
                session_destroy();
                redirect('welcome/login');
            }
        }
        $display['branch_all'] = $this->cm->view_all("branch");
        $display['demo_all'] = $this->cm->view_all("demo");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('profile', $display);
        $this->load->view('footer');
    }
    public function view_course()
    {
        $id = $this->input->post('course_id');
        $display['course_all'] = $this->cm->view_all_course("course");
        $display['department_all'] = $this->cm->view_all("department");
        $display['select_course'] = $this->cm->select_data("course", "course_id", $id);
        $this->load->view('view_course', $display);
    }
    public function view_course_package()
    {
        $id = $this->input->post('package_id');
        $display['course_all'] = $this->cm->view_all_course("course");
        $display['department_all'] = $this->cm->view_all("department");
        $display['select_package'] = $this->cm->select_data("package", "package_id", $id);
        logdata("view course package");
        $this->load->view('view_course_package', $display);
    }
    public function view_course_Relevant()
    {
        $id = $this->input->post('package_id');
        $display['course_all'] = $this->cm->view_all_course("course");
        $display['department_all'] = $this->cm->view_all("department");
        $display['select_Relevant'] = $this->cm->select_data("package", "package_id", $id);
        logdata("RelevantCourse page open");
        $this->load->view('view_course_Relevant', $display);
    }
    public function view_course_faculty()
    {
        $id = $this->input->post('faculty_id');
        $display['course_all'] = $this->cm->view_all_course("course");
        $display['package_all'] = $this->cm->view_all("package");
        $display['department_all'] = $this->cm->view_all("department");
        $display['select_faculty'] = $this->cm->select_data("faculty", "faculty_id", $id);
        $this->load->view('view_course_faculty', $display);
    }
    public function view_course_hod()
    {
        $id = $this->input->post('hod_id');
        $display['course_all'] = $this->cm->view_all_course("course");
        $display['package_all'] = $this->cm->view_all("package");
        $display['department_all'] = $this->cm->view_all("department");
        $display['select_hod'] = $this->cm->select_data("hod", "hod_id", $id);
        $this->load->view('view_course_hod', $display);
    }
    public function setCourse()
    {
        $course_id = $this->input->post('course_id');
        $RelevantCourse_id = $this->input->post('course');
        if (!empty($this->input->post('submit'))) {
            if (is_array($RelevantCourse_id)) {
                $data['RelevantCourse_id'] = implode(",", $RelevantCourse_id);
            } else {
                $data['RelevantCourse_id'] = "";
            }
            $re = $this->cm->update_data("course", $data, "course_id", $course_id);
            if ($re) {
                logdata($data['RelevantCourse_id'] . " RelevantCourse add");
                $display['msg'] = "Course added Successfully";
            }
        }
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        } else {
            $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        }
        $display['department_all'] = $this->cm->view_all("department");
        $display['branch_all'] = $this->cm->view_all("branch");
        $display['course_all'] = $this->cm->view_course("course");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('course', $display);
    }
    public function setPackageCourse()
    {
        $package_id = $this->input->post('package_id');
        $course_ids = $this->input->post('package_course');
        if (!empty($this->input->post('submit'))) {
            if (is_array($course_ids)) {
                $data['course_ids'] = implode(",", $course_ids);
            } else {
                $data['course_ids'] = "";
            }
            $re = $this->cm->update_data("package", $data, "package_id", $package_id);
            if ($re) {
                logdata($data['course_ids'] . " Course Add for package page");
                $display['msg'] = "Course added Successfully";
            }
        }
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['department_all'] = $this->cm->view_all("department");
            $display['branch_all'] = $this->cm->view_all("branch");
            $display['package_all'] = $this->cm->view_all_package("package");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        } else {
            $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
            $display['department_all'] = $this->cm->view_all_data("department");
            $display['branch_all'] = $this->cm->view_all_data("branch");
            $display['package_all'] = $this->cm->view_all_data("package");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        }
        $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('course_package', $display);
    }
    public function setRelevantCourse()
    {
        $package_id = $this->input->post('package_id');
        $RelevantCourse_id = $this->input->post('course_Relevant');
        if (!empty($this->input->post('submit'))) {
            if (is_array($RelevantCourse_id)) {
                $data['RelevantCourse_id'] = implode(",", $RelevantCourse_id);
            } else {
                $data['RelevantCourse_id'] = "";
            }
            $re = $this->cm->update_data("package", $data, "package_id", $package_id);
            if ($re) {
                logdata($data['RelevantCourse_id'] . " RelevantCourse Add for Package page");
                $display['msg'] = "Course added Successfully";
            }
        }
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['package_all'] = $this->cm->view_all_package("package");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        } else {
            $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
            $display['package_all'] = $this->cm->view_all_data("package");
            $display['user_all'] = $this->cm->Role_all_admin("user");
        }
        $display['department_all'] = $this->cm->view_all("department");
        $display['branch_all'] = $this->cm->view_all("branch");
        $display['package_all'] = $this->cm->view_all_Relevant("package");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('course_package', $display);
    }
    public function user()
    {
        if ($_SESSION['logtype'] != "Super Admin" && $_SESSION['logtype'] != "Admin") {
            redirect('welcome');
        }
        logdata("user page open");
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("user", "user_id", $id);
                if ($re) {
                    logdata('User id= ' . $id . " Deleted");
                    redirect('settings/user');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_user'] = $this->cm->select_data("user", "user_id", $id);
                // $b_all =  $this->cm->filter_data("branch","admin_id",$display['select_user']->admin_id);
                // 	foreach ($b_all as $key => $value) {
                // 		if (!empty($value)) {
                // 		$data = $this->cm->filter_data("department","branch_id",$value->branch_id);
                // 		$b = $this->cm->select_data("branch","branch_id",$value->branch_id);
                // 		if(!empty($data)){ $s_name[$b->branch_name] = $data;}
                // 	   }
                // 	}
                // 	$display['s_name'] = $s_name;

            } else if ($this->input->get('action') == "change_s") {
                if ($this->input->get('status') == 0) {
                    $sdata['user_status'] = 1;
                    $re = $this->cm->update_data("user", $sdata, "user_id", $id);
                } else {
                    $sdata['user_status'] = 0;
                    $re = $this->cm->update_data("user", $sdata, "user_id", $id);
                }
                if ($re) {
                    logdata('user id= ' . $id . " Status " . $sdata['status'] . " updated");
                    redirect('settings/user');
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $logall = $this->cm->select_data("logtype", "logtype_id", $data['logtype']);
            if ($_SESSION['logtype'] == "Super Admin") {
                $ins_data['admin_id'] = $data['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $data['admin_id']);
                $ins_data['state_id'] = $all->state_id;
                $ins_data['city_id'] = $all->city_id;
            } else {
                $ins_data['admin_id'] = $_SESSION['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                // print_r($all);
                // die;
                $ins_data['state_id'] = $all->state_id;
                $ins_data['city_id'] = $all->city_id;
            }
            $ins_data['logtype'] = $logall->logtype_name;
            @$ins_data['branch_ids'] = implode(",", $data['b_ids']);
            if (empty($ins_data['branch_ids'])) {
                $ins_data['branch_ids'] = "";
            }
            @$ins_data['department_ids'] = implode(",", $data['depart_ids']);
            if (empty($ins_data['department_ids'])) {
                $ins_data['department_ids'] = "";
            }
            @$ins_data['subdepartment_ids'] = implode(",", $data['subdepart_ids']);
            if (empty($ins_data['subdepartment_ids'])) {
                $ins_data['subdepartment_ids'] = "";
            }
            $ins_data['name'] = $data['name'];
            $ins_data['email'] = $data['email'];
            $ins_data['password'] = $data['password'];
            // print_r($ins_data);
            // die();
            $ins_data['permission'] = implode(",", $data['f_all']);
            $ins_data['f_permission'] = implode(",", $data['fp']);
            $ins_data['m_permission'] = implode(",", $data['m_all']);
            $ins_data['m_parent_id'] = $data['m_parent_id'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("user", $ins_data, "user_id", $id);
                logdata("user id= " . $id . " user name=" . $ins_data['name'] . " Update");
            } else {
                $re = $this->cm->insert_data("user", $ins_data);
                logdata("user name=" . $ins_data['name'] . " add");
            }
            if ($re) {
                redirect('settings/user');
            }
        }
        if (!empty($this->input->get('logtype_action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('logtype_action') == "delete") {
                if ($this->input->get('status') == 0) {
                    $st['status'] = 1;
                } else {
                    $st['status'] = 0;
                }
                $re = $this->cm->update_data("logtype", $st, "logtype_id", $id);
                if ($re) {
                    logdata('logtype id= ' . $id . " Status " . $st['status'] . " updated");
                    redirect('settings/user');
                }
            } else if ($this->input->get('logtype_action') == "edit") {
                $display['select_logtype'] = $this->cm->select_data("logtype", "logtype_id", $id);
                // print_r($display);

            }
        }
        if (!empty($this->input->post('logtype_submit'))) {
            $data1 = $this->input->post();
            // echo "<pre>";
            // print_r($data1);
            // die;
            $data2['f_permission'] = implode(",", $data1['fp']);
            $data2['permission'] = implode(",", $data1['f_all']);
            $data2['m_permission'] = implode(",", $data1['m_all']);
            $data2['logtype_name'] = $data1['logtype_name'];
            if ($_SESSION['logtype'] == "Super Admin") {
                $data2['admin_id'] = 0;
                //$all = $this->cm->select_data("user","admin_id",$data['admin_id']);
                //$ins_data['state_id'] = $all->state_id;
                //$ins_data['city_id'] = $all->city_id;

            } else {
                $data2['admin_id'] = $_SESSION['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                // print_r($all);
                // die;
                $data2['state_id'] = $all->state_id;
                $data2['city_id'] = $all->city_id;
                $data2['parent_id'] = $data1['parent_id'];
            }
            // print_r($data2);
            // die;
            unset($data1['update_id']);
            unset($data1['logtype_submit']);
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("logtype", $data2, "logtype_id", $id);
                logdata("logtype id= " . $id . " logtype name=" . $data2['logtype_name'] . " Update");
            } else {
                $re = $this->cm->insert_data("logtype", $data2);
                logdata("logtype id= " . $id . " logtype name=" . $data2['logtype_name'] . " add");
            }
            if ($re) {
                redirect('settings/user');
            }
        }
        $display['department_all'] = $this->cm->view_all("department");
        $display['branch_all'] = $this->cm->view_all("branch");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['logtype_all'] = $this->cm->filter_data("logtype", "parent_id", "0");
        } else {
            $display['logtype_all'] = $this->tm->view_all("logtype");
        }
        //$display['logtype_all'] = $this->cm->view_all("logtype");
        $display['permission_all'] = $this->cm->view_all("permission");
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['user_all'] = $this->cm->view_all_user("user");
        } else {
            $display['user_all'] = $this->tm->view_all("user");
        }
        // echo "<pre>";
        // print_r($display['logtype_all']);
        // die;
        $display['f_module'] = $this->cm->view_all("f_module");
        $display['m_module'] = $this->cm->view_all("m_module");
        $display['l_module'] = $this->cm->view_all("l_module");
        // if($_SESSION['logtype'] == "Super Admin")
        // {
        // 		$display['log_all'] = $this->cm->filter_data("logtype","parent_id","0");
        // }
        // else
        // {
        $display['log_all'] = $this->cm->view_all("logtype");
        //}
        // echo "<pre>";
        // print_r($display['department_all']);
        // die;
        $display['state'] = $this->cm->view_all("state");
        $display['city'] = $this->cm->view_all("cities");
        $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('user', $display);
    }
    public function user_logtype()
    {
        if ($_SESSION['logtype'] != "Super Admin" && $_SESSION['logtype'] != "Admin") {
            redirect('welcome');
        }
        logdata("user page open");
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("user", "user_id", $id);
                if ($re) {
                    logdata('User id= ' . $id . " Deleted");
                    redirect('settings/user');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_user'] = $this->cm->select_data("user", "user_id", $id);
                $b_all = $this->cm->filter_data("branch", "admin_id", $display['select_user']->admin_id);
                foreach ($b_all as $key => $value) {
                    if (!empty($value)) {
                        $data = $this->cm->filter_data("department", "branch_id", $value->branch_id);
                        $b = $this->cm->select_data("branch", "branch_id", $value->branch_id);
                        if (!empty($data)) {
                            $s_name[$b->branch_name] = $data;
                        }
                    }
                }
                $display['s_name'] = $s_name;
            } else if ($this->input->get('action') == "change_s") {
                if ($this->input->get('status') == 0) {
                    $sdata['user_status'] = 1;
                    $re = $this->cm->update_data("user", $sdata, "user_id", $id);
                } else {
                    $sdata['user_status'] = 0;
                    $re = $this->cm->update_data("user", $sdata, "user_id", $id);
                }
                if ($re) {
                    logdata('user id= ' . $id . " Status " . $sdata['status'] . " updated");
                    redirect('settings/user');
                }
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $logall = $this->cm->select_data("logtype", "logtype_id", $data['logtype']);
            if ($_SESSION['logtype'] == "Super Admin") {
                $ins_data['admin_id'] = $data['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $data['admin_id']);
                $ins_data['state_id'] = $all->state_id;
                $ins_data['city_id'] = $all->city_id;
            } else {
                $ins_data['admin_id'] = $_SESSION['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                // print_r($all);
                // die;
                $ins_data['state_id'] = $all->state_id;
                $ins_data['city_id'] = $all->city_id;
            }
            $ins_data['logtype'] = $logall->logtype_name;
            @$ins_data['branch_ids'] = implode(",", $data['b_ids']);
            if (empty($ins_data['branch_ids'])) {
                $ins_data['branch_ids'] = "";
            }
            @$ins_data['department_ids'] = implode(",", $data['depart_ids']);
            if (empty($ins_data['department_ids'])) {
                $ins_data['department_ids'] = "";
            }
            $ins_data['name'] = $data['name'];
            $ins_data['email'] = $data['email'];
            $ins_data['password'] = $data['password'];
            $ins_data['permission'] = implode(",", $data['f_all']);
            $ins_data['f_permission'] = implode(",", $data['fp']);
            $ins_data['m_permission'] = implode(",", $data['m_all']);
            $ins_data['m_parent_id'] = $data['m_parent_id'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("user", $ins_data, "user_id", $id);
                logdata("user id= " . $id . " user name=" . $ins_data['name'] . " Update");
            } else {
                $re = $this->cm->insert_data("user", $ins_data);
                logdata("user name=" . $ins_data['name'] . " add");
            }
            if ($re) {
                redirect('settings/user');
            }
        }
        if (!empty($this->input->get('logtype_action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('logtype_action') == "delete") {
                if ($this->input->get('status') == 0) {
                    $st['status'] = 1;
                } else {
                    $st['status'] = 0;
                }
                $re = $this->cm->update_data("logtype", $st, "logtype_id", $id);
                if ($re) {
                    logdata('logtype id= ' . $id . " Status " . $st['status'] . " updated");
                    redirect('settings/user');
                }
            } else if ($this->input->get('logtype_action') == "edit") {
                $display['select_logtype'] = $this->cm->select_data("logtype", "logtype_id", $id);
                // print_r($display);

            }
        }
        if (!empty($this->input->post('logtype_submit'))) {
            $data1 = $this->input->post();
            // echo "<pre>";
            // print_r($data1);
            // die;
            $data2['f_permission'] = implode(",", $data1['fp']);
            $data2['permission'] = implode(",", $data1['f_all']);
            $data2['m_permission'] = implode(",", $data1['m_all']);
            $data2['logtype_name'] = $data1['logtype_name'];
            if ($_SESSION['logtype'] == "Super Admin") {
                $data2['admin_id'] = 0;
                //$all = $this->cm->select_data("user","admin_id",$data['admin_id']);
                //$ins_data['state_id'] = $all->state_id;
                //$ins_data['city_id'] = $all->city_id;

            } else {
                $data2['admin_id'] = $_SESSION['admin_id'];
                $all = $this->cm->select_data("user", "admin_id", $_SESSION['admin_id']);
                // print_r($all);
                // die;
                $data2['state_id'] = $all->state_id;
                $data2['city_id'] = $all->city_id;
                $data2['parent_id'] = $data1['parent_id'];
            }
            // print_r($data2);
            // die;
            unset($data1['update_id']);
            unset($data1['logtype_submit']);
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("logtype", $data2, "logtype_id", $id);
                logdata("logtype id= " . $id . " logtype name=" . $data2['logtype_name'] . " Update");
            } else {
                $re = $this->cm->insert_data("logtype", $data2);
                logdata("logtype id= " . $id . " logtype name=" . $data2['logtype_name'] . " add");
            }
            if ($re) {
                redirect('settings/user');
            }
        }
        $display['department_all'] = $this->cm->view_all("department");
        $display['branch_all'] = $this->cm->view_all("branch");
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['logtype_all'] = $this->cm->filter_data("logtype", "parent_id", "0");
        } else {
            $display['logtype_all'] = $this->tm->view_all("logtype");
        }
        //$display['logtype_all'] = $this->cm->view_all("logtype");
        $display['permission_all'] = $this->cm->view_all("permission");
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['user_all'] = $this->cm->view_all_user("user");
        } else {
            $display['user_all'] = $this->tm->view_all("user");
        }
        // echo "<pre>";
        // print_r($display['logtype_all']);
        // die;
        $display['f_module'] = $this->cm->view_all("f_module");
        $display['m_module'] = $this->cm->view_all("m_module");
        $display['l_module'] = $this->cm->view_all("l_module");
        // if($_SESSION['logtype'] == "Super Admin")
        // {
        // 		$display['log_all'] = $this->cm->filter_data("logtype","parent_id","0");
        // }
        // else
        // {
        $display['log_all'] = $this->cm->view_all("logtype");
        //}
        // echo "<pre>";
        // print_r($display['department_all']);
        // die;
        $display['state'] = $this->cm->view_all("state");
        $display['city'] = $this->cm->view_all("cities");
        $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('create_logtype', $display);
    }
    function softset()
    {
        if (!empty($this->input->get('action_ip')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action_ip') == "delete") {
                $re = $this->cm->delete_data("network_ip", "address_id", $id);
                if ($re) {
                    redirect('settings/softset');
                }
            } else if ($this->input->get('action_ip') == "edit") {
                $display['select_ip'] = $this->cm->select_data("network_ip", "address_id", $id);
            }
        }
        if (!empty($this->input->post('submit_ip'))) {
            $data = $this->input->post();
            unset($data['update_id']);
            unset($data['submit_ip']);
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("network_ip", $data, "address_id", $id);
            } else {
                $re = $this->cm->insert_data("network_ip", $data);
            }
            if (@$re) {
                redirect('settings/softset');
            }
        }
        $display['department_all'] = $this->cm->view_all("department");
        $display['network_ip_all'] = $this->cm->view_all("network_ip");
        $display['branch_all'] = $this->cm->view_all("branch");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/software_settings', $display);
    }
    public function selectedCourse()
    {
        $data = $this->input->post();
        for ($i = 0; $i < sizeof($data['selected_course']); $i++) {
            $update['selected_course'] = 1;
            $this->cm->update_data("course", $update, "course_id", $data['selected_course'][$i]);
        }
        redirect('welcome/index');
    }
    public function source()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                if ($this->input->get('status') == 0) {
                    $st['source_status'] = 1;
                } else {
                    $st['source_status'] = 0;
                }
                $re = $this->cm->update_data("source", $st, "source_id", $id);
                if ($re) {
                    redirect('settings/source');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_source'] = $this->cm->select_data("source", "source_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            unset($data['update_id']);
            unset($data['submit']);
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("source", $data, "source_id", $id);
            } else {
                $result = $this->cm->select_data("source", "source_name", $data['source_name']);
                if (empty($result)) {
                    $re = $this->cm->insert_data("source", $data);
                } else {
                    $display['msg_alert'] = $data['source_name'] . "  Source Name Already Exist";
                }
            }
            if (@$re) {
                redirect('settings/source');
            }
        }
        $display['source_all'] = $this->cm->view_all("source");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/source', $display);
    }
    public function setmail()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("mail_address", "email_id", $id);
                if ($re) {
                    redirect('settings/setmail');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_email'] = $this->cm->select_data("mail_address", "email_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $ins['email'] = $data['email'];
            $ins['password'] = $data['password'];
            $ins['branch_id'] = $data['branch_id'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("mail_address", $ins, "email_id", $id);
            } else {
                $result = $this->cm->select_data("mail_address", "email", $ins['email']);
                if (empty($result)) {
                    $re = $this->cm->insert_data("mail_address", $ins);
                } else {
                    $display['msg_alert'] = $ins['email'] . "  Email  Already Exist";
                }
            }
            if (@$re) {
                redirect('settings/setmail');
            }
        }
        $display['mail_address_all'] = $this->cm->view_all("mail_address");
        $display['branch_all'] = $this->cm->view_all("branch");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/set_mailaddress', $display);
        $this->load->view('footer');
    }
    public function setMsgTemplate()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("msg_template", "msg_template_id", $id);
                if ($re) {
                    redirect('settings/setMsgTemplate');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_msg_template'] = $this->cm->select_data("msg_template", "msg_template_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $ins['msg_category_name'] = $data['msg_category_name'];
            $ins['msg_template_text'] = $data['msg_template_text'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("msg_template", $ins, "msg_template_id", $id);
                if (@$re) {
                    redirect('settings/setMsgTemplate');
                }
            } else {
                $check_duplicate = $this->cm->select_data("msg_template", "msg_category_name", $ins['msg_category_name']);
                if (empty($check_duplicate)) {
                    $re = $this->cm->insert_data("msg_template", $ins);
                    if (@$re) {
                        redirect('settings/setMsgTemplate');
                    }
                } else {
                    $display['msg_alert'] = $ins['msg_category_name'] . " Category  Already Exist";
                }
            }
        }
        $display['msg_template_all'] = $this->cm->view_all("msg_template");
        $display['msg_template_category_all'] = $this->cm->view_all("msg_template_category");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/msg_template', $display);
    }
    public function addSchool()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("list_school", "school_id", $id);
                if ($re) {
                    redirect('settings/addSchool');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_list_school'] = $this->cm->select_data("list_school", "school_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $ins['school_name'] = $data['school_name'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("list_school", $ins, "school_id", $id);
                if (@$re) {
                    redirect('settings/addSchool');
                }
            } else {
                $check_duplicate = $this->cm->select_data("list_school", "school_name", $ins['school_name']);
                if (empty($check_duplicate)) {
                    $re = $this->cm->insert_data("list_school", $ins);
                    if (@$re) {
                        redirect('settings/addSchool');
                    }
                } else {
                    $display['msg_alert'] = $ins['school_name'] . " Category  Already Exist";
                }
            }
        }
        $display['list_school_all'] = $this->cm->view_all("list_school");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/add_school', $display);
    }
    public function addInterestLevel()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("list_interest_level", "interest_level_id", $id);
                if ($re) {
                    redirect('settings/addInterestLevel');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_list_interest_level'] = $this->cm->select_data("list_interest_level", "interest_level_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $ins['interest_level_name'] = $data['interest_level_name'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("list_interest_level", $ins, "interest_level_id", $id);
                if (@$re) {
                    redirect('settings/addInterestLevel');
                }
            } else {
                $check_duplicate = $this->cm->select_data("list_interest_level", "interest_level_name", $ins['interest_level_name']);
                if (empty($check_duplicate)) {
                    $re = $this->cm->insert_data("list_interest_level", $ins);
                    if (@$re) {
                        redirect('settings/addInterestLevel');
                    }
                } else {
                    $display['msg_alert'] = $ins['interest_level_name'] . " Category  Already Exist";
                }
            }
        }
        $display['list_interest_level_all'] = $this->cm->view_all("list_interest_level");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/add_interest_level', $display);
    }
    public function addFollowupAction()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("list_followup_action", "followup_action_id", $id);
                if ($re) {
                    redirect('settings/addFollowupAction');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_list_followup_action'] = $this->cm->select_data("list_followup_action", "followup_action_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $ins['followup_action_name'] = $data['followup_action_name'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("list_followup_action", $ins, "followup_action_id", $id);
                if (@$re) {
                    redirect('settings/addFollowupAction');
                }
            } else {
                $check_duplicate = $this->cm->select_data("list_followup_action", "followup_action_name", $ins['followup_action_name']);
                if (empty($check_duplicate)) {
                    $re = $this->cm->insert_data("list_followup_action", $ins);
                    if (@$re) {
                        redirect('settings/addFollowupAction');
                    }
                } else {
                    $display['msg_alert'] = $ins['followup_action_name'] . " Category  Already Exist";
                }
            }
        }
        $display['list_followup_action_all'] = $this->cm->view_all("list_followup_action");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/add_followup_action', $display);
    }
    public function addFollowupMode()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("list_follow_up_mode", "follow_up_mode_id", $id);
                if ($re) {
                    redirect('settings/addFollowupMode');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_list_follow_up_mode'] = $this->cm->select_data("list_follow_up_mode", "follow_up_mode_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $ins['follow_up_mode_name'] = $data['follow_up_mode_name'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("list_follow_up_mode", $ins, "follow_up_mode_id", $id);
                if (@$re) {
                    redirect('settings/addFollowupMode');
                }
            } else {
                $check_duplicate = $this->cm->select_data("list_follow_up_mode", "follow_up_mode_name", $ins['follow_up_mode_name']);
                if (empty($check_duplicate)) {
                    $re = $this->cm->insert_data("list_follow_up_mode", $ins);
                    if (@$re) {
                        redirect('settings/addFollowupMode');
                    }
                } else {
                    $display['msg_alert'] = $ins['follow_up_mode_name'] . " Category  Already Exist";
                }
            }
        }
        $display['list_follow_up_mode_all'] = $this->cm->view_all("list_follow_up_mode");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/add_follow_up_mode', $display);
    }
    public function addStudentResponse()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("list_student_response", "student_response_id", $id);
                if ($re) {
                    redirect('settings/addStudentResponse');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_list_student_response'] = $this->cm->select_data("list_student_response", "student_response_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $ins['student_response_name'] = $data['student_response_name'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("list_student_response", $ins, "student_response_id", $id);
                if (@$re) {
                    redirect('settings/addStudentResponse');
                }
            } else {
                $check_duplicate = $this->cm->select_data("list_student_response", "student_response_name", $ins['student_response_name']);
                if (empty($check_duplicate)) {
                    $re = $this->cm->insert_data("list_student_response", $ins);
                    if (@$re) {
                        redirect('settings/addStudentResponse');
                    }
                } else {
                    $display['msg_alert'] = $ins['student_response_name'] . " Category  Already Exist";
                }
            }
        }
        $display['list_student_response_all'] = $this->cm->view_all("list_student_response");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/add_student_response', $display);
    }
    public function addCancellationReason()
    {
        if ($_SESSION['logtype'] != "Super Admin") {
            redirect('welcome');
        }
        if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
            $id = $this->input->get('id');
            if ($this->input->get('action') == "delete") {
                $re = $this->cm->delete_data("cancel_reason_list", "reason_id", $id);
                if ($re) {
                    redirect('settings/addCancellationReason');
                }
            } else if ($this->input->get('action') == "edit") {
                $display['select_cancel_reason_list'] = $this->cm->select_data("cancel_reason_list", "reason_id", $id);
            }
        }
        if (!empty($this->input->post('submit'))) {
            $data = $this->input->post();
            $ins['reasonName'] = $data['reasonName'];
            if (!empty($this->input->post('update_id'))) {
                $id = $this->input->post('update_id');
                $re = $this->cm->update_data("cancel_reason_list", $ins, "reason_id", $id);
                if (@$re) {
                    redirect('settings/addCancellationReason');
                }
            } else {
                $check_duplicate = $this->cm->select_data("cancel_reason_list", "reasonName", $ins['reasonName']);
                if (empty($check_duplicate)) {
                    $re = $this->cm->insert_data("cancel_reason_list", $ins);
                    if (@$re) {
                        redirect('settings/addCancellationReason');
                    }
                } else {
                    $display['msg_alert'] = $ins['reasonName'] . " Category  Already Exist";
                }
            }
        }
        $display['cancel_reason_list_all'] = $this->cm->view_all("cancel_reason_list");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $this->load->view('header', $update);
        $this->load->view('settings/add_cancellation_reason', $display);
    }
    public function hod()
    {
        @$user_permission = explode(",", $_SESSION['user_permission']);
        logdata("HOD page open");
        if (in_array("Faculty=1", $user_permission) || $_SESSION['logtype'] == "Super Admin" || $_SESSION['logtype'] == "Admin") {
            if (!empty($this->input->get('action')) && !empty($this->input->get('id'))) {
                $id = $this->input->get('id');
                if ($this->input->get('action') == "delete") {
                    $re = $this->cm->delete_data("hod", "hod_id", $id);
                    if ($re) {
                        logdata('hod id= ' . $id . " Deleted");
                        redirect('settings/hod');
                    }
                } else if ($this->input->get('action') == "edit") {
                    $display['select_hod'] = $this->cm->select_data("hod", "hod_id", $id);
                    $b_all = $this->cm->filter_data("branch", "admin_id", $display['select_hod']->admin_id);
                    $d_all = $this->cm->filter_data("department", "admin_id", $display['select_hod']->admin_id);
                    $s_name = array();
                    $sd_name = array();
                    foreach ($b_all as $key => $value) {
                        if (!empty($value)) {
                            $data = $this->cm->filter_data("department", "branch_id", $value->branch_id);
                            $b = $this->cm->select_data("branch", "branch_id", $value->branch_id);
                            if (!empty($data)) {
                                $s_name[$b->branch_name] = $data;
                            }
                        }
                    }
                    $display['s_name'] = $s_name;
                    // echo "<pre>";
                    $i = 0;
                    foreach ($d_all as $key => $value) {
                        if (!empty($value)) {
                            $data = $this->cm->filter_data("subdepartment", "department_ids", $value->department_id);
                            $b = $this->cm->select_data("branch", "branch_id", $value->branch_id);
                            $d = $this->cm->select_data("department", "department_id", $value->department_id);
                            if (!empty($data)) {
                                $sd_name[$b->branch_name][$d->department_name] = $data;
                            }
                        }
                    }
                    $display['sd_name'] = $sd_name;
                } else if ($this->input->get('action') == "change_s") {
                    if ($this->input->get('status') == 0) {
                        $sdata['status'] = 1;
                        $re = $this->cm->update_data("hod", $sdata, "hod_id", $id);
                    } else {
                        $sdata['status'] = 0;
                        $re = $this->cm->update_data("hod", $sdata, "hod_id", $id);
                    }
                    if ($re) {
                        logdata('hod id= ' . $id . " Status " . $sdata['status'] . " updated");
                        redirect('settings/hod');
                    }
                }
            }
            if (!empty($this->input->post('submit'))) {
                $data = $this->input->post();
                // echo "<pre>";
                // print_r($data);
                // die;
                $ins_data['logtype'] = "hod";
                if ($_SESSION['logtype'] == "Super Admin") {
                    $ins_data['admin_id'] = $_POST['admin_id'];
                } else {
                    $ins_data['admin_id'] = $_SESSION['admin_id'];
                }
                $ins_data['parent_id'] = $data['manager_id'];
                $ins_data['m_parent_id'] = $data['manager_id'];
                @$ins_data['branch_ids'] = implode(",", @$data['b_ids']);
                if (empty($ins_data['branch_ids'])) {
                    $ins_data['branch_ids'] = "";
                }
                @$ins_data['department_ids'] = implode(",", $data['depart_ids']);
                if (empty($ins_data['department_ids'])) {
                    $ins_data['department_ids'] = "";
                }
                @$ins_data['subdepartment_id'] = implode(",", $data['subdepart_ids']);
                if (empty($ins_data['subdepartment_id'])) {
                    $ins_data['subdepartment_id'] = "";
                }
                $ins_data['name'] = $data['name_hod'];
                $ins_data['email'] = $data['email'];
                $ins_data['password'] = $data['password'];
                $ins_data['phone'] = $data['phone'];
                $l_module = $this->cm->view_all('l_module');
                $ins_data['permission'] = implode(",", $data['f_all']);
                $ins_data['f_permission'] = implode(",", $data['fp']);
                $ins_data['m_permission'] = implode(",", $data['m_all']);
                if (!empty($this->input->post('update_id'))) {
                    $id = $this->input->post('update_id');
                    $re = $this->cm->update_data("hod", $ins_data, "hod_id", $id);
                    logdata("hod id= " . $id . " hod name=" . $ins_data['name'] . " Update");
                } else {
                    $re = $this->cm->insert_data("hod", $ins_data);
                    logdata("hod name=" . $ins_data['name'] . " add");
                }
                if ($re) {
                    redirect('settings/hod');
                }
            }
            if ($_SESSION['logtype'] == "Super Admin") {
                $display['department_all'] = $this->cm->view_all("department");
                $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
                $display['branch_all'] = $this->cm->view_all("branch");
                $display['faculty_all'] = $this->cm->view_all_facultys("faculty");
                $display['permission_all'] = $this->cm->view_all("permission");
                $display['hod_all'] = $this->cm->view_all("hod");
                $display['user_all'] = $this->cm->Role_all_admin("user");
                $display['select_logtype'] = $this->tm->select_data_fact_permission("logtype", "logtype_name", "hod");
            } else {
                $display['department_all'] = $this->cm->view_all_data("department");
                $display['subdepartment_all'] = $this->cm->view_all_data("subdepartment");
                $display['branch_all'] = $this->cm->view_all_data("branch");
                $display['faculty_all'] = $this->cm->view_all_data("faculty");
                $display['permission_all'] = $this->cm->view_all("permission");
                $display['hod_all'] = $this->cm->view_all_data("hod");
                $display['user_all'] = $this->cm->Role_all_admin("user");
                $display['hod_all'] = $this->cm->view_all_data("hod");
                $display['select_logtype'] = $this->tm->select_data("logtype", "logtype_name", "hod");
            }
            $display['u_all'] = $this->cm->view_all("user");
            $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
            $update['upd_hod'] = $this->cm->view_all("hod");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['upd_see'] = $this->cm->check_update("demo");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $this->load->view('header', $update);
            $this->load->view('hod', $display);
        }
    }
    public function view_faculty()
    {
        $id = $this->input->post('hod_id');
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $display['select_hod'] = $this->cm->select_data("hod", "hod_id", $id);
        $this->load->view('view_faculty', $display);
    }
    public function view_member()
    {
        $id = $this->input->post('user_id');
        $bbid = $this->cm->select_data("user", "user_id", $id);
        $ball = explode(",", $bbid->branch_ids);
        foreach ($ball as $key => $value) {
            $display['all_mem'] = $this->cm->select_manager("user", "branch_ids", $value);
        }
        // echo "<pre>";
        // print_r($display);
        // die;
        $display['user_name'] = $bbid;
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $display['select_hod'] = $this->cm->select_data("hod", "hod_id", $id);
        $this->load->view('view_manager', $display);
    }
    public function setFacultys()
    {
        $hod_id = $this->input->post('hod_id');
        $faculty_id = $this->input->post('hod_faculty');
        if (!empty($this->input->post('submit'))) {
            $fd = array();
            if (!empty($faculty_id)) {
                foreach ($faculty_id as $m => $n) {
                    $data = $this->cm->select_data("faculty", "faculty_id", $n);
                    $fd[] = $data->name;
                }
            }
            if (is_array($faculty_id)) {
                $ins_data['faculty_id'] = implode(",", $faculty_id);
            } else {
                $ins_data['faculty_id'] = "";
            }
            //  echo "<pre>";
            // print_r($ins_data);
            // die();
            $re = $this->cm->update_data("hod", $ins_data, "hod_id", $hod_id);
            if ($re) {
                logdata(implode(" AND ", $fd) . " Faculty Add");
                $display['msg'] = "faculty added Successfully";
            }
        }
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $display['branch_all'] = $this->cm->view_all("branch");
        $display['hod_all'] = $this->cm->view_all_hods("hod");
        $display['faculty_all'] = $this->cm->view_all("faculty");
        $display['permission_all'] = $this->cm->view_all("permission");
        $display['user_all'] = $this->cm->Role_all_admin("user");
        $update['aa_branch'] = $this->cm->filter_data("branch", "admin_id", $_SESSION['admin_id']);
        $update['upd_hod'] = $this->cm->view_all("hod");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['upd_see'] = $this->cm->check_update("demo");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        if ($_SESSION['logtype'] == "Super Admin") {
            $display['select_logtype'] = $this->tm->select_data_fact_permission("logtype", "logtype_name", "hod");
        } else {
            $display['select_logtype'] = $this->tm->select_data("logtype", "logtype_name", "hod");
        }
        $this->load->view('header', $update);
        $this->load->view('hod', $display);
    }
    public function setManagers()
    {
        $user_id = $this->input->post('user_id');
        $faculty_id = $this->input->post('manager');
        if (!empty($this->input->post('submit'))) {
            // echo "<pre>";
            // print_r($faculty_id);
            // print_r($this->input->post());
            // die;
            if (is_array($faculty_id)) {
                $ins_data['parent_id'] = implode(",", $faculty_id);
            } else {
                $ins_data['parent_id'] = "";
            }
            //  echo "<pre>";
            // print_r($ins_data);
            // die();
            $re = $this->cm->update_data("user", $ins_data, "user_id", $user_id);
            if ($re) {
                $this->session->set_flashdata('msg', 'Manager added Successfully');
                return redirect('settings/user');
            }
        }
    }
    public function fetch_all_branch()
    {
        $b_all = $this->input->post('id');
        $b = $this->cm->filter_data("branch", "admin_id", $b_all);
?>
        <table border="1">
            <?php foreach ($b as $k => $n) { ?>
                <tr>
                    <td class="form-control">


                        <input class="filterCheck" type="checkbox" name="b_ids[]" value="<?php echo $n->branch_id; ?>"><?php echo $n->branch_name; ?>

                    </td>
                </tr>
            <?php
            } ?>
        </table>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.filterCheck').change(function() {
                    /*console.log("called");
                    console.log($('#filterForm').serialize());*/
                    // alert($('#filterForm').serialize());
                    $.ajax({
                        type: 'POST',
                        data: $('#filterForm').serialize(),
                        url: "<?php echo base_url(); ?>settings/fetch_all_department",
                        success: function(data) {
                            $('#department').html(data);
                        }
                    });


                    $.ajax({
                        type: 'POST',
                        data: $('#filterForm').serialize(),
                        url: "<?php echo base_url(); ?>settings/fetch_all_manager",
                        success: function(data) {
                            $('#manager_id').html(data);
                        }
                    });


                });
            });
        </script>

        <?php
    }
    public function fetch_all_department()
    {
        $b_all = $this->input->post('b_ids');
        if (!empty($b_all)) {
            foreach ($b_all as $key => $value) {
                if (!empty($value)) {
                    $data = $this->cm->filter_data("department", "branch_id", $value);
                    $b = $this->cm->select_data("branch", "branch_id", $value);
                    if (!empty($data)) {
                        $s_name[$b->branch_name] = $data;
                    }
                }
            }
            // echo "<pre>";
            // print_r($s_name);
            // die;

        ?>
            <table border="1">
                <tr>
                    <th>Branch name</th>
                    <th>Department</th>
                </tr>
                <?php
                if (!empty($s_name)) {
                    foreach ($s_name as $k => $v) { ?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td>

                                <?php foreach ($v as $m => $n) { ?>
                                    <input class="tt" type="checkbox" name="depart_ids[]" value="<?php echo $n->department_id; ?>"><?php echo $n->department_name; ?>
                                <?php
                                } ?>

                            </td>
                        </tr>
                <?php
                    }
                } ?>
            </table>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.tt').change(function() {

                        /*console.log("called");
                        console.log($('#filterForm').serialize());*/
                        // alert($('#filterForm').serialize());
                        $.ajax({
                            type: 'POST',
                            data: $('#filterForm').serialize(),
                            url: "<?php echo base_url(); ?>settings/fatch_all_subdepartment",
                            success: function(data) {
                                $('#subdepartment').html(data);
                            }
                        });
                    });
                });
            </script>
        <?php
        }
    }
    public function fatch_all_subdepartment()
    {
        $b_all = $this->input->post('depart_ids');
        if (!empty($b_all)) {
            foreach ($b_all as $key => $value) {
                if (!empty($value)) {
                    $data = $this->cm->filter_data("subdepartment", "department_ids", $value);
                    $b = $this->cm->select_data("department", "department_id", $value);
                    $d = $this->cm->select_data("branch", "branch_id", $b->branch_id);
                    if (!empty($data)) {
                        $s_name[$d->branch_name][$b->department_name] = $data;
                    }
                }
            }
        ?>
            <table border="1">
                <tr>
                    <th>Branch</th>
                    <th>Department name</th>
                    <th>SubDepartment</th>
                </tr>
                <?php foreach ($s_name as $k => $v) { ?>
                    <tr>
                        <td><?php echo $k; ?></td>
                        <?php foreach ($v as $key => $val) { ?>
                            <td><?php echo $key; ?></td>
                            <td>
                                <?php foreach ($val as $m => $n) { ?>
                                    <input class="ts" onclick="faculty(this.id)" id="subdepartment_id<?php echo $m; ?>" type="radio" name="subdepartment_id" value="<?php echo $n->subdepartment_id; ?>"><?php echo $n->subdepartment_name; ?>
                                <?php
                                } ?>
                            </td>

                        <?php
                        } ?>
                    </tr>
                <?php
                } ?>
            </table>
            <script>
                function faculty(id) {

                    var f_id = $('#' + id).val();


                    if (f_id != '') {
                        $.ajax({
                            url: "<?php echo base_url(); ?>settings/fetch_faculty_alll",
                            method: "POST",
                            data: {
                                f: f_id
                            },
                            success: function(data) {
                                $('#faculty_id').html(data);

                            }
                        });
                    }

                }
            </script>

        <?php
        }
    }
    public function fetch_all_manager()
    {
        $b_all = $this->input->post('b_ids');
        foreach ($b_all as $key => $value) {
            $data = $this->cm->filter_data("user", "branch_ids", $value);
        }
        // echo "<pre>";
        // print_r($data);

        ?>
        <select name="manager_id">
            <option value="0">----SELECT MANAGER---------</option>
            <?php foreach ($data as $k => $v) {
                if ($v->logtype == "Manager") { ?>
                    <option value="<?php echo $v->user_id; ?>"><?php echo $v->name; ?></option>
            <?php
                }
            } ?>
        </select>
    <?php
    }
    public function fetch_faculty_alll()
    {
        // print_r($this->input->post('b'));
        // die;
        $fac_id = $this->input->post('f');
        $all = $this->cm->select_data("subdepartment", "subdepartment_id", $fac_id);
        print_r($all);
        $data['branch_ids'] = $all->branch_id;
        $data['department_id'] = $all->department_ids;
        $data['subdepartment_id'] = $fac_id;
        // $data['state_id'] = $s_name->state_id;
        // $data['city_id'] = $s_name->city_id;
        $faculty_all = $this->cm->filter_data_all_where("faculty", $data);
        // print_r($city_all);
        //  die;

    ?>
        <div class="input-group">
            <select class="form-control select2" required name="faculty_id" style="width: 100%;">
                <option value="">Select Faculty</option>
                <?php foreach ($faculty_all as $row) { ?>
                    <option value="<?php echo $row->faculty_id; ?>" <?php if (isset($m_all)) {
                                                                        if ($row->faculty_id == $m_all->faculty_id) {
                                                                            echo "selected";
                                                                        }
                                                                    } ?>><?php echo $row->name; ?></option>
                <?php
                } ?>
            </select>

        </div>
        <?php
    }
    public function adminWise_branch()
    {
        if ($this->input->post('admin_id')) {
            echo $this->cm->fetch_adminWise_branch($this->input->post('admin_id'));
        }
    }
    public function ajax_department()
    {
        $data = $this->input->post();
        $b_id = $data['branch_id'];
        $department = $this->admi->view_all('department');
        foreach ($department as $dn) {
            $flag = 0;
            $dnbi = explode(',', $dn['branch_id']);
            for ($i = 0; $i < sizeof($b_id); $i++) {
                if (in_array($b_id[$i], $dnbi)) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
        ?>
                <option value="<?php echo $dn['department_id']; ?>"><?php echo $dn['department_name']; ?></option>
            <?php
            }
        }
    }
    public function fetch_admin_wise_department()
    {
        $data = $this->input->post();
        $b_id = $data['branch_id'];
        $department = $this->admi->view_all('department');
        foreach ($department as $dn) {
            $flag = 0;
            $dnbi = explode(',', $dn['branch_id']);
            for ($i = 0; $i < sizeof($b_id); $i++) {
                if (in_array($b_id[$i], $dnbi)) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
            ?>
                <option value="<?php echo $dn['department_id']; ?>"><?php echo $dn['department_name']; ?></option>
        <?php
            }
        }
    }
    public function fetch_subdepartment()
    {
        if ($this->input->post('department_ids')) {
            echo $this->cm->fetch_subdepartment($this->input->post('department_ids'));
        }
    }
    public function fetch_depatment_wise_package()
    {
        if ($this->input->post('department_id')) {
            echo $this->cm->depatment_wise_package($this->input->post('department_id'));
        }
    }
    public function fetch_department_wise_course()
    {
        if ($this->input->post('department_id')) {
            echo $this->cm->department_wise_course($this->input->post('department_id'));
        }
    }
    public function fetch_faculty()
    {
        if ($this->input->post('subdepartment_id')) {
            echo $this->cm->fetch_faculty($this->input->post('subdepartment_id'));
        }
    }
    function select_single_course()
    {
        $faculty_id = $this->input->post('faculty_id');
        $result['selectdata'] = $this->cm->select_data("faculty", "faculty_id", $faculty_id);
        echo json_encode($result);
    }
    function main_menu()
    {
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['upd_see'] = $this->cm->check_update("demo");
        $this->load->view('header', $update);
        $this->load->view('settings/main_menu', $display);
        $this->load->view('f_footer');
    }
    public function ad_menu()
    {
        $data = $this->input->post();
        $ins_data['f_module_name'] = $data['menu'];
        $ins_data['f_module_icon'] = $data['icon'];
        if (!empty($this->input->post('m_id'))) {
            $id = $this->input->post('m_id');
            $re = $this->cm->update_data("f_module", $ins_data, "f_module_id", $id);
        } else {
            $re = $this->cm->insert_data("f_module", $ins_data);
        }
        $falldata = $this->cm->view_all("f_module");
        foreach ($falldata as $key => $value) {
            $fall[] = $value->f_module_name;
        }
        $fdata['f_permission'] = implode(",", $fall);
        $this->cm->update_data("admin", $fdata, "id", $_SESSION['user_id']);
        if ($re) {
            redirect('settings/main_menu');
        }
    }
    public function de_menu($id)
    {
        $m = $this->cm->delete_data("f_module", "f_module_id", $id);
        if ($m) {
            redirect('settings/main_menu');
        }
    }
    public function up_menu($id)
    {
        $display['m_all'] = $this->cm->select_data("f_module", "f_module_id", $id);
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['upd_see'] = $this->cm->check_update("demo");
        $this->load->view('header', $update);
        $this->load->view('settings/main_menu', $display);
    }
    public function change_s($s, $id)
    {
        if ($s == 0) {
            $all['status'] = 1;
        } else {
            $all['status'] = 0;
        }
        $re = $this->cm->update_data("f_module", $all, "f_module_id", $id);
        if ($re) {
            redirect('settings/main_menu');
        }
    }
    public function upd_s()
    {
        $data = $this->input->post();
        if ($data['action'] == "d") {
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->delete_data("f_module", "f_module_id", $value);
            }
        } else if ($data['action'] == "a") {
            $all['status'] = 1;
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->update_data("f_module", $all, "f_module_id", $value);
            }
        } else {
            $all['status'] = 0;
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->update_data("f_module", $all, "f_module_id", $value);
            }
        }
        if ($m) {
            redirect('settings/main_menu');
        }
    }
    function mid_menu()
    {
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['upd_see'] = $this->cm->check_update("demo");
        $this->load->view('header', $update);
        $this->load->view('settings/mid_menu', $display);
        $this->load->view('f_footer');
    }
    public function ad_mid_menu()
    {
        $data = $this->input->post();
        $ins_data['f_module_id'] = $data['f_m_id'];
        $ins_data['module_name'] = $data['menu'];
        $ins_data['module_icon'] = $data['icon'];
        if (!empty($this->input->post('m_id'))) {
            $id = $this->input->post('m_id');
            $re = $this->cm->update_data("m_module", $ins_data, "m_module_id", $id);
        } else {
            $re = $this->cm->insert_data("m_module", $ins_data);
        }
        $falldata = $this->cm->view_all("m_module");
        foreach ($falldata as $key => $value) {
            $fall[] = $value->module_name;
        }
        $fdata['m_permission'] = implode(",", $fall);
        $this->cm->update_data("admin", $fdata, "id", $_SESSION['user_id']);
        if ($re) {
            redirect('settings/mid_menu');
        }
    }
    public function m_de_menu($id)
    {
        $m = $this->cm->delete_data("m_module", "m_module_id", $id);
        if ($m) {
            redirect('settings/mid_menu');
        }
    }
    public function m_up_menu($id)
    {
        $display['m_all'] = $this->cm->select_data("m_module", "m_module_id", $id);
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['upd_see'] = $this->cm->check_update("demo");
        $this->load->view('header', $update);
        $this->load->view('settings/mid_menu', $display);
    }
    public function m_change_s($s, $id)
    {
        if ($s == 0) {
            $all['status'] = 1;
        } else {
            $all['status'] = 0;
        }
        $re = $this->cm->update_data("m_module", $all, "m_module_id", $id);
        if ($re) {
            redirect('settings/mid_menu');
        }
    }
    public function m_upd_s()
    {
        $data = $this->input->post();
        if ($data['action'] == "d") {
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->delete_data("m_module", "m_module_id", $value);
            }
        } else if ($data['action'] == "a") {
            $all['status'] = 1;
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->update_data("m_module", $all, "m_module_id", $value);
            }
        } else {
            $all['status'] = 0;
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->update_data("m_module", $all, "m_module_id", $value);
            }
        }
        if ($m) {
            redirect('settings/mid_menu');
        }
    }
    function las_menu()
    {
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['upd_see'] = $this->cm->check_update("demo");
        $this->load->view('header', $update);
        $this->load->view('settings/las_menu', $display);
        $this->load->view('f_footer');
    }
    public function ad_las_menu()
    {
        $data = $this->input->post();
        $ins_data['m_module_id'] = $data['f_m_id'];
        $ins_data['name'] = $data['menu'];
        $ins_data['icon'] = $data['icon'];
        $ins_data['controller'] = $data['controller'];
        $ins_data['method'] = $data['method'];
        if (!empty($this->input->post('m_id'))) {
            $id = $this->input->post('m_id');
            $re = $this->cm->update_data("l_module", $ins_data, "l_module_id", $id);
        } else {
            $re = $this->cm->insert_data("l_module", $ins_data);
        }
        $falldata = $this->cm->view_all("l_module");
        foreach ($falldata as $key => $value) {
            $fall[] = $value->name;
        }
        $fdata['permission'] = implode(",", $fall);
        $this->cm->update_data("admin", $fdata, "id", $_SESSION['user_id']);
        if ($re) {
            redirect('settings/las_menu');
        }
    }
    public function l_de_menu($id)
    {
        $m = $this->cm->delete_data("l_module", "l_module_id", $id);
        if ($m) {
            redirect('settings/las_menu');
        }
    }
    public function l_up_menu($id)
    {
        $display['m_all'] = $this->cm->select_data("l_module", "l_module_id", $id);
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        $update['m_module'] = $this->cm->view_all("m_module");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['l_module'] = $this->cm->view_all("l_module");
        $update['upd_see'] = $this->cm->check_update("demo");
        $this->load->view('header', $update);
        $this->load->view('settings/las_menu', $display);
    }
    public function l_change_s($s, $id)
    {
        if ($s == 0) {
            $all['status'] = 1;
        } else {
            $all['status'] = 0;
        }
        $re = $this->cm->update_data("l_module", $all, "l_module_id", $id);
        if ($re) {
            redirect('settings/las_menu');
        }
    }
    public function l_upd_s()
    {
        $data = $this->input->post();
        if ($data['action'] == "d") {
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->delete_data("l_module", "l_module_id", $value);
            }
        } else if ($data['action'] == "a") {
            $all['status'] = 1;
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->update_data("l_module", $all, "l_module_id", $value);
            }
        } else {
            $all['status'] = 0;
            foreach ($data['id_all'] as $key => $value) {
                $m = $this->cm->update_data("l_module", $all, "l_module_id", $value);
            }
        }
        if ($m) {
            redirect('settings/las_menu');
        }
    }
    public function fetch_cities()
    {
        $s_id = $this->input->post('s_id');
        $s_name = $this->cm->select_data("state", "state_id", $s_id);
        $city_all = $this->cm->filter_data("cities", "city_state", $s_name->state_name);
        ?>
        <div class="input-group">
            <select class="form-control select2" required name="city_id" style="width: 100%;">
                <option value="0">----Select City---</option>
                <?php foreach ($city_all as $row) { ?>
                    <option value="<?php echo $row->city_id; ?>" <?php if (isset($m_all)) {
                                                                        if ($row->city_id == $m_all->city_id) {
                                                                            echo "selected";
                                                                        }
                                                                    } ?>><?php echo $row->city_name; ?></option>
                <?php
                } ?>
            </select>

        </div>
        <?php
    }
    public function permission_all_data()
    {
        logdata("Permission page open");
        $display['department_all'] = $this->cm->view_all("department");
        $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
        if ($this->input->get('search')) {
            $search = $this->input->get('search');
            if ($_SESSION['logtype'] == "Super Admin") {
                $display['log_all'] = $this->cm->filterByMainPermission("logtype", "parent_id", "0", $search);
            } else {
                $display['log_all'] = $this->tm->filterByMainPermission("logtype", $search);
            }
        } else {
            if ($_SESSION['logtype'] == "Super Admin") {
                $display['log_all'] = $this->cm->filter_data("logtype", "parent_id", "0");
            } else {
                $display['log_all'] = $this->tm->view_all("logtype");
            }
        }
        //$display['log_all'] = $this->tm->view_all("logtype");
        $update['upd_faculty'] = $this->cm->view_all("faculty");
        $update['upd_branch'] = $this->cm->view_all("branch");
        $update['f_module'] = $this->cm->view_all("f_module");
        $update['m_module'] = $this->cm->all_mod_data("m_module", "f_module", "f_module_id");
        // echo "<pre>";
        // print_r($update['m_module']);
        $update['l_module'] = $this->cm->all_mod_data("l_module", "m_module", "m_module_id");
        $update['upd_see'] = $this->cm->check_update("demo");
        $this->load->view('header_test', $update);
        $this->load->view('settings/permision', $display);
        $this->load->view('footer_test');
    }
    public function upd_permission()
    {
        $data = $this->input->post();
        foreach ($data['fp'] as $j => $q) {
            $b['f_permission'] = implode(",", $q);
            $this->cm->update_data("logtype", $b, "logtype_id", $j);
            $a['f_permission'] = implode(",", $q);
            $m = $this->cm->update_data("logtype", $a, "logtype_id", $j);
            // if($_SESSION['logtype'] != "Super Admin"){
            $allogdata = $this->cm->select_data("logtype", "logtype_id", $j);
            if ($allogdata->logtype_name == "Faculty") {
                $this->cm->filter_data_all_permisson_user("faculty", $a, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            } else if ($allogdata->logtype_name == "hod") {
                $this->cm->filter_data_all_permisson_user("hod", $a, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            } else {
                $this->cm->filter_data_all_permisson_user("user", $a, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            }
            //}
            logdata($a['f_permission'] . " Permission Assign " . $allogdata->logtype_name);
        }
        foreach ($data['m_all'] as $j => $q) {
            $d['m_permission'] = implode(",", $q);
            $this->cm->update_data("logtype", $d, "logtype_id", $j);
            $c['m_permission'] = implode(",", $q);
            $m = $this->cm->update_data("logtype", $c, "logtype_id", $j);
            //if($_SESSION['logtype'] != "Super Admin"){
            $allogdata = $this->cm->select_data("logtype", "logtype_id", $j);
            if ($allogdata->logtype_name == "Faculty") {
                $this->cm->filter_data_all_permisson_user("faculty", $c, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            } else if ($allogdata->logtype_name == "hod") {
                $this->cm->filter_data_all_permisson_user("hod", $c, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            } else {
                $this->cm->filter_data_all_permisson_user("user", $c, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            }
            //}
            logdata($c['m_permission'] . " Permission Assign " . $allogdata->logtype_name);
        }
        foreach ($data['f_all'] as $j => $q) {
            $x['permission'] = implode(",", $q);
            $this->cm->update_data("logtype", $x, "logtype_id", $j);
            $y['permission'] = implode(",", $q);
            $m = $this->cm->update_data("logtype", $y, "logtype_id", $j);
            //if($_SESSION['logtype'] != "Super Admin"){
            $allogdata = $this->cm->select_data("logtype", "logtype_id", $j);
            if ($allogdata->logtype_name == "Faculty") {
                $this->cm->filter_data_all_permisson_user("faculty", $y, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            } else if ($allogdata->logtype_name == "hod") {
                $this->cm->filter_data_all_permisson_user("hod", $y, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            } else {
                $this->cm->filter_data_all_permisson_user("user", $y, "admin_id", $_SESSION['admin_id'], "logtype", $allogdata->logtype_name);
            }
            //}
            logdata($y['permission'] . " Permission Assign " . $allogdata->logtype_name);
        }
        return redirect('Settings/permission_all_data');
    }
    public function upd_personal_permission()
    {
        $data = $this->input->post();
        foreach ($data['fp'] as $j => $q) {
            $a['f_permission'] = implode(",", $q);
            $uname = $this->cm->select_data("faculty", "faculty_id", $j);
            if ($uname->f_permission != $uname->f_permission) {
                logdata($a['f_permission'] . " Permission Assign Faculty " . $uname->name);
            }
            $this->cm->update_data("faculty", $a, "faculty_id", $j);
        }
        foreach ($data['m_all'] as $x => $y) {
            $c['m_permission'] = implode(",", $y);
            $uname = $this->cm->select_data("faculty", "faculty_id", $x);
            if ($uname->m_permission != $c['m_permission']) {
                logdata($c['m_permission'] . " Permission Assign Faculty " . $uname->name);
            }
            $this->cm->update_data("faculty", $c, "faculty_id", $x);
        }
        foreach ($data['f_all'] as $w => $z) {
            $b['permission'] = implode(",", $z);
            $uname = $this->cm->select_data("faculty", "faculty_id", $w);
            if ($uname->permission != $b['permission']) {
                logdata($b['permission'] . " Permission Assign Faculty " . $uname->name);
            }
            $this->cm->update_data("faculty", $b, "faculty_id", $w);
        }
        return redirect('Settings/personal_permission_all_data');
    }
    public function filter_branch_wise()
    {
        $id = $this->input->post('id');
        $data['branch'] = $this->cm->filter_data("branch", "admin_id", $id);
        $this->load->view('ajax_filter_barnch', $data);
    }
    // public function filter_department_wise_direct_course()
    // {
    //     $data = $this->input->post();
    //     $course_record = $this->admi->get_course_record('course');
    //     $all_record = array();
    //     foreach ($course_record as $val) {
    //         $course_r = explode(',', $val->branch_id);
    //         if (in_array($data['branch_id'], $course_r)) {
    //             $all_record[] = $val;
    //         }
    //     }
    //     // print_r($all_record);
    //     // exit;
    // }
    public function filter_department_wise()
    {
        $data = $this->input->post();
        // echo "<pre>";
        // print_r($data);
        // exit;
        $b_id = $data['branch_id'];
        $department = $this->admi->view_all('department');
        foreach ($department as $dn) {
            $flag = 0;
            $dnbi = explode(',', $dn['branch_id']);
            for ($i = 0; $i < sizeof($b_id); $i++) {
                if (in_array($b_id[$i], $dnbi)) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
        ?>
                <option 
                    value="<?php echo $dn['department_id']; ?>"
                    <?php
                         if (isset($data['selected']) && in_array($dn['department_id'], explode(",", $data['selected']))) {
                            echo "selected";
                        } 
                    ?>
                ><?php echo $dn['department_name']; ?></option>
            <?php
            }
        }
    }
    public function filter_subdepartment_wise()
    {
        $b_all = $this->input->post('department_id');
        if (!empty($b_all)) {
            foreach ($b_all as $key => $value) {
                if (!empty($value)) {
                    $data = $this->cm->filter_data("subdepartment", "department_ids", $value);
                    $b = $this->cm->select_data("department", "department_id", $value);
                    $d = $this->cm->select_data("branch", "branch_id", $b->branch_id);
                    if (!empty($data)) {
                        $s_name[$d->branch_name][$b->department_name] = $data;
                    }
                }
            }
        }
        $data['subdepartment'] = $s_name;
        $this->load->view('ajax_filter_subdepartments', $data);
    }
    public function filter_subdepartment_wise_hod()
    {
        $data = $this->input->post();
        $sid = $data['subdepartment_id'];
        $hod = $this->admi->view_all('hod');
        foreach ($hod as $dn) {
            $flag = 0;
            $dnbi = explode(',', $dn['subdepartment_id']);
            for ($i = 0; $i < sizeof($sid); $i++) {
                if (in_array($sid[$i], $dnbi)) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
            ?>
                <option value="<?php echo $dn['hod_id']; ?>"><?php echo $dn['name']; ?></option>
            <?php
            }
        }
    }
    public function filter_subdeprt_wise_manager()
    {
        $data = $this->input->post();
        $sid = $data['subdepartment_ids'];
        $manager = $this->cm->view_Manager_record('user');
        foreach ($manager as $dn) {
            $flag = 0;
            $dnbi = explode(',', $dn['subdepartment_ids']);
            for ($i = 0; $i < sizeof($sid); $i++) {
                if (in_array($sid[$i], $dnbi)) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
            ?>
                <option value="<?php echo $dn['user_id']; ?>"><?php echo $dn['name']; ?></option>
            <?php
            }
        }
    }
    public function filter_branch_wise_course_single()
    {
        $data = $this->input->post();
        $b_id = $data['branch_id'];
        $course = $this->admi->view_all('course');
        foreach ($course as $co) {
            $flag = 0;
            $dnbi = explode(',', $co['branch_id']);
            for ($i = 0; $i < sizeof($b_id); $i++) {
                if (in_array($b_id[$i], $dnbi)) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
            ?>
                <option value="<?php echo $co['course_id']; ?>"><?php echo $co['course_name']; ?></option>
            <?php
            }
        }
    }
    public function filter_branch_wise_package()
    {
        $data = $this->input->post();
        $b_id = $data['branch_id'];
        $package = $this->admi->view_all('package');
        foreach ($package as $pac) {
            $flag = 0;
            $dnbi = explode(',', $pac['branch_id']);
            for ($i = 0; $i < sizeof($b_id); $i++) {
                if (in_array($b_id[$i], $dnbi)) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
            ?>
                <option value="<?php echo $pac['package_id']; ?>"><?php echo $pac['package_name']; ?></option>
        <?php
            }
        }
    }
    public function admin_Wise_branchFetch()
    {
        $b_all = $this->input->post('id');
        $b = $this->cm->filter_data("branch", "admin_id", $b_all);
        ?>
        <table border="1" class="table">
            <?php foreach ($b as $k => $n) { ?>
                <tr>
                    <td class="form-control">


                        <input class="filterCheck" type="checkbox" name="b_ids[]" value="<?php echo $n->branch_id; ?>"><?php echo $n->branch_name; ?>

                    </td>
                </tr>
            <?php
            } ?>
        </table>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.filterCheck').change(function() {
                    /*console.log("called");
                    console.log($('#filterForm').serialize());*/
                    // alert($('#filterForm').serialize());
                    $.ajax({
                        type: 'POST',
                        data: $('#filterForm').serialize(),
                        url: "<?php echo base_url(); ?>settings/fetch_depart_alll",
                        success: function(data) {
                            $('#department').html(data);
                        }
                    });
                });
            });
        </script>

        <?php
    }
    public function fetch_depart_alll()
    {
        $b_all = $this->input->post('b_ids');
        if (!empty($b_all)) {
            foreach ($b_all as $key => $value) {
                if (!empty($value)) {
                    $data = $this->cm->filter_data("department", "branch_id", $value);
                    $b = $this->cm->select_data("branch", "branch_id", $value);
                    if (!empty($data)) {
                        $s_name[$b->branch_name] = $data;
                    }
                }
            }
        ?>
            <table border="1" class="table">
                <tr>
                    <th>Branch name</th>
                    <th>Department</th>
                </tr>
                <?php
                if (!empty($s_name)) {
                    foreach ($s_name as $k => $v) { ?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td>
                                <?php foreach ($v as $m => $n) { ?>
                                    <input class="tt" type="checkbox" name="depart_ids[]" value="<?php echo $n->department_id; ?>"><?php echo $n->department_name; ?>
                                <?php
                                } ?>

                            </td>
                        </tr>
                <?php
                    }
                } ?>
            </table>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.tt').change(function() {

                        /*console.log("called");
                        console.log($('#filterForm').serialize());*/
                        // alert($('#filterForm').serialize());
                        $.ajax({
                            type: 'POST',
                            data: $('#filterForm').serialize(),
                            url: "<?php echo base_url(); ?>settings/fetch_subdepart_alll",
                            success: function(data) {
                                $('#subdepartment').html(data);
                            }
                        });
                    });
                });
            </script>
        <?php
        }
    }
    public function fetch_permission_alll()
    {
        //echo "<pre>";
        
        $logtype_name = $this->input->post('logtype_name');
        $f_permission = $this->input->post('f_permission');
        $mdata = $this->input->post();
        $permission = $this->input->post('permission');

        

        $ldata = $this->input->post();
        $f_module = $this->cm->view_all("f_module");
        $m_module = $this->cm->view_all("m_module");
        $l_module = $this->cm->view_all("l_module");

        $logdata = $this->cm->select_data("logtype", "logtype_name", $logtype_name);

        $permissiondata = [];
        if (isset($ldata['update_id'])) {
            $permissiondata = $this->cm->select_data("user", "user_id", $ldata['update_id']);
        }

        // print_r($permissiondata->f_permission);
        // exit;

        //$logdata = $this->cm->select_data("logtype", "logtype_id", $logtype_id);
        
        // echo "<pre>";
        // print_r($ldata);
        // exit;
        ?>

        <?php foreach ($f_module as $key => $value) { ?>
            <div class="form-group  col-md-4 col-sm-12">
                <h6 class="text-dark">
                    <?php echo $value->f_module_name . " "; ?>
                    <input type="checkbox"  class="fp" value="<?php echo $value->f_module_name; ?>" id="<?php echo $value->f_module_name; ?>" name="fp[]" 
                        <?php if (isset($ldata['f_permission']) && in_array($value->f_module_name, explode(",", $ldata['f_permission']))) {
                            echo "checked";
                        } else if (isset($logdata->f_permission) && in_array($value->f_module_name, explode(",", $logdata->f_permission))) {
                            echo "checked";
                        }  else if (isset($permissiondata->f_permission) && in_array($value->f_module_name, explode(",", $permissiondata->f_permission))) {
                            echo "checked";
                        } ?>
                    />
                </h6>
            
                <?php foreach ($m_module as $m) {
                    if ($m->f_module_id == $value->f_module_id) {
                ?>
                        <label class="ml-4 text-dark" for="pwd"><?php echo $m->module_name; ?> 
                        <input class="m_all" type="checkbox" name="m_all[]" value="<?php echo $m->module_name; ?>" 
                            <?php if (isset($ldata['m_permission']) && in_array($m->module_name, explode(",", $ldata['m_permission']))) {
                                    echo "checked";
                                } else if (isset($logdata->m_permission) && in_array($m->module_name, explode(",", $logdata->m_permission))) {
                                    echo "checked";
                                }  else if (isset($permissiondata->m_permission) && in_array($m->module_name, explode(",", $permissiondata->m_permission))) {
                                    echo "checked";
                                }   ?>>:</label>
                        <br>
                        <div style="margin-left: 40px;">
                        <?php foreach ($l_module as $k => $l) {
                            if ($l->m_module_id == $m->m_module_id) {
                        ?>
                                
                                <label style="width:70%;font-weight: normal;"><?php echo $l->name; ?> </label>
                                <label class="radio-inline">
                                    <input type="checkbox" class="f_all" name="f_all[]" value="<?php echo $l->name; ?>" 
                                        <?php if (isset($ldata['permission']) && in_array($l->name, explode(",", $ldata['permission']))) {
                                            echo "checked";
                                        } else if (isset($logdata->permission) && in_array($l->name, explode(",", $logdata->permission))) {
                                            echo "checked";
                                        }  else if (isset($permissiondata->permission) && in_array($l->name, explode(",", $permissiondata->permission))) {
                                            echo "checked";
                                        }   ?>> Yes
                                </label>

                                <br>
                                
            <?php
                            }
                        }
                        echo "</div>";
                    }
                }echo "</div>";
            } ?>
            <?php
        }

        public function fetch_subdepart_alll()
        {
            // echo "<pre>";
            // print_r($this->input->post('depart_ids'));
            // die;
            $b_all = $this->input->post('depart_ids');
            if (!empty($b_all)) {
                foreach ($b_all as $key => $value) {
                    if (!empty($value)) {
                        $data = $this->cm->filter_data("subdepartment", "department_ids", $value);
                        $b = $this->cm->select_data("department", "department_id", $value);
                        $d = $this->cm->select_data("branch", "branch_id", $b->branch_id);
                        if (!empty($data)) {
                            $s_name[$d->branch_name][$b->department_name] = $data;
                        }
                    }
                }
                // echo "<pre>";
                // print_r($s_name);
                // die;
                if (isset($s_name) && !empty($s_name)) {
            ?>
                    <table border="1" class="table">
                        <tr>
                            <th>Branch</th>
                            <th>Department name</th>
                            <th>SubDepartment</th>
                        </tr>
                        <?php
                        $i = 0;
                        foreach ($s_name as $k => $v) { ?>
                            <tr>
                                <td><?php echo $k; ?></td>
                                <?php foreach ($v as $key => $val) { ?>
                                    <td><?php echo $key; ?></td>
                                    <td>
                                        <?php foreach ($val as $m => $n) { ?>
                                            <input class="ts" onclick="demo(this.id)" id="subdepartment_id<?php echo $n->subdepartment_id; ?>" type="radio" name="subdepart_ids<?php echo $i; ?>" value="<?php echo $n->subdepartment_id; ?>"><?php echo $n->subdepartment_name; ?>
                                        <?php
                                        } ?>

                                    </td>
                                <?php
                                } ?>
                            </tr>
                        <?php $i++;
                        } ?>
                    </table>
                    <script>
                        function demo(id) {

                            var d_id = $('#' + id).val();

                            if (d_id != '') {
                                $.ajax({
                                    url: "<?php echo base_url(); ?>settings/fetch_extradepart_alll",
                                    method: "POST",
                                    data: {
                                        d: d_id
                                    },
                                    success: function(data) {
                                        $('#extradepartment').html(data);
                                        // $('#city').html('<option value="">Select City</option>');
                                    }
                                });


                                $.ajax({
                                    url: "<?php echo base_url(); ?>settings/fetch_hod_alll",
                                    method: "POST",
                                    data: {
                                        d: d_id
                                    },
                                    success: function(data) {
                                        $('#hod_id').html(data);
                                        // $('#city').html('<option value="">Select City</option>');
                                    }
                                });

                            }
                            // else
                            // {
                            //  $('#subdepartment').html('<option value="">Select Sub Department</option>');
                            //  // $('#city').html('<option value="">Select City</option>');
                            // }

                        }
                    </script>

            <?php
                }
            }
        }
        public function fetch_extradepart_alll()
        {
            $b_all = $this->input->post('d');
            $all = $this->cm->filter_data("extradepartment", "subdepartment_ids", $b_all);
            $mall = array();
            foreach ($all as $key => $value) {
                $jall = $this->cm->select_data("subdepartment", "subdepartment_id", $value->subdepartment_ids);
                $mall[$jall->subdepartment_name][$key] = $value;
            }
            ?>
            <table border="1" class="table">
                <tr>
                    <th>Department name</th>
                    <th>ExtraDepartment</th>
                </tr>
                <?php foreach ($mall as $k => $v) { ?>
                    <tr>
                        <td><?php echo $k; ?></td>
                        <td>

                            <?php foreach ($v as $m => $n) { ?>
                                <input class="ts" id="extra_id<?php echo $m; ?>" type="radio" name="extra_id" value="<?php echo $n->extra_id; ?>"><?php echo $n->extradepartment_name; ?>
                            <?php
                            } ?>

                        </td>
                    </tr>
                <?php
                } ?>
            </table>
        <?php
        }
        public function fetch_hod_alll()
        {
            // print_r($this->input->post('b'));
            // die;
            $s_id = $this->input->post('d');
            $all = $this->cm->select_data("subdepartment", "subdepartment_id", $s_id);
            // print_r($all);
            $data['branch_ids'] = $all->branch_id;
            $data['department_ids'] = $all->department_ids;
            $data['subdepartment_id'] = $s_id;
            $fdata['branch_ids'] = $all->branch_id;
            $fdata['department_id'] = $all->department_ids;
            $fdata['subdepartment_id'] = $s_id;
            // $data['state_id'] = $s_name->state_id;
            // $data['city_id'] = $s_name->city_id;
            $city_all = $this->cm->filter_data_all_where("hod", $data);
            $faculty_all = $this->cm->filter_data_all_where("faculty", $fdata);
            // echo "<pre>";
            // print_r($faculty_all);
            // die;

        ?>
            <div class="input-group">
                <select class="form-control select2" required name="hod_id" style="width: 100%;">
                    <option value="">Select HOD</option>
                    <?php foreach ($city_all as $row) { ?>
                        <option value="<?php echo $row->name; ?>" <?php if (isset($m_all)) {
                                                                        if ($row->name == $m_all->name) {
                                                                            echo "selected";
                                                                        }
                                                                    } ?>><?php echo $row->name; ?></option>
                    <?php
                    } ?>
                    <?php foreach ($faculty_all as $row) { ?>
                        <option value="<?php echo $row->name; ?>" <?php if (isset($m_all)) {
                                                                        if ($row->name == $m_all->name) {
                                                                            echo "selected";
                                                                        }
                                                                    } ?>><?php echo $row->name; ?></option>
                    <?php
                    } ?>
                </select>

            </div>
        <?php
        }
        public function personal_permission_all_data()
        {
            logdata("Faculty permission page open");
            $display['department_all'] = $this->cm->view_all("department");
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['log_all'] = $this->tm->view_all("logtype");
            if ($this->input->get('search')) {
                $search = $this->input->get('search');
                $display['faculty_all'] = $this->tm->filterByFaculty("faculty", $search);
            } else {
                $display['faculty_all'] = $this->tm->view_all("faculty");
            }
            if ($_SESSION['logtype'] == "Super Admin") {
                $display['select_logtype'] = $this->tm->select_data_fact_permission("logtype", "logtype_name", "Faculty");
            } else {
                $display['select_logtype'] = $this->tm->select_data("logtype", "logtype_name", "Faculty");
            }
            $update['upd_faculty'] = $this->tm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->all_mod_data("m_module", "f_module", "f_module_id");
            $update['l_module'] = $this->cm->all_mod_data("l_module", "m_module", "m_module_id");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header_test', $update);
            $this->load->view('settings/personal_permission', $display);
            $this->load->view('footer_test');
        }
        public function personal_hod_permission_all_data()
        {
            logdata("HOD page open");
            $display['department_all'] = $this->cm->view_all("department");
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            $display['log_all'] = $this->cm->view_all("logtype");
            $update['l_modules'] = $this->cm->view_all("l_module");
            $display['faculty_all'] = $this->cm->view_all("faculty");
            if ($this->input->get('search')) {
                $search = $this->input->get('search');
                $display['hod_all'] = $this->tm->filterByFaculty("hod", $search);
            } else {
                $display['hod_all'] = $this->tm->view_all("hod");
            }
            if ($_SESSION['logtype'] == "Super Admin") {
                $display['select_logtype'] = $this->tm->select_data_fact_permission("logtype", "logtype_name", "HOD");
            } else {
                $display['select_logtype'] = $this->tm->select_data("logtype", "logtype_name", "HOD");
            }
            //$select_logtype=$this->cm->select_data("logtype","logtype_name","HOD");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->all_mod_data("m_module", "f_module", "f_module_id");
            $update['l_module'] = $this->cm->all_mod_data("l_module", "m_module", "m_module_id");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header_test', $update);
            $this->load->view('settings/personal_hod_permission', $display);
            $this->load->view('footer_test');
        }
        public function upd_hod_personal_permission()
        {
            $data = $this->input->post();
            foreach ($data['fp'] as $j => $q) {
                $b['f_permission'] = implode(",", $q);
                $uname = $this->cm->select_data("hod", "hod_id", $j);
                if ($uname->f_permission != $b['f_permission']) {
                    logdata($b['f_permission'] . " Permission Assign HOD " . $uname->name);
                }
                $this->cm->update_data("hod", $b, "hod_id", $j);
            }
            foreach ($data['m_all'] as $j => $q) {
                $c['m_permission'] = implode(",", $q);
                $uname = $this->cm->select_data("hod", "hod_id", $j);
                if ($uname->m_permission != $c['m_permission']) {
                    logdata($c['m_permission'] . " Permission Assign HOD " . $uname->name);
                }
                $this->cm->update_data("hod", $c, "hod_id", $j);
            }
            foreach ($data['f_all'] as $j => $q) {
                $a['permission'] = implode(",", $q);
                $uname = $this->cm->select_data("hod", "hod_id", $j);
                if ($uname->permission != $a['permission']) {
                    logdata($a['permission'] . " Permission Assign HOD " . $uname->name);
                }
                $this->cm->update_data("hod", $a, "hod_id", $j);
            }
            return redirect('Settings/personal_hod_permission_all_data');
        }
        public function personal_user_permission_all_data()
        {
            logdata("Personal user permission page open");
            $display['department_all'] = $this->cm->view_all("department");
            $display['subdepartment_all'] = $this->cm->view_all("subdepartment");
            if ($this->input->get('search')) {
                $search = $this->input->get('search');
                $display['search'] = $this->input->get('search');
                if ($_SESSION['logtype'] == "Super Admin") {
                    $display['log_all'] = $this->cm->filterByMainPermission("logtype", "parent_id", "0", $search);
                } else {
                    $display['log_all'] = $this->tm->filterByMainPermission("logtype", $search);
                }
            } else {
                $display['search'] = null;
                if ($_SESSION['logtype'] == "Super Admin") {
                    $display['log_all'] = $this->cm->filter_data("logtype", "parent_id", "0");
                } else {
                    $display['log_all'] = $this->tm->view_all("logtype");
                }
            }
            $update['l_modules'] = $this->cm->view_all("l_module");
            $display['faculty_all'] = $this->cm->view_all("faculty");
            $display['user_all'] = $this->tm->view_all("user");
            $display['hod_all'] = $this->cm->view_all("hod");
            if ($_SESSION['logtype'] == "Super Admin") {
                $display['filter_all'] = $this->cm->filter_data("logtype", "parent_id", "0");
            } else {
                $display['filter_all'] = $this->tm->view_all("logtype");
            }
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->all_mod_data("m_module", "f_module", "f_module_id");
            $update['l_module'] = $this->cm->all_mod_data("l_module", "m_module", "m_module_id");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header_test', $update);
            $this->load->view('settings/personal_user_permission', $display);
            $this->load->view('footer_test');
        }
        public function upd_user_personal_permission()
        {
            $data = $this->input->post();
            foreach ($data['fp'] as $j => $q) {
                $b['f_permission'] = implode(",", $q);
                $uname = $this->cm->select_data("user", "user_id", $j);
                if ($uname->f_permission != $b['f_permission']) {
                    logdata($b['f_permission'] . " Permission Assign User " . $uname->name);
                }
                $this->cm->update_data("user", $b, "user_id", $j);
            }
            foreach ($data['m_all'] as $m => $n) {
                $s['m_permission'] = implode(",", $n);
                $uname = $this->cm->select_data("user", "user_id", $m);
                if ($uname->m_permission != $s['m_permission']) {
                    logdata($s['m_permission'] . " Permission Assign User " . $uname->name);
                }
                $this->cm->update_data("user", $s, "user_id", $m);
            }
            foreach ($data['f_all'] as $x => $y) {
                $o['permission'] = implode(",", $y);
                $uname = $this->cm->select_data("user", "user_id", $x);
                if ($uname->permission != $o['permission']) {
                    logdata($o['permission'] . " Permission Assign User " . $uname->name);
                }
                $this->cm->update_data("user", $o, "user_id", $x);
            }
            return redirect('Settings/personal_user_permission_all_data');
        }
        public function mailbox()
        {
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header', $update);
            $this->load->view('settings/mailbox');
        }
        public function gmail()
        {
            logdata("User gmail page open");
            $display['all_staffDetails'] = $this->cm->get_remarks_id_wise("user", "status", "0");
            $display['all_hod'] = $this->cm->view_all("hod");
            $display['all_faculty'] = $this->cm->view_all("faculty");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header', $update);
            $this->load->view('settings/gmail', $display);
            //$this->load->view('footer');
            // $this->load->view('header_test',$update);
            // $this->load->view('settings/gmail_test',$display);
            //$this->load->view('footer_test');

        }
        public function gmail_hod()
        {
            logdata("Hod gmail page open");
            //$display['all_staffDetails'] = $this->cm->view_all("user");
            $display['all_hod'] = $this->cm->get_remarks_id_wise("hod", "status", "0");
            $display['all_faculty'] = $this->cm->view_all("faculty");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header', $update);
            $this->load->view('settings/gmail_hod', $display);
            //$this->load->view('footer');
            // $this->load->view('header_test',$update);
            // $this->load->view('settings/gmail_test',$display);
            //$this->load->view('footer_test');

        }
        public function gmail_faculty()
        {
            logdata("Faculty gmail page open");
            //$display['all_staffDetails'] = $this->cm->view_all("user");
            $display['all_hod'] = $this->cm->view_all("hod");
            $display['all_faculty'] = $this->cm->get_remarks_id_wise("faculty", "status", "0");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header', $update);
            $this->load->view('settings/gmail_faculty', $display);
            //$this->load->view('footer');
            // $this->load->view('header_test',$update);
            // $this->load->view('settings/gmail_test',$display);
            //$this->load->view('footer_test');

        }
        public function gmail_placement_company()
        {
            logdata("All member gmail page open");
            //$display['all_staffDetails'] = $this->cm->view_all("user");
            // $display['all_hod'] = $this->cm->get_remarks_id_wise("hod","status","0");
            // $display['all_faculty'] = $this->cm->get_remarks_id_wise("faculty","status","0");
            // $display['all_user'] = $this->cm->get_remarks_id_wise("user","status","0");
            $display['all_super'] = $this->cm->view_all("placement_bulk_email");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header', $update);
            $this->load->view('settings/gmail_placement_company', $display);
            //$this->load->view('footer');
            // $this->load->view('header_test',$update);
            // $this->load->view('settings/gmail_test',$display);
            //$this->load->view('footer_test');

        }
        public function fetch_mail_all_placement_company()
        {
            $all = explode(',', $this->input->post('u_id'));
            //	$after = substr('biohazard@online.ge', strpos('biohazard@online.ge','@')+strlen('@'));
            foreach ($all as $key => $value) {
                $display[] = $this->cm->select_data("placement_bulk_email", "placement_email_id", $value);
                ///$display[] = $this->cm->select_data("faculty","faculty_id",$value);

            }
            // echo "<pre>";
            // print_r($display);
            // die;
            foreach ($display as $k => $v) {
                $vall[] = $v->email;
            }
            //$mail = implode(",", $vall);

        ?>
            <style type="text/css">
                #show_all_email ul {
                    list-style: none;
                    margin: 0;
                    padding: 8px;
                    border: 1px solid #ccc;
                    display: inline-block;
                    width: 540px;
                }

                #show_all_email ul li {
                    float: left;
                    padding: 1px 11px;
                    border: 1px solid #ccc;
                    margin: 5px 7px;
                    border-radius: 22px;
                    background: red;
                    color: #fff;
                }
            </style>

            <ul st><?php foreach ($vall as $m => $n) { ?>
                    <li><?php echo $n; ?></li>
                <?php
                    } ?>
            </ul>



        <?php
        }
        public function placement_company_email_all_data()
        {
            $temp = 0;
            $data = $this->input->post();
            $w = count($_FILES['doc']['name']);
            $imgall = array();
            for ($i = 0; $i < $w; $i++) {
                $image = $_FILES['doc']['name'][$i];
                $_FILES['image']['name'] = $_FILES['doc']['name'][$i];
                $_FILES['image']['type'] = $_FILES['doc']['type'][$i];
                $_FILES['image']['tmp_name'] = $_FILES['doc']['tmp_name'][$i];
                $_FILES['image']['error'] = $_FILES['doc']['error'][$i];
                $_FILES['image']['size'] = $_FILES['doc']['size'][$i];
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "images/mail_image/";
                $new_name = time() . $image;
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image')) {
                    $imagedata = $this->upload->data();
                    $imgall[$i] = $imagedata['file_name'];
                }
            }
            $all = explode(',', $data['id']);
            foreach ($all as $key => $value) {
                $display[] = $this->cm->select_data("placement_bulk_email", "placement_email_id", $value);
            }
            // echo "<pre>";
            // print_r($display);
            // print_r($data);
            // exit;
            $designation = "";
            $phone_no = "";
            $designation = $_SESSION['designation'];
            $phone_no = $_SESSION['personal_no'];
            $image_profile = "";
            if ($_SESSION['user_image'] == "") {
                $image_profile = 'https://yt3.ggpht.com/a/AATXAJwFLo1upoSjLx9WIWZpSqj6jLOZrEYewbu34A=s68-c-k-c0xffffffff-no-rj-mo';
            } else {
                $image_profile = base_url() . "dist/img/" . $_SESSION['user_image'] . "";
            }
            $count = 1;
            foreach ($display as $k => $v) {
                $config = array(
                    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
                    'smtp_host' => 'smtp.gmail.com', 'smtp_port' => 465,
                    // 'smtp_user' => "piyush0101nakarani@gmail.com",
                    'smtp_user' => "placement.rnwmultimedia@gmail.com", 'smtp_pass' => "rnw#tpo2020", 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
                    'mailtype' => 'text', //plaintext 'text' mails or 'html'
                    'smtp_timeout' => '50', //in seconds
                    'charset' => 'UTF-8', 'wordwrap' => TRUE
                );
                if ($count == 50) {
                    break;
                }
                $email = $v->email;
                $this->load->library('email');
                $this->email->initialize($config);
                $from_data = $data['from_data'];
                $to = $email;
                $subject = $data['subject'];
                $message = "<html>
						<head>
							<title></title>
						</head>
						<body style='font-family: Roboto;'>
							<style type='text/css'>
								h1,h2,h3,h4,h5,h6,p{
									margin: 0;
								}
								body{
									font-family: Roboto;
								}
								ul{
									list-style: none;
									margin: 0;
									padding: 0;
								}
								.info_list li{
									border-bottom: 1px solid #000;
									padding: 15px 0;
								}
								.info_list li:last-child{
									border: 0;
								}
								.info_list li > p{
									margin-bottom: 10px;
								}
								table tr td table{
									
									padding: 20px 0;
									border: 1px solid #ccc;
								}
								.profile_icon{
									width: 150px;
									height: 150px;
									overflow: hidden;
									border: 1px solid #000;
									border-radius: 100%;
								}
								.profile_icon_block{
									border-right: 2px solid red;
								}
								.main_name{
									font-size: 20px;
									color: red;
									margin-bottom: 5px;
								}
								.profile_icon_block_info{
									padding-left: 20px;
								}
							</style>

						<table width='100%'>
							<tr  style='padding: 20px 0;border: 1px solid #ccc;'>
								<td align='center'  style='padding: 20px 0;border: 1px solid #ccc;'>
									<a href='https://rnwmultimedia.com/''>
										<img src='https://www.rnwmultimedia.com/wp-content/uploads/2020/03/logo.png' width='300px'>
									</a>
								</td>
							</tr>
							<tr  style='padding: 20px 0;border: 1px solid #ccc;'>
								<td  style='padding: 20px 0;border: 1px solid #ccc;'>
									<ul class='info_list' style='list-style: none;margin: 5px;padding: 5px;'>
										
										<li style='border-bottom: 1px solid #000;padding: 15px 0;'>
											<h3 style='color:black;'><strong>Subject: </strong>  " . @$data['subject'] . "</h3>
										</li>
										<li style='border-bottom: 1px solid #000;padding: 15px 0;border: 0;'>
											<p style='color:red;font-size:14px'>Hello Dear Sir/Ma'am,</p>
											
											<p style='color:black;font-size:16px;'>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hope you are doing well.
											<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please draw your kind attention to the given document about the Placement, and we wish your support further.
											<br><br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;With this, Red & White Family wishes you and your family a Very Happy Diwali & Happy New Year in advance. May this year bring you success and happiness.
											</p>
											<p style='color:black;font-size:14px'><strong>Thank You</strong> </p>


											<p style='text-align:right;color:black;font-size:14px'>
											<strong>
													Training & Placement Manager<br>
													Red & White Group of Institutes<br>
													www.rnwmultimedia.com<br>
													+91 9331313196<br>
											</strong> </p>

											<br>
											<p>
											<b>Education</b> is the passport to the future, for tomorrow belongs to those who prepare for it today.” “Your attitude, not your aptitude, will determine your altitude.” “If you think <b>education</b> is expensive, try ignorance.” “The only person who is <b>educated</b> is the one who has learned how to learn …and change.
											</p>	

										</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td> 
									<table width='100%'  >
										<tr  style='padding: 10px 0;border: 1px solid #ccc;'>
											<td  style='padding: 20px 0;border: 1px solid #ccc;width:11%;'>
												<div>
													<div style='margin-right:-177px !important;margin-left:23px;width: 70px;height: 70px;overflow: hidden;border: 1px solid #000;border-radius: 100%;'>
														<img src='" . @$image_profile . "' width='100%'>

													</div>
												</div>
											</td>
											<td  style='padding: 20px 0;border: 1px solid #ccc;'>
												<div >
													<h3  style='margin: 0;font-size: 20px;color: red;margin-bottom: 5px;margin-left:20px;'>Red & White Placement Department</h3>
													
													<p style='margin: 0;font-size: 14px;color: black;margin-bottom: 5px;margin-left:20px;'>+91 93 313131 96</p>
													<p style='margin: 0;font-size: 14px;color: black;margin-bottom: 5px;margin-left:20px;'>Red & White Group of Institutes</p>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</body>
					</html>";
                $this->email->set_mailtype("html");
                $this->email->set_newline("\r\n");
                $this->email->from($from_data, "Red and White Placement Department");
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                $path = FCPATH . "images/mail_image/";
                foreach ($imgall as $file_name) {
                    $this->email->attach($path . $file_name);
                }
                if ($this->email->send()) {
                    $this->email->clear();
                    $this->session->set_flashdata("email_sent", "Email sent successfully.");
                } else {
                    $temp = 1;
                    $this->session->set_flashdata("email_sent_error", "Error in sending Email.");
                }
                $insertRecord = array('email' => $email);
                $this->cm->insert_data("send_email_verify", $insertRecord);
                $count++;
                // $member_type[] = $v->logtype;

            }
            // if($temp == 0)
            // {
            // 	 $mdata['subject'] = $data['subject'];
            // 	 if($this->input->post('mem_all'))
            // 	 {
            // 	  	$mdata['member_id'] = implode(',', $all_mem_id);
            // 	 }
            // 	 else
            // 	 {
            // 	 	$mdata['member_id'] = $data['id'];
            // 	 }
            // 	 $mdata['description'] = $data['description'];
            // 	 $mdata['document'] = implode(',', $imgall);
            // 	 $mdata['created_at'] = date('Y-m-d H:i:S');
            // 	 $mdata['logtype'] = $_SESSION['logtype'];
            // 	 $mdata['account_id'] = $_SESSION['user_id'];
            // 	 $mdata['admin_id'] = $_SESSION['admin_id'];
            // 	 $mdata['from'] = $data['from_data'];
            // 	 $mdata['image_type'] = implode(",", $member_type);
            // 	 $this->cm->insert_data("email_details",$mdata);
            // 	 logdata($mdata['subject']." mail Send");
            // }
            return redirect('settings/gmail_placement_company');
        }
        // public function send_placement_multiple_email()
        // {
        //      $from_email = $this->input->post('from_email');
        //      $to_email = $this->input->post('to_email');
        //      $to_mail = explode(',', $to_email);
        //      $mail_count= count($to_mail);
        //      for($i=0;$i<$mail_count;$i++)
        //      {
        //          $mail_id = TRIM($to_mail[$i]);
        //          $subject_url = $this->input->post('url');
        //          $message = $this->input->post('message');
        //          $this->email->from($from_email);
        //          $this->email->to($mail_id);
        //          $this->email->subject($url);
        //          $this->email->message($message);
        //          $this->email->send();
        //          $this->email->clear();
        //     }
        //
        //     // redirect(base_url() . 'shop/shopDetail/' . $shop_id . '/' . $shopname);
        // }
        public function gmail_all_member()
        {
            logdata("All member gmail page open");
            //$display['all_staffDetails'] = $this->cm->view_all("user");
            $display['all_hod'] = $this->cm->get_remarks_id_wise("hod", "status", "0");
            $display['all_faculty'] = $this->cm->get_remarks_id_wise("faculty", "status", "0");
            $display['all_user'] = $this->cm->get_remarks_id_wise("user", "status", "0");
            $display['all_super'] = $this->cm->view_all("admin");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header', $update);
            $this->load->view('settings/gmail_all_member', $display);
            //$this->load->view('footer');
            // $this->load->view('header_test',$update);
            // $this->load->view('settings/gmail_test',$display);
            //$this->load->view('footer_test');

        }
        public function mail_all_data()
        {
            $temp = 0;
            $data = $this->input->post();
            // echo "<pre>";
            // print_r($data);
            // die;
            $w = count($_FILES['doc']['name']);
            $imgall = array();
            for ($i = 0; $i < $w; $i++) {
                // echo $i;
                $image = $_FILES['doc']['name'][$i];
                // // echo $i;
                $_FILES['image']['name'] = $_FILES['doc']['name'][$i];
                $_FILES['image']['type'] = $_FILES['doc']['type'][$i];
                $_FILES['image']['tmp_name'] = $_FILES['doc']['tmp_name'][$i];
                $_FILES['image']['error'] = $_FILES['doc']['error'][$i];
                $_FILES['image']['size'] = $_FILES['doc']['size'][$i];
                $config['allowed_types'] = "*";
                $config['upload_path'] = FCPATH . "images/mail_image/";
                $new_name = time() . $image;
                $config['file_name'] = $new_name;
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image')) {
                    $imagedata = $this->upload->data();
                    $imgall[$i] = $imagedata['file_name'];
                }
            }
            $all = explode(',', $data['id']);
            if ($this->input->post('faculty')) {
                foreach ($all as $key => $value) {
                    $display[] = $this->cm->select_data("faculty", "faculty_id", $value);
                }
            } else if ($this->input->post('hod')) {
                foreach ($all as $key => $value) {
                    $display[] = $this->cm->select_data("hod", "hod_id", $value);
                }
            } else if ($this->input->post('mem_all')) {
                foreach ($all as $key => $value) {
                    $before = substr($value, 0, strpos($value, '_'));
                    $all_mem_id[] = $before;
                    $after = substr($value, strpos($value, '_') + strlen('_'));
                    if ($after == "Super Admin") {
                        $display[] = $this->cm->select_data("admin", "id", $before);
                    } else if ($after == "hod") {
                        $display[] = $this->cm->select_data("hod", "hod_id", $before);
                    } else if ($after == "Faculty") {
                        $display[] = $this->cm->select_data("faculty", "faculty_id", $before);
                    } else {
                        $display[] = $this->cm->select_data("user", "user_id", $before);
                    }
                    ///$display[] = $this->cm->select_data("faculty","faculty_id",$value);

                }
            } else {
                foreach ($all as $key => $value) {
                    $display[] = $this->cm->select_data("user", "user_id", $value);
                }
            }
            $designation = "";
            $phone_no = "";
            $designation = $_SESSION['designation'];
            $phone_no = $_SESSION['personal_no'];
            $image_profile = "";
            if ($_SESSION['user_image'] == "") {
                $image_profile = 'https://yt3.ggpht.com/a/AATXAJwFLo1upoSjLx9WIWZpSqj6jLOZrEYewbu34A=s68-c-k-c0xffffffff-no-rj-mo';
            } else {
                $image_profile = base_url() . "dist/img/" . $_SESSION['user_image'] . "";
            }
            $config = array(
                'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
                'smtp_host' => 'smtp.gmail.com', 'smtp_port' => 465, 'smtp_user' => "piyush0101nakarani@gmail.com", 'smtp_pass' => "0101Piyush!@#$0101", 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
                'mailtype' => 'text', //plaintext 'text' mails or 'html'
                'smtp_timeout' => '4', //in seconds
                'charset' => 'UTF-8', 'wordwrap' => TRUE
            );
            foreach ($display as $k => $v) {
                $email = $v->email;
                // echo $email;
                // die;
                // $this->load->config('email');
                $this->load->library('email');
                $this->email->initialize($config);
                $from_data = $data['from_data'];
                $to = $email;
                $subject = $data['subject'];
                $message = " <html>
<head>
	<title></title>
</head>
<body style='font-family: Roboto;'>
	<style type='text/css'>
		h1,h2,h3,h4,h5,h6,p{
			margin: 0;
		}
		body{
			font-family: Roboto;
		}
		ul{
			list-style: none;
			margin: 0;
			padding: 0;
		}
		.info_list li{
			border-bottom: 1px solid #000;
			padding: 15px 0;
		}
		.info_list li:last-child{
			border: 0;
		}
		.info_list li > p{
			margin-bottom: 10px;
		}
		table tr td table{
			background-color: #FEF6E1;
			padding: 20px 0;
			border: 1px solid #ccc;
		}
		.profile_icon{
			width: 150px;
			height: 150px;
			overflow: hidden;
			border: 1px solid #000;
			border-radius: 100%;
		}
		.profile_icon_block{
			border-right: 2px solid red;
		}
		.main_name{
			font-size: 20px;
			color: red;
			margin-bottom: 5px;
		}
		.profile_icon_block_info{
			padding-left: 20px;
		}
	</style>

	<table width='100%'>
		<tr  style='background-color: #FEF6E1;padding: 20px 0;border: 1px solid #ccc;'>
			<td align='center'  style='background-color: #FEF6E1;padding: 20px 0;border: 1px solid #ccc;'>
				<a href='https://rnwmultimedia.com/''>
					<img src='https://www.rnwmultimedia.com/wp-content/uploads/2020/03/logo.png' width='300px'>
				</a>
			</td>
		</tr>
		<tr  style='background-color: #FEF6E1;padding: 20px 0;border: 1px solid #ccc;'>
			<td  style='background-color: #FEF6E1;padding: 20px 0;border: 1px solid #ccc;'>
				<ul class='info_list' style='list-style: none;margin: 5px;padding: 5px;'>
					
					<li style='border-bottom: 1px solid #000;padding: 15px 0;'>
						<h3 style='color:black;'><strong>Subject: </strong>  " . $data['subject'] . "</h3>
					</li>
					<li style='border-bottom: 1px solid #000;padding: 15px 0;border: 0;'>
						<p style='color:red;font-size:14px'>Hello,</p>
						<p style='color:red;font-size:14px'>Respected, $v->designation $v->name </p>
						<p style='color:black;font-size:16px;'>&nbsp;&nbsp;&nbsp;&nbsp; " . $data['description'] . ".</p>
						<p style='color:black;font-size:14px'><strong>Thanks and Regards</strong> </p>
					</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td> 
				<table width='100%'  >
					<tr  style='background-color: #FEF6E1;padding: 20px 0;border: 1px solid #ccc;'>
						<td  style='background-color: #FEF6E1;padding: 20px 0;border: 1px solid #ccc;width:21%;'>
							<div style='border-right: 2px solid red;'>
								<div style='margin-right:-177px !important;margin-left:23px;width: 150px;height: 150px;overflow: hidden;border: 1px solid #000;border-radius: 100%;'>
									<img src='" . $image_profile . "' width='100%'>

								</div>
							</div>
						</td>
						<td  style='background-color: #FEF6E1;padding: 20px 0;border: 1px solid #ccc;'>
							<div >
								<h3  style='margin: 0;font-size: 20px;color: red;margin-bottom: 5px;margin-left:20px;'>" . $_SESSION['user_name'] . "</h3>
								<p style='margin: 0;font-size: 14px;color: black;margin-bottom: 5px;margin-left:20px;'>" . $designation . "</p>
								<p style='margin: 0;font-size: 14px;color: black;margin-bottom: 5px;margin-left:20px;'>" . $phone_no . "</p>
								<p style='margin: 0;font-size: 14px;color: black;margin-bottom: 5px;margin-left:20px;'>Red & White Multimedia Education</p>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>";
                // $message = "<h1><b>Name:</b>$v->name\n<b>Email:</b>$v->email\n<b>Designation:</b>$v->designation</h1>\n<h2>Details:<h2>\n'".$data['description']."' ";
                $this->email->set_mailtype("html");
                $this->email->set_newline("\r\n");
                $this->email->from($from_data, $_SESSION['user_name']);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                $path = FCPATH . "images/mail_image/";
                foreach ($imgall as $file_name) {
                    $this->email->attach($path . $file_name);
                }
                if ($this->email->send()) {
                    $this->session->set_flashdata("email_sent", "Email sent successfully.");
                } else {
                    $temp = 1;
                    $this->session->set_flashdata("email_sent_error", "Error in sending Email.");
                }
                $member_type[] = $v->logtype;
            }
            if ($temp == 0) {
                $mdata['subject'] = $data['subject'];
                if ($this->input->post('mem_all')) {
                    $mdata['member_id'] = implode(',', $all_mem_id);
                } else {
                    $mdata['member_id'] = $data['id'];
                }
                $mdata['description'] = $data['description'];
                $mdata['document'] = implode(',', $imgall);
                $mdata['created_at'] = date('Y-m-d H:i:S');
                $mdata['logtype'] = $_SESSION['logtype'];
                $mdata['account_id'] = $_SESSION['user_id'];
                $mdata['admin_id'] = $_SESSION['admin_id'];
                $mdata['from'] = $data['from_data'];
                $mdata['image_type'] = implode(",", $member_type);
                $this->cm->insert_data("email_details", $mdata);
                logdata($mdata['subject'] . " mail Send");
            }
            return redirect('settings/gmail');
        }
        public function fetch_email_designation()
        {
            $all = explode(',', $this->input->post('u_id'));
            foreach ($all as $key => $value) {
                $display[] = $this->cm->select_data("staff_detail", "id", $value);
            }
        ?>
            <table class="table table-bordered table-striped create_responsive_table">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Designation</th>
                </tr>

                <?php foreach ($display as $k => $v) { ?>
                    <tr>
                        <td><?php echo $v->name; ?></td>
                        <td><?php echo $v->email; ?></td>
                        <td><?php echo $v->designation; ?></td>
                    </tr>
                <?php
                } ?>
            </table>
        <?php
        }
        public function fetch_email()
        {
            $all = explode(',', $this->input->post('u_id'));
            foreach ($all as $key => $value) {
                $display[] = $this->cm->select_data("user", "user_id", $value);
            }
            foreach ($display as $k => $v) {
                $vall[] = $v->email;
            }
            //$mail = implode(",", $vall);

        ?>
            <style type="text/css">
                #show_all_email ul {
                    list-style: none;
                    margin: 0;
                    padding: 8px;
                    border: 1px solid #ccc;
                    display: inline-block;
                    width: 540px;
                }

                #show_all_email ul li {
                    float: left;
                    padding: 1px 11px;
                    border: 1px solid #ccc;
                    margin: 5px 7px;
                    border-radius: 22px;
                    background: red;
                    color: #fff;
                }
            </style>

            <ul st><?php foreach ($vall as $m => $n) { ?>
                    <li><?php echo $n; ?></li>
                <?php
                    } ?>
            </ul>



        <?php
        }
        public function fetch_mail_hod()
        {
            $all = explode(',', $this->input->post('u_id'));
            foreach ($all as $key => $value) {
                $display[] = $this->cm->select_data("hod", "hod_id", $value);
            }
            foreach ($display as $k => $v) {
                $vall[] = $v->email;
            }
            //$mail = implode(",", $vall);

        ?>
            <style type="text/css">
                #show_all_email ul {
                    list-style: none;
                    margin: 0;
                    padding: 8px;
                    border: 1px solid #ccc;
                    display: inline-block;
                    width: 540px;
                }

                #show_all_email ul li {
                    float: left;
                    padding: 1px 11px;
                    border: 1px solid #ccc;
                    margin: 5px 7px;
                    border-radius: 22px;
                    background: red;
                    color: #fff;
                }
            </style>

            <ul st><?php foreach ($vall as $m => $n) { ?>
                    <li><?php echo $n; ?></li>
                <?php
                    } ?>
            </ul>



        <?php
        }
        public function fetch_mail_faculty()
        {
            $all = explode(',', $this->input->post('u_id'));
            foreach ($all as $key => $value) {
                $display[] = $this->cm->select_data("faculty", "faculty_id", $value);
            }
            foreach ($display as $k => $v) {
                $vall[] = $v->email;
            }
            //$mail = implode(",", $vall);

        ?>
            <style type="text/css">
                #show_all_email ul {
                    list-style: none;
                    margin: 0;
                    padding: 8px;
                    border: 1px solid #ccc;
                    display: inline-block;
                    width: 540px;
                }

                #show_all_email ul li {
                    float: left;
                    padding: 1px 11px;
                    border: 1px solid #ccc;
                    margin: 5px 7px;
                    border-radius: 22px;
                    background: red;
                    color: #fff;
                }
            </style>

            <ul st><?php foreach ($vall as $m => $n) { ?>
                    <li><?php echo $n; ?></li>
                <?php
                    } ?>
            </ul>



        <?php
        }
        public function all_gmail()
        {
            logdata("Show mail page open");
            // $m = "hello.jpg";
            // $j =substr($m, strlen($m)-4,4);
            // echo $j;
            // die;
            $display['all_super'] = $this->cm->view_all("admin");
            $display['all_faculty'] = $this->cm->view_all("faculty");
            $display['all_hod'] = $this->cm->view_all("hod");
            $display['all_user'] = $this->cm->view_all("user");
            $update['upd_faculty'] = $this->cm->view_all("faculty");
            $update['upd_branch'] = $this->cm->view_all("branch");
            $update['f_module'] = $this->cm->view_all("f_module");
            $update['m_module'] = $this->cm->view_all("m_module");
            $update['l_module'] = $this->cm->view_all("l_module");
            $display['gall'] = $this->tm->view_all_email("email_details", "logtype", $_SESSION['logtype'], "account_id", $_SESSION['user_id']);
            $update['upd_see'] = $this->cm->check_update("demo");
            $this->load->view('header', $update);
            $this->load->view('settings/gmail_details', $display);
        }
        public function fetch_mail_all_member()
        {
            $all = explode(',', $this->input->post('u_id'));
            //	$after = substr('biohazard@online.ge', strpos('biohazard@online.ge','@')+strlen('@'));
            foreach ($all as $key => $value) {
                $before = substr($value, 0, strpos($value, '_'));
                $after = substr($value, strpos($value, '_') + strlen('_'));
                if ($after == "Super Admin") {
                    $display[] = $this->cm->select_data("admin", "id", $before);
                } else if ($after == "hod") {
                    $display[] = $this->cm->select_data("hod", "hod_id", $before);
                } else if ($after == "Faculty") {
                    $display[] = $this->cm->select_data("faculty", "faculty_id", $before);
                } else {
                    $display[] = $this->cm->select_data("user", "user_id", $before);
                }
                ///$display[] = $this->cm->select_data("faculty","faculty_id",$value);

            }
            // echo "<pre>";
            // print_r($display);
            // die;
            foreach ($display as $k => $v) {
                $vall[] = $v->email;
            }
            //$mail = implode(",", $vall);

        ?>
            <style type="text/css">
                #show_all_email ul {
                    list-style: none;
                    margin: 0;
                    padding: 8px;
                    border: 1px solid #ccc;
                    display: inline-block;
                    width: 540px;
                }

                #show_all_email ul li {
                    float: left;
                    padding: 1px 11px;
                    border: 1px solid #ccc;
                    margin: 5px 7px;
                    border-radius: 22px;
                    background: red;
                    color: #fff;
                }
            </style>

            <ul st><?php foreach ($vall as $m => $n) { ?>
                    <li><?php echo $n; ?></li>
                <?php
                    } ?>
            </ul>



            <?php
        }
        public function change_f_mod()
        {
            $id = $this->input->post('a_name');
            $data = $this->cm->filter_data("m_module", "f_module_id", $id);
            foreach ($data as $key => $value) { ?>
                <label class="ml-4 text-dark" for="pwd"><?php echo $value->module_name; ?> <input type="checkbox" name="m_all[]" value="<?php echo $value->module_name; ?>" onclick="l_chnage_mod(<?php echo $value->m_module_id; ?>)">:</label>
                <br>
                <div style="margin-left: 40px" id="l_change_mod<?php echo $value->m_module_id; ?>"></div>
            <?php
            } ?>

            <script type="text/javascript">
                function l_chnage_mod(id) {

                    var a = id;
                    $.ajax({
                        url: "<?php echo base_url(); ?>settings/change_l_mod",
                        type: 'POST',
                        data: {
                            a_name: a
                        },
                        success: function(res) {
                            $('#l_change_mod' + id).html(res);
                        }
                    });
                }
            </script>
            <?php
        }
        public function change_l_mod()
        {
            $id = $this->input->post('a_name');
            $select_logtype = $this->cm->select_data("logtype", "logtype_name", 'Admin');
            $data = $this->cm->filter_data("l_module", "m_module_id", $id);
            foreach ($data as $k => $l) { ?>
                <label style="width:70%;font-weight: normal;"><?php echo $l->name; ?> </label>
                <label class="radio-inline">
                    <input type="checkbox" name="f_all[]" value="<?php echo $l->name; ?>" <?php if (isset($select_logtype->permission) && in_array($l->name, explode(",", $select_logtype->permission))) {
                        echo "checked";
                    } ?>> Yes
                </label>
                <br>
                <?php
            }
        }
        public function fetch_department_data()
        {
            $branch_id = $this->input->post('branch_id');
            $record['branch_all'] = $this->cm->match_branch('branch', 'branch_id', $branch_id);
            $departments = explode(',', $record['branch_all']->department_ids);
            for ($i = 0; $i < sizeof($departments); $i++) {
                $department_name[] = $this->cm->match_department('department', 'department_id', $departments[$i]);
            }
            $record['department_filter'] = $department_name;
            $this->load->view('erp/ajax_departments_data', $record);
        }
        public function fetch_documnet_barnch_wise_departmet()
        {
            $data = $this->input->post();
            $bid = $data['branch_id'];
            $department = $this->admi->view_all('department');
            foreach ($department as $dn) {
                $flag = 0;
                $dnbi = explode(',', $dn['branch_id']);
                if (in_array($bid, $dnbi)) {
                    $flag = 1;
                }
                if ($flag == 1) {
                ?>
                    <option value="<?php echo $dn['department_id']; ?>"><?php echo $dn['department_name']; ?></option>

    <?php
                }
            }
        }
        public function GetRecord_branchWise()
        {
            $branch_id = $this->input->post('branch_id');
            $data = $this->cm->fetch_data_branchwise($branch_id);
            echo json_encode(array('record' => $data));
        }
        public function GetRecord_facWise()
        {
            $faculty_id = $this->input->post('faculty_id');
            $data = $this->cm->fetch_data_fachwise($faculty_id);
            echo json_encode(array('record' => $data));
        }
        public function GetRecord_departmentWise()
        {
            $course_orsingle = $this->input->post('course_orsingle');
            $data = $this->cm->fetch_data_Depratmentwise($course_orsingle);
            echo json_encode(array('record' => $data));
        }
        public function GetRecord_p_deprat()
        {
            $course_orpackage = $this->input->post('course_orpackage');
            $data = $this->cm->fetch_data_p_depart($course_orpackage);
            echo json_encode(array('record' => $data));
        }
        public function Get_faculty()
        {
            $id = $this->input->post('branch_id');
            //$course_id = $this->input->post('course_id');
            echo $this->cm->Get_faculty($id);
        }

        // public function Get_faculty()
        // {
        // 	  $course_id = $this->input->post('course_id');
        // 	  //$branch_id = $this->input->post('branch_id');
        // 	$record['course_all'] = $this->cm->match_branch('course','course_id',$course_id);
        // 	$facultys = $record['course_all']->course_id;
        // 	// print_r($facultys);
        // 	// exit;
        // 	//$data=$this->cm->match_department('faculty','faculty_id',$facultys);
        // 	 for($i=0; $i<sizeof($facultys); $i++)
        // 	 {
        // 		$name[] =  $this->cm->match_department('faculty','faculty_id',$facultys,[$i]);
        // 	}
        // 	   $record['get_faculty_filter'] =  $name ;
        // 	   // echo "<pre>";
        // 	   // print_r($record['get_faculty_filter']);
        // 	   // exit;
        //      $this->load->view('ajax_get_facultiy_filter',$record);
        // }
    }
