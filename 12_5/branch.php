<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assetslogin/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assetslogin/css/main.css">
    
  <link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/skins/_all-skins.min.css">
  
   <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/select2.min.css">

   <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<style type="text/css">
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #2255a4;
    color: white;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}
.select2-container--default .select2-selection--multiple {
    line-height: 20px;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    list-style: none;
    }

.form-control:focus {
    border-color: #3c8dbc !important;
     box-shadow: block !important; 
}
</style>

<style>
         /* The customcheck */
         .customcheck {
         display: block;
         position: relative;
         padding-left: 35px;
         margin-bottom: 12px;
         cursor: pointer;
         -webkit-user-select: none;
         -moz-user-select: none;
         -ms-user-select: none;
         user-select: none;
         }
         /* Hide the browser's default checkbox */
         .customcheck input {
         position: absolute;
         opacity: 0;
         cursor: pointer;
         }
         /* Create a custom checkbox */
         .checkmark {
         position: absolute;
         top: 0;
         left: 0;
         height: 25px;
         width: 25px;
         background-color: #eee;
         border-radius: 5px;
         }
         /* On mouse-over, add a grey background color */
         .customcheck:hover input ~ .checkmark {
         background-color: #ccc;
         }
         /* When the checkbox is checked, add a blue background */
         .customcheck input:checked ~ .checkmark {
         background-color: #02cf32;
         border-radius: 5px;
         }
         /* Create the checkmark/indicator (hidden when not checked) */
         .checkmark:after {
         content: "";
         position: absolute;
         display: none;
         }
         /* Show the checkmark when checked */
         .customcheck input:checked ~ .checkmark:after {
         display: block;
         }
         /* Style the checkmark/indicator */
         .customcheck .checkmark:after {
         left: 9px;
         top: 5px;
         width: 5px;
         height: 10px;
         border: solid white;
         border-width: 0 3px 3px 0;
         -webkit-transform: rotate(45deg);
         -ms-transform: rotate(45deg);
         transform: rotate(45deg);
         }
      </style>
      <style>
         .form-group input[type="checkbox"] {
         display: none;
         }
         .form-group input[type="checkbox"] + .btn-group > label span {
         width: 10px;
         }
         .form-group input[type="checkbox"] + .btn-group > label span:first-child {
         display: none;
         }
         .form-group input[type="checkbox"] + .btn-group > label span:last-child {
         display: inline-block;   
         }
         .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
         display: inline-block;
         }
         .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
         display: none;   
         }
         .modal-title{
         font-size: 18px;
         color: black;
         }
      </style>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.1/themes/fontawesome-stars.min.css" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.1/themes/bars-reversed.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <style>
         .br-theme-bars-reversed .br-widget a {
         background-color: pink;
         }
         .br-theme-bars-reversed .br-widget a.br-active,
         .br-theme-bars-reversed .br-widget a.br-selected {
         background-color: #ff446a;
         }
         .br-theme-bars-reversed .br-widget .br-current-rating {
         color: #ff446a;
         font-size: 20px;  
         }
         .checked {
         color: orange;
         }
         .tbl{
         background:#7460ee;
         color: white;
         }
         .all-btn a {margin: 0 5px;}
         .box-header {padding: 8px 0;}
         .btn {border-radius: 0; transition: all 0.5s;}
         .text-white {color: white;}
         .all-btn span {padding: 0 10px; background: white; margin-left: 10px; border-radius: 5px;}
         .inactive-a {background: rgb(66,133,244);}
         .inactive-a>span {color: rgb(66,133,244);}
         .active-a {background: rgb(244,180,0);}
         .active-a>span {color: rgb(244,180,0);}
         .postpone-a {background: rgb(171,71,188);}
         .postpone-a>span {color: rgb(171,71,188);}
         .confirm-a {background: rgb(15,157,88);}
         .confirm-a>span {color: rgb(15,157,88);}
         .discart-a {background: rgb(219,68,55);}
         .discart-a>span {color: rgb(219,68,55);}
         .profile-a {background: rgb(0, 172, 193);}
         .profile-a>span {color: rgb(0, 172, 193);}
         .inactive-a:hover, .inactive-a:focus,
         .active-a:hover, .active-a:focus,
         .postpone-a:hover, .postpone-a:focus,
         .confirm-a:hover, .confirm-a:focus,
         .discart-a:hover, .discart-a:focus,
         .profile-a:hover, .profile-a:focus {
         color: white;
         }
         .nav-tabs>li>a {margin-right: 15px;}
         .nav-tabs>li.active>a,
         .nav-tabs>li.active>a:focus,
         .nav-tabs>li.active>a:hover {border-radius: 0;}
         .form-control {
         height: auto;
         padding: 4px 7px;
         line-height: 24px;
         }
         .modal-title{
         font-size: 20px;
         }
         .em{
         color: black;
         }
      </style>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        BRANCH
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
       
        <li class="active">Branch</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
		 <div class="col-md-8">
          <!-- general form elements -->
          <div class="box box-primary" style="padding:20px;">
            <div class="box-header with-border" style="margin-bottom:10px;">
              <h3 class="box-title"><span id="tilte_status">CREATE BRANCH</span></h3>
            </div>
          
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url(); ?>settings/branch">
             <div class="row" style="padding:10px">
               <div class="col-xl-12">
                  <input type="hidden" name="update_id" value="<?php if(!empty($select_branch->branch_id)) { echo $select_branch->branch_id; } ?>"/>
                          <div class="col-md-3">
                          <div class="form-group">
                          <label>Session:<span style="color: red;">*</span></label>
                          
                              <select class="select2 form-control" name="no_year[]" multiple="multiple" >
                                 <option>Select Year</option>

                                        <?php for($i=2019;$i<=2030;$i++){ ?>
                                          <option <?php if($i==@$select_branch->session) { echo "selected"; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                          </select>
                          
                          </div>
                        </div>
                        <?php if($_SESSION['logtype']=="Admin") { ?>
                           <div class="col-md-3">
                                <div class="form-group">
                                  <label for="pwd">Branch Name:<span style="color: red;">*</span></label>
                                 <input class="form-control"  value="<?php if(!empty($select_branch->branch_name)) { echo $select_branch->branch_name; } ?>"  type="text"  name="branch_name" placeholder="Enter Branch Name" required>
                                </div>
                              </div>
                         <div class="col-md-3">
                              <div class="form-group" >
                                  <label for="pwd">Branch Code:<span style="color: red;">*</span></label>
                                 <input class="form-control"  value="<?php if(!empty($select_branch->branch_code)) { echo $select_branch->branch_code; } ?>"  type="text"  name="branch_code" placeholder="Enter Branch Code" required>
                                </div> 
                                </div> 
                             <?php } else { ?> 
                              <div class="col-md-3"> 
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
                             <div class="col-md-3">
                            <div class="form-group" >
                            <label for="pwd">Branch Name:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->branch_name)) { echo $select_branch->branch_name; } ?>"  type="text"  name="branch_name" placeholder="Enter Branch Name" required>
                          </div>
                        </div>
                             <div class="col-md-3">
                            <div class="form-group" >
                            <label for="pwd">Branch Code:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->branch_code)) { echo $select_branch->branch_code; } ?>"  type="text"  name="branch_code" placeholder="Enter Branch Code" required>
                          </div>  
                        </div>
                        <?php } ?>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Branch Title:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->branch_title)) { echo $select_branch->branch_title; } ?>"  type="text"  name="branch_title" placeholder="Enter Branch Title" required>
                          </div>  
                        </div>
                         <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Country:<span style="color: red;">*</span></label>
                            <select required class="form-control" name="country_id"  required>
                                <option value="">Select Country</option>
                                  <?php foreach($country_all as $val) { ?>
                                    <option <?php if($val->country_id==@$select_branch->country_id) { echo "selected"; } ?>   value="<?php echo $val->country_id; ?>"><?php echo $val->country_name; ?></option>
                                  <?php } ?>
                              </select>
                          </div> 
                        </div>
                          <div class="col-md-3"> 
                          <div class="form-group">
                            <label for="pwd">State:<span style="color: red;">*</span></label>
                            <select required class="form-control" name="state_id"  required>
                                <option value="">Select State</option>
                                  <?php foreach($state_all as $val) { ?>
                                    <option <?php if($val->state_id==@$select_branch->state_id) { echo "selected"; } ?>   value="<?php echo $val->state_id; ?>"><?php echo $val->state_name; ?></option>
                                  <?php } ?>
                              </select>
                          </div>
                        </div>
                        <div class="col-md-3"> 
                          <div class="form-group">
                            <label for="pwd">City:<span style="color: red;">*</span></label>
                            <select required class="form-control" name="city_id"  required>
                                <option value="">Select City</option>
                                  <?php foreach($city_all as $val) { ?>
                                    <option <?php if($val->city_id==@$select_branch->city_id) { echo "selected"; } ?>   value="<?php echo $val->city_id; ?>"><?php echo $val->city_name; ?></option>
                                  <?php } ?>
                              </select>
                          </div>
                        </div>  
                                               <div class="col-md-3">
                         <div class="form-group">
                            <label for="email">Email:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->email)) { echo $select_branch->email; } ?>"  type="text"  name="email" placeholder="Enter Email ID" required>
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Phone(Landline):<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->phone_landline_no)) { echo $select_branch->phone_landline_no; } ?>"  type="text"  name="phone_landline_no" placeholder="Enter Landline No" required>
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Mobile 1:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->mobile_one)) { echo $select_branch->mobile_one; } ?>"  type="text"  name="mobile_one" placeholder="Enter Mobile No" required>
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Mobile 2:</label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->mobile_two)) { echo $select_branch->mobile_two; } ?>"  type="text"  name="mobile_two" placeholder="Enter Mobile No">
                          </div>  
                        </div>
                         <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Website:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->website_name)) { echo $select_branch->website_name; } ?>"  type="text"  name="website_name" placeholder="Enter Website Name" required>
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Pan No:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->pan_no)) { echo $select_branch->pan_no; } ?>"  type="text"  name="pan_no" placeholder="Enter Pan No">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">CIN:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->CIN)) { echo $select_branch->CIN; } ?>"  type="text"  name="CIN" placeholder="Enter CIN">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">GST Number:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->gst_no)) { echo $select_branch->gst_no; } ?>"  type="text"  name="gst_no" placeholder="Enter GST No">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Bank Name:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->bank_name)) { echo $select_branch->bank_name; } ?>"  type="text"  name="bank_name" placeholder="Eneter Bank Name">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Account Holder Name:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->account_name)) { echo $select_branch->account_name; } ?>"  type="text"  name="account_name" placeholder="Eneter Holder Name">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Account No:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->account_no)) { echo $select_branch->account_no; } ?>"  type="text"  name="account_no" placeholder="Eneter Account No">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">IFSC No:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->ifsc)) { echo $select_branch->ifsc; } ?>"  type="text"  name="ifsc" placeholder="Eneter IFSC No">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Account Type:<span style="color: red;">*</span></label>
                           <input class="form-control"  value="<?php if(!empty($select_branch->account_type)) { echo $select_branch->account_type; } ?>"  type="text"  name="account_type" placeholder="Like Saving">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Branch Logo:<span style="color: red;">*</span></label>
                            <input class="form-control" type="file" name="branch_logo">
                          </div>  
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                            <label for="pwd">Address:<span style="color: red;">*</span></label>
                           <textarea class="form-control" type="text" name="branch_address" rows="2" required><?php if(!empty($select_branch->branch_address)) { echo $select_branch->branch_address; } ?></textarea>
                          </div>  
                        </div>
                      <div class="col-md-12">
                         <div class="form-group">
                            <input type="submit" name="submit" value="Submit" class="btn btn-success">
                          </div>  
                        </div>
                        
                        
                          <!-- <input type="submit" name="submit" value="submit" class="btn btn-success" style="margin-left: 12.5px;">                         -->
          </div>
        </div>
      
        </div>
        </div>

      </form>
      </div>
        <div class="row">
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-success" style="padding:20px;">
            <div class="box-header with-border">
              <h3 class="box-title">Display BRANCH</h3>
            </div>
            <!-- <button class="btn btn-info" style="margin-left: 89%;" data-toggle="modal" data-target="#myModal">Create Receipt Configration</button> -->
            <!-- /.box-header -->
             <div class="[ form-group ]">
             <input type="checkbox" name="fancy-checkbox-default" id="fancy-checkbox-default" autocomplete="off" />
             <div class="[ btn-group ]">
                <label for="fancy-checkbox-default" onclick='selectAll()'  class="[ btn btn-sm btn-default ]">
                <span class="[ glyphicon glyphicon-ok ]"></span>
                <span> </span>
                </label>
                <label for="fancy-checkbox-default" id="selectall" onclick='selectAll()' class="[ btn btn-sm btn-default active ]">
                Select All
                </label>
                <label for="fancy-checkbox-default" id="unselectall" style="display:none"  onclick='selectAll()' class="[ btn btn-sm btn-default active ]">
                 Branch Permission
                </label>
             </div>
             <button type="button" class="btn btn-sm btn-success" onclick='UpdateUserData()'>Receipt Configration</button>
          </div>
              <table id="example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>SNo</th>
                  <th>Logo</th>
                  <th>Details-1</th>
                  <th>Details-2</th>
                  <th>Bank-Info</th>
                  <th>Status</th>
                  <th>action</th>
                </tr>
                </thead>
                <tbody>
                <?php $sno=1; foreach($branch_all as $val) { ?>
                <tr>
                  <td>
                    <label class="customcheck"><?php echo $sno; ?>
                    <input type="checkbox" name="mark" value="<?php echo $val->branch_id; ?>">
                    <span class="checkmark"></span>
                    </label>
                  </td>
                  <td><img src="http://demo.rnwmultimedia.com/dist/branchlogo/<?php echo $val->branch_logo;  ?>" class="img-responsive" alt="Cinque Terre" width="100" height="100"></td>
                   <td> 
                       <?php echo "<b>Branch Name : </b>". $val->branch_name?><br>
                       <?php echo "<b>Branch Code : </b>". $val->branch_code?><br>
                       <?php echo "<b>Branch Title : </b>". $val->branch_title?><br>
                       <?php echo "<b>Website : </b>". $val->website_name?><br>
                       <?php echo "<b>Email : </b>". $val->email?><br>
                       <?php echo "<b>Mobile No 1 : </b>". $val->mobile_one?><br>
                       <?php echo "<b>Mobile NO 2 : </b>". $val->mobile_two?><br>
                       <?php echo "<b>LandlineNo : </b>". $val->phone_landline_no?><br>
                       <?php echo "<b>Address : </b>". $val->branch_address?>
                  </td>
                  <td>
                    <?php   $adminids = explode(",",$val->admin_id);
                        foreach($user_all as $row) { if(in_array($row->user_id,$adminids)) {  echo "<b>Admin : </b>". $row->name; }  } ?><br>
                        <?php  $countryids = explode(",",$val->country_id);
                        foreach($country_all as $row) { if(in_array($row->country_id,$countryids)) {  echo "<b>Country : </b>". $row->country_name; }  } ?><br>
                        <?php  $stateids = explode(",",$val->state_id);
                        foreach($state_all as $row) { if(in_array($row->state_id,$stateids)) {  echo "<b>State : </b>". $row->state_name; }  } ?><br>
                        <?php  $cityids = explode(",",$val->city_id);
                        foreach($city_all as $row) { if(in_array($row->city_id,$cityids)) {  echo "<b>City : </b>". $row->city_name; }  } ?><br>
                        <?php echo "<b>Pan No : </b>". $val->pan_no?><br>
                       <?php echo "<b>CIN : </b>". $val->CIN?><br>
                       <?php echo "<b>GST No : </b>". $val->gst_no?><br>
                  </td>
                   <td> 
                       <?php echo "<b>Bank Name : </b>". $val->bank_name?><br>
                       <?php echo "<b>Account Holder Name : </b>". $val->account_name?><br>
                       <?php echo "<b>Account No : </b>". $val->account_no?><br>
                       <?php echo "<b>IFSC Code : </b>". $val->ifsc?><br>
                       <?php echo "<b>Account Type : </b>". $val->account_type?>
                  </td>
                  <td><label style="color:#a6a6a6"> <?php if($val->branch_status=="0") { echo "Active"; } if($val->branch_status=="1") { echo  "Disable"; } ?> </label></td>
                  <td>
                     
                  	
                  	
                  	    <div class="dropdown">
                            <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown">Action
                            <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                              <li><a href="<?php echo base_url(); ?>settings/branch?action=edit&id=<?php echo @$val->branch_id; ?>"> Edit</a></li>
                              <li><a href="#" onclick="return get_record_permission(<?php echo $val->branch_id; ?>)"><i class="fas fa-pen-square" ></i>Edit Receipt</a></li>
                              <li><a href="<?php echo base_url(); ?>settings/branch?action=delete&id=<?php echo @$val->branch_id; ?>"onclick="return confirm('Are you sure you want to delete this task?');">Delete</a></li>
                              <?php if($val->branch_status==0) { ?>
                              <li ><a href="<?php echo base_url(); ?>settings/branch?action=status&id=<?php echo @$val->branch_id; ?>&status=<?php echo @$val->branch_status; ?>">Disable</a></li>
                              <?php } else {  ?>
                              
                              <li ><a href="<?php echo base_url(); ?>settings/branch?action=status&id=<?php echo @$val->branch_id; ?>&status=<?php echo @$val->branch_status; ?>">Active</a></li>
                             <?php } ?>
                            </ul>
                          </div>
                  	
                  	</td>
                </tr>
                
             <?php $sno++; } ?>
                </tbody>
              </table>
          </div>
        </div>
            
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><b>Receipt Permission</b></h4>
      </div>
      <div class="modal-body">
        <div id="receipt_msg"></div>
          <div class="col-xl-12">
            <div class="col-md-4">
              <label for="pwd">Receipt Type:<span style="color: red;">*</span></label><br>
             <input type="radio" name="receipt_type" value="GST">GST
             <input type="radio" name="receipt_type" value="NoN GST" checked>NoN GST
          </div>
             <div class="col-md-4">
              <label for="pwd">Branch Logo:<span style="color: red;">*</span></label><br>
             <input type="radio" name="logo" value="Yes">Yes
             <input type="radio" name="logo" value="No">No
          </div>
               <div class="col-md-4">
              <div class="form-group" >
              <label for="pwd">Brnach Address:<span style="color: red;">*</span></label><br>
              <input type="radio" name="address" value="Yes">Yes
             <input type="radio" name="address" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Branch Title:<span style="color: red;">*</span></label><br>
             <input type="radio" name="b_title" value="Yes">Yes
             <input type="radio" name="b_title" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Course:<span style="color: red;">*</span></label><br>
             <input type="radio" name="course" value="Yes">Yes
             <input type="radio" name="course" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Receipt No:<span style="color: red;">*</span></label><br>
             <input type="radio" name="receipt_no" value="Yes">Yes
             <input type="radio" name="receipt_no" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Receipt Date:<span style="color: red;">*</span></label><br>
             <input type="radio" name="receipt_date" value="Yes">Yes
             <input type="radio" name="receipt_date" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Gr ID:<span style="color: red;">*</span></label><br>
             <input type="radio" name="gr_id" value="Yes">Yes
             <input type="radio" name="gr_id" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Enrollment No:<span style="color: red;">*</span></label><br>
             <input type="radio" name="enrollment_no" value="Yes">Yes
             <input type="radio" name="enrollment_no" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">GST No:<span style="color: red;">*</span></label><br>
             <input type="radio" name="gst_number" value="Yes">Yes
             <input type="radio" name="gst_number" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Name:<span style="color: red;">*</span></label><br>
             <input type="radio" name="name" value="Yes">Yes
             <input type="radio" name="name" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Fees Paid:<span style="color: red;">*</span></label><br>
             <input type="radio" name="pay_now" value="Yes">Yes
             <input type="radio" name="pay_now" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Intsallment No:<span style="color: red;">*</span></label><br>
             <input type="radio" name="installment_no" value="Yes">Yes
             <input type="radio" name="installment_no" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Tuition Fees:<span style="color: red;">*</span></label><br>
             <input type="radio" name="tuition_fees" value="Yes">Yes
             <input type="radio" name="tuition_fees" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Total Pay:<span style="color: red;">*</span></label><br>
             <input type="radio" name="total_pay" value="Yes">Yes
             <input type="radio" name="total_pay" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">The Sum Of:<span style="color: red;">*</span></label><br>
             <input type="radio" name="the_sum_of" value="Yes">Yes
             <input type="radio" name="the_sum_of" value="No">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Remarks:<span style="color: red;">*</span></label><br>
             <input type="radio" name="remarks" value="Yes">Yes
             <input type="radio" name="remarks" value="No">No
            </div>  
          </div>
          <div class="col-md-12">
           <div class="form-group">
          <input type="submit" class="btn btn-success" name="submit" id="receipt_confi_add">
        </div>
      </div>
          </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
      </div>
    </div>

  </div>
