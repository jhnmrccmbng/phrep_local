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
      }</style>
  </head>
  <body>
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          
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
$where = array("username" => $mi['username']);
$getUserUpdate = $obj->getUser("phrepuser", $where);
    if($getUserUpdate){?>
          
          <div class="row">
              <div class="col-lg-12">
                  <span class="pull-right"><a href="#" data-href="researcher_action.php?d=1&p=<?php echo $_GET['id']; ?>&u=<?php echo $userid; ?>" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#confirm-withdraw">Withdraw</a></span>
              </div>
          </div>
              
          <div class="row">
              <div class="col-lg-6">
                  <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">    
                          
                              <div class="col-lg-12">
                                  
                                  
                                  
                                      <?php
                                      $getmaxmes = $obj->getmaxmessage($id);
                                      if($getmaxmes>=1){
                                          echo '<div class="panel panel-default">
                                                    <div class="panel-body">
                                                      <h1>Note for Incomplete</h1>';
                                          
                                          
                                          
                                        for($i=0;$i<=$getmaxmes;$i++){
                                          $where = array("subid" => $id, "times" => $i);
                                          $getmessage = $obj->fetch_record_with_where("message", $where);
                                          foreach($getmessage as $message){
                                              
                                              if($i == $getmaxmes){
                                                echo '<span class="label label-success">NEW</span>  '.$i.'.) '.$message['message'].'<br>'; 
                                                  
                                              }
                                              else{
                                                echo $i.'.) '.$message['message'].'<br>';                                                  
                                              }
                                              
                                          }

                                        }
                                        echo '</div>
                                            </div>'; 
                                      }
                                      ?>
                                  
                                  
                                  
                                  
                                  
                                  
                              <h1>Upload Files</h1>
                                  <div class="panel panel-default">
                                      <div class="panel-body">
                                        <div class="alert alert-warning">
                                            Step 1: <br>Select its <b>File Type</b>. Please upload the required documents.
                                        </div>
                                        <select id="doctype" name="doctype" class="form-control" required>
                                            <option value="" disabled selected>(Select file type to upload)</option>
                                            <?php 
                                            $myrow = $obj->fetch_record("document_type");
                                            foreach ($myrow as $row) {
                                                ?>  <?php 
//                                                        $doc = $row['docid'];
//                                                        if ($doc == 1){
                                                            //NONE
//                                                        }
//                                                        else{
                                                        $id1 = "inst_id"; $id2 = "id"; $id3 = "secretary"; $id4 = "id"; $id5 = "id"; $id6 = "memberId";
                                                        $myrow2 = $obj->fetch_record_for_doctype("combased", "rec_list", "phrepuser", "document_control", $id, $id1, $id2, $id3, $id4, $id5, $id6, $doc);
                                                        $count = count($myrow2);
                                                        if ($count > 0){?>
                                                            <option value="<?php echo $row['docid'].",".$row['forfilename']; ?>"><?php echo $row['doctype_desc']; ?>*</option><?php
                                                        }
                                                        else{?>
                                                            <option value="<?php echo $row['docid'].",".$row['forfilename']; ?>"><?php echo $row['doctype_desc']; ?></option><?php
                                                        }
//                                                        }


                                                    ?>
                                                <?php
                                            }
                                            ?>
                                        </select> <br>
                                        <div class="alert alert-warning">
                                            Step 2: <br>Only doc, docx, xls, xlsx, ppt, pptx, or pdf files are allowed to be uploaded. Once uploaded, click <strong>Submit</strong> button found below.
                                        </div>

                                            <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                            <?php $actual_link = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].""; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                                            <?php $mi = getMemberInfo(); ?>
                                            <input id="username" name="username" type="hidden" value="<?php echo $userid; ?>" placeholder="" class="form-control input-md">
                                        
                                            <input id="userid" name="userid" type="hidden" value="<?php echo $userid;?>">
                                        <span class="btn btn-default btn-file">
                                            <input type = "file" name = "addsupfile" required/>                        
                                        </span>
                                        <input type = "submit" class="btn btn-success"/>         
                                        
                                        <br><br>
                                        <div class="alert alert-danger">
                                            Step 3: <br> Don't forget to click "Resubmit" button to confirm your uploaded requirements.
                                        </div>                              
                            </div>
                            </div>
                                  
                              </div>

                      </form>
              </div>
              <div class="col-lg-6">
                  <h1>List of Files</h1>
                            <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">                             
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
                              
                          </form>
                  <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Files History</div>

                        <!-- Table -->
                        <table class="table">
                            
                            <tr>
                                <th>Document</th>
                                <th><center><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></center></th>
                            </tr>
                            
                            <?php 
                            $where = array("sub_id"=>$id);
                            $getfiles = $obj->fetch_record_with_where("document", $where);
                            if($getfiles > 0){
                                foreach($getfiles as $files){
                                    if(($files['kind'] == 'MP') || ($files['kind'] == 'SF')){
                                        
                                        if($files['finaldoc'] != '0'){
                                            echo '<tr class="success">
                                                <td>'.$files['file_name'].' ('.$files['doctypetimes'].')<br><small>'.$obj->ordinalize($files['revision']).' version | Uploaded last '.date("M j, Y",strtotime($files['date_uploaded'])).'</small></td>
                                                <td><center><a href="#" data-href="researcher_action.php?deletedoc=1&d='.$files['file_id'].'&id='.$id.'" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirm-deletedoc"><span data-toggle="tooltip" title="Delete?" class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a>&nbsp;| ';
                                            echo '<a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$files['file_name'].'&embedded=true" class="btn btn-primary btn-xs" role="button"><span data-toggle="tooltip" title="View" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>&nbsp;';
                                            #echo '<a class="btn btn-success" href="'.$files['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>';
                                            echo '</center></td>
                                            </tr>';                                        
                                        }
                                    }          
                                }
                            }
                            ?>
                            
                        </table>
                      </div>
                  
                  
              </div>
          </div>
        <br><br>
          <div class="row"><!--THIS IS THE BUTTON--><hr>
              <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">   
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
                        
                  <input id="fname" name="submid" type="hidden" value="<?php echo $id; ?>" placeholder="" class="form-control input-md">
                  <div class="col-lg-3"></div>
                  <div class="col-lg-6"><center> 
                          <div class="alert alert-danger" role="alert">WARNING! Please Review all your uploaded file before resubmitting.</div>
                          <button type="submit" name="submitincomplete" class="btn btn-info">Resubmit</button>
                          <button type="button" class="btn btn-dedfault" onclick='goBack()'>Cancel</button>
                  </div>
                  <div class="col-lg-3"></div>
            </form>
              </div><!--THIS IS THE BUTTON-->
              
              
              
</div>
<?php } else{ echo '<br><br><br><br><br><br><br><div class="row">
              <div class="col-lg-4"></div>
              <div class="col-lg-4"><div class="alert alert-danger" role="alert"><center>Please update your profile to get started.<br><br><a class="btn btn-success" href="update_profile.php" role="button">Update Profile!</a></center></div></div>
              <div class="col-lg-4"></div>
          </div> '; }?>
        

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
</script>

<script>
function goBack() {
    window.history.back();
}
</script>

<div class="modal fade" id="confirm-withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to withdraw this proposal? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-withdraw').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>


<div class="modal fade" id="confirm-deletedoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this document file? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-deletedoc').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>