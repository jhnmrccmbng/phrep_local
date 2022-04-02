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
            <h1>Proposal's Files</h1>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <?php $id = (int) $_GET['id'];?>
                    <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="sub_id">
                    <div class="col-lg-12">
                    
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Amended Files</h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-condensed">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th>DATE AMENDED</th>
                                    <th>DATE UPLOADED</th>
                                    <th><center>ACTION</center></th>
                                </tr>
                                
                                <?php
                                $myrow = $obj->showingUploadedFiles("document", "document_type", "doctype", "docid", $id);
                                echo '<tr class="success"><td colspan="4">FOR AMENDMENT</td></tr>';
                                if($myrow>0){
                                    foreach($myrow as $row){
                                        if(($row[finaldoc]=='1')&&($row[amend]=='1')&&($row[finalamend]=='1')){
                                            echo '<tr>';
                                            echo '<td>' . $row[doctype_desc] . '</td>';
                                            echo '<td>';
                                            echo date("F j, Y", strtotime($row["date_modified"]));
                                            echo '</td>';
                                            echo'<td>';
                                            ?>
                                            <?php $d = strtotime($row["date_uploaded"]);
                                            echo date("F j, Y", $d); ?><?php
                                            echo'</td><td><center>
                                                <a target = "_blank" href="https://docs.google.com/gview?url=www.bocasystems.com/documents/fgl46.doc&embedded=true">
                                                <button data-toggle="tooltip" title="VIEW" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button></a> | 
                                                <button data-toggle="tooltip" title="DOWNLOAD" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></button>
                                                </center></td></tr>';
                                        }
                                    }
                                }
                                echo '<tr><td colspan="4"></td></tr>';
                                echo '<tr><td colspan="4"><center><b>FILES HISTORY</b></center></td></tr>';
                                ?>
                                
                                <?php 
                                
                                $getmaxrevision = $obj->getMaxRevision($id);
//                                echo $getmaxrevision;
                                
                                for($i = 1; $i <= $getmaxrevision; $i++){
                                    $id = $_GET['id'];
                                    $myrow = $obj->showingUploadedFilesforAmendment("document", "document_type", "doctype", "docid", $id, $i);
                                        echo '<tr class="success"><td colspan="4">'.$getnum = $obj->ordinalize($i).' Revision</td></tr>';
                                        if ($myrow > 0) {
                                            foreach ($myrow as $row) {
                                                
                                                if(($row[finaldoc]!='1')&&($row[amend]!='1')&&($row[finalamend]!='1')){
                                                    
                                                    if($row[finaldoc]=='1'){
                                                        echo '<tr>';
                                                        echo '<td>' . $row[doctype_desc] . '<span class="badge">Final Doc</span></td>';
                                                        echo '<td>';
                                                        echo date("F j, Y", strtotime($row["date_modified"]));
                                                        echo '</td>';

                                                        echo'<td>';
                                                        ?>
                                                        <?php $d = strtotime($row["date_uploaded"]);
                                                        echo date("F j, Y", $d); ?><?php
                                                        echo'</td><td><center>
                                                            <a target = "_blank" href="https://docs.google.com/gview?url=www.bocasystems.com/documents/fgl46.doc&embedded=true">
                                                            <button data-toggle="tooltip" title="VIEW" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button></a> | 
                                                            <button data-toggle="tooltip" title="DOWNLOAD" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></button>
                                                            </center></td></tr>';
                                                    }
                                                    else{
                                                        echo '<tr>';
                                                        echo '<td>' . $row[doctype_desc] . '</td>';
                                                        echo '<td>';
                                                        echo date("F j, Y", strtotime($row["date_modified"]));
                                                        echo '</td>';

                                                        echo'<td>';
                                                        ?>
                                                        <?php $d = strtotime($row["date_uploaded"]);
                                                        echo date("F j, Y", $d); ?><?php
                                                        echo'</td><td><center>
                                                            <a target = "_blank" href="https://docs.google.com/gview?url=www.bocasystems.com/documents/fgl46.doc&embedded=true">
                                                            <button data-toggle="tooltip" title="VIEW" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button></a> | 
                                                            <button data-toggle="tooltip" title="DOWNLOAD" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></button>
                                                            </center></td></tr>';
                                                        
                                                    }
                                                }
                                        }
                                    }
                                    
                                    else{
                                        echo '<tr><td colspan="4"><i><center>No supplementary files have been added to this submission.</center></i></td></tr>';
                                    }
                                    
                                    echo '<tr><td colspan="4"></td></tr>';
                                }
                                ?>
                            </table>
                            </div>
                          </div>
                        
                    </div>
                    <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusaction" type="hidden" value="3" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusdate" type="hidden" value="<?php 
                        date_default_timezone_set('Asia/Manila');
                        $datetime = date("Y-m-d H:i:s",strtotime("now")); echo $datetime;?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statususername" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                    
                     <?php
                     $maxrev = $obj->getmaxreviewer($id);
                     $allRev = $obj->getAllReviewer($id, $maxrev);
                     $nextmax = $maxrev + 1;
                     if($allRev > 0){
                         foreach($allRev as $revs){
                             echo '<input id="subid" name="subids[]" type="hidden" value="'.$id.'">';
                             echo '<input id="review" name="review[]" type="hidden" value="'.$nextmax.'">';
                             echo '<input id="puserid" name="puserid[]" type="hidden" value="'.$revs[phrepuser_id].'">';
                             echo '<input id="primerev" name="primerev[]" type="hidden" value="'.$revs[primary_reviewer].'">';  
                             echo '<input id="confirm" name="confirm[]" type="hidden" value="'.$revs[confirmation].'">';                            
                         }
                     }
                     ?>
                     <?php 
                        $maxrev = $obj->getmaxreviewer($id);
                        $maxp = $maxrev + 1;
                        $getChairman = $obj->pullChairman($id, $userid, '1');
                        if($getChairman>0){
                            foreach($getChairman as $cm){
                                echo '<input id="subidss" name="subidss" type="hidden" value="'.$id.'">'; 
                                echo '<input id="reviews" name="reviews" type="hidden" value="'.$maxp.'">'; 
                                echo '<input id="puserids" name="puserids" type="hidden" value="'.$cm[phrepuser_id].'">'; 
                            }
                        }
                     ?>
                     
                     
                    <?php 
                    
                    
                    $getmax = $obj->getMax($id);
                    if(count($getmax)>0){
                        foreach($getmax as $maxid){
                            $where = array("sub_id"=>$id, "id"=>$maxid[maxid]);
                            $myrow = $obj->fetch_record_with_where("proposal_status", $where);
                            $num = count($myrow);
                               if($num>0){    
                                   foreach($myrow as $row){
                                       if (($row[status_action]=='7')||($row[status_action]=='8')||($row[status_action]=='9')){                          
                                            echo '<div class="row">
                                                        <div class="col-lg-4"></div>
                                                        <div class="col-lg-4"><center>           
                                                            <button type="submit" name="complete" class="btn btn-success">Complete</button>
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
                                                   <button type="submit" name="complete" class="btn btn-success">Complete</button>
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