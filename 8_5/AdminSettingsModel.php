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

  public function get_branch_reco ($tbl, $field, $id) {
    $this->db->where($field, $id);
    $this->db->from($tbl);
    $data = $this->db->get();
    return $data->row();
  }

  public function delete_branch($tbl, $field, $id) {
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
}
