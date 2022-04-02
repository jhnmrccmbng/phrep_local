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
      } .tr_link{cursor:pointer}</style>
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
              <div class="col-lg-10"><h2>Send Notification for Reports</h2></div>
              
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
                                  if($getproposal){foreach($getproposal as $prop){$title = $prop['prop_ptitle']; echo '<tr><td><center><span class="glyphicon glyphicon-file" aria-hidden="true"></span></center></td><td>'.$prop['prop_ptitle'].'</td></tr>';}}
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
                  <hr>
                    <div class="row">
                    <div class="col-lg-12">
                        
                        <?php
                        $where = array("sub_id" => $id);
                        $getp = $obj->fetch_record_with_where("proposal", $where);
                        foreach($getp as $p){
                            $where = array("id" => $p['username']);
                            $getu = $obj->fetch_record_with_where("phrepuser", $where);
                            foreach($getu as $u){
                                $fullname = $u['fname'].' '. $u['mname'].' '.$u['lname'];
                                $lastname = $u['lname'];
                                
                                $where = array("memberID" => $u['username']);
                                $getemail = $obj->fetch_record_with_where("membership_users", $where);
                                foreach($getemail as $em){
                                    $email = $em['email'];
                                }
                            }
                        }
                        ?>
                        
                        <?php 
                        $where = array("id" => $userid);
                        $getsec = $obj->fetch_record_with_where("phrepuser", $where);
                        foreach($getsec as $sec){
                            $secname = $sec['fname'].' '.$sec['mname']." ".$sec['lname'];
                            $where = array("secretary" => $sec['id']);
                            $geterc = $obj->fetch_record_with_where("rec_list", $where);
                            foreach($geterc as $erc){
                                $n = $erc['erc_initials']." - ".$erc['erc_name'];
                            }
                            
                            $where = array("memberID" => $sec['username']);
                            $secemail = $obj->fetch_record_with_where("membership_users", $where);
                            foreach($secemail as $semail){
                                $sm = $semail['email'];
                            }
                        }
                        
                        ?>
                        
                        <form action = "sec_dashboard_action.php" method = "POST" enctype = "multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email to</label>
                                <input type="email" class="form-control" value="<?php echo $fullname.' - '.$email;?>" disabled>
                                <input type="hidden" class="form-control" name="emailnotif" value="<?php echo $email;?>">
                                <input type="hidden" class="form-control" name="emailsec" value="<?php echo $sm;?>">
                                <input type="hidden" class="form-control" name="subject" value="Progress Report Notification">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="sendreport" name="sendreport">
                                    <?php 
                                    echo '<p>Dear <strong>'.$lastname.'</strong>,</p>

                                    <p>This is to respectfully request you to submit a report on your study <strong>&quot;'.ucwords($title).'&quot;</strong>. Please see attached official request letter of the '.$n.'.</p>

                                    Yours truly,<br />
                                    <strong>'.$secname.'</strong><br />
                                    Secretariat'; 
//                                    echo $body;
                                    ?>

                                </textarea>
                            </div>
                            <input class="btn btn-primary" type="submit" name="sendnotifnow" value="Send">
                            <!--<a href="#" data-href="sec_dashboard_action.php?sendprogressnotif=1&e=<?php #echo $e;?>&b=<?php #echo $b;?>" class="btn btn-success" data-toggle="modal" data-target="#confirm-sendprogress">Send</a>-->
                        </form>
                    </div>
                    </div>
                  
              
              </div>    
              <div class="col-lg-3">            
                  
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
</script>

<script>
    CKEDITOR.replace( 'sendreport' );
</script>

<div class="modal fade" id="confirm-sendprogress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1>Reminder!</h1>
            </div>
            <div class="modal-body">
                Are you sure you want to send a notification for progress report? 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary btn-ok">Okay</a>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-sendprogress').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>