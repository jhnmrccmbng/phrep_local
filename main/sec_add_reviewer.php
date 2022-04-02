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
      }</style>
  </head>
  <body>
      <form action = "sec_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
          
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
                            $where = array("secretary" => $userid);
                            $getUserID = $obj->getUser("rec_list", $where);
                            if($getUserID){
                                foreach($getUserID as $erc){
                                    $ercid = $erc['id'];
                                }
                            }
                            ?>
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          <input type="hidden" value="<?php echo $id; ?>" name="sub_id">
          <div class="row">
              <h2>Dashboard</h2>
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
                  
                  <div class="row">
                      <button type="button" class="btn btn-primary">
                          <span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Proposal
                      </button>
                  </div><br>
                  
              </div>
              <div class="col-lg-10 col-xs-12">
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="panel panel-primary">
                            <div class="panel-heading">
                              <h3 class="panel-title">Add Another Reviewer</h3>
                            </div>
                              <table class="table table-bordered">
                                  <tr>
                                      <th>CODE</th><th>TITLE</th><th>INVESTIGATOR</th>
                                  </tr>
                                  
                                  <tr>
                                  <?php
                                  $myrow = $obj->joiningTwoTabless("proposal", "phrepuser", "username", "id", $id);
                                  if($myrow){
                                      foreach($myrow as $row){
                                        echo '<td>'.$row['code'].'</td>'; 
                                            $uc = ucwords($row['prop_ptitle']);
                                            $strlen = strlen($row['"prop_ptitle"']);
                                            if ($strlen>50){echo'<td>'.substr($uc, 0, 50).'...</td>';}
                                            else {echo'<td>'.substr($uc, 0, 50).'</td>'; }
                                        echo'<td>'.$row['fname'].' '.$row['mname'].' '.$row['lname'].'</td>';
                                      }
                                  }
                                  ?>
                                      
                                  </tr>
                              </table>
                              
                            <div class="panel-body">
                                        <div class="form-group">
                                          <label class="col-md-3 control-label" for="coreviewer">Additional Reviewer</label>
                                          <div class="col-md-7">
                                              <table class="table table-condensed table-bordered">
                                                  <tr>
                                                      <th><span class="glyphicon glyphicon-user" aria-hidden="true"></span></th>
                                                      <th>NAME</th>
                                                  </tr>
                                                  
                                                    <?php 
                                                    $notassigned = $obj->getIDNotAssigned($id, $ercid);

                                                    // foreach($notassigned as $id => $tobeassigned){
                                                    //    echo "<pre>".$tobeassigned['phrepuser_id']."</pre>";
                                                    // }
                                                    
                                                    if($notassigned){
                                                        foreach($notassigned as $tobeassigned){

                                                            $where = array("id" => $tobeassigned['phrepuser_id']);
                                                            $getreviewer = $obj->fetch_record_with_where("phrepuser", $where);

                                                            if($getreviewer){
                                                                foreach($getreviewer as $rev){
                                                        
                                                                    echo '<tr>';
                                                                    echo '<td><input type="checkbox" name="rev[]" id="rev'.$rev['id'].'" value="'.$rev['id'].'"></td>';
                                                                    echo '<td>'.$rev['fname'].' '.$rev['mname'].' '.$rev['lname'].'</td>';
                                                                    echo '</tr>';
                                                                }
                                                            }

                                                        }
                                                    }
                                                    else{echo "<tr><td colspan='2'><center><i>Sorry! All Reviewers were assigned.</i></center></td></tr>";}
                                                    
                                                    ?>
                                                      
                                                  
                                              </table>
                                              
                                          </div>
                                        </div>
                                

                                        <div class="form-group">
                                          <label class="col-md-3 control-label" for="confirmexpedited"></label>
                                          <div class="col-md-7">
                                            <button id="confirmexpedited" name="assignexpeditedadd" class="btn btn-primary">Assign</button>
                                            <button type='button' class="btn btn-default" onclick='goBack()'>Cancel</button>
                                          </div>
                                        </div>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                            </div>
                              
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
    </form>
  </body>
  
</html>
<?php 
include_once("$currDir/footer.php");
?>
<script>
    $(document).ready(function(){ 
        $('#sandbox-container input').datepicker({
            orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
    });
    
</script>

<script>
$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

<script>
function goBack() {
    window.history.back();
}
</script>