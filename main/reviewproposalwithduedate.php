<?php
include "sec_dashboard_action.php";
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
          <form class="form-horizontal" role="form" action="sec_dashboard_action.php" method="POST">
        <!--<form class="form-horizontal" role="form" action="submission_action.php" method="POST">-->  

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
               <?php $id = (int) $_GET['id'];?>
              <?php 
              $where = array("sub_id" => $id);
              $getproposalinfo = $obj->fetch_record_with_where("proposal", $where);
              if($getproposalinfo){
                  foreach($getproposalinfo as $pi){
                      echo '<h1>'.$pi["prop_ptitle"].'<br><small>'.$pi["code"].'</small></h1>';
                  }
              }
              ?>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="sub_id">
                    <div class="col-lg-12">
                    
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Attachments</h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-striped">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th>ORIGINAL FILE NAME</th>
                                    <th>DATE UPLOADED</th>
                                    <th><center>ACTION</center></th>
                                </tr>

                                <?php 
                                $id = $_GET['id'];
                                $files = $obj->getfilessubmitted($id);
                                
                                    if($files){
                                    foreach ($files as $row) {
                                        $idd = $row['doctype'];
                                        if ($idd == '1'){
                                                echo '<tr class="success">
                                                    <td>
                                                        '.$row[doctype_desc].' ('.$row[doctypetimes].')<br><small>'.$obj->ordinalize($row[revision]).' revision</small>
                                                    </td>
                                                    <td>
                                                        '.$row[orig_filename].' <span class="badge"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span></span>
                                                    </td>';?>
                                                        
                                                    <?php echo'<td>';?>
                                                    <?php $d = strtotime($row["date_uploaded"]);   echo date("M j, Y",$d); ?><?php
                                                echo'</td>
                                                    <td>
                                                    <input name="dlfile" type="hidden" value="'.$row["file_name"].'">
                                                    <button class="btn btn-success" type="submit" name="download">Download</button>
                                                    <a target = "_blank" href="https://docs.google.com/gview?url='.$currDir.'/main/uploads/main/'.$row["file_name"].'&embedded=true">
                                                        <button type="button" class="btn btn-primary">View</button></a> 
                                                    </td>
                                                </tr>';

                                        }
                                        else{
                                        $myrow3 = $obj->checkingUploadFiles("combased", "document_control", "inst_id", "erc_id", $idd, $id);
                                        $naa = count($myrow3);
                                            if ($naa > 0){
                                                echo '<tr class="danger">
                                                    <td>
                                                        '.$row[doctype_desc].' ('.$row[doctypetimes].')<br><small>'.$obj->ordinalize($row[revision]).' submission</small>
                                                    </td>
                                                    <td>
                                                        '.$row[orig_filename].' <span class="badge"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span></span>
                                                    </td>
                                                    <td>';?>
                                                        <?php $d = strtotime($row["date_uploaded"]);   echo date("M j, Y",$d); ?><?php
                                                echo'</td>
                                                    <td>
                                                    <input name="dlfile" type="hidden" value="'.$row["file_name"].'">
                                                    <button class="btn btn-success" type="submit" name="download">Download</button>
                                                    <a target = "_blank" href="https://docs.google.com/gview?url='.$currDir.'/main/uploads/main/'.$row["file_name"].'&embedded=true">
                                                        <button type="button" class="btn btn-primary">View</button></a> 
                                                    </td>
                                                </tr>';
                                            }
                                            else{

                                                echo '<tr>
                                                    <td>
                                                        '.$row[doctype_desc].' ('.$row[doctypetimes].')<br><small>'.$obj->ordinalize($row[revision]).' submission</small>
                                                    </td>
                                                    <td>
                                                        '.$row[orig_filename].'
                                                    </td>';?>
                                                        
                                                    <?php echo'<td>';?>
                                                        <?php $d = strtotime($row["date_uploaded"]);   echo date("M j, Y",$d); ?><?php
                                                echo'</td>
                                                    <td>
                                                    <input name="dlfile" type="hidden" value="'.$row["file_name"].'">
                                                    <button class="btn btn-success" type="submit" name="download">Download</button>
                                                    <a target = "_blank" href="https://docs.google.com/gview?url='.$currDir.'/main/uploads/main/'.$row["file_name"].'&embedded=true">
                                                        <button type="button" class="btn btn-primary">View</button></a> 
                                                    </td>
                                                </tr>';
                                            }
                                        }

                                    }
                                    }
                                    else{
                                        echo '<tr><td colspan="4"><i><center>No supplementary files have been added to this submission.</center></i></td></tr>';
                                    }
                                ?>
                            </table>
                            </div>
                          </div>
                        
                     <hr>
                     <h4>Assign New Due Date of Review:</h4>
                     <div class="row">
                         <div class="col-lg-3">
                             <div id="sandbox-container">
                                 <input type="text" class="form-control" name="newddate" readonly required>
                             </div><br>
                         </div>
                     </div> 
                     
                    </div>
                    <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusaction" type="hidden" value="1" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusdate" type="hidden" value="<?php 
                        date_default_timezone_set('Asia/Manila');
                        $datetime = date("Y-m-d H:i:s",strtotime("now")); echo $datetime;?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statususername" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                    
                        
                         
                         
                         <?php 
                    
                    
                    $getmax = $obj->getMax($id);
                    if(count($getmax)>0){
                        foreach($getmax as $maxid){
                            $where = array("sub_id"=>$id, "id"=>$maxid[maxid]);
                            $myrow = $obj->fetch_record_with_where("proposal_status", $where);
                            $num = count($myrow);
                               if($num>0){    
                                   foreach($myrow as $row){
                                       if ($row[status_action]=='0'){                          
                                            echo '<div class="row">
                                                        <div class="col-lg-4"></div>
                                                        <div class="col-lg-4"><center>           
                                                            <button type="submit" name="completeresubmitted" class="btn btn-success">Complete</button>
                                                            <button type="submit" name="incomplete" class="btn btn-danger">Incomplete</button>
                                                            <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button></center>
                                                        </div>
                                                        <div class="col-lg-4"></div>              
                                                  </div> ';                                             
                                       }
                                       else if($row['status_action'] == '14'){                       
                                            echo '<div class="row">
                                                        <div class="col-lg-4"></div>
                                                        <div class="col-lg-4"><center>           
                                                            <button type="submit" name="completeresubmitted" class="btn btn-success">Complete</button>
                                                            <button type="submit" name="incomplete" class="btn btn-danger">Incomplete</button>
                                                            <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button></center>
                                                        </div>
                                                        <div class="col-lg-4"></div>              
                                                  </div> ';                                             
                                       }
                                       else{
                                            echo '<div class="row">
                                                        <div class="col-lg-12">
                                                                <div class="alert alert-danger">
                                                                    <strong>Sorry!</strong> This proposal has been tagged as complete already.
                                                                </div>
                                                        </div>            
                                                  </div> ';              
                                           
                                       }
                                   }    
                               }
                               else{                           
                                   echo '<div class="row">
                                               <div class="col-lg-4"></div>
                                               <div class="col-lg-4"><center>           
                                                   <button type="submit" name="completeresubmitted" class="btn btn-success">Complete</button>
                                                   <button type="submit" name="incomplete" class="btn btn-danger">Incomplete</button>
                                                   <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button></center>
                                               </div>
                                               <div class="col-lg-4"></div>              
                                         </div> ';                                  
                               }
                               
                        }
                    }
                    
                               
                    
                    ?> 
                     
                      
                  
                  
                </div>
            </div>
          </div>
          </form> 
          </div><!--THIS IS THE FORM AREA-->
        

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
function goBack() {
    window.history.back();
}
</script>



<!--DATE PICKER-->
<script>
    $(document).ready(function(){ 
        $('#sandbox-container input').datepicker({
            orientation: "top right",
            autoclose: true,
            todayHighlight: true
        });
    });
    
</script>
<!--DATE PICKER-->