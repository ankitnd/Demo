<link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/jquery-selectric/selectric.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/izitoast/css/iziToast.min.css">
<style type="text/css">
  li.select2-selection__choice {
    background-color: #5864BC !important;
  }
</style>
<div class="main-wrapper main-wrapper-1">
  <div class="main-content">
    <section class="section">
      <div class="section-body">
        <div class="row">
          <div class="col-12 d-flex justify-content-between">
            <h6 class="page-title text-dark mb-3">Branch</h6>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb p-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Library</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data</li>
              </ol>
            </nav>
          </div>
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between income-wrapper">
                <div class="d-flex justify-content-between">
                </div>
                <div class="table-right-content">
                  <button href="#" class="btn btn-info" data-toggle="modal" data-target="#createbranch">
                    <span><i class="fas fa-plus mr-1"></i>Create Branch</span>
                  </button>
                  <button href="#" class="btn btn-info" data-toggle="modal" onclick='UpdateUserData(<?php echo json_encode($receipt) ?>)'>
                    <span><i class="far fa-edit"></i>Receipt Cofiguration</span>
                  </button>
                  <button href="#" class="btn btn-info" data-toggle="modal" data-target="#filterbranch">
                    <span><i class="fas fa-filter mr-1"></i>Filter</span>
                  </button>
                  <button class="btn">
                    <span><i class="fas fa-arrow-left mr-1"></i> Back</span>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="msg"></div>
                <div class="table-responsive">
                  <table class="table table-striped normal-table branch-table" id="table-1">
                    <thead>
                      <tr>
                        <th class="text-center">
                          <div class="custom-checkbox custom-checkbox-table custom-control">
                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                          </div>
                        </th>
                        <th>SN</th>
                        <th>Logo</th>
                        <th width="300px">Details-1</th>
                        <th width="200px">Details-2</th>
                        <th width="300px">Bank Info</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $sno = 1;
                      foreach ($branch_all as $val) { ?>
                        <tr class="text-center">
                          <td class="p-0 text-center">
                            <div class="custom-checkbox custom-control">
                              <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkboxis-<?php echo $sno; ?>" name="mark" value="<?php echo $val->branch_id; ?>">
                              <label for="checkboxis-<?php echo $sno; ?>" class="custom-control-label">&nbsp;</label>
                            </div>
                          </td>
                          <td><?php echo $sno; ?></td>
                          <td><img src="http://demo.rnwmultimedia.com/dist/branchlogo/<?php echo $val->branch_logo;  ?>" width="90px" style="box-shadow: none;border: 0;border-radius: 0;" /></td>
                          <td>
                            <strong>Branch Name :</strong> <span><?php echo $val->branch_name; ?></span> </br>
                            <strong>Branch Code :</strong> <span><?php echo $val->branch_code; ?></span> </br>
                            <strong>Branch Title :</strong> <span><?php echo $val->branch_title; ?></span> </br>
                            <strong>Website :</strong> <span><?php echo $val->website_name; ?></span> </br>
                            <strong>Email :</strong> <span><?php echo $val->email; ?></span> </br>
                            <strong>Mobile No 1 :</strong> <span><?php echo $val->mobile_one; ?></span></br>
                            <strong>Mobile No 2 :</strong> <span><?php echo $val->mobile_two; ?> </span> </br>
                            <strong>Landline No :</strong> <span><?php echo $val->phone_landline_no; ?></span> </br>
                            <strong>Address :</strong> <span><?php echo $val->branch_address; ?></span> </br>
                          </td>
                          <td>
                            <?php $adminids = explode(",", $val->admin_id);
                            foreach ($user_all as $row) {
                              if (in_array($row->user_id, $adminids)) {
                                echo "<strong>Admin : </strong><span>" . $row->name . "</span>";
                              }
                            } ?><br>
                            <?php $countryids = explode(",", $val->country_id);
                            foreach ($country_all as $row) {
                              if (in_array($row->country_id, $countryids)) {
                                echo "<strong>Country : </strong><span>" . $row->country_name . "</span>";
                              }
                            } ?><br>
                            <?php $stateids = explode(",", $val->state_id);
                            foreach ($state_all as $row) {
                              if (in_array($row->state_id, $stateids)) {
                                echo "<strong>State : </strong><span>" . $row->state_name . "</span>";
                              }
                            } ?><br>
                            <?php $cityids = explode(",", $val->city_id);
                            foreach ($city_all as $row) {
                              if (in_array($row->city_id, $cityids)) {
                                echo "<strong>City : </strong><span>" . $row->city_name . "</span>";
                              }
                            } ?><br>
                            <?php echo "<strong>Pan No : </strong><span>" . $val->pan_no . "</span>" ?><br>
                            <?php echo "<strong>CIN : </strong><span>" . $val->CIN . "</span>" ?><br>
                            <?php echo "<strong>GST No : </strong><span>" . $val->gst_no . "</span>" ?><br>
                          </td>
                          <td>
                            <?php echo "<strong>Bank Name : </strong><span>" . $val->bank_name . "</span>" ?><br>
                            <?php echo "<strong>Account Holder Name : </strong>" . $val->account_name . "</span>" ?><br>
                            <?php echo "<strong>Account No : </strong>" . $val->account_no . "</span>" ?><br>
                            <?php echo "<strong>IFSC Code : </strong>" . $val->ifsc . "</span>" ?><br>
                            <?php echo "<strong>Account Type : </strong>" . $val->account_type . "</span>" ?>
                          </td>
                          <td><label style="color:#a6a6a6"> <?php if ($val->branch_status == "0") {
                                                              echo "Active";
                                                            }
                                                            if ($val->branch_status == "1") {
                                                              echo  "Disable";
                                                            } ?> </label></td>
                          <td>
                            <div class="dropdown">
                              <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                              <div class="dropdown-menu">
                                <?php $xx = $val->branch_id; ?>
                                <a class="dropdown-item has-icon" href="javascript:doc_upd(<?php echo $xx; ?>)">
                                  <i class="far fa-edit"></i> Edit
                                </a>
                                <a href="#" class="dropdown-item has-icon" onclick="return get_record_permission(<?php echo $val->branch_id; ?>)"><i class="fas fa-pen-square"></i> Edit Receipt</a>
                                <a class="dropdown-item has-icon text-danger" href="javascript:remove_doc(<?php echo $xx; ?>)">
                                  <i class="far fa-trash-alt"></i> Delete
                                </a>
                                <?php if ($val->branch_status == 0) { ?>
                                  <a class="dropdown-item has-icon" href="javascript:doc_status_upd(<?php echo $xx; ?>, 1)">
                                    <i class="fas fa-ban"></i> Disable
                                  </a>
                                <?php } else {  ?>
                                  <a class="dropdown-item has-icon" href="javascript:doc_status_upd(<?php echo $xx; ?>, 0)">
                                    <i class="far fa-check-circle"></i> Active
                                  </a>
                                <?php } ?>
                              </div>
                          </td>
                        </tr>
                      <?php $sno++;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Create Branch -->
  <div class="modal fade" id="createbranch" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="myLargeModalLabel">Create Branch</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data" class="document-createmodal" method="post" name="branch_add" id="branch_add">
          <input type="hidden" name="branch_id" id="branch_id" class="form-control">
          <div class="modal-body">
            <div class="card">
              <div class="branch-items row mb-2">
                <div class="form-group col-md-4 col-sm-12">
                  <label for="">Session :</label>
                  <select id="session" class="select2 form-control" name="session[]" multiple="multiple">
                    <option>Select Year</option>

                    <?php for ($i = 2019; $i <= 2040; $i++) { ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <?php if ($_SESSION['logtype'] == "Admin") { ?>
                  <div class="form-group col-md-4 col-sm-12">
                    <div class="form-group">
                      <label for="pwd">Branch Name:<span style="color: red;">*</span></label>
                      <input class="form-control" value="<?php if (!empty($select_branch->branch_name)) {
                                                            echo $select_branch->branch_name;
                                                          } ?>" type="text" name="branch_name" id="branch_name" placeholder="Enter Branch Name" required>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-12">
                    <div class="form-group">
                      <label for="pwd">Branch Code:<span style="color: red;">*</span></label>
                      <input class="form-control" value="<?php if (!empty($select_branch->branch_code)) {
                                                            echo $select_branch->branch_code;
                                                          } ?>" type="text" id="branch_code" name="branch_code" placeholder="Enter Branch Code" required>
                    </div>
                  </div>
                <?php } else { ?>
                  <div class="form-group col-md-4 col-sm-12">
                    <div class="form-group">
                      <label for="email">Admin:<span style="color: red;">*</span></label>
                      <select required class="form-control" name="admin_id" id="admin_id" required>
                        <option value="">Select Admin</option>
                        <?php foreach ($user_all as $val) { ?>
                          <option <?php if ($val->user_id == @$select_branch->admin_id) {
                                    echo "selected";
                                  } ?> value="<?php echo $val->user_id; ?>"><?php echo $val->name; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-12">
                    <div class="form-group">
                      <label for="pwd">Branch Name:<span style="color: red;">*</span></label>
                      <input class="form-control" type="text" id="branch_name" name="branch_name" placeholder="Enter Branch Name" required>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-12">
                    <div class="form-group">
                      <label for="pwd">Branch Code:<span style="color: red;">*</span></label>
                      <input class="form-control" type="text" id="branch_code" name="branch_code" placeholder="Enter Branch Code" required>
                    </div>
                  </div>
                <?php } ?>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Branch Title:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="branch_title" name="branch_title" placeholder="Enter Branch Title" required>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Branch Logo:<span style="color: red;">*</span></label>
                  <img id="branch_logo" src="" width="90px" />
                  <?= form_open_multipart('AdminSettings/ajax_branch_submit'); ?>
                  <input class="form-control" type="file" name="branch_logo">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Website:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="website_name" name="website_name" placeholder="Enter Website Name" required>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="email">Email:<span style="color: red;">*</span></label>
                  <input class="form-control" id="email" type="text" name="email" placeholder="Enter Email ID" required>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Mobile No 1 :<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="mobile_one" name="mobile_one" placeholder="Enter Mobile No" required>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Mobile No 2:</label>
                  <input class="form-control" type="text" id="mobile_two" name="mobile_two" placeholder="Enter Mobile No">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Landline No :<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="phone_landline_no" name="phone_landline_no" placeholder="Enter Landline No" required>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Address :<span style="color: red;">*</span></label>
                  <textarea class="form-control" type="text" id="branch_address" name="branch_address" rows="2" required><?php if (!empty($select_branch->branch_address)) {
                                                                                                                            echo $select_branch->branch_address;
                                                                                                                          } ?></textarea>
                </div>
              </div>
              <div class="branch-items row mb-2">
                <div class="form-group  col-md-4 col-sm-12">
                  <label for="pwd">Country:<span style="color: red;">*</span></label>
                  <select required class="form-control" name="country_id" id="country_id" required>
                    <option value="">Select Country</option>
                    <?php foreach ($country_all as $val) { ?>
                      <option <?php if ($val->country_id == @$select_branch->country_id) {
                                echo "selected";
                              } ?> value="<?php echo $val->country_id; ?>"><?php echo $val->country_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group  col-md-4 col-sm-12">
                  <label for="pwd">State:<span style="color: red;">*</span></label>
                  <select required class="form-control" name="state_id" id="state_id" required>
                    <option value="">Select State</option>
                    <?php foreach ($state_all as $val) { ?>
                      <option <?php if ($val->state_id == @$select_branch->state_id) {
                                echo "selected";
                              } ?> value="<?php echo $val->state_id; ?>"><?php echo $val->state_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">City:<span style="color: red;">*</span></label>
                  <select required class="form-control" name="city_id" id="city_id" required>
                    <option value="">Select City</option>
                    <?php foreach ($city_all as $val) { ?>
                      <option <?php if ($val->city_id == @$select_branch->city_id) {
                                echo "selected";
                              } ?> value="<?php echo $val->city_id; ?>"><?php echo $val->city_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Pan No:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="pan_no" name="pan_no" placeholder="Enter Pan No">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">CIN:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="CIN" name="CIN" placeholder="Enter CIN">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">GST No:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="gst_no" name="gst_no" placeholder="Enter GST No">
                </div>
              </div>
              <div class="branch-items row mb-2">
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Bank Name:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="bank_name" name="bank_name" placeholder="Eneter Bank Name">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Account Holder Name:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="account_name" name="account_name" placeholder="Eneter Holder Name">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Account No:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="account_no" name="account_no" placeholder="Eneter Account No">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">IFSC No:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="ifsc" name="ifsc" placeholder="Eneter IFSC No">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                  <label for="pwd">Account Type:<span style="color: red;">*</span></label>
                  <input class="form-control" type="text" id="account_type" name="account_type" placeholder="Like Saving">
                </div>
              </div>
            </div>
            <input type="submit" class="btn btn-primary" id="Submit" name="submit" value="Submit">
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Filter Branch -->
  <div class="modal fade" id="filterbranch" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="myLargeModalLabel">Filter</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="<?php echo base_url(); ?>AdminSettings/createbranch">
            <div class="card">
              <div class="branch-items row mb-2">
                <div class="form-group col-md-6 col-sm-12">
                  <label for="">Branch Name :</label>
                  <input type="text" class="form-control" name="filter_branch" value="<?php if (!empty($filter_branch)) {
                                                                                        echo @$filter_branch;
                                                                                      } ?>" placeholder="Branch Name">
                </div>
                <div class="form-group col-md-6 col-sm-12">
                  <label for="">Mobile No 1 :</label>
                  <input name="mobile_one" id="mobile_one" type="text" name="filter_mobile_one" value="<?php if (!empty($filter_mobile_one)) {
                                                                                                          echo @$filter_mobile_one;
                                                                                                        } ?>" class="form-control" placeholder="Branch Mobile">
                </div>
                <div class="form-group  col-md-6 col-sm-12">
                  <label for="">Admin :</label>
                  <select class="form-control" name="admin_id" id="admin_id">
                    <option value="">Select Admin</option>
                    <?php foreach ($user_all as $val) { ?>
                      <option <?php if (@$filter_admin_id == $val->user_id) {
                                echo "selected";
                              } ?> <?php if ($val->user_id == @$select_branch->admin_id) {
                                                                                                    echo "selected";
                                                                                                  } ?> value="<?php echo $val->user_id; ?>"><?php echo $val->name; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                  <label for="">Bank Name :</label>
                  <input id="bank_name" type="text" class="form-control" name="bank_name" value="<?php if (!empty($filter_bank_name)) {
                                                                                                    echo @$filter_bank_name;
                                                                                                  } ?>">
                </div>
              </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit" name="filter_branch_data">
            <a class="btn btn-light text-dark" href="<?php echo base_url(); ?>AdminSettings/createbranch">Reset</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Rexeipt -->
  <div class="modal fade" id="modal_open" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="myLargeModalLabel">Edit Receipt Permission</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="receipt_upd_msg"></div>
          <input type="hidden" name="" class="form-control" id="upd_receipt_permission_id" />
          <div class="card">
            <div class="branch-items row mb-2">
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Receipt Type:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_type" value="GST" id="receipt_type2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>GST</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_type" value="NoN GST" id="receipt_type1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>NoN GST</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Branch Logo:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="logo" value="Yes" id="logo1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="logo" value="No" id="logo2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Brnach Address:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="address" value="Yes" id="address1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="address" value="No" id="address2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Branch Title:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="b_title" value="Yes" id="branch_title1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="b_title" value="No" id="branch_title2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Course</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="course" value="Yes" id="course1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="course" value="No" id="course2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Receipt No:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_no" value="Yes" id="receipt_no1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_no" value="No" id="receipt_no2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Receipt Date:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_date" value="Yes" id="receipt_date1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_date" value="No" id="receipt_date2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Gr ID:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="gr_id" value="Yes" id="gr_id1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="gr_id" value="No" id="gr_id2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Enrollment No:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="enrollment_no" value="Yes" id="enrollment_no1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="enrollment_no" value="No" id="enrollment_no2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">GST No:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="gst_number" value="Yes" id="gst_no1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="gst_number" value="No" id="gst_no2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Name:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="name" value="Yes" id="name1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="name" value="No" id="name2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Fees Paid:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="pay_now" value="Yes" id="pay_now1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="pay_now" value="No" id="pay_now2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Intsallment No:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="installment_no" value="Yes" id="installment_no1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="installment_no" value="No" id="installment_no2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Tuition Fees:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="tuition_fees" value="Yes" id="tuition_fees1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="tuition_fees" value="No" id="tuition_fees2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Total Pay:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="total_pay" value="Yes" id="total_pay1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="total_pay" value="No" id="total_pay2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">The Sum Of:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="the_sum_of" value="Yes" id="the_sum_of1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="the_sum_of" value="No" id="the_sum_of2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Remarks:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="remarks" value="Yes" id="remarks1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="remarks" value="No" id="remarks2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input type="submit" class="btn btn-success" name="submit" id="receipt_confi_upds">
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Rexeipt -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="myLargeModalLabel">Edit Receipt Permission</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="receipt_upd_msg"></div>
          <input type="hidden" name="" class="form-control" id="upd_receipt_permission_id" />
          <div class="card">
            <div class="branch-items row mb-2">
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Receipt Type:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_type" value="GST" id="receipt_type2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>GST</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_type" value="NoN GST" id="receipt_type1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>NoN GST</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Branch Logo:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="logo" value="Yes" id="logo1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="logo" value="No" id="logo2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Brnach Address:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="address" value="Yes" id="address1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="address" value="No" id="address2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Branch Title:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="b_title" value="Yes" id="branch_title1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="b_title" value="No" id="branch_title2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Course</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="course" value="Yes" id="course1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="course" value="No" id="course2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Receipt No:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_no" value="Yes" id="receipt_no1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_no" value="No" id="receipt_no2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Receipt Date:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_date" value="Yes" id="receipt_date1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="receipt_date" value="No" id="receipt_date2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Gr ID:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="gr_id" value="Yes" id="gr_id1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="gr_id" value="No" id="gr_id2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Enrollment No:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="enrollment_no" value="Yes" id="enrollment_no1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="enrollment_no" value="No" id="enrollment_no2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">GST No:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="gst_number" value="Yes" id="gst_no1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="gst_number" value="No" id="gst_no2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Name:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="name" value="Yes" id="name1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="name" value="No" id="name2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Fees Paid:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="pay_now" value="Yes" id="pay_now1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="pay_now" value="No" id="pay_now2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Intsallment No:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="installment_no" value="Yes" id="installment_no1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="installment_no" value="No" id="installment_no2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Tuition Fees:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="tuition_fees" value="Yes" id="tuition_fees1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="tuition_fees" value="No" id="tuition_fees2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Total Pay:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="total_pay" value="Yes" id="total_pay1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="total_pay" value="No" id="total_pay2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">The Sum Of:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="the_sum_of" value="Yes" id="the_sum_of1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="the_sum_of" value="No" id="the_sum_of2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-group">
                  <label class="d-block">Remarks:</label>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="remarks" value="Yes" id="remarks1">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>Yes</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-jelly p-round p-bigger">
                    <input class="form-check-input" type="radio" name="remarks" value="No" id="remarks2">
                    <div class="state p-info">
                      <i class="icon material-icons">done</i>
                      <label>No</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input type="submit" class="btn btn-success" name="submit" id="receipt_confi_add">
        </div>
        </form>
      </div>
    </div>
  </div>                                                                                            

    <script src="<?php echo base_url(); ?>dist/assets/js/app.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/izitoast/js/iziToast.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="<?php echo base_url(); ?>dist/assets/js/page/toastr.js"></script>
    <!-- JS Libraies -->
    <script src="<?php echo base_url(); ?>dist/assets/bundles/apexcharts/apexcharts.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="<?php echo base_url(); ?>dist/assets/js/page/index.js"></script>
    <!-- JS Libraies -->
    <script src="<?php echo base_url(); ?>dist/assets/bundles/datatables/datatables.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/jquery-ui/jquery-ui.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="<?php echo base_url(); ?>dist/assets/js/page/datatables.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/cleave-js/dist/cleave.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/cleave-js/dist/addons/cleave-phone.us.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.0-beta/js/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="<?php echo base_url(); ?>dist/assets/js/page/forms-advanced-forms.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/js/scripts.js"></script>
    <!-- Custom JS File -->
    <script src="<?php echo base_url(); ?>dist/assets/js/custom.js"></script>
    <script src="<?php echo base_url(); ?>dist/assets/bundles/izitoast/js/iziToast.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="<?php echo base_url(); ?>dist/assets/js/page/toastr.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
      // just for the demos, avoids form submit
      //   jQuery.validator.setDefaults({
      //     debug: true,
      //     success: "valid"
      //   });
      $("#branch_add").validate({
        rules: {
          w_template_name: {
            //required: true,
          },
          w_template_message: {
            // required: true
          }
        },
        messages: {
          w_template_name: {
            required: "<div style='color:red'>Enter Template Name</div>",
          },
          w_template_message: {
            required: "<div style='color:red'>Please enter template Message</div>"
          }
        },
        submitHandler: function() {
          event.preventDefault();
          var form = $('#branch_add')[0];
          var data = new FormData(form);

          $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "<?php echo base_url(); ?>AdminSettings/ajax_branch_submit",
            type: "post",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function(resp) {
              var data = $.parseJSON(resp);
              var ddd = data['all_record'].status;
              if (ddd == '1') {
                $('#msg_doc').html(iziToast.success({
                  title: 'Success',
                  timeout: 2000,
                  message: 'HI! This Record Inserted.',
                  position: 'topRight'
                }));

                setTimeout(function() {
                  location.reload();
                }, 2020);
              } else if (ddd == '2') {
                $('#msg_doc').html(iziToast.success({
                  title: 'success',
                  timeout: 2000,
                  message: 'HI! This Record Updated',
                  position: 'topRight'
                }));

                setTimeout(function() {
                  location.reload();
                }, 2020);
              } else if (ddd == '3') {
                $('#msg_doc').html(iziToast.error({
                  title: 'Canceled!',
                  timeout: 2000,
                  message: 'Someting Wrong!!',
                  position: 'topRight'
                }));

                setTimeout(function() {
                  location.reload();
                }, 2020);
              }
            }
          });
        }
      });

      function doc_upd(branch_id) {
        console.log(branch_id);
        $.ajax({
          url: "<?php echo base_url(); ?>AdminSettings/get_record_branch",
          type: "post",
          data: {
            'branch_id': branch_id
          },
          success: function(resp) {
            $("#createbranch").modal();
            var data = $.parseJSON(resp);

            var branch_id = data['single_record'].branch_id;
            var session = data['single_record'].session;
            var branch_name = data['single_record'].branch_name;
            var admin_id = data['single_record'].admin_id;
            var country_id = data['single_record'].country_id;
            var state_id = data['single_record'].state_id;
            var city_id = data['single_record'].city_id;
            var branch_code = data['single_record'].branch_code;
            var branch_title = data['single_record'].branch_title;
            var email = data['single_record'].email;
            var phone_landline_no = data['single_record'].phone_landline_no;
            var mobile_one = data['single_record'].mobile_one;
            var mobile_two = data['single_record'].mobile_two;
            var website_name = data['single_record'].website_name;
            var pan_no = data['single_record'].pan_no;
            var CIN = data['single_record'].CIN;
            var bank_name = data['single_record'].bank_name;
            var account_name = data['single_record'].account_name;
            var account_no = data['single_record'].account_no;
            var ifsc = data['single_record'].ifsc;
            var account_type = data['single_record'].account_type;
            var gst_no = data['single_record'].gst_no;
            var branch_address = data['single_record'].branch_address;
            var branch_logo = data['single_record'].branch_logo;

            $('#branch_id').val(branch_id);
            $('#branch_name').val(branch_name);

            var arr = session.split(",");

            for (i = 0; i < arr.length; i++) {
              //console.log(arr[i]);
              $('#session option[value=' + arr[i] + ']').attr('selected', true);
            }
            //$('#session option[value=' + session + ']').attr('selected', true);
            $('#admin_id').val(admin_id);
            $('#country_id').val(country_id);
            $('#state_id').val(state_id);
            $('#city_id').val(city_id);
            $('#branch_code').val(branch_code);
            $('#branch_title').val(branch_title);
            $('#email').val(email);
            $('#phone_landline_no').val(phone_landline_no);
            $('#mobile_one').val(mobile_one);
            $('#mobile_two').val(mobile_two);
            $('#website_name').val(website_name);
            $('#pan_no').val(pan_no);
            $('#CIN').val(CIN);
            $('#bank_name').val(bank_name);
            $('#account_name').val(account_name);
            $('#account_no').val(account_no);
            $('#ifsc').val(ifsc);
            $('#gst_no').val(gst_no);
            $('#branch_address').val(branch_address);
            $("#branch_logo").attr("src", "http://demo.rnwmultimedia.com/dist/branchlogo/" + branch_logo);

            $('#submit').val('Update');
          }

        });
      }

      function doc_status_upd(branch_id, status) {
        console.log(branch_id);
        $.ajax({
          url: "<?php echo base_url(); ?>AdminSettings/update_status",
          type: "post",
          data: {
            'id': branch_id,
            'status': status,
            'table': 'branch',
            'field': 'branch_status',
            'check_field': 'branch_id'
          },
          success: function(resp) {
            var data = $.parseJSON(resp);
            var ddd = data['status'].status;
            console.log("dddddd", ddd);
            if (ddd == '1') {
              $('#msg').html(iziToast.success({
                title: 'success',
                timeout: 2000,
                message: 'HI! Branch status updated.',
                position: 'topRight'
              }));


              setTimeout(function() {
                location.reload();
              }, 2020);
            } else if (ddd == '2') {
              $('#msg').html(iziToast.error({
                title: 'Canceled!',
                timeout: 2000,
                message: '',
                position: 'topRight'
              }));

              setTimeout(function() {
                location.reload();
              }, 2020);
            }
          }
        });
      }

      function remove_doc(branch_id) {
        var conf = confirm("Are U Sure to Delete Record");
        if (conf) {
          $.ajax({
            url: "<?php echo base_url(); ?>AdminSettings/delete_record",
            type: "post",
            data: {
              'id': branch_id,
              'table': 'branch',
              'field': 'branch_id'
            },
            success: function(resp) {
              var data = $.parseJSON(resp);
              var ddd = data['all_record'].status;
              console.log("dddddd", ddd);
              if (ddd == '1') {
                $('#msg').html(iziToast.success({
                  title: 'success',
                  timeout: 2000,
                  message: 'HI! This Record Deleted.',
                  position: 'topRight'
                }));


                setTimeout(function() {
                  location.reload();
                }, 2020);
              } else if (ddd == '2') {
                $('#msg').html(iziToast.error({
                  title: 'Canceled!',
                  timeout: 2000,
                  message: '',
                  position: 'topRight'
                }));

                setTimeout(function() {
                  location.reload();
                }, 2020);
              }
            }
          });
        }
      }
    </script>
    <script type="text/javascript">
      function get_record_permission(branch_id) {
        $('#modal_open').modal('show');
        $.ajax({
          url: "<?php echo base_url(); ?>AdminSettings/get_record_receipt",
          type: "post",
          data: {
            'branch_id': branch_id
          },
          success: function(res) {
            $('#modal_open').modal('show');
            var data = $.parseJSON(res);

            $('#upd_receipt_permission_id').val(data.record['single_record'].receipt_permission_id);

            var receipt_type = data.record['single_record'].receipt_type;
            if (receipt_type == "NoN GST") {
              $("#receipt_type1").prop("checked", true);
            } else {
              $("#receipt_type2").prop("checked", true);
            }

            var logo = data.record['single_record'].logo;
            if (logo == "Yes") {
              $("#logo1").prop("checked", true);
            } else {
              $("#logo2").prop("checked", true);
            }

            var branch_title = data.record['single_record'].branch_title;
            if (branch_title == "Yes") {
              $("#branch_title1").prop("checked", true);
            } else {
              $("#branch_title2").prop("checked", true);
            }

            var address = data.record['single_record'].address;
            if (address == "Yes") {
              $("#address1").prop("checked", true);
            } else {
              $("#address2").prop("checked", true);
            }

            var course = data.record['single_record'].course;
            if (course == "Yes") {
              $("#course1").prop("checked", true);
            } else {
              $("#course2").prop("checked", true);
            }

            var receipt_no = data.record['single_record'].receipt_no;
            if (receipt_no == "Yes") {
              $("#receipt_no1").prop("checked", true);
            } else {
              $("#receipt_no2").prop("checked", true);
            }

            var receipt_date = data.record['single_record'].receipt_date;
            if (receipt_date == "Yes") {
              $("#receipt_date1").prop("checked", true);
            } else {
              $("#receipt_date2").prop("checked", true);
            }

            var gr_id = data.record['single_record'].gr_id;
            if (gr_id == "Yes") {
              $("#gr_id1").prop("checked", true);
            } else {
              $("#gr_id2").prop("checked", true);
            }

            var enrollment_no = data.record['single_record'].enrollment_no;
            if (enrollment_no == "Yes") {
              $("#enrollment_no1").prop("checked", true);
            } else {
              $("#enrollment_no2").prop("checked", true);
            }

            var gst_no = data.record['single_record'].gst_no;
            if (gst_no == "Yes") {
              $("#gst_no1").prop("checked", true);
            } else {
              $("#gst_no2").prop("checked", true);
            }

            var name = data.record['single_record'].name;
            if (name == "Yes") {
              $("#name1").prop("checked", true);
            } else {
              $("#name2").prop("checked", true);
            }

            var pay_now = data.record['single_record'].pay_now;
            if (pay_now == "Yes") {
              $("#pay_now1").prop("checked", true);
            } else {
              $("#pay_now2").prop("checked", true);
            }

            var remarks = data.record['single_record'].remarks;
            if (remarks == "Yes") {
              $("#remarks1").prop("checked", true);
            } else {
              $("#remarks2").prop("checked", true);
            }

            var installment_no = data.record['single_record'].installment_no;
            if (installment_no == "Yes") {
              $("#installment_no1").prop("checked", true);
            } else {
              $("#installment_no2").prop("checked", true);
            }

            var tuition_fees = data.record['single_record'].tuition_fees;
            if (tuition_fees == "Yes") {
              $("#tuition_fees1").prop("checked", true);
            } else {
              $("#tuition_fees2").prop("checked", true);
            }

            var total_pay = data.record['single_record'].total_pay;
            if (total_pay == "Yes") {
              $("#total_pay1").prop("checked", true);
            } else {
              $("#total_pay2").prop("checked", true);
            }

            var the_sum_of = data.record['single_record'].the_sum_of;
            if (the_sum_of == "Yes") {
              $("#the_sum_of1").prop("checked", true);
            } else {
              $("#the_sum_of2").prop("checked", true);
            }
          }

        });

      }
      $('#receipt_confi_upds').on('click', function() {

        var receipt_permission_id = $('#upd_receipt_permission_id').val();
        var receipt_type = $('input[name="receipt_type"]:checked').val();
        var logo = $('input[name="logo"]:checked').val();
        var address = $('input[name="address"]:checked').val();
        var branch_title = $('input[name="b_title"]:checked').val();
        var course = $('input[name="course"]:checked').val();
        var receipt_no = $('input[name="receipt_no"]:checked').val();
        var receipt_date = $('input[name="receipt_date"]:checked').val();
        var gr_id = $('input[name="gr_id"]:checked').val();
        var enrollment_no = $('input[name="enrollment_no"]:checked').val();
        var gst_no = $('input[name="gst_number"]:checked').val();
        var name = $('input[name="name"]:checked').val();
        var pay_now = $('input[name="pay_now"]:checked').val();
        var installment_no = $('input[name="installment_no"]:checked').val();
        var tuition_fees = $('input[name="tuition_fees"]:checked').val();
        var total_pay = $('input[name="total_pay"]:checked').val();
        var the_sum_of = $('input[name="the_sum_of"]:checked').val();
        var remarks = $('input[name="remarks"]:checked').val();

        $.ajax({
          type: "POST",
          url: "<?php echo base_url() ?>AdminSettings/Admission_upd_receipt_permission",
          data: {
            'receipt_permission_id': receipt_permission_id,
            'receipt_type': receipt_type,
            'logo': logo,
            'address': address,
            'branch_title': branch_title,
            'course': course,
            'receipt_no': receipt_no,
            'receipt_date': receipt_date,
            'gr_id': gr_id,
            'enrollment_no': enrollment_no,
            'gst_no': gst_no,
            'name': name,
            'pay_now': pay_now,
            'installment_no': installment_no,
            'tuition_fees': tuition_fees,
            'total_pay': total_pay,
            'the_sum_of': the_sum_of,
            'remarks': remarks
          },
          success: function(resp) {
            var data = $.parseJSON(resp);
            var ddd = data['all_record'].status;
            if (ddd == '1') {
              $('#receipt_upd_msg').fadeIn();
              $('#receipt_upd_msg').html("<div class='alert alert-success' role='alert'><b>Successfully Updated Permission</b></div>");
              $('#receipt_upd_msg').fadeOut(3000);

              setTimeout(function() {
                location.reload();
              }, 2500);
            } else if (ddd == '2') {
              $('#receipt_upd_msg').fadeIn();
              $('#receipt_upd_msg').html("<div class='alert alert-danger' role='alert'><b>Someting Wrong!!</b></div>");
              $('#receipt_upd_msg').fadeOut(3000);

              setTimeout(function() {
                location.reload();
              }, 2500);
            }
          }
        });
        return false;
      });

      function UpdateUserData(receipt) {
        var id = "", flag = "";
        var items = document.getElementsByName('mark');

        for (var i = 0; i < items.length; i++) {
          if (items[i].type == 'checkbox') {
            if (items[i].checked) {
              receipt.map((a) => {
                if (a.branch_id === items[i].value) {
                  flag = "found"
                }
              })
            }
          }
        }
        
        if (flag === "") {
          for (var i = 0; i < items.length; i++) {
            if (items[i].type == 'checkbox') {
              if (items[i].checked) {
                console.log("idddd", items[i].value);
                //if (id === ) {
                  if (id == "") {
                    id = items[i].value;
                  } else {
                    id = id + "," + items[i].value;
                  }
                //}
              }
            }
          }

          if (id != "") {
            $('#id').val(id);
            
            $('#myModal').modal("show").on('click', '#updateok', function(e) {

            });
          } else {
            alert("Please select branch.");
          }

          } else {
            alert("Selected branch already configured.")
          }
      }
      $('input[name=mark]:checked').map(function() {
        branch_id.push($(this).val());
      });

      $('#receipt_confi_add').on('click', function() {

        var branch_id = [];

        $('input[name=mark]:checked').map(function() {
          branch_id.push($(this).val());
        });

        var receipt_type = $('input[name="receipt_type"]:checked').val();
        var logo = $('input[name="logo"]:checked').val();
        var address = $('input[name="address"]:checked').val();
        var branch_title = $('input[name="b_title"]:checked').val();
        var course = $('input[name="course"]:checked').val();
        var receipt_no = $('input[name="receipt_no"]:checked').val();
        var receipt_date = $('input[name="receipt_date"]:checked').val();
        var gr_id = $('input[name="gr_id"]:checked').val();
        var enrollment_no = $('input[name="enrollment_no"]:checked').val();
        var gst_no = $('input[name="gst_number"]:checked').val();
        var name = $('input[name="name"]:checked').val();
        var pay_now = $('input[name="pay_now"]:checked').val();
        var installment_no = $('input[name="installment_no"]:checked').val();
        var tuition_fees = $('input[name="tuition_fees"]:checked').val();
        var total_pay = $('input[name="total_pay"]:checked').val();
        var the_sum_of = $('input[name="the_sum_of"]:checked').val();
        var remarks = $('input[name="remarks"]:checked').val();

        $.ajax({
          type: "POST",
          url: "<?php echo base_url() ?>AdminSettings/Admission_receipt_permission",
          data: {
            'branch_id': branch_id,
            'receipt_type': receipt_type,
            'logo': logo,
            'address': address,
            'branch_title': branch_title,
            'course': course,
            'receipt_no': receipt_no,
            'receipt_date': receipt_date,
            'gr_id': gr_id,
            'enrollment_no': enrollment_no,
            'gst_no': gst_no,
            'name': name,
            'pay_now': pay_now,
            'installment_no': installment_no,
            'tuition_fees': tuition_fees,
            'total_pay': total_pay,
            'the_sum_of': the_sum_of,
            'remarks': remarks
          },
          success: function(resp) {
            var data = $.parseJSON(resp);
            var ddd = data['all_record'].status;
            if (ddd == '1') {
              $('#msg').html(iziToast.success({
                title: 'success',
                timeout: 2000,
                message: 'HI! Successfully Posted Permission.',
                position: 'topRight'
              }));


              setTimeout(function() {
                location.reload();
              }, 2020);
            } else if (ddd == '2') {
              $('#msg').html(iziToast.error({
                title: 'Canceled!',
                timeout: 2000,
                message: '',
                position: 'topRight'
              }));

              setTimeout(function() {
                location.reload();
              }, 2020);
            }
          }
        });
        return false;
      });
    </script>

    </body>
    <!-- index.html  21 Nov 2019 03:47:04 GMT -->

    </html>