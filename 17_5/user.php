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
    .th{
      font-size: 12px;
    }
    .td{
      font-size: 11px;
      color: black;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
USER        
</h1>
<ol class="breadcrumb">
<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>

<li class="active">user</li>
</ol>
</section>

<!-- Main content -->
<section class="content">
<div class="row">

<?php if(!empty($_SESSION['msg'])) { ?>
<div class="col-md-12">

<div id="yellow" style="padding:2px 5px 2px 5px" class="box yellow bg-green"><?php echo $_SESSION['msg']; ?></div>
</div>
<?php } ?>
<div class="col-md-12" >
<!-- general form elements -->
<div class="box box-primary">
<div class="box-header with-border">
<h3 class="box-title">Create User </h3>
<a href="<?php echo base_url(); ?>settings/user_logtype">
<button type="button" class="btn btn-info btn-xs pull-right" >Create Logtype</button>
</a>
</div>

<form  method="post" action="<?php echo base_url(); ?>settings/user">
<div class="row" style="padding:10px">
<div class="col-md-6" >
<input type="hidden" name="update_id" value="<?php if(!empty($select_user->user_id)) { echo $select_user->user_id; } ?>"/>
<?php if($_SESSION['logtype'] == "Super Admin"){ ?>
<div class="form-group row">
<label  class="col-md-2">Admin</label>
<div class="col-md-10">
<select  class="form-control" name="admin_id"  id="admin">
<option value="">Select Admin</option>
<?php foreach($user_all as $val) { if($val->status==0 && $val->logtype == "Admin") { ?>
<option <?php if($val->user_id==@$select_user->admin_id) { echo "selected"; } ?>   value="<?php echo $val->user_id; ?>" ><?php echo $val->name; ?></option>
<?php } } ?>
</select>
</div>
</div>
<div class="form-group row">
<label class="col-md-2">Branch</label>
<div class="col-md-10">
    <select class="select2 form-control" name="b_ids[]" id="branch_id" multiple="multiple">
       <option>Select Branch</option>
       <?php $branch_ids = explode(",",$select_user->branch_ids);   
       foreach($branch_all as $row) {  ?> 
    <option <?php if(in_array($row->branch_id,$branch_ids)) { echo "selected"; } ?>
     value="<?php echo $row->branch_id; ?>"><?php echo $row->branch_name; ?></option>
  <?php } ?>  
</select>
</div>
</div>
<?php }else{ ?>
<div class="form-group row">
<label class="col-md-2">Branch</label>
<div class="col-md-10">
    <select class="select2 form-control" name="b_ids[]" id="b_id" multiple="multiple">
       <option>Select Branch</option>
       <?php $branch_ids = explode(",",$select_user->branch_ids);   
       foreach($branch_all as $row) {  ?> 
    <option <?php if(in_array($row->branch_id,$branch_ids)) { echo "selected"; } ?>
     value="<?php echo $row->branch_id; ?>"><?php echo $row->branch_name; ?></option>
  <?php } ?>
</select>
</div>
</div>  
<?php } ?>


<div class="form-group row">
<label class="col-md-2">Department</label>
<div class="col-md-10">
    <select class="select2 form-control" name="depart_ids[]" id="department" multiple="multiple">
       <option>Select Department</option>
       <?php $departids = explode(",",$select_user->department_ids);   
       foreach($department_all as $row) {  ?> 
    <option <?php if(in_array($row->department_id,$departids)) { echo "selected"; } ?>
     value="<?php echo $row->department_id; ?>"><?php echo $row->department_name; ?></option>
  <?php } ?>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-2">Sub Department</label>
<div class="col-md-10">
<select class="select2 form-control" name="subdepart_ids[]" id="subdepartment" multiple="multiple">
<option>Select SubDepartment</option>
<?php $subdepartid = explode(",",$select_user->subdepartment_ids);   
foreach($subdepartment_all as $row) {  ?> 
<option <?php if(in_array($row->subdepartment_id,$subdepartid)) { echo "selected"; } ?>
value="<?php echo $row->subdepartment_id; ?>"><?php echo $row->subdepartment_name; ?></option>
<?php } ?>
</select>
</div>
</div>




<div class="form-group">
<label for="email">Logtype:</label>
<select required class="form-control filterlogtype" name="logtype"  required>
<option value="">Select Logtype</option>
<?php foreach($logtype_all as $val) { if($val->logtype_name != "Admin") { ?>
<option <?php if($val->logtype_name==@$select_user->logtype) { echo "selected"; } ?>   value="<?php echo $val->logtype_id; ?>"><?php echo $val->logtype_name; ?></option>
<?php } } ?>
</select>
</div>
<div class="form-group" >
<label for="pwd">Parent :</label>
<select  class="form-control" name="m_parent_id">
<option value="0">--SELECT PARENT----</option>
<?php foreach ($user_all as $key => $value) { ?>
<option value="<?php echo $value->user_id; ?>" <?php if(isset($select_user) && $select_user->m_parent_id == $value->user_id){echo "selected"; } ?>><?php echo $value->name;  ?></option>
<?php } ?>
</select>
</div>
<div class="form-group" >
<label for="pwd">Log Name:</label>
<input class="form-control"  value="<?php if(!empty($select_user->name)) { echo $select_user->name; } ?>"  type="text"  name="name" placeholder="Enter Name" required>
</div>
<div class="form-group" >
<label for="pwd">Email Id:</label>
<input class="form-control"  value="<?php if(!empty($select_user->email)) { echo $select_user->email; } ?>"  type="email"  name="email" placeholder="Enter Email" required>
</div>
<div class="form-group" >
<label for="pwd">Password :</label>
<input type="password" class="form-control"  value="<?php if(!empty($select_user->password)) { echo $select_user->password; } ?>"  type="text"  name="password" placeholder="Enter password" required>
</div>


</div>  
<div class="col-md-6" >
<div class="form-group"  id="permission_all">

<label for="pwd">Permission :-</label>

<br><br>

<?php if(isset($_GET['id']) && !empty($_GET['id'])){ ?>

<?php foreach ($f_module as $key => $value) { ?>
<h3><?php echo $value->f_module_name; ?><input type="checkbox" name="fp[]" value="<?php echo $value->f_module_name; ?>" <?php if(isset($select_user->f_permission) && in_array($value->f_module_name, explode(",",$select_user->f_permission))){echo "checked";} ?>></h3>
<?php foreach($m_module as $m){ 
if($m->f_module_id == $value->f_module_id){
?>
<label for="pwd"><?php echo $m->module_name; ?> <input type="checkbox" name="m_all[]" value="<?php echo $m->module_name; ?>"<?php if(isset($select_user->m_permission) && in_array($m->module_name, explode(",",$select_user->m_permission))){echo "checked";} ?>>:</label>
<br>
<?php foreach($l_module as $k=> $l){ 
if($l->m_module_id == $m->m_module_id){
?> 
<label style="width:30%;font-weight: normal;"><?php echo $l->name; ?> </label> 
<label class="radio-inline">
<input type="checkbox" name="f_all[]"  value="<?php echo $l->name ;?>" <?php if(isset($select_user->permission) && in_array($l->name, explode(",",$select_user->permission))){echo "checked";} ?>>Yes
</label>

<br>
<?php } } } } }  ?>   



<?php }else{ ?>

<?php
//   $allp = explode(',', $_SESSION['user_permission']);
foreach ($f_module as $key => $value) { ?>
<h3><?php echo $value->f_module_name; ?><input type="checkbox" name="fp[]" value="<?php echo $value->f_module_name; ?>" onclick="change_mod(<?php echo $value->f_module_id; ?>)" ></h3>
<div id="all_change_mod<?php echo $value->f_module_id; ?>"></div>
<?php } ?>

<?php } ?>

</div>
</div> 
<div class="col-md-12" >
<input type="submit" name="submit" value="Save" class="btn btn-primary pull-right" />
</div> 

</div>
</form> 
</div>

</div>
</div>

<div class="row">
<div class="col-md-12" >
<!-- general form elements -->
<div class="box box-success" style="padding:20px;">
<div class="box-header with-border">
<h3 class="box-title">Display User</h3>
</div>
<!-- /.box-header -->

<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>
<th>Logtype</th>
<th>Name</th>
<th>Branch</th>
<th>Department</th>
<th>Last Seen</th>
<th>Status</th>
<th>action</th>
</tr>
</thead>
<tbody>
<?php foreach($user_all as $val) { if($val->logtype != "Admin"){ ?>
<tr>
<td><?php echo $val->logtype; ?></td>
<td><?php echo "Name : ".$val->name; ?>   <?php echo "<br>Email : ".$val->email; ?> </td>
<td><?php $branch_ids = explode(",",$val->branch_ids);
foreach($branch_all as $row) { if(in_array($row->branch_id,$branch_ids)) {  echo $row->branch_name."<br>"; }  } ?></td>
<td><?php $depart_ids = explode(",",$val->department_ids);
foreach($department_all as $row) { if(in_array($row->department_id,$depart_ids)) {  echo $row->department_name."<br>"; }  } ?></td>
<td><?php echo $val->timestamp; ?></td>
<td>
<?php if($val->logtype != "Manager" && $val->logtype != "Admin"){ ?>
<button type="button" onclick="viewManager(<?php echo $val->user_id;?>)" class="btn btn-warning btn-sm" value="<?php echo $val->branch_ids;?>">Manager</button></td>
<?php } ?>
<td>

<?php if($val->user_status == 0){ ?>
<a href="#">Active</a>
<?php }else { ?>
<a href="#">Deactive</a>
<?php } ?>
</td>        
<td>
<div class="dropdown">
<button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown">Action
<span class="caret"></span></button>
<ul class="dropdown-menu">
<li><a href="<?php echo base_url(); ?>settings/user?action=edit&id=<?php echo @$val->user_id; ?>"> Edit</a></li>

<li ><a href="<?php echo base_url(); ?>settings/user?action=delete&id=<?php echo @$val->user_id; ?>">Delete</a></li>

<li ><?php if($val->user_status == 0){ ?>
<a href="<?php echo base_url(); ?>settings/user?action=change_s&id=<?php echo @$val->user_id; ?>&status=0">Active</a>
<?php }else { ?>
<a href="<?php echo base_url(); ?>settings/user?action=change_s&id=<?php echo @$val->user_id; ?>&status=1">Deactive</a>
<?php } ?>
</li>

</ul>
</div>

</td>


</tr>

<?php } } ?>

</tfoot>
</table>


</div>

</div>
</div>


<div class="modal fade"  id="mylogtype" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Logtype</h4>
<h2><a href="<?php echo base_url(); ?>Settings/permission_all_data"> Show all permission</a>
<a href="<?php echo base_url(); ?>Settings/personal_permission_all_data">Personal permission</a>
<a href="<?php echo base_url(); ?>Settings/personal_hod_permission_all_data">HoD</a>
<a href="<?php echo base_url(); ?>Settings/personal_user_permission_all_data">User</a>
</h2>
</div>
<div class="modal-body">

<div class="row">
<form method="post" action="<?php echo base_url(); ?>settings/user">
<div class="col-md-12">
<input type="hidden" name="update_id" value="<?php if(!empty($select_logtype->logtype_id)) { echo $select_logtype->logtype_id; } ?>" >


<?php if($_SESSION['logtype'] == "Super Admin"){ ?>
<div class="form-group">
<label for="email">Log Type:</label>
<input type="text" name="logtype_name" class="form-control" value="<?php if(!empty($select_logtype->logtype_name)) { echo $select_logtype->logtype_name; } ?>" placeholder="Enter Log Type">
</div>
<?php } else{ ?>

<div class="form-group">
<label for="email">Parent Log Type:</label>
<select class="form-control" name="parent_id" >
<option value="0">---select Parent Logtype---</option>
<?php foreach($log_all as $v){ if($v->parent_id == 0){?>
<option value="<?php echo $v->logtype_id ?>" <?php if(isset($select_logtype)){ if($v->logtype_id == $select_logtype->parent_id){echo "selected";} }?>><?php echo $v->logtype_name; ?></option>
<?php } }?>
</select>

</div>

<div class="form-group">
<label for="email">LogType NAME:</label>
<select class="form-control" name="logtype_name" >
<option value="0">---select Logtype Name---</option>
<?php foreach($log_all as $v){  if($v->parent_id == 0 && $v->logtype_name!="Admin"){?>
<option value="<?php echo $v->logtype_name ?>" <?php if(isset($select_logtype)){ if($v->logtype_name == $select_logtype->logtype_name){echo "selected";} }?>><?php echo $v->logtype_name; ?></option>
<?php } }?>
</select>

</div>
<?php } ?>





</div>
<div class="col-md-12" >
<div class="form-group" >

<label for="pwd">Permission :-</label>

<br><br>


<?php if($_SESSION['logtype'] == "Super Admin"){ ?>


<?php if(isset($_GET['id']) && !empty($_GET['id'])){ ?>

<?php foreach ($f_module as $key => $value) { ?>
<h3><?php echo $value->f_module_name; ?><input type="checkbox" name="fp[]" value="<?php echo $value->f_module_name; ?>" <?php if(isset($select_logtype->f_permission) && in_array($value->f_module_name, explode(",",$select_logtype->f_permission))){echo "checked";} ?>></h3>
<?php foreach($m_module as $m){ 
if($m->f_module_id == $value->f_module_id){
?>
<label for="pwd"><?php echo $m->module_name; ?> <input type="checkbox" name="m_all[]" value="<?php echo $m->module_name; ?>"<?php if(isset($select_logtype->m_permission) && in_array($m->module_name, explode(",",$select_logtype->m_permission))){echo "checked";} ?>>:</label>
<br>
<?php foreach($l_module as $k=> $l){ 
if($l->m_module_id == $m->m_module_id){
?> 
<label style="width:30%;font-weight: normal;"><?php echo $l->name; ?> </label> 
<label class="radio-inline">
<input type="checkbox" name="f_all[]"  value="<?php echo $l->name ;?>" <?php if(isset($select_logtype->permission) && in_array($l->name, explode(",",$select_logtype->permission))){echo "checked";} ?>>Yes
</label>
<br>
<?php } } } } }  ?>   



<?php }else{ ?>
<?php foreach ($f_module as $key => $value) { ?>
<h3><?php echo $value->f_module_name; ?><input type="checkbox" name="fp[]" value="<?php echo $value->f_module_name; ?>" onclick="change_mod(<?php echo $value->f_module_id; ?>)" <?php if(isset($select_logtype) && in_array($value->f_module_name, explode(",", $select_logtype->permission))){echo "checked";} ?>></h3>
<div id="all_change_mod<?php echo $value->f_module_id; ?>"></div>
<?php } ?>

<?php } ?>
<?php }else { ?>


<?php if(isset($_GET['id']) && !empty($_GET['id'])){ ?>

<?php foreach ($f_module as $key => $value) { ?>
<h3><?php echo $value->f_module_name; ?><input type="checkbox" name="fp[]" value="<?php echo $value->f_module_name; ?>" <?php if(isset($select_logtype->f_permission) && in_array($value->f_module_name, explode(",",$select_logtype->f_permission))){echo "checked";} ?>></h3>
<?php foreach($m_module as $m){ 
if($m->f_module_id == $value->f_module_id){
?>
<label for="pwd"><?php echo $m->module_name; ?> <input type="checkbox" name="m_all[]" value="<?php echo $m->module_name; ?>"<?php if(isset($select_logtype->m_permission) && in_array($m->module_name, explode(",",$select_logtype->m_permission))){echo "checked";} ?>>:</label>
<br>
<?php foreach($l_module as $k=> $l){ 
if($l->m_module_id == $m->m_module_id){
?> 
<label style="width:30%;font-weight: normal;"><?php echo $l->name; ?> </label> 
<label class="radio-inline">
<input type="checkbox" name="f_all[]"  value="<?php echo $l->name ;?>" <?php if(isset($select_logtype->permission) && in_array($l->name, explode(",",$select_logtype->permission))){echo "checked";} ?>>Yes
</label>

<br>
<?php } } } } }  ?>   



<?php }else{ ?>

<?php
//   $allp = explode(',', $_SESSION['user_permission']);
foreach ($f_module as $key => $value) { ?>
<h3><?php echo $value->f_module_name; ?><input type="checkbox" name="fp[]" value="<?php echo $value->f_module_name; ?>" onclick="change_mod(<?php echo $value->f_module_id; ?>)" ></h3>
<div id="all_change_mod<?php echo $value->f_module_id; ?>"></div>
<?php } ?>

<?php } ?>

<?php } ?>



</div>
<input type="submit" name="logtype_submit" class="btn btn-sm btn-success" value="Save">
</form> 
</div> 
</div>


<table class="table" style="margin-top:15px;">
<thead>
<tr>
<th>Logtype</th>
<th>Hirachiy</th>
<th>Status</th>
<th>Action</th>

</tr>
</thead>
<tbody>
<?php foreach($logtype_all as $val) { ?>
<tr>
<td><?php echo $val->logtype_name; ?></td>
<td><?php if(isset($val->group_nameall)){ foreach ($val->group_nameall as $key => $value) {
echo $value;
}} ?></td>
<td><label style="color:#a6a6a6"> <?php if($val->status=="0") { echo "Active"; } if($val->status=="1") { echo  "Disable"; } ?> </label></td>
<td>
<div class="dropdown">
<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown">Action
<span class="caret"></span></button>
<ul class="dropdown-menu">
<li><a href="<?php echo base_url(); ?>settings/user?logtype_action=edit&id=<?php echo @$val->logtype_id; ?>"> Edit</a></li>
<?php if($val->status==0) { ?>
<li ><a href="<?php echo base_url(); ?>settings/user?logtype_action=delete&id=<?php echo @$val->logtype_id; ?>&status=<?php echo @$val->status; ?>">Disable</a></li>
<?php } else {  ?>

<li ><a href="<?php echo base_url(); ?>settings/user?logtype_action=delete&id=<?php echo @$val->logtype_id; ?>&status=<?php echo @$val->status; ?>">Active</a></li>
<?php } ?>
</ul>
</div>


</td>

</tr>
<?php } ?>
</tbody>
</table>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
</div>
</div>

</div>
</div>

<div class="col-ms-12">
<div id="viewc"></div>

</div>



</section>
<!-- /.content -->



</div>

<!-- /.content-wrapper -->




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
<!-- script>
$(document).ready(function(){
$('#branch').change(function(){
console.log("heloo");
var branch_id = $('#branch').val();
alert(branch_id);
if(branch_id !='')
{
$.ajax({
url:"<?php echo base_url(); ?>settings/fetch_depart_alll",
method:"POST",
data:{branch_id:branch_id},
success:function(data)
{
$('#department').html(data);
// $('#city').html('<option value="">Select City</option>');
}
});
}
else
{
$('#department').html('<option value="">Select Department</option>');
// $('#city').html('<option value="">Select City</option>');
}

});
});
</script> -->

<script src="<?php echo base_url(); ?>dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>dist/js/select2.min.js"></script>
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
<script type="text/javascript">
  $(document).ready(function(){
    $('#admin').change(function(){
      var d = $(this).val();
   
      $.ajax({
        type:'POST',
        data:{

          'id':d
        },
        url:"<?php echo base_url(); ?>settings/filter_branch_wise",
        success:function(data){
          $('#branch_id').html(data);
        }
      });
    });
});
</script>
<script>
  $(document).ready(function(){
 
$('#branch_id').change(function(){
 
  var branch_id = $('#branch_id').val();
  // alert(department_id);
 
   $.ajax({
    url:"<?php echo base_url(); ?>settings/filter_department_wise",
    method:"POST",
    data:{
      'branch_id' : branch_id},
    success:function(data)
    {
     $('#department').html(data);
 
    }
   });
 });

$('#b_id').change(function(){
 
  var branch_id = $('#b_id').val();
  //var course_id = $('#course_orsingle').val();
 // var branch_id =  $('#branch_id').val();
 
   $.ajax({
    url:"<?php echo base_url(); ?>settings/fetch_admin_wise_department",
    method:"POST",
    data:{ 'branch_id' : branch_id },
    success:function(data)
    {
     $('#department').html(data);
 
    }


   });
 });
  });
</script>

<script>
  $(document).ready(function(){
 
$('#department').change(function(){
 
  var department_id = $('#department').val();
  // alert(department_id);
 
   $.ajax({
    url:"<?php echo base_url(); ?>settings/filter_subdepartment_wise",
    method:"POST",
    data:{
      'department_id' : department_id},
    success:function(data)
    {
     $('#subdepartment').html(data);
 
    }
   });
 });
  });
</script>

<script type="text/javascript">
$(document).ready(function(){
$('.filterCheck').change(function(){
/*console.log("called");
console.log($('#filterForm').serialize());*/
// alert($('#filterForm').serialize());
$.ajax({
type:'POST',
data:$('#filterForm').serialize(),
url:"<?php echo base_url(); ?>settings/fetch_depart_alll",
success:function(data){
$('#department').html(data);
}
});
});
});

$(document).ready(function(){
$('.filterlogtype').change(function(){
/*console.log("called");
console.log($('#filterForm').serialize());*/

$.ajax({
type:'POST',
data:$('#filterForm').serialize(),
url:"<?php echo base_url(); ?>settings/fetch_permission_alll",
success:function(data){
$('#permission_all').html(data);
}
});
});
});
</script>
<script type="text/javascript">
function change_mod(id)
{

var a = id;
$.ajax({
url:"<?php echo base_url(); ?>settings/change_f_mod",
type:'POST',
data:{a_name:a},
success:function(res){
$('#all_change_mod'+id).html(res);
}
});
}
</script>
<script>




$(document).ready(function(){
$('#state_id').change(function(){

var s = $('#state_id').val();
alert(s);
if(s !='')
{
$.ajax({
url:"<?php echo base_url(); ?>settings/fetch_cities",
method:"POST",
data:{s_id:s},
success:function(data)
{
$('#city_id').html(data);
// $('#city').html('<option value="">Select City</option>');
}
});
}
else
{
$('#getfaculty').html('<option value="">Select Faculty</option>');
// $('#city').html('<option value="">Select City</option>');
}

});
});
</script>

<!-- page script -->
<?php if(!empty($select_logtype)) { ?>
<script>

$('#mylogtype').modal("show");
</script>
<?php } ?>

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

<script>
function viewManager(id)
{


$.ajax({
url:"<?php echo base_url(); ?>settings/view_member",
type:"post",
data:{
'user_id':id

},
success: function(data) {
$('#viewc').html(data);
$('#myModal_course').modal('show');
}

});
}
</script>
</body>
</html>
