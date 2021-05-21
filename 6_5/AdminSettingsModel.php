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
}
