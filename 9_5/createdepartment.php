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
                  <h6 class="page-title text-dark mb-3">Department</h6>
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
                           <button href="#" class="btn btn-info" data-toggle="modal" data-target="#createdepartment">
                              <span><i class="fas fa-plus mr-1"></i>Create Department</span>
                           </button>
                           <button href="#" class="btn btn-info" data-toggle="modal" data-target="#filterdepartment">
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
                                    <th>SN</th>
                                    <th>Branch Name</th>
                                    <th>Department Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php $sno = 1;
                                 foreach ($department_all as $val) { ?>
                                    <tr>
                                       <td><?php echo $sno; ?></td>
                                       <td><?php $branch_ids = explode(",", $val->branch_id);
                                             foreach ($branch_all as $row) {
                                                if (in_array($row->branch_id, $branch_ids)) {
                                                   echo $row->branch_name . "<br>";
                                                }
                                             } ?></td>
                                       <td><?php echo $val->department_name . "<br>" ?></td>
                                       <td>
                                          <label style="color:#a6a6a6">
                                             <?php
                                             if ($val->depart_status == "0") {
                                                echo "Active";
                                             }
                                             if ($val->depart_status == "1") {
                                                echo  "Disable";
                                             } ?>
                                          </label>
                                       </td>
                                       <td>
                                          <div class="dropdown">
                                             <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                             <div class="dropdown-menu">
                                                <a class="dropdown-item has-icon" href="javascript:doc_upd(<?php echo $val->department_id; ?>)">
                                                   <i class="far fa-edit"></i> Edit
                                                </a>
                                                <a class="dropdown-item has-icon" href="javascript:remove_doc(<?php echo $val->department_id; ?>)">
                                                   <i class="far fa-trash-alt text-danger"></i> Delete
                                                </a>
                                                <?php if ($val->depart_status == 0) { ?>
                                                   <a class="dropdown-item has-icon" href="javascript:doc_status_upd(<?php echo $val->department_id; ?>, 1)">
                                                      <i class="fas fa-ban"></i> Disable
                                                   </a>
                                                <?php } else {  ?>
                                                   <a class="dropdown-item has-icon" href="javascript:doc_status_upd(<?php echo $val->department_id; ?>, 0)">
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
   <div class="modal fade" id="createdepartment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title text-dark" id="myLargeModalLabel">Create Department</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <form class="document-createmodal" method="post" name="dept_add" id="dept_add">
               <input type="hidden" name="department_id" id="department_id" class="form-control">
               <div class="modal-body">
                  <div class="card">
                     <div class="branch-items row mb-2">
                        <input type="hidden" name="update_id" value="<?php if (!empty($select_department->department_id)) {
                                                                        echo $select_department->department_id;
                                                                     } ?>" />

                        <?php if ($_SESSION['logtype'] == "Admin") { ?>

                           <div class="form-group  col-md-4 col-sm-12">
                              <label for="email">Department:</label>
                              <input type="text" class="form-control" name="department_name" >
                           </div>

                           <div class="form-group  col-md-4 col-sm-12">
                              <label>Branch</label>
                              <select class="form-control" name="b_ids[]" id="b_ids" multiple="multiple">
                                 <option>Select Branch</option>
                                 <?php $branch_ids = explode(",", $select_department->branch_id);
                                 foreach ($branch_all as $row) {  ?>
                                    <option value="<?php echo $row->branch_id; ?>"><?php echo $row->branch_name; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                        <?php } else { ?>
                           <div class="form-group  col-md-4 col-sm-12">
                              <label for="email">Admin:</label>
                              <select required class="form-control" name="admin_id" id="admin_id" required>
                                 <option value="">Select Admin</option>
                                 <?php foreach ($user_all as $val) { ?>
                                    <option <?php if ($val->user_id == @$select_department->admin_id) {
                                                echo "selected";
                                             } ?> value="<?php echo $val->user_id; ?>"><?php echo $val->name; ?></option>
                                 <?php } ?>
                              </select>
                           </div>

                           <div class="form-group  col-md-4 col-sm-12">
                              <label for="email">Department:</label>
                              <input type="text" class="form-control" name="department_name" id="department_name">
                           </div>

                           <div class="form-group  col-md-4 col-sm-12">
                              <label>Branch</label>
                              <select class="select2 form-control" name="b_ids[]" id="b_ids" multiple="multiple">
                                 <option>Select Branch</option>
                                 <?php $branch_ids = explode(",", $select_department->branch_id);
                                 foreach ($branch_all as $row) {  ?>
                                    <option <?php if (in_array($row->branch_id, $branch_ids)) {
                                                echo "selected";
                                             } ?> value="<?php echo $row->branch_id; ?>"><?php echo $row->branch_name; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                        <?php  } ?>
                     </div>
                  </div>
                  <input type="submit" class="btn btn-primary" id="Submit" name="submit" value="Submit">
               </div>
            </form>
         </div>
      </div>
   </div>

   <!-- Filter Department -->
   <div class="modal fade" id="filterdepartment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                        <label for="">Department Name :</label>
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

         $("#dept_add").validate({
            rules: {
               w_template_name: {
                  required: true,
               },
               w_template_message: {
                   required: true
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
               var formdata = $('#dept_add').serialize();
               
               $.ajax({
                  url: "<?php echo base_url(); ?>AdminSettings/ajax_dept_submit",
                  type: "post",
                  data: formdata,
                  success: function(resp) {
                     var data = $.parseJSON(resp);
                     var ddd = data['all_record'].status;
                     if (ddd == '1') {
                        $('#msg_doc').html(iziToast.success({
                           title: 'Success',
                           timeout: 2000,
                           message: data['all_record'].msg,
                           position: 'topRight'
                        }));

                        setTimeout(function() {
                           location.reload();
                        }, 2020);
                     } else if (ddd == '2') {
                        $('#msg_doc').html(iziToast.error({
                           title: 'Canceled!',
                           timeout: 2000,
                           message: data['all_record'].msg,
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
   </script>
   <script>
      function doc_upd(department_id) {
         //console.log(department_id);
         $.ajax({
         url: "<?php echo base_url(); ?>AdminSettings/get_record_dept",
         type: "post",
         data: {
            'department_id': department_id
         },
         success: function(resp) {
            $("#createdepartment").modal();
            var data = $.parseJSON(resp);

            var admin_id = data['single_record'].admin_id;
            var department_name = data['single_record'].department_name;
            var department_id = data['single_record'].department_id;
            var branch_id = data['single_record'].branch_id;
            
            
            $('#admin_id').val(admin_id);
            $('#department_id').val(department_id);
            $('#department_name').val(department_name);

            var arr = branch_id.split(",");

            $("select[name=test]").val([1,2,3])
            
            for (i = 0; i < arr.length; i++) {
               $('#b_ids option[value=' + arr[i] + ']').attr('selected', true);
            }
            
            $('#submit').val('Update');
         }

         });
      }

      function remove_doc(department_id) {
         var conf = confirm("Are U Sure to Delete Record");
         if (conf) {
         $.ajax({
            url: "<?php echo base_url(); ?>AdminSettings/delete_record",
            type: "post",
            data: {
               'id': department_id,
               'table': 'department',
               'field': 'department_id'
            },
            success: function(resp) {
               var data = $.parseJSON(resp);
               var ddd = data['all_record'].status;
               console.log("dddddd", ddd);
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
               } else if (ddd == '2') {
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

      function doc_status_upd(id, status) {
         console.log(id);
         $.ajax({
            url: "<?php echo base_url(); ?>AdminSettings/update_status",
            type: "post",
            data: {
               'id': id,
               'status': status,
               'table': 'department',
               'field': 'depart_status',
               'check_field': 'department_id'
            },
            success: function(resp) {
               var data = $.parseJSON(resp);
               var ddd = data['status'].status;
               console.log("dddddd", ddd);
               if (ddd == '1') {
                  $('#msg').html(iziToast.success({
                     title: 'success',
                     timeout: 2000,
                     message: data['status'].msg,
                     position: 'topRight'
                  }));


                  setTimeout(function() {
                     location.reload();
                  }, 2020);
               } else if (ddd == '2') {
                  $('#msg').html(iziToast.error({
                     title: 'Canceled!',
                     timeout: 2000,
                     message: data['status'].msg,
                     position: 'topRight'
                  }));

                  setTimeout(function() {
                     location.reload();
                  }, 2020);
               }
            }
         });
      }
   </script>
   </body>
   <!-- index.html  21 Nov 2019 03:47:04 GMT -->

   </html>