</div>

<!-- updateAdmission Permission -->

<div id="modal_open" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><b>Edit Receipt Permission</b></h4>
      </div>
      <div class="modal-body">
        <div id="receipt_upd_msg"></div>
        <input type="hidden" name="" class="form-control" id="upd_receipt_permission_id">
          <div class="col-xl-12">
            <div class="col-md-4">
              <label for="pwd">Receipt Type:<span style="color: red;">*</span></label><br>
             <input type="radio" name="receipt_type" value="GST" id="receipt_type2">GST
             <input type="radio" name="receipt_type" value="NoN GST" id="receipt_type1">NoN GST
          </div>
             <div class="col-md-4">
              <label for="pwd">Branch Logo:<span style="color: red;">*</span></label><br>
             <input type="radio" name="logo" value="Yes" id="logo1">Yes
             <input type="radio" name="logo" value="No" id="logo2">No
          </div>
               <div class="col-md-4">
              <div class="form-group" >
              <label for="pwd">Brnach Address:<span style="color: red;">*</span></label><br>
              <input type="radio" name="address" value="Yes" id="address1">Yes
             <input type="radio" name="address" value="No" id="address2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Branch Title:<span style="color: red;">*</span></label><br>
             <input type="radio" name="b_title" value="Yes" id="branch_title1">Yes
             <input type="radio" name="b_title" value="No" id="branch_title2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Course:<span style="color: red;">*</span></label><br>
             <input type="radio" name="course" value="Yes" id="course1">Yes
             <input type="radio" name="course" value="No" id="course2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Receipt No:<span style="color: red;">*</span></label><br>
             <input type="radio" name="receipt_no" value="Yes" id="receipt_no1">Yes
             <input type="radio" name="receipt_no" value="No" id="receipt_no2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Receipt Date:<span style="color: red;">*</span></label><br>
             <input type="radio" name="receipt_date" value="Yes" id="receipt_date1">Yes
             <input type="radio" name="receipt_date" value="No" id="receipt_date2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Gr ID:<span style="color: red;">*</span></label><br>
             <input type="radio" name="gr_id" value="Yes" id="gr_id1">Yes
             <input type="radio" name="gr_id" value="No" id="gr_id2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Enrollment No:<span style="color: red;">*</span></label><br>
             <input type="radio" name="enrollment_no" value="Yes" id="enrollment_no1">Yes
             <input type="radio" name="enrollment_no" value="No" id="enrollment_no2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">GST No:<span style="color: red;">*</span></label><br>
             <input type="radio" name="gst_number" value="Yes" id="gst_no1">Yes
             <input type="radio" name="gst_number" value="No" id="gst_no2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Name:<span style="color: red;">*</span></label><br>
             <input type="radio" name="name" value="Yes" id="name1">Yes
             <input type="radio" name="name" value="No" id="name2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Fees Paid:<span style="color: red;">*</span></label><br>
             <input type="radio" name="pay_now" value="Yes" id="pay_now1">Yes
             <input type="radio" name="pay_now" value="No" id="pay_now2">No
            </div>  
          </div>
           <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Intsallment No:<span style="color: red;">*</span></label><br>
             <input type="radio" name="installment_no" value="Yes" id="installment_no1">Yes
             <input type="radio" name="installment_no" value="No" id="installment_no2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Tuition Fees:<span style="color: red;">*</span></label><br>
             <input type="radio" name="tuition_fees" value="Yes" id="tuition_fees1">Yes
             <input type="radio" name="tuition_fees" value="No" id="tuition_fees2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Total Pay:<span style="color: red;">*</span></label><br>
             <input type="radio" name="total_pay" value="Yes" id="total_pay1">Yes
             <input type="radio" name="total_pay" value="No" id="total_pay2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">The Sum Of:<span style="color: red;">*</span></label><br>
             <input type="radio" name="the_sum_of" value="Yes" id="the_sum_of1">Yes
             <input type="radio" name="the_sum_of" value="No" id="the_sum_of2">No
            </div>  
          </div>
          <div class="col-md-4">
           <div class="form-group">
              <label for="pwd">Remarks:<span style="color: red;">*</span></label><br>
             <input type="radio" name="remarks" value="Yes" id="remarks1">Yes
             <input type="radio" name="remarks" value="No" id="remarks2">No
            </div>  
          </div>
          <div class="col-md-12">
           <div class="form-group">
          <input type="submit" class="btn btn-success" name="submit" id="receipt_confi_upds">
        </div>
      </div>
          </div>
      </div>
     <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
      </div>
    </div>
     

  </div>
