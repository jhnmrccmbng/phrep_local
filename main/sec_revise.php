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
              <div class="col-lg-10"><h2>Decision - Revise</h2></div>
              
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
                                                    
                          <div class="panel panel-default">
                              
                                <table class="table table-bordered">
                                    <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span></center></td><td>'.$prop['code'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-file" aria-hidden="true"></span></center></td><td>'.$prop['prop_ptitle'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-user" aria-hidden="true"></span></center></td><td>'.$prop['fname'].' '.$prop['mname'].' '.$prop['lname'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr ><td><center><span class="glyphicon glyphicon-calendar" aria-hidden="true" data-toggle="tooltip" title="DUE DATE"></span></center></td><td>'.$prop['rt_duedate'].'</td></tr>';}}
                                  ?>
                                    
                                </table>
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
                                  if($status['status_action']=='5'){
                                      echo '<div class="row">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-success" role="alert"><strong>Done!</strong> Investigator has been notified!</div>
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
                                                                    
                                  <h4>Attached Decision Letter:</h4>       
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
                                            
                                            $getmax = $obj->getmaxrl($id);
                                            
                                            
                                            ?>
                                            <input id="revision" name="revision" type="hidden" value="<?php echo $getmax; ?>" placeholder="" class="form-control input-md">
                                            <input id="times" name="times" type="hidden" value="1" placeholder="" class="form-control input-md">
                                            <input id="docname" name="docname" type="hidden" value="RevisedLetter" placeholder="" class="form-control input-md">
                                            <input id="date" name="date" type="hidden" value="<?php $date;?>" placeholder="" class="form-control input-md">
                                            <input id="doctype" name="doctype" type="hidden" value="16" placeholder="" class="form-control input-md">
                                            <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="RL" placeholder="" class="form-control input-md">
                                            <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                            <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                        
                                            
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <span class="btn btn-default btn-file">
                                                <input id="attach" type = "file" name = "sendrl" require/>  
                                                </span>                      
                                                <input id="attached" type = "submit" name="clearance" class="btn btn-primary"/>
                                            </div>
                                        </div>
                                        <?php 
                                        $where = array("sub_id" => $id, "doctype" => "16", "kind" => "RL", "finaldoc" => "1");
                                        $getclerance = $obj->fetch_record_with_where("document", $where);
                                        if($getclerance){
                                            foreach($getclerance as $clear){
                                               echo '<br><span class="glyphicon glyphicon-file" aria-hidden="true"></span> '.$clear['orig_filename'];
                                               echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$clear['path'].'">';
                                               $ecpath = $clear['path'];
                                            }
                                        }

                                        ?>
                                        </form>
                                <hr>
                                <form action = "sec_dashboard_action.php" method = "POST" class="form">
                                <h4>Recommendation</h4>
                                <div class="form-group">
                                    <label for="comment">Write comment:</label>
                                    <textarea class="form-control" rows="5" id="collatedsugg" name="collatedsugg"></textarea>
                                </div>
                                <?php
                                $getmaxrev = $obj->getmaxreviewer($id);
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
                                        <div class="row">
                                            <div class="col-lg-12">
                                                
                                                
                                                <div class="alert alert-danger" role="alert"><h4>Please mark a check below for revision.</h4></div>
                                                <table class="table table-bordered table-condensed">
                                                    <tr>
                                                        <th><center><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></center></th>
                                                        <th>File Type</th>
                                                    </tr>
                                                    
                                                    <?php 
                                                    $getdocs = $obj->getdocumentforrevision($id);
                                                    if($getdocs){
                                                        foreach($getdocs as $doc){
                                                            echo '<tr>';
                                                                echo '<td>';
                                                                    echo '<div class="checkbox"><center>';
                                                                        echo '<label><input type="checkbox" name="revise[]" value="'.$doc['file_id'].'"></label>';
                                                                    echo '</center></div>';                                                            
                                                                echo '</td>';
                                                                echo '<td>'; 
                                                                    echo $doc['doctype_desc'].' ('.$doc['doctypetimes'].')';
                                                                echo '<br><small class="small">'.$doc['orig_filename'].' | '.$getnum = $obj->ordinalize($doc['revision']).' version</small></td>';
                                                                                                                        
                                                            echo '</tr>';
                                                        }
                                                    }
                                                    ?>
                                                    
                                                    
                                                </table>
                                                
                                            </div>
                                        </div>   
                                    <?php 
//                                    $maxrev = $obj->getmaxreviewer($id);
//                                    echo '<input type="hidden" value="'.$maxrev.'" name="maxrev">';
//                                    
//                                    $getreviewers = $obj->getMemberReviewer($id, $maxrev);
//                                    foreach($getreviewers as $reviwer){
//                                        echo '<input type="hidden" value="'.$reviwer['phrepuser_id'].','.$reviwer['primary_reviewer'].'" name="pid[]">';
//                                    }
//                                    $getreviewersChair = $obj->getChairmanReviewer($id, $maxrev);
//                                    echo '<input type="hidden" value="'.$getreviewersChair.'" name="chid">';
                                    
//                                    $maxrev = $obj->getmaxreviewer($id);
//                                    echo '<input type="hidden" value="'.$maxrev.'" name="maxrev">';
//                                    
//                                    $getreviewers = $obj->getMemberReviewer($id, $maxrev);
//                                    foreach($getreviewers as $reviwer){
//                                        echo '<input type="hidden" value="'.$reviwer['phrepuser_id'].','.$reviwer['primary_reviewer'].'" name="pid[]">';
//                                    }
                                    
                                    ?>
                                    <hr>           
                                    
                                        <?php 
                                        $getmaxrl = $obj->getmaxrll($id);
                                        if($getmaxrl>0){ $d = "";} else{$d = "disabled";}
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-3">
                                            </div>
                                            <div class="col-lg-6"><center>
                                                    <button name="reviseprop" type="submit" id="form-submit" class="btn btn-warning" <?php echo $d;?>>REVISE</button> </center>
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
              <div class="col-lg-3">
                  <div class="row">
                      
                      <center>
                          <div class="panel panel-primary">
                            <div class="panel-heading">Reviewer's Decision</div>
                            <table class="table table-condensed table-bordered table-hover table-striped table-responsive">
                                <tr>
                                    <th>Reviewer</th>
                                    <th>Decision</th>
                                </tr>
                                
                                <?php 
                                $maxrev = $obj->getmaxreviewer($id);
                                $getevaluators = $obj->getreviewerseval($userid, $id, $maxrev);
                                if($getevaluators){
                                    foreach($getevaluators as $eval){
                                        if($eval['desid'] == '1'){
                                        echo '<tr class="success">';
                                        echo '<td>'.substr($eval['fname'],0, 1).'. '.substr($eval['mname'],0,1).'. '.$eval['lname'].'</td>';
                                        echo '<td>'.$eval['dec_desc'].'</td>';
                                        echo '</tr>';                                            
                                        }
                                        else if($eval['desid'] == '2'){
                                        echo '<tr class="warning">';
                                        echo '<td>'.substr($eval['fname'],0, 1).'. '.substr($eval['mname'],0,1).'. '.$eval['lname'].'</td>';
                                        echo '<td>'.$eval['dec_desc'].'</td>';
                                        echo '</tr>';                                            
                                        }
                                        else{
                                        echo '<tr>';
                                        echo '<td>'.substr($eval['fname'],0, 1).'. '.substr($eval['mname'],0,1).'. '.$eval['lname'].'</td>';
                                        echo '<td>'.$eval['dec_desc'].'</td>';
                                        echo '</tr>';                                            
                                        }
                                        
                                    }
                                }
                                
                                ?>
                                
                                
                            </table>
                        </div>                         
                          
                        </center>
                  </div>                  
                  
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