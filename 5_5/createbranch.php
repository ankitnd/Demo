<link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/jquery-selectric/selectric.css">
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
                        <button href="#" class="btn btn-info" data-toggle="modal" data-target="#filterbranch">
                        <span><i class="fas fa-filter mr-1"></i>Filter</span>
                        </button>
                        <button class="btn">
                        <span><i class="fas fa-arrow-left mr-1"></i> Back</span>
                        </button>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-striped normal-table branch-table" id="table-1">
                           <thead>
                              <tr>
                                <th class="text-center">
                                    <div class="custom-checkbox custom-checkbox-table custom-control">
                                    <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                                        class="custom-control-input" id="checkbox-all">
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
                                <?php $sno=1; foreach($branch_all as $val) { ?>
                                    <tr>
                                        <td class="p-0 text-center">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                                    value="<?php echo $val->branch_id; ?>">
                                                <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td><?php echo $sno; ?></td>
                                        <td><img src="http://demo.rnwmultimedia.com/dist/branchlogo/<?php echo $val->branch_logo;  ?>" width="90px" style="box-shadow: none;border: 0;border-radius: 0;"/></td> 
                                        <td>
                                            <strong>Branch Name :</strong> <span><?php echo $val->branch_name; ?></span> </br>
                                            <strong>Branch Code :</strong> <span><?php echo $val->branch_code; ?></span> </br>
                                            <strong>Branch Title :</strong> <span><?php echo $val->branch_title; ?></span> </br>
                                            <strong>Website :</strong> <span><?php echo $val->website_name; ?></span> </br>
                                            <strong>Email :</strong> <span><?php echo $val->email; ?></span> </br>
                                            <strong>Mobile No 1 :</strong> <span><?php echo $val->mobile_one; ?></span></br> 
                                            <strong>Mobile No 2 :</strong> <span><?php echo $val->mobile_two; ?> </span> </br>
                                            <strong>Landline No :</strong> <span><?php echo $val->phone_landline_no; ?></span>      </br>                      
                                            <strong>Address :</strong> <span><?php echo $val->branch_address; ?></span>   </br>                        
                                        </td>
                                        <td>
                                            <?php   $adminids = explode(",",$val->admin_id);
                                                foreach($user_all as $row) { if(in_array($row->user_id,$adminids)) {  echo "<strong>Admin : </strong><span>". $row->name . "</span>"; }  } ?><br>
                                                <?php  $countryids = explode(",",$val->country_id);
                                                foreach($country_all as $row) { if(in_array($row->country_id,$countryids)) {  echo "<strong>Country : </strong><span>". $row->country_name . "</span>"; }  } ?><br>
                                                <?php  $stateids = explode(",",$val->state_id);
                                                foreach($state_all as $row) { if(in_array($row->state_id,$stateids)) {  echo "<strong>State : </strong><span>". $row->state_name . "</span>"; }  } ?><br>
                                                <?php  $cityids = explode(",",$val->city_id);
                                                foreach($city_all as $row) { if(in_array($row->city_id,$cityids)) {  echo "<strong>City : </strong><span>". $row->city_name . "</span>"; }  } ?><br>
                                                <?php echo "<strong>Pan No : </strong><span>". $val->pan_no . "</span>"?><br>
                                                <?php echo "<strong>CIN : </strong><span>". $val->CIN . "</span>"?><br>
                                                <?php echo "<strong>GST No : </strong><span>". $val->gst_no . "</span>"?><br>
                                        </td>
                                        <td> 
                                            <?php echo "<strong>Bank Name : </strong><span>". $val->bank_name . "</span>"?><br>
                                            <?php echo "<strong>Account Holder Name : </strong>". $val->account_name . "</span>"?><br>
                                            <?php echo "<strong>Account No : </strong>". $val->account_no . "</span>"?><br>
                                            <?php echo "<strong>IFSC Code : </strong>". $val->ifsc . "</span>"?><br>
                                            <?php echo "<strong>Account Type : </strong>". $val->account_type . "</span>"?>
                                        </td>
                                        <td><label style="color:#a6a6a6"> <?php if($val->branch_status=="0") { echo "Active"; } if($val->branch_status=="1") { echo  "Disable"; } ?> </label></td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                                <div class="dropdown-menu">
                                                    <a
                                                        class="dropdown-item has-icon" 
                                                        href="<?php echo base_url(); ?>settings/branch?action=edit&id=<?php echo @$val->branch_id; ?>"
                                                    >
                                                        <i class="far fa-edit"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item has-icon" href="#" class="dropdown-item has-icon" onclick="return get_record_permission(<?php echo $val->branch_id; ?>)"><i class="fas fa-pen-square" ></i>Edit Receipt</a>
                                                    <a  class="dropdown-item has-icon text-danger" href="<?php echo base_url(); ?>settings/branch?action=delete&id=<?php echo @$val->branch_id; ?>"onclick="return confirm('Are you sure you want to delete this task?');"><i class="far fa-trash-alt"></i> Delete</a>
                                                    <?php if($val->branch_status==0) { ?>
                                                        <a class="dropdown-item has-icon" href="<?php echo base_url(); ?>settings/branch?action=status&id=<?php echo @$val->branch_id; ?>&status=<?php echo @$val->branch_status; ?>"><i class="fas fa-ban" ></i> Disable</a>
                                                    <?php } else {  ?>
                                                        <a class="dropdown-item has-icon" href="<?php echo base_url(); ?>settings/branch?action=status&id=<?php echo @$val->branch_id; ?>&status=<?php echo @$val->branch_status; ?>"><i class="far fa-check-circle"></i> Active</a>
                                                    <?php } ?>
                                                </div>
                                        </td>
                                    <!-- <td>
                                        <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                        <div class="dropdown-menu">
                                            <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                                            <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> View</a>
                                            <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                                            Delete</a>
                                        </div>
                                        </div>
                                    </td>  -->
                                </tr>
                                <?php $sno++; } ?>
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
        <form enctype="multipart/form-data" class="document-createmodal"  method="post" name="branch_add" id="branch_add">
            <div class="modal-body">
                <div class="card"> 
                    <div class="branch-items row mb-2">
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="">Session :</label>
                            <select class="select2 form-control" name="session[]" multiple="multiple" >
                                 <option>Select Year</option>

                                        <?php for($i=2019;$i<=2030;$i++){ ?>
                                          <option <?php if($i==@$select_branch->session) { echo "selected"; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                        <?php if($_SESSION['logtype']=="Admin") { ?>
                           <div class="form-group col-md-4 col-sm-12">
                                <div class="form-group">
                                  <label for="pwd">Branch Name:<span style="color: red;">*</span></label>
                                 <input class="form-control"  value="<?php if(!empty($select_branch->branch_name)) { echo $select_branch->branch_name; } ?>"  type="text"  name="branch_name" placeholder="Enter Branch Name" required>
                                </div>
                              </div>
                         <div class="form-group col-md-4 col-sm-12">
                              <div class="form-group" >
                                  <label for="pwd">Branch Code:<span style="color: red;">*</span></label>
                                 <input class="form-control"  value="<?php if(!empty($select_branch->branch_code)) { echo $select_branch->branch_code; } ?>"  type="text"  name="branch_code" placeholder="Enter Branch Code" required>
                                </div> 
                                </div> 
                             <?php } else { ?> 
                              <div class="form-group col-md-4 col-sm-12"> 
                              <div class="form-group">
                                <label for="email">Admin:<span style="color: red;">*</span></label>
                                <select required class="form-control" name="admin_id"  required>
                                      <option value="">Select Admin</option>
                                        <?php foreach($user_all as $val) { ?>
                                          <option <?php if($val->user_id==@$select_branch->admin_id) { echo "selected"; } ?>   value="<?php echo $val->user_id; ?>"><?php echo $val->name; ?></option>
                                        <?php } ?>
                                    </select>
                              </div>
                            </div>
                             <div class="form-group col-md-4 col-sm-12">
                            <div class="form-group" >
                            <label for="pwd">Branch Name:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->branch_name)) { echo $select_branch->branch_name; } ?>"  type="text"  name="branch_name" placeholder="Enter Branch Name" required>
                          </div>
                        </div>
                             <div class="form-group col-md-4 col-sm-12">
                            <div class="form-group" >
                            <label for="pwd">Branch Code:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->branch_code)) { echo $select_branch->branch_code; } ?>"  type="text"  name="branch_code" placeholder="Enter Branch Code" required>
                          </div>  
                        </div>
                        <?php } ?>
                        <!-- <div class="form-group col-md-4 col-sm-12">
                            <label for="">Branch Name :</label>
                            <input name="branch_name" type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="">Branch Code :</label>
                            <input name="branch_code" type="text" class="form-control">
                        </div> -->
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Branch Title:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->branch_title)) { echo $select_branch->branch_title; } ?>"  type="text"  name="branch_title" placeholder="Enter Branch Title" required>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Branch Logo:<span style="color: red;">*</span></label>
                            <input class="form-control" type="file" name="branch_logo">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Website:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->website_name)) { echo $select_branch->website_name; } ?>"  type="text"  name="website_name" placeholder="Enter Website Name" required>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="email">Email:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->email)) { echo $select_branch->email; } ?>"  type="text"  name="email" placeholder="Enter Email ID" required>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Mobile No 1 :<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->mobile_one)) { echo $select_branch->mobile_one; } ?>"  type="text"  name="mobile_one" placeholder="Enter Mobile No" required>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                        <label for="pwd">Mobile No 2:</label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->mobile_two)) { echo $select_branch->mobile_two; } ?>"  type="text"  name="mobile_two" placeholder="Enter Mobile No">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Landline No :<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->phone_landline_no)) { echo $select_branch->phone_landline_no; } ?>"  type="text"  name="phone_landline_no" placeholder="Enter Landline No" required>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Address :<span style="color: red;">*</span></label>
                            <textarea class="form-control" type="text" name="branch_address" rows="2" required><?php if(!empty($select_branch->branch_address)) { echo $select_branch->branch_address; } ?></textarea>
                        </div>
                    </div>
                    <div class="branch-items row mb-2">
                        <!-- <div class="form-group  col-md-4 col-sm-12">
                            <label for="">Admin :</label>
                            <select required class="form-control" name="admin_id"  required>
                                <option value="">Select Admin</option>
                                <option value="65">Hiral Khunt</option>
                            </select>
                        </div> -->
                        <div class="form-group  col-md-4 col-sm-12">
                            <label for="pwd">Country:<span style="color: red;">*</span></label>
                            <select required class="form-control" name="country_id"  required>
                                <option value="">Select Country</option>
                                  <?php foreach($country_all as $val) { ?>
                                    <option <?php if($val->country_id==@$select_branch->country_id) { echo "selected"; } ?>   value="<?php echo $val->country_id; ?>"><?php echo $val->country_name; ?></option>
                                  <?php } ?>
                              </select>
                        </div>
                        <div class="form-group  col-md-4 col-sm-12">
                            <label for="pwd">State:<span style="color: red;">*</span></label>
                            <select required class="form-control" name="state_id"  required>
                                <option value="">Select State</option>
                                  <?php foreach($state_all as $val) { ?>
                                    <option <?php if($val->state_id==@$select_branch->state_id) { echo "selected"; } ?>   value="<?php echo $val->state_id; ?>"><?php echo $val->state_name; ?></option>
                                  <?php } ?>
                              </select>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">City:<span style="color: red;">*</span></label>
                            <select required class="form-control" name="city_id"  required>
                                <option value="">Select City</option>
                                  <?php foreach($city_all as $val) { ?>
                                    <option <?php if($val->city_id==@$select_branch->city_id) { echo "selected"; } ?>   value="<?php echo $val->city_id; ?>"><?php echo $val->city_name; ?></option>
                                  <?php } ?>
                              </select>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Pan No:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->pan_no)) { echo $select_branch->pan_no; } ?>"  type="text"  name="pan_no" placeholder="Enter Pan No">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">CIN:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->CIN)) { echo $select_branch->CIN; } ?>"  type="text"  name="CIN" placeholder="Enter CIN">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">GST No:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->gst_no)) { echo $select_branch->gst_no; } ?>"  type="text"  name="gst_no" placeholder="Enter GST No">
                        </div>
                    </div>
                    <div class="branch-items row mb-2">
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Bank Name:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->bank_name)) { echo $select_branch->bank_name; } ?>"  type="text"  name="bank_name" placeholder="Eneter Bank Name">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Account Holder Name:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->account_name)) { echo $select_branch->account_name; } ?>"  type="text"  name="account_name" placeholder="Eneter Holder Name">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Account No:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->account_no)) { echo $select_branch->account_no; } ?>"  type="text"  name="account_no" placeholder="Eneter Account No">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">IFSC No:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->ifsc)) { echo $select_branch->ifsc; } ?>"  type="text"  name="ifsc" placeholder="Eneter IFSC No">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="pwd">Account Type:<span style="color: red;">*</span></label>
                            <input class="form-control"  value="<?php if(!empty($select_branch->account_type)) { echo $select_branch->account_type; } ?>"  type="text"  name="account_type" placeholder="Like Saving">
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
            <div class="card"> 
                <div class="branch-items row mb-2">
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="">Branch Name :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="">Mobile No 1  :</label>
                        <input type="text" class="form-control">
                    </div> 
                    <div class="form-group  col-md-6 col-sm-12">
                        <label for="">Admin :</label>
                        <input type="text" class="form-control" >
                    </div> 
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="">Bank Name :</label>
                        <input type="text" class="form-control">
                    </div> 
                </div>
            </div>
            <button type="button" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-light text-dark">Reset</button>
        </div>
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
      var formdata = $('#branch_add').serialize();
     // console.log("Hello", formdata)
      $.ajax({
        url: "<?php echo base_url(); ?>AdminSettings/ajax_branch_submit",
        type: "post",
        data: formdata,
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
        }else if(ddd == '2'){
          $('#msg_doc').html(iziToast.success({
            title: 'success',
            timeout: 2000,
            message: 'HI! This Record Updated',
            position: 'topRight'
          }));

          setTimeout(function() {
            location.reload();
          }, 2020);
        }
         else if(ddd == '3'){
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

  function doc_upd(documents_id) {
    $.ajax({
      url: "<?php echo base_url(); ?>Admission/get_record_docpermission",
      type: "post",
      data: {
        'documents_id': documents_id
      },
      success: function(resp) {
        $(".createdoc").modal();
        var data = $.parseJSON(resp);
        var documents_id = data['single_record'].documents_id;
        var branch_id = data['single_record'].branch_id;
        var department_id = data['single_record'].department_id;
        var photos = data['single_record'].photos;
        var tenth_marksheet = data['single_record'].tenth_marksheet;
        var twelth_marksheet = data['single_record'].twelth_marksheet;
        var leaving_certy = data['single_record'].leaving_certy;
        var treal_certy = data['single_record'].treal_certy;
        var light_bill = data['single_record'].light_bill;
        var aadhar_card = data['single_record'].aadhar_card;
        
        $('#documents_id').val(documents_id);
        $('#branch').val(branch_id);
        $('#department').val(department_id);
        if(photos=="yes")
        {
          $("#photos_yes").prop("checked", true);
        }
        else
        {
          $("#photos_no").prop("checked", true);
        }
        if(tenth_marksheet=="yes")
        {
          $("#tenth_marksheet_yes").prop("checked", true);
        }
        else
        {
          $("#tenth_marksheet_no").prop("checked", true);
        }
        if(twelth_marksheet=="yes")
        {
          $("#twelth_marksheet_yes").prop("checked", true);
        }
        else
        {
          $("twelth_marksheet_no").prop("checked", true);
        }
        if(leaving_certy=="yes")
        {
          $("#leaving_certy_yes").prop("checked", true);
        }
        else
        {
          $("#leaving_certy_no").prop("checked", true);
        }
        if(treal_certy=="yes")
        {
          $("#treal_certy_yes").prop("checked", true);
        }
        else
        {
          $("#treal_certy_no").prop("checked", true);
        }
        if(light_bill=="yes")
        {
          $("#light_bill_yes").prop("checked", true);
        }
        else
        {
          $("#light_bill_no").prop("checked", true);
        }
        if(aadhar_card=="yes")
        {
          $("#aadhar_card_yes").prop("checked", true);
        }
        else
        {
          $("#aadhar_card_no").prop("checked", true);
        }
        
        $('#submit').val('Update');
      }

    });
  }

  function remove_doc(documents_id) {
    var conf =  confirm("Are U Sure to Delete Record");
    if(conf){
        $.ajax({
          url : "<?php echo base_url(); ?>Admission/remove_doc",
          type :"post",
          data:{
            'documents_id' : documents_id
          },
          success:function(resp)
          {
              var data = $.parseJSON(resp);
              var ddd = data['all_record'].status;
              if (ddd == '1') {
              $('#deleted_msg').html(iziToast.success({
                title: 'success',
                timeout: 2000,
                message: 'HI! This Record Deleted.',
                position: 'topRight'
              }));

              setTimeout(function() {
                location.reload();
              }, 2020);
            }else if(ddd == '2'){
              $('#deleted_msg').html(iziToast.error({
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

</body> 
<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>