
    
    <?php  @$branch_ids = explode(",",$_SESSION['branch_ids']); ?>

    
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
  
  <style>

/* Rating Star Widgets Style */
.rating-stars ul {
  list-style-type:none;
  padding:0;
  
  -moz-user-select:none;
  -webkit-user-select:none;
}
.rating-stars ul > li.star {
  display:inline-block;
  
}

.rating-stars ul > li.star_give {
  display:inline-block;
  
}

/* Idle State of the stars */
.rating-stars ul > li.star > i.fa {
  font-size:2.5em; /* Change the size of the stars */
  color:#ccc; /* Color on idle state */
}


.rating-stars ul > li.star_give > i.fa {
  font-size:1em; /* Change the size of the stars */
  color:#FF912C; /* Color on idle state */
   list-style-type:none;
  padding:0;
  
  -moz-user-select:none;
  -webkit-user-select:none;

}


/* Hover state of the stars */
.rating-stars ul > li.star.hover > i.fa {
  color:#FFCC36;
}


/* Selected state of the stars */
.rating-stars ul > li.star.selected > i.fa {
  color:#FF912C;
}

</style>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    
    
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
       
        <li class="active">Complain List</li>
      </ol>
    </section>

    <!-- Main content -->
 <a href="javascript:add_category()"  style="margin-left:50px; padding:5px; background-color: violet; color:white;">Add Job Subcategory </a>

 <?php if(@$this->session->flashdata('msg')) { ?>
                  <div class="col-md-8" >
              <div id="yellow" style="padding:2px 5px 2px 5px" class="box yellow bg-green"><?php echo $this->session->flashdata('msg'); ?></div>
          </div>
          <?php } ?>
     
<br><br>
    <section class="content">
      <div class="col-md-12" >
          <div class="box box-success" style="padding:4px;">
            <div class="box-header with-border">
              
            </div>
            <!-- /.box-header -->
                     <div class="row"> 
               <div class="col-md-12">
                   
              
             <div style="margin-top:5px;" id="result_record">
              <table  id="example1"  class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Main Category</th>
                    <th>Job Position </th>
                    <th>Job subcategory </th>
                    <th>Date</th>
                   <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($position as $pos) { ?>
                    <tr>
                        <td><?php echo $pos->job_subcategory_id; ?></td>
                        <td><?php echo $pos->job_main_cat; ?></td>
                        <td><?php echo $pos->job_position_cat; ?></td>
                        <td><?php echo $pos->job_subcategory_name; ?></td>
                        <td><?php echo $pos->job_sub_created_date; ?></td>

                        <td><a href="javascript:delete_job(<?php echo $pos->job_subcategory_id; ?>)" style="color:red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                          <a href="javascript:update_job(<?php echo $pos->job_subcategory_id; ?>)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                    </tr>
                        <?php } ?>
                        
                </tbody>
              </table>
             </div> 
             
              </div>
              
               
             
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

  function selectPlace()
  {
    var b_id = $('#branch_id').val();
    //alert(b_id);
    $.ajax({
      url:"<?php echo base_url(); ?>PropertyController/branchWisePlace",
      type:"post",
      data:{
        'branch_id':b_id
      },
      success : function(data){
        $('#place_box').html(data);
      
      }
    });


      
  }

  function delete_job(id)
  {
    var conf =  confirm("Are U Sure to Delete Record");
    if(conf){
        $.ajax({
          url : "<?php echo base_url(); ?>FormController/delete_job_subcategory",
          type :"post",
          data:{
            'job_post_id' : id
          },
          success:function(res)
          {
                setTimeout(function() {
                    location.reload();
                }, 100);
          }
        });
    }
  }

  function get_company_detail_ajax(company_id=''){
    $.ajax({
      url : "<?php echo base_url(); ?>FormController/ajax_company_detail_get",
      type:"post",
      data:{
        'company_id':company_id
      },
      success:function(Resp){
        $('#get_company_detail').html(Resp);
      }
    });
  }
</script>

</body>
</html>

    


<div id="show_modal" class="modal fade" role="dialog" >
  <div class="modal-dialog" style="width:600px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Add Job Subcategory</h3>
      </div>
      <div class="modal-body" id="">
        <div id="result_job"></div>
     <form method="post" name="add_job_position" id="add_job_position">  
      <table class="table table-bordered table-striped">
          <thead >

            <tr>
               <td>Job Main Category</td>
              <td>
                <select class="form-control" name="job_main_cat" id="job_main_cat" onchange="return get_position_category()">
                    <option value=''>--select--</option>
                    <?php foreach($jmCategory as $jmc) { ?>
                    <option value="<?php echo $jmc->job_category_name; ?>"><?php echo $jmc->job_category_name; ?></option>

                    <?php } ?>
                </select>
              </td>

            </tr>


            <tr>
               <td>Job Position Category</td>
              <td>
                <select class="form-control" name="job_position_cat" id="job_position_cat">
                    <option value=''>--select--</option>
                    <?php foreach($jmPosition as $jmp) { ?>
                    <option value="<?php echo $jmp->job_position; ?>"><?php echo $jmp->job_position; ?></option>

                    <?php } ?>
                </select>
              </td>

            </tr>

            <tr>
               <td>Job  Subcategory</td>
              <td><input type="text" name="job_subcategory_name" id="job_subcategory_name" class="form-control">
              </td>
              <input type="text" name="job_subcategory_id_r" id="job_subcategory_id_r">
            </tr>
            <tr>
              <td></td>
              <td><input type="submit" name="submit" value="Save" id="submit" class="btn btn-success"></td>
            </tr>
          </thead>
       </table>
     </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
    </div>
  </div>