</div>
    
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    
    <strong>Copyright©2018 Red & White Multimedia.</strong> All rights
    reserved.
  </footer>

 
</div>

<script src="<?php echo base_url(); ?>assetslogin/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assetslogin/js/main.js"></script>



<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(); ?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>
<!-- page script -->

<script src="<?php echo base_url(); ?>dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/js/select2.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.1/jquery.barrating.min.js"></script>

  <script>
  $('#receipt_confi_add').on('click', function() {
     
    var branch_id = [];

    $('input[name=mark]:checked').map(function() {
         branch_id.push($(this).val()); });
    
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
       url: "<?php echo base_url() ?>Settings/Admission_receipt_permission",
       data: {
         'branch_id' : branch_id ,'receipt_type' : receipt_type , 'logo' : logo , 'address' : address , 'branch_title' : branch_title , 'course' : course ,  'receipt_no' : receipt_no ,'receipt_date' : receipt_date , 'gr_id' : gr_id , 'enrollment_no' : enrollment_no , 'gst_no' : gst_no , 'name' : name , 'pay_now' : pay_now ,'installment_no' : installment_no , 'tuition_fees' : tuition_fees , 'total_pay' : total_pay ,'the_sum_of' : the_sum_of ,'remarks' : remarks },    
       success: function(resp) 
       {
            var data = $.parseJSON(resp);
            var ddd = data['all_record'].status;
            if(ddd == '1')
            {
                $('#receipt_msg').fadeIn();
                $('#receipt_msg').html("<div class='alert alert-success' role='alert'><b>Successfully Posted Permission</b></div>");
                $('#receipt_msg').fadeOut(3000);

                setTimeout(function() {
                    location.reload();
                }, 2500);
            }
            else if(ddd == '2')
            {
                $('#receipt_msg').fadeIn();
                $('#receipt_msg').html("<div class='alert alert-danger' role='alert'><b>Someting Wrong!!</b></div>");
                $('#receipt_msg').fadeOut(3000);

                setTimeout(function() {
                    location.reload();
                }, 2500);
            }
       }
     });
     return false;
   });

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
       url: "<?php echo base_url() ?>Settings/Admission_upd_receipt_permission",
       data: {
         'receipt_permission_id' : receipt_permission_id ,'receipt_type' : receipt_type , 'logo' : logo , 'address' : address , 'branch_title' : branch_title , 'course' : course ,  'receipt_no' : receipt_no ,'receipt_date' : receipt_date , 'gr_id' : gr_id , 'enrollment_no' : enrollment_no , 'gst_no' : gst_no , 'name' : name , 'pay_now' : pay_now ,'installment_no' : installment_no , 'tuition_fees' : tuition_fees , 'total_pay' : total_pay , 'the_sum_of' : the_sum_of ,'remarks' : remarks },    
       success: function(resp) 
       {
            var data = $.parseJSON(resp);
            var ddd = data['all_record'].status;
            if(ddd == '1')
            {
                $('#receipt_upd_msg').fadeIn();
                $('#receipt_upd_msg').html("<div class='alert alert-success' role='alert'><b>Successfully Updated Permission</b></div>");
                $('#receipt_upd_msg').fadeOut(3000);

                setTimeout(function() {
                    location.reload();
                }, 2500);
            }
            else if(ddd == '2')
            {
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
</script>

<script type="text/javascript">
  function get_record_permission(branch_id)
   {
    $('#modal_open').modal('show');
       $.ajax({
         url: "<?php echo base_url(); ?>Settings/get_record_receipt",
         type : "post",
         data :{ 'branch_id' : branch_id },
         success:function(res)
         {
          $('#modal_open').modal('show');
           var data = $.parseJSON(res);
   
           $('#upd_receipt_permission_id').val(data.record['single_record'].receipt_permission_id);

            var receipt_type = data.record['single_record'].receipt_type;
            if(receipt_type=="NoN GST")
            {
              $("#receipt_type1").prop("checked", true);
            }
            else
            {
              $("#receipt_type2").prop("checked", true);
            }

            var logo = data.record['single_record'].logo;
            if(logo=="Yes")
            {
              $("#logo1").prop("checked", true);
            }
            else
            {
              $("#logo2").prop("checked", true);
            }

            var branch_title = data.record['single_record'].branch_title;
            if(branch_title=="Yes")
            {
              $("#branch_title1").prop("checked", true);
            }
            else
            {
              $("#branch_title2").prop("checked", true);
            }

            var address = data.record['single_record'].address;
            if(address=="Yes")
            {
              $("#address1").prop("checked", true);
            }
            else
            {
              $("#address2").prop("checked", true);
            }

            var course = data.record['single_record'].course;
            if(course=="Yes")
            {
              $("#course1").prop("checked", true);
            }
            else
            {
              $("#course2").prop("checked", true);
            }

            var receipt_no = data.record['single_record'].receipt_no;
            if(receipt_no=="Yes")
            {
              $("#receipt_no1").prop("checked", true);
            }
            else
            {
              $("#receipt_no2").prop("checked", true);
            }

            var receipt_date = data.record['single_record'].receipt_date;
            if(receipt_date=="Yes")
            {
              $("#receipt_date1").prop("checked", true);
            }
            else
            {
              $("#receipt_date2").prop("checked", true);
            }

            var gr_id = data.record['single_record'].gr_id;
            if(gr_id=="Yes")
            {
              $("#gr_id1").prop("checked", true);
            }
            else
            {
              $("#gr_id2").prop("checked", true);
            }

            var enrollment_no = data.record['single_record'].enrollment_no;
            if(enrollment_no=="Yes")
            {
              $("#enrollment_no1").prop("checked", true);
            }
            else
            {
              $("#enrollment_no2").prop("checked", true);
            }

            var gst_no = data.record['single_record'].gst_no;
            if(gst_no=="Yes")
            {
              $("#gst_no1").prop("checked", true);
            }
            else
            {
              $("#gst_no2").prop("checked", true);
            }

            var name = data.record['single_record'].name;
            if(name=="Yes")
            {
              $("#name1").prop("checked", true);
            }
            else
            {
              $("#name2").prop("checked", true);
            }

            var pay_now = data.record['single_record'].pay_now;
            if(pay_now=="Yes")
            {
              $("#pay_now1").prop("checked", true);
            }
            else
            {
              $("#pay_now2").prop("checked", true);
            }

            var remarks = data.record['single_record'].remarks;
            if(remarks=="Yes")
            {
              $("#remarks1").prop("checked", true);
            }
            else
            {
              $("#remarks2").prop("checked", true);
            }

            var installment_no = data.record['single_record'].installment_no;
            if(installment_no=="Yes")
            {
              $("#installment_no1").prop("checked", true);
            }
            else
            {
              $("#installment_no2").prop("checked", true);
            }

            var tuition_fees = data.record['single_record'].tuition_fees;
            if(tuition_fees=="Yes")
            {
              $("#tuition_fees1").prop("checked", true);
            }
            else
            {
              $("#tuition_fees2").prop("checked", true);
            }

            var total_pay = data.record['single_record'].total_pay;
            if(total_pay=="Yes")
            {
              $("#total_pay1").prop("checked", true);
            }
            else
            {
              $("#total_pay2").prop("checked", true);
            }

             var the_sum_of = data.record['single_record'].the_sum_of;
            if(the_sum_of=="Yes")
            {
              $("#the_sum_of1").prop("checked", true);
            }
            else
            {
              $("#the_sum_of2").prop("checked", true);
            }
         }
   
       });
   
   }
</script>
   <script>
        //***********************************//
        // For select 2
        //***********************************//
        $(".select2").select2();

        /*colorpicker*/
        $('.demo').each(function() {
        //
        // Dear reader, it's actually very easy to initialize MiniColors. For example:
        //
        //  $(selector).minicolors();
        //
        // The way I've done it below is just for the demo, so don't get confused
        // by it. Also, data- attributes aren't supported at this time...they're
        // only used for this demo.
        //
        $(this).minicolors({
                control: $(this).attr('data-control') || 'hue',
                position: $(this).attr('data-position') || 'bottom left',

                change: function(value, opacity) {
                    if (!value) return;
                    if (opacity) value += ', ' + opacity;
                    if (typeof console === 'object') {
                        console.log(value);
                    }
                },
                theme: 'bootstrap'
            });

        });
        

    </script>



<script>
   function selectAll(){
     if($('#fancy-checkbox-default').prop("checked") == true){
        var items=document.getElementsByName('mark');
       for(var i=0; i<items.length; i++){
         if(items[i].type=='checkbox')
           items[i].checked=false;
       }
 
       $('#selectall').show();
       $('#unselectall').hide();
     }
     else
     {
      var items=document.getElementsByName('mark');
 
       for(var i=0; i<items.length; i++){
         if(items[i].type=='checkbox')
           items[i].checked=true;
       }
 
         $('#selectall').hide();
       $('#unselectall').show();

   }
 }

function UpdateUserData()
{
     var id="";
     var items=document.getElementsByName('mark');
   for(var i=0; i<items.length; i++){
     if(items[i].type=='checkbox')
     {
         if(items[i].checked)
         {
             if(id=="")
             {
                 id =items[i].value;
             }
             else
             {
                 id = id+","+items[i].value;
             }
         }
     }
   }

   if(id!="")
   {
       $('#id').val(id);
       $('#myModal').modal("show").on('click', '#updateok', function(e) {

   });
   }
   else
   {
       alert("Please Select Row");
   }
}      
</script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

</body>
</html>
