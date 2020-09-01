<div class="container">
    <form method="post" id="import_form" enctype="multipart/form-data">
        <div class="form-group form-row">
            <label class='col-md-3 col-form-label'>Select Excel Or CSV File</label>
            <div class="col-md-4">
            <input type="file" name="file" id="file" required accept=".xls, .xlsx,.csv" />
            </div>
        </div>
        <div class="form-group form-row">
            <label class='col-md-3'></label>
            <div class="col-md-4">
            <button class='btn btn-danger' type="submit">
                <span id='loading' style='display:none' class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Upload</button>
            </div>
        </div>
        <div id='progress' style='display:none' class="form-row form-group">
            <div class="col-md-8">
                <div class="progress" style="height:10px">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                        aria-valuemin="0" aria-valuemax="100" style="width:0%">
                            0%
                    </div>
                </div>
            </div>
       </div>
    </form>

  <div>
    <div class="table-responsive-md">
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Phone No</th>
                    <th>Email ID</th>
                    <th>Company</th>
                    <th>Designation</th>
                </tr>
            </thead>
        </table>
    </div>
  </div>
    
<script>
$(function(){

 $('#import_form').on('submit', function(event){
  event.preventDefault();
  $.ajax({
    xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                percentComplete = parseInt(percentComplete * 100);

                $('.progress-bar').width(percentComplete+'%');
                $('.progress-bar').html(percentComplete+'%');

            }
            }, false);

            return xhr;
        },
   url:"<?php echo base_url(); ?>excel_import/import",
   method:"POST",
   data:new FormData(this),
   contentType:false,
   cache:false,
   processData:false,
   beforeSend: function() {
        $('[type="submit"]').attr('disabled',true);
        $('#progress').fadeIn();
        // $("#loading").show();
    },
   success:function(data){
       if(data){
        toastr.success('successfully inserted')
       }else {
        toastr.error('somethink went to wrong')
       }
    
   },
   complete:function(data){
       $('[type="submit"]').attr('disabled',false);
       $('#progress').fadeOut(2000)
    //    $("#loading").hide();
       $('#example').DataTable().ajax.reload()
   // $('#file').val('');
   // load_data();
    // alert(data);
   },

  })
 });

 $('#example').DataTable( {
        processing: true,
        serverSide: true,
        dom: '<lf<tB>ip>',
        ajax:{  
                url:"<?php echo base_url(); ?>excel_import/fetch_employee",  
                type:"POST"  
           },  
        columnDefs:[  
                {  
                     "targets":[0, 3, 4],  
                     "orderable":false,  
                },  
           ],  

           buttons: [
            { 
                extend: 'csv',
                className: 'btn btn-info',
                filename: 'employee',
             },
            { extend: 'excel', 
              className: 'btn btn-success',
              filename: 'employee',
             }
        ]
    } );

});
</script>
