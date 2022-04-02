<?php
include "rev_dashboard_action.php";
date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));

?>
<?php
	$currDir = dirname(__FILE__);
        $currDir = substr($currDir, 0, -5);
        //echo $currDir;
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	
	/* grant access to the groups 'Admins' and 'Data entry' */
	$mi = getMemberInfo();
	if(!in_array($mi['group'], array('Admins', 'Reviewer'))){
            header("location: ../index.php?signIn=1");
//		exit;
	}

       
include_once("$currDir/header.php");
?>
<!DOCTYPE html>
<html lang="en"><?php $id = (int) $_GET['id'];?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Franklin" rel="stylesheet">
    <link rel="stylesheet" href="../resources/select2/select2.css">

    <style>
      body {
        font-family: 'Libre Franklin', sans-serif;
      }
    .doc {
    width: 100%;
    height: 500px;
}
    </style>
  </head>
  <body>
      <div class="container-fluid">   
                            
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
         
        <div class="row"><!--THIS IS THE FORM AREA-->
            <?php 
            $maxrev = $obj->getmaxreviewer($id);
            $gettingInfo = $obj->exclusiveForAssignedProposal($userid, $id, $maxrev);
            if($gettingInfo){
                foreach($gettingInfo as $info){                    
                    echo '<div class="row">
                    <div class="col-lg-6"><h1>Proposal Information<small><br>'.$info[prop_ptitle].'</small></h1></div>
                    <div class="col-lg-6">
                        <h3><small>
                                <p class="text-right">
                                    Submitted last ';?><?php $d = strtotime($info[date_submitted]);   echo date("F j, Y",$d); ?> <?php echo'<br><br>'.$info[rt_name].'<br>
                                </p>
                        </small></h3>            
                    </div>
                    </div>';
                }
            }
            
            ?>
            
            <hr>
            <div class="row">
                <div class = "col-lg-3">
                    <div class="panel panel-default">
                    <div class="panel-heading"><b>Details</b></div>
                        <?php 
                        $maxrev = $obj->getmaxreviewer($id);
                        $gettingInfo = $obj->exclusiveForAssignedProposal($userid, $id,$maxrev);
                        if($gettingInfo){
                            foreach($gettingInfo as $info){  
                                echo '<div class="list-group">
                                    <a href="#" class="list-group-item" data-toggle="tooltip" title="PROPOSAL CODE"><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span> '.$info[code].'</a>
                                    <a href="#" class="list-group-item" data-toggle="tooltip" title="PRIMARY INVESTIGATOR"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> ';
                                    $where = array("id"=>$info['pi']);    
                                    $gettingpi = $obj->fetch_record_with_where("phrepuser", $where);
                                    if($gettingpi){
                                        foreach($gettingpi as $pi){
                                            echo $pi[fname].' '.$pi[mname].' '.$pi[lname];
                                        }
                                    }
                                echo '</a>
                                    <a href="#" class="list-group-item" data-toggle="tooltip" title="REVIEW DEADLINE"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>';?><?php $d = strtotime($info[rt_duedate]);   echo ' '.date("F j, Y",$d); ?> <?php echo'</a>
                                    <a class="btn btn-success list-group-item" href="'.PREPEND_PATH.'rev_proposalview.php?id='.$info[sub_id].'" role="button" data-toggle="tooltip" title="SEE MORE DETAILS">View More</a>
                                </div>';                                   
                            }
                        }

                        ?>
                    </div>
                </div>
                <div class="col-lg-9"><h2>Upload Evaluation Form</h2> <hr>
                    
                        <div class="row">
                        <form action = "rev_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">                             
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
                          
                            <div class="col-lg-12">          
                                  <fieldset> 
                                     <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                          <input id="kind" name="kind" type="hidden" value="EF" placeholder="" class="form-control input-md">
                                          <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                                          <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                          <?php $mi = getMemberInfo(); ?>
                                          <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                                          <input id="doctype" name="doctype" type="hidden" value="14" placeholder="" class="form-control input-md">
                                  </fieldset>     
                                <?php 
                                    $maxEF = $obj->getmaxreviewer($id);
                                    echo '<input name="maxef" type="hidden" value="'.$maxEF.'">';
                                ?>
                              
                              <span class="btn btn-default btn-file">
                              <input type = "file" name = "image" required/>                        
                              </span>
                              <input type = "submit" class="btn btn-success"/>
                          </div>
                      </form>
                      </div>
                        
                        <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-striped">
                                <tr>
                                    <th>TITLE</th>
                                    <th>ORIGINAL FILE NAME</th>
                                    <th>DATE UPLOADED</th>
                                    <th>ACTION</th>
                                </tr>
                        
                            <?php 
                            $id = $_GET['id'];
                            $myrow = $obj->showingUploadedFiless("document", "document_type", "doctype", "docid", $id, $userid);
                            $num = count($myrow);
                                if($num>0){
                                foreach ($myrow as $row) {
                                    if($row[kind]=='EF'){
                                            echo '<tr>
                                                <td>
                                                    '.$row[doctype_desc].'
                                                </td>
                                                <td>
                                                    '.$row[orig_filename].'
                                                </td>
                                                <td>';?>
                                                    <?php $d = strtotime($row["date_uploaded"]);   echo date("F j, Y",$d); ?><?php
                                            echo'</td>
                                                <td>
                                                    <a href="upload_file.php?delete=1&id='.$row[file_id].'&subid=';?><?php echo $_GET['id']; ?><?php echo'" class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>';
                                        
                                    }
                                    
                                }
                                }
                                else{
                                    echo '<tr><td colspan="4"><i><center>No Evaluation Form has been submitted.</center></i></td></tr>';
                                }
                            ?>
                        </table>
                    </div>                
                </div>
                        
                    <form action = "rev_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal"> 
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
                            $maxEF = $obj->getmaxEFdoc($id, '14');
                            echo '<input name="maxef" type="hidden" value="'.$maxEF.'">';
                        ?>
                    <div class="row">
                        <div class="col-lg-6">
                                <h2>Decision</h2>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select id="decision" name="decision" class="form-control" required>
                                        <option value="" disabled selected>Select your decision</option>
                                        <?php 
                                        $getdecsion = $obj->fetch_record("rev_decision");
                                        if ($getdecsion){
                                            foreach($getdecsion as $dec){
                                                echo '<option value="'.$dec[id].'">'.$dec[dec_desc].'</option>';
                                            }
                                        }
                                        ?>
                                  </select>
                                </div>
                              </div>
                        </div>    
                        
                        
                    <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusaction" type="hidden" value="1" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusdate" type="hidden" value="<?php 
                        date_default_timezone_set('Asia/Manila');
                        $datetime = date("Y-m-d H:i:s",strtotime("now")); echo $datetime;?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statususername" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                    </div>
                    
                    <div class="alert alert-danger" role="alert">WARNING! Once clicked "Submit Evaluation", it cannot be undone. Please double check.</div>
                        <?php 
                            echo '<div class="row">
                                        <div class="col-lg-2"></div>
                                            <div class="col-lg-8"><center>                                                   
                                                <button type="submit" class="btn btn-success" name="passevaluation">Submit Evaluation</button>
                                                <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button></center>
                                            </div>
                                        <div class="col-lg-2"></div>              
                                    </div> <br>';    
                             ?>
                    
                    </form><br>
                    
                    
                </div>
            </div>
          </div>
        
          </div><!--THIS IS THE FORM AREA-->
        

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
   
  </body>
  
</html>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
<script>
function goBack() {
    window.history.back();
}
</script>