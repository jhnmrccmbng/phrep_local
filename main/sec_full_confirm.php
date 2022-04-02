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
                              <h3 class="panel-title">Full Review</h3>
                            </div>
                              <table class="table table-bordered">
                                  <tr>
                                      <th>CODE</th><th>TITLE</th><th>PRIMARY INVESTIGATOR</th>
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
                                
                                <?php 
                                    $where = array("sub_id" => $id);
                                    $checkifassigned = $obj->fetch_record("rev_groups", $where);
                                    if($checkifassigned){
                                        echo '<div class="alert alert-danger">
                                                <strong>Opps!</strong> This proposal has been assigned already!.
                                              </div>';
                                    }
                                    else{?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="chairman">Chairman</label>  
                                            <div class="col-md-4">

                                                <?php 
                                                $id1 = "rec_list_id"; $id2 = "id"; $id3 = "phrepuser_id";
                                                $where = array("a.type_id" => 1, "b.secretary" => $userid);
                                                $checkChairman = $obj->selectChairman("rec_groups", "rec_list", "phrepuser", $id1, $id2, $id3, $where);
                                                if($checkChairman){
                                                    foreach($checkChairman as $row){
                                                        echo '<input id="chairmanid" name="chairmanid" value="'.$row['id'].'" type="hidden" placeholder="" class="form-control input-md">';
                                                        echo '<input id="chairman" name="chairman" value="'.$row['fname'].' '.$row['mname'].' '.$row['lname'].'" type="text" placeholder="" class="form-control input-md" disabled="disabled">';
                                                        echo '<label><input type="hidden" value="0" name="cmprime"><input type="checkbox" value="1" name="cmprime">&nbsp; Primary Reviewer?</label>';
                                                    }
                                                }
                                                ?>

                                            </div>
                                          </div>
                                          
                                          <!-- Multiple Checkboxes -->
                                            <div class="form-group">
                                              <label class="col-md-4 control-label" for="checkboxes">Choose Primary Reviewers (PR)</label>
                                              <div class="col-md-4">
                                                <?php
                                                  $id1 = "id"; $id2 = "secretary"; $id3 = "id"; $id4 = "rec_list_id";
                                                  $checkgrouprev = $obj->joiningThreeTables("phrepuser", "rec_list", "rec_groups", $id1, $id2, $id3, $id4, $userid);
                                                  if($checkgrouprev){
                                                      foreach($checkgrouprev as $rowrev){
                                                          if($rowrev['type_id'] == 1){
                                                              
                                                          }
                                                          else{
                                                          $where = array("id" => $rowrev['phrepuser_id']);
                                                          $fetchuser = $obj->fetch_record_with_where("phrepuser", $where);
                                                          if($fetchuser){
                                                              foreach($fetchuser as $rowf){
                                                                    echo '<div class="checkbox">
                                                                      <label for="checkboxes-0">
                                                                        <input type="hidden" id="checkboxes-'.$rowf['id'].'" name="prirev-'.$rowf['id'].'"  value="0">
                                                                        <input type="checkbox" id="checkboxes-'.$rowf['id'].'" name="prirev-'.$rowf['id'].'" value="1">
                                                                        '.$rowf['fname'].' '.$rowf['mname'].' '.$rowf['lname'].'
                                                                      </label>
                                                                    </div>';                                                                    
                                                              }                                                            
                                                          }
                                                              
                                                          }
                                                      }
                                                  }
                                                ?>
                                              </div>
                                            </div>
                                
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="startdate">Review Due Date</label>  
                                            <div class="col-md-4">

                                              <div id="sandbox-container">
                                                        <input type="text" class="form-control" name="duedate" readonly>
                                              </div>

                                            </div>
                                        </div>
                                
                                                <?php 
                                                $id1 = "id"; $id2 = "secretary"; $id3 = "id"; $id4 = "rec_list_id";
                                                $checkChairman = $obj->joiningThreeTables("phrepuser", "rec_list", "rec_groups", $id1, $id2, $id3, $id4, $userid);
                                                if($checkChairman){
                                                    foreach($checkChairman as $row){
                                                        if($row['type_id'] == 1){
                                                            echo '<input id="reviewer" name="chairman" value="'.$row['phrepuser_id'].'" type="hidden" placeholder="" class="form-control input-md">';
                                                        }
                                                        else{echo '<input id="reviewer" name="reviewer[]" value="'.$row['phrepuser_id'].'" type="hidden" placeholder="" class="form-control input-md">';}                                                        
                                                    }
                                                }
                                                ?>

                                        <div class="form-group">
                                          <label class="col-md-4 control-label" for="notetocoreviewer">Note to Reviewers</label>
                                          <div class="col-md-4">                     
                                            <textarea class="form-control" id="notetocoreviewer" name="notetocoreviewer"></textarea>
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label class="col-md-4 control-label" for="confirmfull"></label>
                                          <div class="col-md-8">
                                            <button id="confirmexpedited" name="assignfull" class="btn btn-primary">Assign</button>
                                            <a class="btn btn-default" href="sec_dashboard_action.php?fulldelete=1&id=<?php echo $id; ?>&u=<?php echo $userid; ?>" role="button">Cancel</a>
                                            <!--<button id="cancel" name="cancel" class="btn btn-default" onclick='goBack()'>Cancel</button>-->
                                          </div>
                                        </div>
                                <?php    }
                                ?>
                                
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