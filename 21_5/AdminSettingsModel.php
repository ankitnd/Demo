<?php
class AdminSettingsModel extends CI_Model
{

  public function __construct() {
    parent::__construct();
    // Your own constructor code
  }

  public function update_record($tbl, $data, $field, $id) {
      $this->db->where($field, $id);
      return $this->db->update($tbl, $data); 
  }

  public  function import_record($tbl, $record) {
    return $this->db->insert($tbl, $record);
  }

  public function get_reco ($tbl, $field, $id) {
    $this->db->where($field, $id);
    $this->db->from($tbl);
    $data = $this->db->get();
    return $data->row();
  }

  public function delete_record($tbl, $field, $id) {
    $this->db->where($field, $id);
    return $this->db->delete($tbl);
  }

  public function fetch_branch_reco($tbl, $filter = 0) {
    if (!empty($filter['filter_branch_data'])) {
      
      if (!empty($filter['filter_branch'])) {
        $this->db->like("branch_name", $filter['filter_branch']);
      }
      if (!empty($filter['admin_id'])) {
        $this->db->like("admin_id", $filter['admin_id']);
      }
      if (!empty($filter['mobile_one'])) {
        $this->db->like("mobile_one", $filter['mobile_one']);
      }
      if (!empty($filter['bank_name'])) {
        $this->db->like("bank_name", $filter['bank_name']);
      }
    }

    $this->db->from($tbl);
    $data = $this->db->get();
    return $data->result();
  }

  public function fetch_dept_record($tbl, $filter = 0) {
    if (!empty($filter['filter_dept_data'])) {
      
      if (!empty($filter['filter_b_ids'])) {
        
        foreach ($filter['filter_b_ids'] as $like) {
            $this->db->like('branch_id', $like);
        }
        //$this->db->like("branch_id", $filter['filter_branch_id']);
      }
      if (!empty($filter['filter_department_name'])) {
        $this->db->like("department_name", $filter['filter_department_name']);
      }
    }

    $this->db->from($tbl);
    $data = $this->db->get();
    //echo $this->db->last_query(); die();
    return $data->result();
  }

  public function fetch_subdept_reco($tbl, $filter = 0) {
    if (!empty($filter['filter_subdept_data'])) {
      
      if (!empty($filter['filter_b_ids'])) {
        
        foreach ($filter['filter_b_ids'] as $like) {
            $this->db->like('branch_id', $like);
        }
        //$this->db->like("branch_id", $filter['filter_branch_id']);
      }
      if (!empty($filter['filter_subdepartment_name'])) {
        $this->db->like("subdepartment_name", $filter['filter_subdepartment_name']);
      }
    }

    $this->db->from($tbl);
    $data = $this->db->get();
    //echo $this->db->last_query(); die();
    return $data->result();
  }


  public function get_dept_reco ($tbl, $field, $id) {
    $this->db->where($field, $id);
    $this->db->from($tbl);
    $data = $this->db->get();
    return $data->row();
    //echo $this->db->last_query(); die();
  }

  public function fetch_course_reco($tbl, $filter = 0) {
    if (!empty($filter['filter_course_data'])) {
      
      if (!empty($filter['filter_branch'])) {
        $this->db->like("branch_id", $filter['filter_branch']);
      }
      if (!empty($filter['filter_department'])) {
        $this->db->like("department_id", $filter['filter_department']);
      }
      if (!empty($filter['filter_subdepartment'])) {
        $this->db->like("subdepartment_id", $filter['filter_subdepartment']);
      }
      if (!empty($filter['filter_course'])) {
        $this->db->like("course_id", $filter['filter_course']);
      }
      if (!empty($filter['filter_code'])) {
        $this->db->like("course_id", $filter['filter_code']);
      }
    }

      $this->db->from($tbl);
      $data = $this->db->get();
      return $data->result();
  }

  public function fetch_subcourse_reco($tbl, $filter = 0) {
		if (!empty($filter['filter_course_data'])) {
		  
		  if (!empty($filter['filter_course'])) {
			$this->db->like("course_id", $filter['filter_course']);
		  }
		  if (!empty($filter['filter_subcourse'])) {
			$this->db->like("subcourse_id", $filter['filter_subcourse']);
		  }
		  if (!empty($filter['filter_code'])) {
			$this->db->like("subcourse_id", $filter['filter_code']);
		  }
		}
	
      $this->db->from($tbl);
      $data = $this->db->get();
      return $data->result();
	  }

	  public function fetch_package_reco($tbl, $filter = 0) {
		if (!empty($filter['filter_package_data'])) {
		  
		  if (!empty($filter['filter_branch'])) {
			$this->db->like("branch_id", $filter['filter_branch']);
		  }
		  if (!empty($filter['filter_department'])) {
			$this->db->like("department_id", $filter['filter_department']);
		  }
		  if (!empty($filter['filter_subdepartment'])) {
			$this->db->like("subdepartment_id", $filter['filter_subdepartment']);
		  }
		  if (!empty($filter['filter_package'])) {
			$this->db->like("package_id", $filter['filter_package']);
		  }
		  if (!empty($filter['filter_code'])) {
			$this->db->like("package_id", $filter['filter_code']);
		  }
		}
	
      $this->db->from($tbl);
      $data = $this->db->get();
      return $data->result();
	  }

    public function fetch_subpackage_reco($tbl, $filter = 0) {
      if (!empty($filter['filter_package_data'])) {
        
        if (!empty($filter['filter_course'])) {
        $this->db->like("course_id", $filter['filter_course']);
        }
        if (!empty($filter['filter_subcourse'])) {
        $this->db->like("subcourse_id", $filter['filter_subcourse']);
        }
        if (!empty($filter['filter_code'])) {
        $this->db->like("subcourse_id", $filter['filter_code']);
        }
      }
    
        $this->db->from($tbl);
        $data = $this->db->get();
        return $data->result();
      }
}
