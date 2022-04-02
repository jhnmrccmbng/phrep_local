<?php
include "researcher_action.php";

?>
<?php
	$currDir = dirname(__FILE__);
        $currDir = substr($currDir, 0, -5);
//        echo $currDir;
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	
	/* grant access to the groups 'Admins' and 'Data entry' */
	$mi = getMemberInfo();
	if(!in_array($mi['group'], array('Admins', 'Researcher'))){
            header("location: ../index.php?signIn=1");
//		exit;
	}

       
include_once("$currDir/header.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Franklin" rel="stylesheet">
    <link rel="stylesheet" href="../resources/select2/select2.css">

    <style>
      body {
        font-family: 'Libre Franklin', sans-serif;
      }.tr_link {cursor:pointer}</style>
  </head>
  <body>
      <div class="container-fluid"><?php $id = (int) $_GET['id'];?>
          <?php 
                    $where = array("username" => $mi['username']);
                    $getUserID = $obj->getUser("phrepuser", $where);
                    if($getUserID){
                        foreach($getUserID as $user){
                            echo '<input type="hidden" class="form-control" value="'.$user[id].'" name="userid">';
                            $userid = $user[id];
                        }
                    }
          ?>
              
<?php
$where = array("username" => $mi['username']);
$getUserUpdate = $obj->getUser("phrepuser", $where);
    if($getUserUpdate){?>
          
          <div class="row">
              <div class="col-lg-2"><h2></h2></div>
              <div class="col-lg-10">           
                  <h1>Submit Letter</h1>
                  <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form">
                      <h3>Free and Prior Informed Consent Approval<br><small>Attach Letter</small></h3>

                          <input id="times" name="times" type="hidden" value="1" placeholder="" class="form-control input-md">
                          <?php $whered = array("docid" => "23");
                          $getdoctype = $obj->fetch_record_with_where("document_type", $whered);
                          foreach ($getdoctype as $gdt) {
                              $docname = $gdt["forfilename"];
                              $did = $gdt["docid"];
                          } ?>
                          <input id="docname" name="docname" type="hidden" value="<?php echo $docname; ?>" placeholder="" class="form-control input-md">
                          <input id="date" name="date" type="hidden" value="<?php $date; ?>" placeholder="" class="form-control input-md">
                          <input id="doctype" name="doctype" type="hidden" value="<?php echo $did; ?>" placeholder="" class="form-control input-md">
                          <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                          <input id="kind" name="kind" type="hidden" value="FPI" placeholder="" class="form-control input-md">
                            <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                          <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                          <input id="username" name="username" type="hidden" value="<?php echo $userid; ?>" placeholder="" class="form-control input-md">

                          <div class="row">
                              <div class="col-lg-12">
                                  <span class="btn btn-default btn-file">
                                      <input id="attach" type = "file" name = "imageiccsubmit" accept="application/pdf"/>  
                                  </span>                      
                                  <input id="attached" type = "submit" name="iccs" class="btn btn-primary"/>

                                  <?php
                                  $where = array("sub_id" => $id, "doctype" => "23", "kind" => "FPI", "finaldoc" => "1");
                                  $getclerance = $obj->fetch_record_with_where("document", $where);
                                  if ($getclerance) {
                                      foreach ($getclerance as $clear) {
                                          echo '<br><br><span class="glyphicon glyphicon-file" aria-hidden="true"></span> ' . $clear[file_name];
                                          echo ' <input id="ecpath" name="ecpath" type="hidden" value="' . $clear[path] . '">';
                                          $ecpath = $clear[path];
                                      }
                                      echo '<br><br><a class="btn btn-primary" href=dashboard.php#approved" role="button">Done</a>';
                                  }
                                  ?>  
                              </div>                                          
                          </div>

                      </form>
              </div>
          </div>
          
          <div class="row">
                <div class="col-lg-2">
                </div>
              
                <div class="col-lg-10 tab-content">
                    
                    
                    
                    
                </div>
          </div>
<?php } else{ echo '<br><br><br><br><br><br><br><div class="row">
              <div class="col-lg-4"></div>
              <div class="col-lg-4"><div class="alert alert-danger" role="alert"><center>Please update your profile to get started.<br><br><a class="btn btn-success" href="update_profile.php" role="button">Update Profile!</a></center></div></div>
              <div class="col-lg-4"></div>
          </div> '; }?>
        
          
          
          
          
          
          
      </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
  </body>
  
</html>
<?php 
include_once("$currDir/footer.php");
?>
<script>
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});


$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

<script>
$('#myTab a').click(function(e) {
  e.preventDefault();
  $(this).tab('show');
  history.pushState( null, null, $(this).attr('href') );
});

// store the currently selected tab in the hash value
$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
  var id = $(e.target).attr("href").substr(1);
  window.location.hash = id;
});

// on load of the page: switch to the currently selected tab
var hash = window.location.hash;
$('#myTab a[href="' + hash + '"]').tab('show');
</script>



<div id="dataModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Proposal</h4>
            </div>
            <div class="modal-body" id="proposal_detail">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var subid = $(this).attr("id");
  $.ajax({
   url:"selectproposal.php",
   method:"POST",
   data:{sid:subid},
   success:function(data){
    $('#proposal_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
 
</script>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Attention!</h1>
                </div>
                <div class="modal-body">
                    Are you sure to delete unfinished submission?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>

<script>
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var subid = $(this).attr("id");
  $.ajax({
   url:"selectapproved.php",
   method:"POST",
   data:{sid:subid},
   success:function(data){
    $('#proposal_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
</script>