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
               <h6 class="page-title text-dark mb-3">Sub Department</h6>
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
                        <button href="#" class="btn btn-info" data-toggle="modal" data-target="#createsubdepartment">
                        <span><i class="fas fa-plus mr-1"></i>Create SubDepartment</span>
                        </button>
                        <button href="#" class="btn btn-info" data-toggle="modal" data-target="#filtersubdepartment">
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
                                 <th>Sub Department Name</th>
                                 <th>Status</th> 
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr> 
                                <td>1</td>
                                 <td>
                                    RW4
                                    <br>
                                    RW3
                                 </td>
                                 <td>INTERNATIONAL</td>  
                                 <td>Programing</td>                        
                                 <td>Active</td>
                                 <td>
                                    <div class="dropdown">
                                       <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                       <div class="dropdown-menu">
                                          <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                                          Delete</a>
                                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> Disable</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr> 
                                <td>1</td>
                                 <td>
                                    RW4
                                    <br>
                                    RW3
                                 </td>
                                 <td>FASHION</td>       
                                  <td>Programing</td>                   
                                 <td>Active</td>
                                 <td>
                                    <div class="dropdown">
                                       <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                       <div class="dropdown-menu">
                                          <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                                          Delete</a>
                                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> Disable</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr> 
                                <td>1</td>
                                 <td>
                                    RW4
                                    <br>
                                    RW3
                                 </td>
                                 <td>INTERIOR</td>    
                                 <td>Programing</td>                      
                                 <td>Active</td>
                                 <td>
                                    <div class="dropdown">
                                       <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                       <div class="dropdown-menu">
                                          <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                                          Delete</a>
                                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> Disable</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr> 
                                <td>1</td>
                                 <td>
                                    RW4
                                    <br>
                                    RW3
                                 </td>
                                 <td>COMPUTER</td> 
                                 <td>Programing</td>                         
                                 <td>Active</td>
                                 <td>
                                    <div class="dropdown">
                                       <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                       <div class="dropdown-menu">
                                          <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                                          Delete</a>
                                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> Disable</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
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
<div class="modal fade" id="createsubdepartment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                        <label for="">SubDepartment Name :</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div class="form-group  col-md-4 col-sm-12">
                        <label for="">Branch :</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div class="form-group  col-md-4 col-sm-12">
                        <label for="">Department:</label>
                        <select required class="form-control" name="admin_id"  required>
                            <option value="">Select Department</option>
                            <option    value="65">Computer</option>
                        </select>
                    </div> 
                </div>
            </div>
            <button type="button" class="btn btn-primary">Submit</button>
        </div>
    </div>
    </div>
</div>

<!-- Filter Department -->
<div class="modal fade" id="filtersubdepartment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                        <label for="">SubDepartment Name :</label>
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

</body> 
<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>