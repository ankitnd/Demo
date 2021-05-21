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
                              <?php $sno=1; foreach ($department_all as $val) { ?>
                                 <tr>
                                    <td><?php echo $sno; ?></td>
                                    <td><?php $branch_ids = explode(",", $val->branch_id);
                                          foreach ($branch_all as $row) {
                                             if (in_array($row->branch_id, $branch_ids)) {
                                                echo $row->branch_name ."<br>";
                                             }
                                          } ?></td>
                                    <td><?php echo $val->department_name ."<br>" ?></td>
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
                                          <a class="dropdown-item has-icon" href="javascript:doc_delete(<?php echo $val->department_id; ?>)">
                                             <i class="far fa-trash-alt text-danger"></i> Delete
                                          </a>
                                          <a class="dropdown-item has-icon" href="javascript:doc_disable(<?php echo $val->department_id; ?>)">
                                             <i class="fas fa-eye"></i> Disable
                                          </a>
                                       </div>
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
        <div class="modal-body">
            <div class="card"> 
                <div class="branch-items row mb-2">
                    <div class="form-group  col-md-4 col-sm-12">
                        <label for="">Admin :</label>
                        <select required class="form-control" name="admin_id"  required>
                            <option value="">Select Admin</option>
                            <option    value="65">Hiral Khunt</option>
                        </select>
                    </div>
                    <div class="form-group  col-md-4 col-sm-12">
                        <label for="">Country :</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div class="form-group  col-md-4 col-sm-12">
                        <label for="">Branch :</label>
                        <input type="text" class="form-control" />
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="dept_add">Submit</button>
        </div>
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

<!-- General JS Scripts -->
<script src="<?php echo base_url(); ?>dist/assets/js/app.min.js"></script>
<script src="<?php echo base_url(); ?>dist/assets/bundles/datatables/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>dist/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>  
<script src="<?php echo base_url(); ?>dist/assets/bundles/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>dist/assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>
<!-- Page Specific JS File -->
<script src="<?php echo base_url(); ?>dist/assets/js/page/datatables.js"></script>
<!-- Page Specific JS File -->
<script src="<?php echo base_url(); ?>dist/assets/js/page/index.js"></script>
<!-- JS Libraies --> 
<script src="<?php echo base_url(); ?>dist/assets/js/scripts.js"></script>
<!-- Custom JS File -->
<script src="<?php echo base_url(); ?>dist/assets/js/custom.js"></script>

<script>
   function doc_add(dept_id) {
      $("#dept_add").validate({
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
   }
</script>
</body> 
<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>