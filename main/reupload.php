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
      }.tr_link{cursor:pointer}</style>
  </head>
  <body>
      <div class="container-fluid"><?php $subid = (int) $_GET['id'];?>
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
                  <span class="pull-right"><a href="#" data-href="researcher_action.php?d=1&p=<?php echo $subid;?>&u=<?php echo $userid; ?>" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#confirm-withdraw">Withdraw</a></span>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-2"></div>
              <div class="col-lg-10">
                  
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-6"><h2>Resubmission</h2>
                  <div class="row">
                      <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Files History</div>

                        <!-- Table -->
                        <table class="table">
                            
                            <tr>
                                <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                <th>VER</th>
                                <th>Type</th>
                                <th>Date Uploaded</th>
                            </tr>
                            
                            <?php 
                            $where = array("sub_id"=>$subid);
                            $getfiles = $obj->fetch_record_with_where("document", $where);
                            if($getfiles > 0){
                                foreach($getfiles as $files){
                                    if(($files['kind'] == 'MP') || ($files['kind'] == 'SF')){
                                        
                                        
                                        if($files['finaldoc'] == '1'){
                                            echo '<tr class="success">
                                                <td><span class="glyphicon glyphicon-ok" aria-hidden="true"></td>';
                                            echo '<td>'.$files['revision'].'</td>';
                                            
                                            if($files['newsubmit'] == '1'){
                                                echo '<td>'.$files['orig_filename'].' ('.$files['doctypetimes'].') <span class="badge">New</span></td>';
                                            }
                                            else {echo '<td>'.$files['orig_filename'].' ('.$files['doctypetimes'].')</td>';}
                                            
                                            echo'<td>'.date("M j, Y",strtotime($files['date_uploaded'])).'</td>
                                            </tr>';                                            
                                        }
                                        
//                                        if($files['finaldoc'] == '0'){
//                                            echo '<tr class="warning">
//                                                <td><span class="glyphicon glyphicon-repeat" aria-hidden="true"></td>
//                                                <td>'.$files['revision'].'</td>
//                                                <td>'.$files['file_name'].' ('.$files['doctypetimes'].')</td>
//                                                <td>'.date("M j, Y",strtotime($files['date_uploaded'])).'</td>
//                                            </tr>';                                            
//                                        }
//                                        else{
//                                            echo '<tr class="success">
//                                                <td><span class="glyphicon glyphicon-ok" aria-hidden="true"></td>
//                                                <td>'.$files['revision'].'</td>
//                                                <td>'.$files['file_name'].' ('.$files['doctypetimes'].')</td>
//                                                <td>'.date("M j, Y",strtotime($files['date_uploaded'])).'</td>
//                                            </tr>';  
//                                            
//                                        }
                                        
                                        
                                    }          
                                }
                            }
                            ?>
                            
                        </table>
                      </div>
                  </div>
                  
              </div>
              
              <div class="col-lg-6">
                  <h2>Decision Letter from Secretariat.</h2>
                  <div class="panel panel-default">
                    <div class="panel-body">
                        
                        <?php 
                        $getrl = $obj->getmaxrl($subid);
                        $where = array("sub_id" => $subid, "kind" => "RL", "finaldoc" => "1");
                        $getrlfile = $obj->fetch_record_with_where("document", $where);
                        foreach($getrlfile as $rl){
                            echo '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> '.$rl['file_name'];
                            echo '<a href = "uploads/main/'.$rl['orig_filename'].'" class="pull-right">Download</a>';
                        }
                        ?>
                        
                    </div>
                  </div>
                  
                  
                  <h2>Upload the file</h2>
                  <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">  
                        <div class="row">    
                              <div class="col-lg-12">
                                  <?php
                                  $where = array("username" => $mi['username']);
                                  $getUserID = $obj->getUser("phrepuser", $where);
                                  if ($getUserID) {
                                      foreach ($getUserID as $user) {
                                          echo '<input type="hidden" class="form-control" value="' . $user['id'] . '" name="userid">';
                                          $userid = $user['id'];
                                      }
                                  }
                                  ?>
                                  <div class="col-lg-12 alert alert-info" role="alert">Step 1: <br>Select its <b>File Type</b>. Please upload the 
                                      required documents.</div>
                                  <select id="doctype" name="doctype" class="form-control" required="required">
                                      <option value="" disabled selected>(Select file type to upload)</option>
                                      
                                    <?php 
                                    $getdocs = $obj->grouptwo($subid);
                                    foreach($getdocs as $dc){                                        
                                        $getdoctoresubmit = $obj->getdoctoresubmit($subid, $dc['doctype'], $dc['doctypetimes']);
                                        
                                        if($getdoctoresubmit){
                                            
                                            foreach($getdoctoresubmit as $ds){

                                                echo "<option value='" . $ds['docid'] . "," . $ds['forfilename'] . "," . $ds['revision'] . "," . $ds['doctypetimes'] . "'>" . $ds['doctype_desc'] . " (".$ds['doctypetimes'].")</option>";
                                            }                                          
                                        }
                                    }
                                    ?>      
                                  </select>
                                  
                              </div>    
                          </div><br>
                          <div class="row">    
                              <div class="col-lg-12">           
                                  <fieldset> 
                                      <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                                      <input id="kind" name="kind" type="hidden" value="MP" placeholder="" class="form-control input-md">
                                        <?php $actual_link = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                                      <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                                        <?php $mi = getMemberInfo(); ?>
                                      <input id="username" name="username" type="hidden" value="<?php echo $mi['username']; ?>" placeholder="" class="form-control input-md">
                                  </fieldset>  
                                  
                                  <div class="alert alert-info" role="alert">Step 2: <br>Only doc, docx, xls, xlsx, ppt, pptx, or pdf 
                                      files are allowed to be uploaded.</div>
                                  <span class="btn btn-default btn-file">
                                      <input type = "file" name = "resubmit" accept="application/pdf"/>                        
                                  </span>
                                  <input type = "submit" class="btn btn-success"/>
                              </div>


                          </div>
                          
                          <hr>
                        </form>    
                        <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">    
                          
                          <input type="button" class="btn btn-primary" onclick="showAddFiles()" value="(OPTIONAL) Add Supplementary Files">
                          <div class="row" id="addfiles" style="display: none">
                              <div class="col-lg-12">
                              <h3>Additional Files (OPTIONAL)</h3>
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
                                                        $doc = $row['docid'];
                                                        if ($doc == 1){
                                                            //NONE
                                                        }
                                                        else{
                                                        $id1 = "inst_id"; $id2 = "id"; $id3 = "secretary"; $id4 = "id"; $id5 = "id"; $id6 = "memberId";
                                                        $myrow2 = $obj->fetch_record_for_doctype("combased", "rec_list", "phrepuser", "document_control", $id, $id1, $id2, $id3, $id4, $id5, $id6, $doc);
                                                        $count = count($myrow2);
                                                        if ($count > 0){?>
                                                            <option value="<?php echo $row['docid'].",".$row['forfilename']; ?>"><?php echo $row['doctype_desc']; ?>*</option><?php
                                                        }
                                                        else{
                                                            if($row['docid'] != '16'){ ?>
                                                            <option value="<?php echo $row['docid'].",".$row['forfilename']; ?>"><?php echo $row['doctype_desc']; ?></option><?php
                                                            }
                                                        }
                                                        }


                                                    ?>
                                                <?php
                                            }
                                            ?>
                                        </select> <br>
                                        <div class="alert alert-warning">
                                            Step 2: <br>Only doc, docx, xls, xlsx, ppt, pptx, or pdf files are allowed to be uploaded.
                                        </div>

                                            <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                            <?php $actual_link = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                                            <?php $mi = getMemberInfo(); ?>
                                            <input id="username" name="username" type="hidden" value="<?php echo $userid; ?>" placeholder="" class="form-control input-md">
                                        
                                            <input id="userid" name="userid" type="hidden" value="<?php echo $userid;?>">
                                        <span class="btn btn-default btn-file">
                                            <input type = "file" name = "addsupfile" required/>                        
                                        </span>
                                        <input type = "submit" class="btn btn-success"/>
                                      </div>
                                  </div>
                                  
                              </div>
                          </div>

                      </form>
                      <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal"> 
                          
                        <?php
                        $getmax = $obj->getmaxstatus($subid);
                        if($getmax>0){
                            foreach($getmax as $sa){
                                $getstataction = $obj->getstat($subid, $sa['sa']);
                                if($getstataction>0){
                                    foreach($getstataction as $stat){
                                        if ($stat['status_action'] != '3'){
                                
                                 ?>
                                    
                                    <div class="row"><!--THIS IS THE BUTTON--><hr>
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
                                        <input id="submid" name="submid" type="hidden" value="<?php echo $subid;?>">
                                          <div class="col-lg-2"></div>   
                                          
                                          <?php
                                            if($ds['docid']){$d = "disabled";} else{$d = '';}
                                          ?>
                                          
                                          <div class="col-lg-8"><center><small>*Once clicked "Done Resubmit", it cannot be undone.</small>           
                                              <button type="submit" name="resubmitfiles" class="btn btn-info" <?php echo $d;?>>Done Resubmit</button>
                                              <button type="button" class="btn btn-danger">Cancel</button></center>
                                          </div>
                                          
                                          
                                          <div class="col-lg-2"></div>              
                                    </div>
                                
                                <?php }
                                else{echo '<br><div class="alert alert-danger" role="alert">You have already submitted what was required.</div>';}
                                    }
                                }
                            }
                        }
                    ?>
                          </form>
                                    
                </div>
              </div>
              
              
          </div>
<?php } else{ echo '<br><br><br><br><br><br><br><div class="row">
              <div class="col-lg-4"></div>
              <div class="col-lg-4"><div class="alert alert-danger" role="alert"><center>Please update your profile to get started.<br><br><a class="btn btn-success" href="update_profile.php" role="button">Update Profile!</a></center></div></div>
              <div class="col-lg-4"></div>
          </div> '; }?>
        
          
          
          
          
          
          
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
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});


$('tr['data-href']').on("click", function() {
    document.location = $(this).data('href');
});
</script>

<script>
function showAddFiles() {
    var x = document.getElementById("addfiles");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
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