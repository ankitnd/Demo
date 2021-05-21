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
                              <tr>
                                <td class="p-0 text-center">
                                    <div class="custom-checkbox custom-control">
                                    <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                        id="checkbox-1">
                                    <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                                    </div>
                                </td>
                                 <td>1</td>
                                 <td><img src="https://demo.rnwmultimedia.com/dist/branchlogo/1617974984rnw-logo-receipt.png" width="90px" style="box-shadow: none;border: 0;border-radius: 0;"/></td> 
                                 <td>
                                 <strong>Branch Name :</strong> <span>RW1 Web Technology</span> </br>
                                    <strong>Branch Code :</strong> <span>RW1WT</span> </br>
                                    <strong>Branch Title :</strong> <span>Red & White Institute</span> </br>
                                    <strong>Website :</strong> <span>rnwmultimedia.com</span> </br>
                                    <strong>Email :</strong> <span>info@rnwmultimedia.com</span> </br>
                                    <strong>Mobile No 1 :</strong> <span>7878780511</span></br> 
                                    <strong>Mobile No 2 :</strong> <span> </span> </br>
                                    <strong>Landline No :</strong> <span>02225437XYZ</span>      </br>                      
                                    <strong>Address :</strong> <span>206, Sun Shine, Opp. CNG Pump, Sudama Chowk, Motavarachha</span>   </br>                        
                                 </td>
                                 <td>
                                    <strong>Admin :</strong> <span>Hiral Khunt</span> </br>
                                    <strong>Country :</strong> <span>India</span> </br>
                                    <strong>State :</strong> <span>Gujarat</span> </br>
                                    <strong>City :</strong> <span>Surat</span> </br>
                                    <strong>Pan No :</strong> <span>FHEFHFTEST0101</span> </br>
                                    <strong>CIN :</strong> <span>REUIOEUIU010</span></br> 
                                    <strong>GST No :</strong> <span>24ASQPD8578EIZE</span> </br>
                                 </td>
                                 <td>
                                    <strong>Bank Name :</strong> <span>Kotak Mahindra Bank</span> </br>
                                    <strong>Account Holder Name :</strong> <span>RWn01</span> </br>
                                    <strong>Account No :</strong> <span>2233114455</span> </br>
                                    <strong>IFSC Code :</strong> <span>KKBK0002855</span> </br>
                                    <strong>Account Type :</strong> <span>saving</span> </br>
                                 </td>
                                 <td><strong class="text-primary">Active</strong></td>
                                 <td>
                                    <div class="dropdown">
                                       <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                       <div class="dropdown-menu">
                                          <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> View</a>
                                          <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                                          Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                <td class="p-0 text-center">
                                    <div class="custom-checkbox custom-control">
                                    <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                        id="checkbox-2">
                                    <label for="checkbox-2" class="custom-control-label">&nbsp;</label>
                                    </div>
                                </td>
                                 <td>1</td>
                                 <td><img src="https://demo.rnwmultimedia.com/dist/branchlogo/1617974984rnw-logo-receipt.png" width="90px" style="box-shadow: none;border: 0;border-radius: 0;"/></td> 
                                 <td>
                                    <strong>Branch Name :</strong> <span>RW1 Web Technology</span> </br>
                                    <strong>Branch Code :</strong> <span>RW1WT</span> </br>
                                    <strong>Branch Title :</strong> <span>Red & White Institute</span> </br>
                                    <strong>Website :</strong> <span>rnwmultimedia.com</span> </br>
                                    <strong>Email :</strong> <span>info@rnwmultimedia.com</span> </br>
                                    <strong>Mobile No 1 :</strong> <span>7878780511</span></br> 
                                    <strong>Mobile No 2 :</strong> <span> </span> </br>
                                    <strong>Landline No :</strong> <span>02225437XYZ</span>      </br>                      
                                    <strong>Address :</strong> <span>206, Sun Shine, Opp. CNG Pump, Sudama Chowk, Motavarachha</span>   </br>                        
                                 </td>
                                 <td>
                                    <strong>Admin :</strong> <span>Hiral Khunt</span> </br>
                                    <strong>Country :</strong> <span>India</span> </br>
                                    <strong>State :</strong> <span>Gujarat</span> </br>
                                    <strong>City :</strong> <span>Surat</span> </br>
                                    <strong>Pan No :</strong> <span>FHEFHFTEST0101</span> </br>
                                    <strong>CIN :</strong> <span>REUIOEUIU010</span></br> 
                                    <strong>GST No :</strong> <span>24ASQPD8578EIZE</span> </br>
                                 </td>
                                 <td>
                                    <strong>Bank Name :</strong> <span>Kotak Mahindra Bank</span> </br>
                                    <strong>Account Holder Name :</strong> <span>RWn01</span> </br>
                                    <strong>Account No :</strong> <span>2233114455</span> </br>
                                    <strong>IFSC Code :</strong> <span>KKBK0002855</span> </br>
                                    <strong>Account Type :</strong> <span>saving</span> </br>
                                 </td>
                                 <td><strong class="text-primary">Active</strong></td>
                                 <td>
                                    <div class="dropdown">
                                       <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                       <div class="dropdown-menu">
                                          <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> View</a>
                                          <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                                          Delete</a>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                              <td class="p-0 text-center">
                                    <div class="custom-checkbox custom-control">
                                    <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                        id="checkbox-3">
                                    <label for="checkbox-3" class="custom-control-label">&nbsp;</label>
                                    </div>
                                </td>
                                 <td>1</td>
                                 <td><img src="https://demo.rnwmultimedia.com/dist/branchlogo/1617974984rnw-logo-receipt.png" width="90px" style="box-shadow: none;border: 0;border-radius: 0;"/></td> 
                                 <td>
                                    <strong>Branch Name :</strong> <span>RW1 Web Technology</span> </br>
                                    <strong>Branch Code :</strong> <span>RW1WT</span> </br>
                                    <strong>Branch Title :</strong> <span>Red & White Institute</span> </br>
                                    <strong>Website :</strong> <span>rnwmultimedia.com</span> </br>
                                    <strong>Email :</strong> <span>info@rnwmultimedia.com</span> </br>
                                    <strong>Mobile No 1 :</strong> <span>7878780511</span></br> 
                                    <strong>Mobile No 2 :</strong> <span> </span> </br>
                                    <strong>Landline No :</strong> <span>02225437XYZ</span>      </br>                      
                                    <strong>Address :</strong> <span>206, Sun Shine, Opp. CNG Pump, Sudama Chowk, Motavarachha</span>   </br>                        
                                 </td>
                                 <td>
                                    <strong>Admin :</strong> <span>Hiral Khunt</span> </br>
                                    <strong>Country :</strong> <span>India</span> </br>
                                    <strong>State :</strong> <span>Gujarat</span> </br>
                                    <strong>City :</strong> <span>Surat</span> </br>
                                    <strong>Pan No :</strong> <span>FHEFHFTEST0101</span> </br>
                                    <strong>CIN :</strong> <span>REUIOEUIU010</span></br> 
                                    <strong>GST No :</strong> <span>24ASQPD8578EIZE</span> </br>
                                 </td>
                                 <td>
                                    <strong>Bank Name :</strong> <span>Kotak Mahindra Bank</span> </br>
                                    <strong>Account Holder Name :</strong> <span>RWn01</span> </br>
                                    <strong>Account No :</strong> <span>2233114455</span> </br>
                                    <strong>IFSC Code :</strong> <span>KKBK0002855</span> </br>
                                    <strong>Account Type :</strong> <span>saving</span> </br>
                                 </td>
                                 <td><strong class="text-primary">Active</strong></td>
                                 <td>
                                    <div class="dropdown">
                                       <a href="#" data-toggle="dropdown" class="btn btn-light text-dark dropdown-toggle text-white">Options</a>
                                       <div class="dropdown-menu">
                                          <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> View</a>
                                          <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                                          Delete</a>
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
<div class="modal fade" id="createbranch" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title text-dark" id="myLargeModalLabel">Create Branch</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="card"> 
                <div class="branch-items row mb-2">
                     <div class="form-group col-md-4 col-sm-12">
                        <label for="">Session :</label>
                        <select class="form-control select2" id="course_orpackage" multiple="">
                            <option value="">Select Package</option>
                            <option  value="2019">2019</option>
                            <option  value="2020">2020</option>
                            <option  value="2021">2021</option>
                            <option  value="2022">2022</option>
                            <option  value="2023">2023</option>
                            <option  value="2024">2024</option>
                            <option  value="2025">2025</option>
                            <option  value="2026">2026</option>
                            <option  value="2027">2027</option>
                            <option  value="2028">2028</option>
                            <option  value="2029">2029</option>
                            <option  value="2030">2030</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Branch Name :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Branch Code :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Branch Title :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Branch Logo :</label>
                        <input type="file">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Website :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Email :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Mobile No 1  :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Mobile No 2 :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">LandlineNo :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Address :</label>
                        <textarea type="text" class="form-control"></textarea>
                    </div>
                </div>
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
                        <select required class="form-control" name=""  required>
                            <option value="">Select Country</option>
                            <option value=" ">Hiral Khunt</option>
                        </select>
                    </div>
                    <div class="form-group  col-md-4 col-sm-12">
                        <label for="">State :</label>
                        <select required class="form-control" name=""  required>
                            <option value="">Select State</option>
                            <option value=" ">Hiral Khunt</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">City :</label>
                        <select required class="form-control" name=""  required>
                            <option value="">Select City</option>
                            <option value=" ">Hiral Khunt</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Pan No :</label>
                        <input type="text" class="form-control" >
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">CIN :</label>
                        <input type="text" class="form-control" >
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">GST No :</label>
                        <input type="text" class="form-control" >
                    </div>
                </div>
                <div class="branch-items row mb-2">
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Bank Name :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Account Holder Name :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Account No :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">IFSC Code :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="">Account Type :</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary">Submit</button>
        </div>
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