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
                            echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid" id="userid">';
                            $userid = $user['id'];
                        }
                    }
                    ?>
          
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          
          <div class="row">
              <div class="col-lg-12">
                  <h2>
                      <?php
                      $where = array("secretary" => $userid);
                      $getRECname = $obj->fetch_record("rec_list", $where);
                      foreach($getRECname as $rec){
                          echo $rec['erc_name'];
                          $recid = $rec['id'];
                      }
                      ?>
                  </h2>
              </div>
          </div>
          <hr>
          <div class="row">
              <div class="col-lg-2"><h2>Dashboard</h2>
                  <?php include("$currDir/main/sec_dashboard_pane.php"); ?>                  
              </div>
              
              
              <div class="col-lg-10">
                <div class="row">
                <div class="col-lg-9">
                    <form class="form-horizontal" role="form" action="sec_dashboard_action.php" method="POST">  
                        <fieldset>
                        <?php
                            if (isset($_GET['update'])) {
                                if (isset($_GET['u'])) {
                                    $id = $_GET['u'];

                                    $row = $obj->selectReviewer($id);
                                    foreach($row as $r){
                                        
                        ?>

                        <!-- Form Name -->
                        <legend>Update Secretary</legend>
                        
                        <input id="id" name="id" type="hidden" value="<?php echo $id; ?>">
                        <input id="usernameid" name="usernameid" type="hidden" value="<?php echo $r['username']; ?>">
                        
                        <!-- Text input-->
<!--                        <div class="form-group" id="usernametextbox">
                          <label class="col-md-3 control-label" for="username">Username</label>  
                          <div class="col-md-8">
                              <input id="username" value="<?php #echo $r['memberID'];?>" name="username" type="text" placeholder="" class="form-control input-md" required="" disabled="" onBlur="checkAvailability()">
                              <span id="user-availability-status"><small class="text-danger">Please contact PHREP Coordinator to request username change.</small></span>
                          </div>
                        </div>-->
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="email">Email</label>  
                          <div class="col-md-8">
                          <input id="email" value="<?php echo $r['email'];?>" name="email" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>
                        
                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="title">Title</label>
                          <div class="col-md-8">
                            <select id="title" name="title" class="form-control">
                              <option selected value="<?php echo $r['title'];?>"><?php echo $r['title'];?></option>
                              <option value="Mr.">Mr.</option>
                              <option value="Ms.">Ms.</option>
                              <option value="Mrs.">Mrs.</option>
                              <option value="Dr.">Dr.</option>
                              <option value="Atty.">Atty.</option>
                              <option value="Rev.">Rev.</option>
                              <option value="Hon.">Hon.</option>
                              <option value="Sec.">Sec.</option>
                              <option value="Prof.">Prof.</option>
                            </select>
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="fname">First Name</label>  
                          <div class="col-md-8">
                          <input id="fname" value="<?php echo $r['fname'];?>" name="fname" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="mname">Middle Name</label>  
                          <div class="col-md-8">
                          <input id="mname" value="<?php echo $r['mname'];?>" name="mname" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="lname">Last Name</label>  
                          <div class="col-md-8">
                          <input id="lname" value="<?php echo $r['lname'];?>" name="lname" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="pnum">Contact Number</label>  
                          <div class="col-md-8">
                          <input id="pnum" value="<?php echo $r['pnum'];?>" name="pnum" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="institution">Institution</label>  
                          <div class="col-md-8">
                          <input id="institution" value="<?php echo $r['institution'];?>" name="institution" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>
                        
                        
                        <!-- Button (Double) -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="savesec"></label>
                          <div class="col-md-8">
                            <button id="savesec" name="updaterev" class="btn btn-primary">Update</button>
                          </div>
                        </div>
                        
                        
                        <?php
                                }
                        } 
                            } else{
                        ?>
                        <!-- Form Name -->
                        <h2>Register Reviewer</h2>
                        <!-- Text input-->
                        <input id="recid" name="recid" type="hidden" value="<?php echo $recid;?>">
                        <div class="form-group" id="usernametextbox">
                          <label class="col-md-3 control-label" for="username">Username</label>  
                          <div class="col-md-8">
                              <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required onBlur="checkAvailability()">
                              
                          </div>
                        </div>
                        

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="email">Email</label>  
                          <div class="col-md-8">
                          <input id="email" name="email" type="text" placeholder="" class="form-control input-md" required>

                          </div>
                        </div>
                        
                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="title">Title</label>
                          <div class="col-md-8">
                            <select id="title" name="title" class="form-control">
                                <option disabled selected>(Select title)</option>
                              <option value="Mr.">Mr.</option>
                              <option value="Ms.">Ms.</option>
                              <option value="Mrs.">Mrs.</option>
                              <option value="Dr.">Dr.</option>
                              <option value="Atty.">Atty.</option>
                              <option value="Rev.">Rev.</option>
                              <option value="Hon.">Hon.</option>
                              <option value="Sec.">Sec.</option>
                              <option value="Prof.">Prof.</option>
                            </select>
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="fname">First Name</label>  
                          <div class="col-md-8">
                          <input id="fname" name="fname" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="mname">Middle Name</label>  
                          <div class="col-md-8">
                          <input id="mname" name="mname" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="lname">Last Name</label>  
                          <div class="col-md-8">
                          <input id="lname" name="lname" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>    
                        
                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="title">Member Type</label>
                          <div class="col-md-8">
                            <select id="membertype" name="membertype" class="form-control">
                                <option disabled selected>(Select Membership)</option>
                              <option value="1">Chairman</option>
                              <option value="2">Member</option>
                            </select>
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="pnum">Contact Number</label>  
                          <div class="col-md-8">
                          <input id="pnum" name="pnum" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="institution">Institution</label>  
                          <div class="col-md-8">
                          <input id="institution" name="institution" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>
                        
                        <input id="password" name="password" type="hidden" value="<?php $get = $obj->generateRandomString(); echo MD5($get); ?>">
                        <input id="subject" name="subject" type="hidden" value="Thank you for signing up!">
                        
                        <!--            SENDING EMAIL               -->
                        <?php 
                        $getSecretary = $obj->getRECName($userid);
                        foreach($getSecretary as $emailsec){
                            echo "<input name='recname' type='hidden' value='".$emailsec['erc_name']."'>";
                        }

                        //Getting email template
                        $where = array("id"=>"3");
                        $getTemplate = $obj->fetch_record_with_where("email_templates", $where);
                        foreach($getTemplate as $template){
                            echo '<input type="hidden" value="'.$template['subject'].'" name="subject">';
                            echo '<textarea name="tempbody" style="display:none;">'.$template['body'].'</textarea>'; 
                        }

                        ?>

                        <!--            SENDING EMAIL               -->     
                        
                        <!-- Button (Double) -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="saverev"></label>
                          <div class="col-md-8">
                            <button id="saverev" name="saverev" class="btn btn-primary">Save</button>
                          </div>
                        </div>
                        
                        <?php                                
                        }
                        ?>
                        
                        </fieldset>
                        </form>

                    
                    
                </div>
                <div class="col-lg-2"></div>
            </div>
                  
                  <hr>
                  <h3>List of Reviewers</h3>
                    <form action = "sec_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                      
                            <table class="table table-bordered table-condensed">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th><center>Action</center></th>
                                </tr>
                                    <?php 
                                    $whererec = array("secretary" => $userid);
                                    $getrec = $obj->fetch_record_with_where("rec_list", $whererec);
                                    foreach($getrec as $rc){
                                        $rec = $rc['id'];
                                    }
                                    $getreviewer = $obj->fetchReviewers($userid);
                                    foreach($getreviewer as $revs){
                                        echo '<tr>';
                                        echo '<td>'.$revs['fname'].' '.$revs['mname'].' '.$revs['lname'].'</td>';
                                        echo '<td>'.$revs['type_name'].'</td>';
                                        echo '<td>'.$revs['memberID'].'</td>';
                                        echo '<td>'.$revs['email'].'</td>';
                                        echo '<td><center><a class="btn btn-warning btn-sm" href="sec_register_reviewer.php?update=1&u='.$revs['uid'].'" role="button">Edit</a> | '
                                                . ' <a href="" data-href="sec_register_reviewer.php?deleterev=1&i='.$revs['uid'].'&m='.$revs['memberID'].'&r='.$rec.'" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirm-delete">Delete</a></center></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                            </table>
                    </form>
                  
              </div>
          </div>
      </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
  </body>
  
</html>

            <div id="dataModal" class="modal fade">
              <div class="modal-dialog">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Proposal</h4>
                </div>
                <div class="modal-body" id="proposal_detail">

                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
               </div>
              </div>
            </div>



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
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var subid = $(this).attr("id");
  $.ajax({
   url:"sec_selectproposal.php",
   method:"POST",
   data:{sid:subid},
   success:function(data){
    $('#proposal_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
</script>

<script>
function checkAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_username.php",
data:'username='+$("#username").val(),
type: "POST",
success:function(data){
$("#usernametextbox").html(data);
},
error:function (){}
});
}
</script>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this reviewer? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

var u = document.getElementById("userid").value;
$("#idp").attr("href", "sec_personal_info.php?id="+u);
</script>