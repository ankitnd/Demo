
 <link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/datatables/datatables.min.css">
 <link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/jquery-selectric/selectric.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">

<div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
<div class="main-content">
        <section class="section">
          <div class="section-body">
             <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header d-flex justify-content-between income-wrapper">

                    <?php $total = 0; for($i=0; $i<sizeof($overdue_fees_list); $i++) {  
                           $total = $total + $overdue_fees_list[$i]->due_amount;
                    }?> 
                        <div class="d-flex justify-content-between">
                          <h4><strong>Overdue Fees Record : <span class="text-danger"><?php setlocale(LC_MONETARY, 'en_IN'); echo money_format('%!i',$total);  ?> /-₹</span></strong></h4>
                        </div>   
                       
                    <div class="table-right-content">
                        <button class="btn btn-info" data-toggle="modal" data-target=".bd-example-modal-lg">
                        <span><i class="fas fa-filter mr-1"></i>Filter</span> 
                        </button>
                        <button class="btn btn-info dropdown-toggle" tabindex="0" aria-controls="tableExport" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><span><i class="fas fa-tasks mr-1"></i> Task</span></button>
                        <div class="dropdown-menu">
                        <a class="dropdown-item text-dark" href="#" style="white-space: normal;line-height: 20px;">Download Pending Payment List</a>
                        </div>
                        <button class="btn text-dark">
                        <span><i class="fas fa-arrow-left mr-1"></i> Back</span> 
                        </button>
                    </div> 
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped overdue-table" id="table-1">
                        <thead>
                          <tr class="text-center">
                          <th>SN</th>
                          <th>Due Date</th>
                          <th>Student Details</th>
                          <th>Parent Details</th>
                          <th>Branch / Courses / Assigned To</th>
                          <th>Inst No</th>
                          <th>Sub-Total Amount (₹)</th>
                          <th>Tax Amount (₹)</th>
                          <th>Total Amount (₹)</th>
                          <th>Received Amount (₹)</th>
                          <th>Due Amount (₹)</th>
                          <th width="100px">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $sno=1; foreach($overdue_fees_list as $val) { ?>
                          <tr class="text-center">
                            <td><?php echo $sno; ?></td>
                            <td><?php $odudate = $val->installment_date;
                               echo $second_date = date("d-M-Y", strtotime($odudate)); ?></td>
                            <td> <?php $admids = explode(",",$val->admission_id);
                              foreach($admisison_process_data as $row) { if(in_array($row->admission_id,$admids)) {  echo $row->surname." ".$row->first_name."<br>"."(RNW/Year ".$row->year."/".
                                "<br>".$row->enrollment_number.")"."<br>".$row->email."<br>"."M : ".$row->mobile_no; }  } ?>
                              </td>
                            <td>
                              <?php $admids = explode(",",$val->admission_id);
                              foreach($admisison_process_data as $row) { if(in_array($row->admission_id,$admids)) {  echo $row->surname." ".$row->father_name."<br>".$row->father_email."<br>"."M : ".$row->father_mobile_no; }  } ?>
                            </td>
                            <td>
                              <?php $admids = explode(",",$val->admission_id); ?>
                              <?php foreach($admisison_process_data as $row) { if(in_array($row->admission_id,$admids)) { ?>
                                <?php $branch_ids = explode(",",$row->branch_id);
                                   foreach($list_branch as $ba) { if(in_array($ba->branch_id,$branch_ids)) {  echo $ba->branch_name."<br>"; }  } ?>
                                <?php if($row->type=="single") { ?>
                                  <?php $coids = explode(",",$row->course_id);
                                   foreach($list_course as $co) { if(in_array($co->course_id,$coids)) {  echo $co->course_name."<br>"; }  } ?>
                                <?php } else { ?>
                                  <?php $paids = explode(",",$row->package_id);
                                   foreach($list_package as $po) { if(in_array($po->package_id,$paids)) {  echo $po->package_name."<br>"; }  } ?>
                                <?php } ?>
                                <?php } }?>
                            </td>
                            <td><?php echo $val->installment_no; ?></td>
                            <td><?php echo $val->due_amount; ?></td>
                            <td>0</td>
                            <td><?php echo $val->due_amount; ?></td>
                            <td>0</td>
                            <td><?php echo $val->due_amount; ?></td>
                            <td>
                              <a href="<?php echo base_url(); ?>Account/view_admission?action=Qwx&Trl=<?php echo @$val->admission_id; ?>" class="bg-primary action-icon text-white btn-primary" target="_blank"><i class="fas fa-eye"></i></a>
                              <!-- <a href="#" class="bg-primary action-icon text-white btn-primary"><i class="far fa-edit"></i></a>  -->
                            </td>
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
</div>
</div>


 <!-- Large modal -->
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="myLargeModalLabel">Search</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <form method="post" action="<?php  echo base_url(); ?>Account/overdue_fees">
         <div class="form-row">
          <div class="form-group col-md-3">
            <label for="inputEmail4">First Name</label>
            <input type="text" class="form-control" id="" name="filter_fname" value="<?php if(!empty($filter_fname)) { echo @$filter_fname; } ?>"  placeholder="Name">
          </div>
          <div class="form-group col-md-3">
            <label for="inputPassword4">Last Name</label>
            <input type="text" class="form-control" id="" name="filter_lname" value="<?php if(!empty($filter_lname)) { echo @$filter_lname; } ?>" placeholder="Surname">
          </div>
           <div class="form-group col-md-3">
            <label for="inputPassword4">Email</label>
            <input type="email" class="form-control" id="" name="filter_email" value="<?php if(!empty($filter_email)) { echo @$filter_email; } ?>" placeholder="Email">
          </div>
           <div class="form-group col-md-3">
            <label for="inputPassword4">Mobile</label>
            <input type="text" class="form-control" id="" name="filter_mobile" value="<?php if(!empty($filter_mobile)) { echo @$filter_mobile; } ?>" placeholder="Mobile">
          </div>
          <div class="form-group col-md-3">
            <label>GR Id</label>
            <input type="text" class="form-control" id="" name="" value="" placeholder="GR Id">
          </div>
           <div class="form-group col-md-3">
            <label for="inputPassword4">Enrollment No</label>
            <input type="text" class="form-control" id="" name="filter_enrollnno" value="<?php if(!empty($filter_enrollnno)) { echo @$filter_enrollnno; } ?>" placeholder="Enrollment No">
          </div>
          <div class="form-group col-md-3">
            <label>Batch</label>
            <input type="text" class="form-control" id="" name="" value="" placeholder="Batch">
          </div>
          <div class="form-group col-md-3">
          <label for="inputState">Branch</label>
          <select id="inputState" class="form-control" name="filter_branch" id="filter_branch">
            <option value="">Select Branch</option>
           <?php foreach($list_branch as $ld) { ?>
            <option <?php if(@$filter_branch==$ld->branch_id) { echo "selected"; } ?> value="<?php echo $ld->branch_id; ?>"><?php echo $ld->branch_name; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label for="inputState">Course</label>
          <select id="inputState" class="form-control" name="filter_course" id="filter_course">
            <option value="">Select Course</option>
           <?php foreach($list_course as $lp) { ?>
            <option <?php if(@$filter_course==$lp->course_id) { echo "selected"; } ?> value="<?php echo $lp->course_id; ?>"><?php echo $lp->course_name; ?></option>
            <?php } ?>
          </select>
        </div>
         <div class="form-group col-md-3">
          <label for="inputState">Package</label>
          <select id="inputState" class="form-control" name="filter_package" id="filter_package">
            <option value="">Select Package</option>
           <?php foreach($list_package as $lp) { ?>
            <option <?php if(@$filter_package==$lp->package_id) { echo "selected"; } ?> value="<?php echo $lp->package_id; ?>"><?php echo $lp->package_name; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-6 form-group" >
                  <label>Date From To </label> 
            <input type="hidden" id="fromdate" name="filter_from_date" value="<?php if(!empty($filter_from_date)) { echo @$filter_from_date; } ?>">
            <input type="hidden" id="todate" name="filter_to_date" value="<?php if(!empty($filter_to_date)) { echo @$filter_to_date; } ?>">
            <div id="reportrange">
                <i class="far fa-calendar-alt"></i>&nbsp;
                <span><?php if(!empty($filter_from_date) && !empty($filter_to_date)) { echo @$filter_from_date." - ".$filter_to_date; } ?></span> <i class="fa fa-caret-down"></i>
            </div>
        </div>
        
      <div class="form-group col-md-3">
          <label for="inputState">Assigned To</label>
          <select id="inputState" class="form-control" id="">
            <option value="">Select</option>
          </select>
        </div>
      <div class="form-group col-md-3">
        <label>Greater Than Amount</label>
        <input type="text" class="form-control" name="" value="" placeholder="Amount">
      </div>
  
        </div>
         <div class="text-right">
          <!-- <button class="btn btn-success mr-1" type="submit" name="filter_overdue_fees">Filter</button> -->
          <input type="submit" class="btn btn-primary" value="Filter" name="filter_overdue_fees">
          <a class="btn btn-light text-dark" href="<?php echo base_url(); ?>Account/overdue_fees">Reset</a>  
        </div>
        </form>
        </div>
      </div>
    </div>
  </div>

<!-- Vertically Center -->
<div class="modal fade" id="'exampleModalCenter'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        add content here..
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>dist/assets/js/app.min.js"></script>
  <script src="<?php echo base_url(); ?>dist/assets/bundles/datatables/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>dist/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>  
  <!-- Page Specific JS File -->
  <script src="<?php echo base_url(); ?>dist/assets/js/page/datatables.js"></script>
  <script src="<?php echo base_url(); ?>dist/assets/bundles/sweetalert/sweetalert.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="<?php echo base_url(); ?>dist/assets/js/page/sweetalert.js"></script>
  <!-- JS Libraies -->
  <script src="<?php echo base_url(); ?>dist/assets/bundles/apexcharts/apexcharts.min.js"></script> 
<script src="<?php echo base_url(); ?>dist/assets/bundles/jquery-selectric/jquery.selectric.min.js"></script> 
<!-- Page Specific JS File -->
  <!-- Page Specific JS File -->
  <script src="<?php echo base_url(); ?>dist/assets/js/page/index.js"></script>
  <script src="<?php echo base_url(); ?>dist/assets/bundles/cleave-js/dist/cleave.min.js"></script>
  <script src="<?php echo base_url(); ?>dist/assets/bundles/cleave-js/dist/addons/cleave-phone.us.js"></script> 
  <script src="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script> 
  <script src="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
  <script src="<?php echo base_url(); ?>dist/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script> 
  <!-- Page Specific JS File -->
  <script src="<?php echo base_url(); ?>dist/assets/js/page/forms-advanced-forms.js"></script>
  <!-- JS Libraies --> 
  <script src="<?php echo base_url(); ?>dist/assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="<?php echo base_url(); ?>dist/assets/js/custom.js"></script>
  <!-- JS Libraies --> 
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script type="text/javascript">
$(function() {


   var start1=moment().subtract(1, 'days');
    var end1=moment();

    
    var start=""
     var end=""
    
    

    function cb(start, end) {
        $('#fromdate').val(start.format('YYYY-MM-DD'));
        $('#todate').val(end.format('YYYY-MM-DD'));
        $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
    }

    $('#reportrange').daterangepicker({
        startDate: start1,
        endDate: end1,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});
</script>

  </body> 


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>