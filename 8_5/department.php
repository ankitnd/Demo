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
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Department

</h1>
<ol class="breadcrumb">
<li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>

<li class="active">Department</li>
</ol>
</section>

<!-- Main content -->
<section class="content">

<?php if(!empty($msg_alert)) { ?>
<div class="col-md-8" >

<div id="yellow" style="padding:2px 5px 2px 5px" class="box yellow bg-red"><?php echo $msg_alert; ?></div>
</div>
<?php } ?>

<div class="row">
<div class="col-md-6">
<!-- general form elements -->
<div class="box box-primary" style="padding:20px;">
<div class="box-header with-border" style="margin-bottom:10px;">
<h3 class="box-title">ADD Department</h3>
</div>
<form  method="post" action="<?php echo base_url(); ?>settings/department">
<div class="row" style="padding:10px">
<div class="col-md-8">
<input type="hidden" name="update_id" value="<?php if(!empty($select_department->department_id)) { echo $select_department->department_id; } ?>"/>

<?php if($_SESSION['logtype']=="Admin") { ?>

<div class="form-group">
<label for="email">Department:</label>
<input type="text" class="form-control" name="department_name" value="<?php if(!empty($select_department->department_name)) { echo $select_department->department_name; } ?>">
</div>

<div class="form-group">
<label>Branch</label>
    <select class="select2 form-control" name="b_ids[]" id="branch" multiple="multiple">
       <option>Select Branch</option>
      <?php $branch_ids = explode(",",$select_department->branch_id);   
       foreach($branch_all as $row) {  ?> 
    <option <?php if(in_array($row->branch_id,$branch_ids)) { echo "selected"; } ?>
     value="<?php echo $row->branch_id; ?>"><?php echo $row->branch_name; ?></option>
  <?php } ?>   
</select>
</div>




<!-- <div class="form-group">
<label for="pwd">Department Name:</label>
<input class="form-control"   value="<?php if(!empty($select_department->department_name)) { echo $select_department->department_name; } ?>" id="department" type="text"  name="department_name" placeholder="Enter Department Name" >
<div style="margin-left: 320px;margin-top: -33px;">
<input type="button"  onclick="addDepartment()" class="btn btn-success" value="Add" />
<button style="font-size:30px;color:green;"  id="department"  onclick="addDepartment()" ><i class="fa fa-plus"></i></button>  -->
<!-- </div>
<div id="showCourse"></div>
</div>  -->
<?php } else { ?>
<div class="form-group">
<label for="email">Admin:</label>
<select required class="form-control" name="admin_id"  id="admin" required>
<option value="">Select Admin</option>
<?php foreach($user_all as $val) { ?>
<option <?php if($val->user_id==@$select_department->admin_id) { echo "selected"; } ?>   value="<?php echo $val->user_id; ?>"><?php echo $val->name; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group">
<label for="email">Department:</label>
<input type="text" class="form-control" name="department_name" value="<?php if(!empty($select_department->department_name)) { echo $select_department->department_name; } ?>">
</div>

<div class="form-group">
<label>Branch</label>
    <select class="select2 form-control" name="b_ids[]" id="branch" multiple="multiple">
       <option>Select Branch</option>
      <?php $branch_ids = explode(",",$select_department->branch_id);   
       foreach($branch_all as $row) {  ?> 
    <option <?php if(in_array($row->branch_id,$branch_ids)) { echo "selected"; } ?>
     value="<?php echo $row->branch_id; ?>"><?php echo $row->branch_name; ?></option>
  <?php } ?>   
</select>
</div>



<!--  <div class="form-group">
<label for="pwd">Department Name:</label>
<input class="form-control"   value="<?php if(!empty($select_department->department_name)) { echo $select_department->department_name; } ?>" id="department" type="text"  name="department_name" placeholder="Enter Department Name" >
<div style="margin-left: 320px;margin-top: -33px;">
<input type="button"  onclick="addDepartment()" class="btn btn-success" value="Add" /> -->
<!-- <button style="font-size:30px;color:green;"  id="department"  onclick="addDepartment()" ><i class="fa fa-plus"></i></button>  -->
<!--  </div>
<div id="showCourse"></div>
</div> -->
<?php  } ?>



<br>
<input type="submit" name="submit" value="submit" class="btn btn-success">
     
</div>
</div>
</form>
</div>
</div>
<div class="row">
<div class="col-md-12" >
<!-- general form elements -->
<div class="box box-success" style="padding:20px;">
<div class="box-header with-border">
<h3 class="box-title">Display Department</h3>
</div>
<!-- /.box-header -->

<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>

<th>Branch Name</th>
<th>Department Name</th>
<th>Status</th>
<th>action</th>
</tr>
</thead>
<tbody>
<?php foreach($department_all as $val) { ?>
<tr>
<td><?php $branch_ids = explode(",",$val->branch_id);
foreach($branch_all as $row) { if(in_array($row->branch_id,$branch_ids)) {  echo "<br>".$row->branch_name; }  } ?></td>
<td><?php echo "<br>".$val->department_name?></td>


<td><br><label style="color:#a6a6a6"> <?php if($val->depart_status=="0") { echo "Active"; } if($val->depart_status=="1") { echo  "Disable"; } ?> </label></td>
<td><br>
<div class="dropdown">
<button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown">Action
<span class="caret"></span></button>
<ul class="dropdown-menu">
<li><a href="<?php echo base_url(); ?>settings/department?action=edit&id=<?php echo @$val->department_id; ?>"> Edit</a></li>
<li><a href="<?php echo base_url(); ?>settings/department?action=delete&id=<?php echo @$val->department_id; ?>"onclick="return confirm('Are you sure you want to delete this task?');">Delete</a></li>
<?php if($val->depart_status==0) { ?>
<li ><a href="<?php echo base_url(); ?>settings/department?action=status&id=<?php echo @$val->department_id; ?>&status=<?php echo @$val->depart_status; ?>">Disable</a></li>
<?php } else {  ?>

<li ><a href="<?php echo base_url(); ?>settings/department?action=status&id=<?php echo @$val->department_id; ?>&status=<?php echo @$val->depart_status; ?>">Active</a></li>
<?php } ?>
</ul>
</div>

</td>
</tr>

<?php } ?>

</tfoot>
</table>


</div>

</div>
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
<!-- page script -->
<script>

if (document.getElementById("yellow") != null) {
setTimeout(function() {
document.getElementById('yellow').style.display = 'none';
}, 5000);
}
</script>

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
$('#admin').change(function(){
console.log("heloo");
var admin_id = $('#admin').val();
if(admin_id !='')
{
$.ajax({
url:"<?php echo base_url(); ?>settings/adminWise_branch",
method:"POST",
data:{admin_id:admin_id},
success:function(data)
{
$('#branch').html(data);
// $('#seat_course').html('<option value="">Select Seat Course</option>');
}
});
}
else
{
$('#branch').html('<option value="">Select Branch</option>');
// $('#seat_course').html('<option value="">Select Seat Course</option>');
}
});
</script>
<script>
var kb=1;
function removedepartment(dvid)
{

$('#'+dvid).remove();

}

function addDepartment()
{
var department = $('#department').val();
alert(department);
$.ajax({
url:'<?php echo base_url(); ?>settings/select_department',
type:'post',
dataType:"JSON",
data:{
'department_id':department

},
success: function(data)
{
var e = $('<div class="col-sm-4" id="hello'+kb+'"><div class="box box-success box-solid" style="padding:0px;"><div class="box-header" style="padding:0px 0px 0px 5px;"><h6 >'+data.selectdata+'<input type="hidden" name="department_name[]" value="'+data.selectdata+'"></h6><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" onclick="removedepartment('+"'hello"+kb+"'"+')"><i class="fa fa-times"></i></button></div> </div> </div> </div>');

$("#showCourse").append(e);
$('#department').val(' ');
kb++;  
}
});
}
</script>

</body>
</html>