</div>




    
 <script>



function give_rate(company_id)
{
  
  $("#rating-modal").modal();  
  $('#star_company_id').val(company_id);

}

function add_category()
{
  $("#show_modal").modal();
  $('#job_position').val('');
  $('#submit').val('Save');
}
</script>
<script>
  $(document).ready(function(){
    $('#filter_section_company').click(function(){
          $('#filter_section').slideToggle();
  });

      $('#stars li').on('mouseover', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
   
    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });
    
  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });
  
  
  /* 2. Action to perform on click */
  $('#stars li').on('click', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');
    
    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }
    
    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }
    
    // JUST RESPONSE (Not needed)
    var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
    var msg = "";
    if (ratingValue > 1) {
        // alert("Thanks! You rated this " + ratingValue + " stars.");
        $('#cr_star_id').val(ratingValue);
    }
    else {
        // alert("We will improve ourselves. You rated this " + ratingValue + " stars.");
        $('#cr_star_id').val(ratingValue);
    }
    responseMessage(msg);
  });
});


//session automatic  
    $(document).ready(function(){
      var submenu = sessionStorage.getItem("submenu");
      var leadsubmenu = sessionStorage.getItem("leadsubmenu");
      $('#sub_menu_'+submenu).addClass('show');
      $('#lead_submenu_'+leadsubmenu).addClass('show');
      // $('#lead_submenu_'+leadsubmenu).css({"background-color": "yellow", "color": "white"});
    });

    function getClick(id,subid){
      sessionStorage.setItem("submenu", id);
      sessionStorage.setItem("leadsubmenu", subid);
    }

    function hideMenu(id,subid){
      $("#sub_menu_"+id).removeClass('show');
      $("#lead_submenu_"+subid).removeClass('show');
    }
//end session of sidebar menu open 




// $('#filter_section').hide();
 </script>


 <script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});



</script>


<!-- <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script> -->
<script>
// just for the demos, avoids form submit
jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
$( "#add_job_position" ).validate({
  rules: {
    job_main_cat: {
      required: true,
    },
    job_position_cat:{
      required:true
    },
    job_subcategory_name:{
      required : true
    }

  },
  messages:{
    job_main_cat:{
      required : "<div style='color:red'>Enter Job Main category Name</div>",
    },
    job_position_cat:{
      required : "<div style='color:red'>Enter Job Position category Name</div>", 
    },
    job_subcategory_name:{
      required : "<div style='color:red'>Enter Job  sub-category Name</div>", 
    }
  },
  submitHandler: function () {
       event.preventDefault();
       var formdata =  $('#add_job_position').serialize();

       $.ajax({
          url : "<?php echo base_url(); ?>FormController/add_record_subcategory",
          type : "post",
          data: formdata,
          success:function(resp)
          {
            var data = $.parseJSON(resp);
            var ddd = data['all_record'].status;
            if(ddd == '1')
            {
                $('#result_job').fadeIn();
                $('#result_job').html("<div class='alert alert-success'>Successfully Inserted Record</div>");
                $('#result_job').fadeOut(2000);

                setTimeout(function() {
                    location.reload();
                }, 3000);

            }
            else if(ddd == '2'){
              $('#result_job').fadeIn();
                $('#result_job').html("<div class='alert alert-success'>Successfully Updated Record</div>");
                $('#result_job').fadeOut(2000);

                setTimeout(function() {
                    location.reload();
                }, 3000);
            }
          }
       });
   }
});


function get_position_category()
{
   var main_cat =  $('#job_main_cat').val();
   $.ajax({
     url : "<?php echo base_url(); ?>FormController/get_position_main_sub",
     type : "post",
     data : {
        'main_sub_cat' : main_cat
     },
     success:function(respo){
       $('#job_position_cat').html(respo);
     }
   });
}

function update_job(jobPosId){
  $.ajax({
     url : "<?php echo base_url(); ?>FormController/update_job_subcategory",
     type : "post",
     data:{
       'job_position_id' : jobPosId
     },
     success:function(resp)
     {
      $("#show_modal").modal();
      $('#submit').val('Update');
      var data = $.parseJSON(resp);
      var job_main_cat = data['single_record'].job_main_cat;
      var job_position_cat = data['single_record'].job_position_cat;
      var job_subcategory_name = data['single_record'].job_subcategory_name;
      var job_subcategory_id = data['single_record'].job_subcategory_id;
      $('#job_main_cat').val(job_main_cat);
      $('#job_position_cat').val(job_position_cat);
      $('#job_subcategory_name').val(job_subcategory_name);
      $('#job_subcategory_id_r').val(job_subcategory_id);
     }

  });
}





  //session automatic  
    $(document).ready(function(){
        var submenu = sessionStorage.getItem("submenu");
         var leadsubmenu = sessionStorage.getItem("leadsubmenu");
           $('#sub_menu_'+submenu).addClass('show');
           $('#lead_submenu_'+leadsubmenu).addClass('show');
    });

    function getClick(id,subid){
      sessionStorage.setItem("submenu", id);
      sessionStorage.setItem("leadsubmenu", subid);
    }
//end session of sidebar menu open 

</script>





