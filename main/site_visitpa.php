<?php
include "sec_dashboard_action.php";
date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));

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
	if(!in_array($mi['group'], array('Admins', 'Secretary'))){
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
      } .tr_link{cursor:pointer}
    .small {
    font-size: 11px;}
    </style>
  </head>
  <body>
        <?php 
            $where = array("username" => $mi['username']);
            $getUserID = $obj->getUser("phrepuser", $where);
                if($getUserID){
                    foreach($getUserID as $user){
                        echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid">';
                        $userid = $user['id'];
                    }
                }
         
                    
        ?>
          
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          <div class="row">
              <div class="col-lg-2"><h2>Dashboard</h2></div>
              <div class="col-lg-10"><h2>Decision - Conduct Site Visit</h2></div>
              
          </div>
          <div class="row">
              <div class="col-lg-2">
                  
                  <div class="row">
                      <div class="list-group">
                        <a href="#" class="list-group-item active">
                          Submissions
                        </a>
                        <a href="#" class="list-group-item">My Profile</a>
                        <a href="#" class="list-group-item">Messages</a>
                        <a href="#" class="list-group-item">Documents</a>
                        <a href="#" class="list-group-item">Certificates</a>
                      </div>
                  </div>
                  
              </div>
              <div class="col-lg-7">
                  <div class="row">
                      <div class="col-lg-12">
                        <?php
                          $where = array("sub_id" => $id);
                          $getprop = $obj->fetch_record_with_where("proposal", $where);
                          foreach ($getprop as $prop) {
                              $ptitle = $prop['prop_ptitle'];
                              $code = $prop['code'];
                              $where = array("id" => $prop['username']);
                              $getuser = $obj->fetch_record_with_where("phrepuser", $where);
                              foreach ($getuser as $user) {
                                  $title = $user['title'];
                                  $fname = $user['fname'];
                                  $mname = $user['mname'];
                                  $lname = $user['lname'];
                              }
                          }
                          ?>
                          
                          
                            <div class="panel panel-default">
                                <div class="panel-body">
                                   <?php echo $code;?>
                                   <h3><?php echo $ptitle;?><br><small><?php echo $title.' '.$fname.' '.$mname.' '.$lname;?></small></h3>
                                    
                                </div>
                            </div>  
                      </div>                        
                  </div>
                  <?php 
                  $revised = $obj->getMax($id);
                  if($revised){
                      foreach($revised as $revi){
                          $stat = $obj->getStat($revi['maxid']);
                          if($stat){
                              foreach($stat as $status){
                                  if($status['status_action']=='18'){
                                      echo '<div class="row">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-success" role="alert"><strong>Done!</strong> This post-approval request has been tagged as Revised! Researcher will receive email notification.</div>
                                                    <a class="btn btn-default btn-lg btn-block" href="sec_dashboard_active.php" role="button">Home</a>
                                                </div>
                                            </div>';
                                  }
                                  else{ ?>
                                      

                  
                  <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form action = "sec_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form">
                                                                    
                                  <h3>Attached Decision Letter:</h3>       
                                            <?php 
                                                $where = array("username" => $mi['username']);
                                                $getUserID = $obj->getUser("phrepuser", $where);
                                                if($getUserID){
                                                    foreach($getUserID as $user){
                                                        echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid">';
                                                        $userid = $user['id'];
                                                    }
                                                }
                                            ?>
                                            <?php
                                            date_default_timezone_set('Asia/Manila');
                                            $date = date("mdyHis", strtotime("now"));
                                            
                                            $getmaxppaid = $obj->getmaxpropapp($id);
                                            $getmax = $obj->getmaxrlpa($id, $getmaxppaid);
                                            
                                            
                                            ?>
                                            <input name="pid" type="hidden" value="<?php echo $getmaxppaid; ?>">
                                            <input id="revision" name="revision" type="hidden" value="<?php echo $getmax; ?>" placeholder="" class="form-control input-md">
                                            <input id="times" name="times" type="hidden" value="1" placeholder="" class="form-control input-md">
                                            <input id="docname" name="docname" type="hidden" value="SendVisitLetter" placeholder="" class="form-control input-md">
                                            <input id="date" name="date" type="hidden" value="<?php $date;?>" placeholder="" class="form-control input-md">
                                            <input id="doctype" name="doctype" type="hidden" value="41" placeholder="" class="form-control input-md">
                                            <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="SVL" placeholder="" class="form-control input-md">
                                            <?php $actual_link = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'].""; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                            <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                        
                                            
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <span class="btn btn-default btn-file">
                                                <input id="attach" type = "file" name = "sendvisitpa" require/>  
                                                </span>                      
                                                <input id="attached" type = "submit" name="clearance" class="btn btn-primary"/>
                                            </div>
                                        </div>
                                        <?php 
                                        $where = array("sub_id" => $id, "doctype" => "41", "kind" => "SVL", "finaldoc" => "1");
                                        $getclerance = $obj->fetch_record_with_where("document_postapproval", $where);
                                        if($getclerance){
                                            foreach($getclerance as $clear){
                                               echo '<br>';
//                                               echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$clear['path'].'">';
                                               
                                               echo '<div class="alert alert-success" role="alert">
                                                        <strong><span class="glyphicon glyphicon-file" aria-hidden="true"></span> '.$clear['orig_filename'].'</strong>
                                                        <a href="#" data-toggle="modal" data-target="#confirm-deletesitedoc" data-href="sec_dashboard_action.php?erase=1&id='.$clear['file_id'].'&subid='.$id.'&loc=site_visitpa"><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></a>
                                                    </div>';
                                               
//                                               $ecpath = $clear['path'];
                                            }
                                        }

                                        ?>
                                        </form>
                                <hr>
                                <form action = "sec_dashboard_action.php" method = "POST" class="form">
                                <input name="pid" type="hidden" value="<?php echo $getmaxppaid; ?>">
                                
                                
                                <h3>Set the date for site visit</h3>
                                <div class="form-group col-sm-6">
                                    <label for="name" class="h4">From</label>
                                    <div id="sandbox-container">
                                        <input type="text" class="form-control" id="startdate" name="stclearance"  placeholder="" required readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="name" class="h4">To</label>
                                    <div id="sandbox-container">
                                        <input type="text" class="form-control" id="enddate" name="endclearance" placeholder="" required readonly>
                                    </div>
                               </div>
                                
                                
                                
                                <h3>Recommendation</h3>
                                <div class="form-group">
                                    <label for="comment">Write comment:</label>
                                    <textarea class="form-control" rows="5" id="collatedsugg" name="collatedsugg"></textarea>
                                </div>
                                <?php
                                $ppaid = $obj->getmaxpropapp($id);
                                $getmaxrev = $obj->getmaxreviewerpaa($id, $ppaid);
                                echo '<input type="hidden" class="form-control" value="'.$getmaxrev.'" name="rev">';
                                echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$ecpath.'">';
                                ?>
                                
                                <hr>
                                    <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                    <?php 
                                        $where = array("username" => $mi['username']);
                                        $getUserID = $obj->getUser("phrepuser", $where);
                                        if($getUserID){
                                            foreach($getUserID as $user){
                                                echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid">';
                                                $userid = $user['id'];
                                            }
                                        }
                                    ?>
                                    
                                    
                                    
                                        <?php 
                                        $getmaxrl = $obj->getmaxrllpa($id);
                                        if($getmaxrl>0){ $d = "";} else{$d = "disabled";}
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-3">
                                            </div>
                                            <div class="col-lg-6"><center>
                                                    <button name="sitevisit" type="submit" id="form-submit" class="btn btn-warning">Endorse to a Site Visit</button> </center>
                                            </div>
                                            <div class="col-lg-3">
                                            </div>
                                        </div>
                                    
                                    
                                                                   
                                    
                                    
                                </form>                                        
                            </div>
                      </div>
                        
                        
                        
                    </div>
                    </div>
                            <?php  }
                              }
                          }
                      }
                  }
                  ?>
                  
              
              </div>   
          </div>
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
$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});

CKEDITOR.replace( 'collatedsugg' );
</script>

<script>

jQuery("[id^=comment]").on("keyup", function () {
    if (jQuery(this).val() != "" ) {
            jQuery("[id^=chx]").attr("required", "true");
    } else {        
        jQuery("#chx)".removeAttr("disabled");
    }
});

</script>

<script>
$(document).on('click', '.comment', function() {
        
        alert(this.id);
});
</script>

<script>
function goBack() {
    window.history.back();
}
</script>

<!--DATE PICKER-->
<script>
    $(document).ready(function(){ 
        $('#sandbox-container input').datepicker({
            orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
    });
    
</script>
<!--DATE PICKER-->

<div class="modal fade" id="confirm-deletesitedoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Do you want to delete the file?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-deletesitedoc').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>