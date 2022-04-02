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
                            echo '<input type="hidden" class="form-control" value="'.$user[id].'" name="userid">';
                            $userid = $user[id];
                        }
                    }
          ?>
              
<?php
$where = array("username" => $mi['username']);
$getUserUpdate = $obj->getUser("phrepuser", $where);
    if($getUserUpdate){?>
          
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
//                            $getmaxecdoc = $obj->getmaxecdocrev($subid);
                            $where = array("sub_id"=>$subid, "post_request_times" => $_GET["idt"]);
                            $getfiles = $obj->fetch_record_with_where("document", $where);
                            if($getfiles > 0){
                                foreach($getfiles as $files){
                                        
                                        if($files[post_finaldoc] == '0'){
                                            echo '<tr class="warning">
                                                <td><span class="glyphicon glyphicon-repeat" aria-hidden="true"></td>
                                                <td>'.$files[post_revision].'</td>
                                                <td>'.$files[file_name].'</td>
                                                <td>'.date("F j, Y",strtotime($files["date_uploaded"])).'</td>
                                            </tr>';                                            
                                        }
                                        else{
                                            echo '<tr class="success">
                                                <td><span class="glyphicon glyphicon-ok" aria-hidden="true"></td>
                                                <td>'.$files[post_revision].'</td>
                                                <td>'.$files[file_name].'</td>
                                                <td>'.date("F j, Y",strtotime($files["date_uploaded"])).'</td>
                                            </tr>';  
                                            
                                        }
                                        
                                }
                            }
                            ?>
                            
                        </table>
                      </div>
                  </div>
                  
              </div>
              
              <div class="col-lg-6"><h2>Upload the file</h2>
                  <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">  
                        <div class="row">    
                              <div class="col-lg-12">
                                  <?php
                                  $where = array("username" => $mi['username']);
                                  $getUserID = $obj->getUser("phrepuser", $where);
                                  if ($getUserID) {
                                      foreach ($getUserID as $user) {
                                          echo '<input type="hidden" class="form-control" value="' . $user[id] . '" name="userid">';
                                          $userid = $user[id];
                                      }
                                  }
                                  ?>
                                  <div class="col-lg-12 alert alert-info" role="alert">Step 1: <br>Select its <b>File Type</b>. Please upload the 
                                      required documents.</div>
                                  <select id="doctype" name="doctype" class="form-control" required="required">
                                      <option value="" disabled selected>(Select file type to upload)</option>
                                      <?php
                                      $getMaxrevision = $obj->getMaxRevisepost($subid);
                                      if ($getMaxrevision > 0) {
                                          foreach ($getMaxrevision as $maxrev) {

                                              $getfilerev = $obj->getReviseFilepost($subid, $maxrev[maxrev]);
                                              if ($getfilerev > 0) {
                                                  foreach ($getfilerev as $revfile) {
                                                      echo "<option value='" . $revfile[docid] . "," . $revfile[forfilename] . "," . $revfile[post_revision] . "," . $revfile[amend] . "'>" . $revfile[doctype_desc] . "</option>";
                                                  }
                                              } else {
                                                  echo "<option value='" . $revfile[docid] . "'>NONE</option>";
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
                                      <?php $rq = $obj->getmaxtimes($subid); echo '<input id="rqtimes" name="rqtimes" type="hidden" value="'.$rq.'">'; ?>
                                      
                                      <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                                      <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                        <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                                      <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                                        <?php $mi = getMemberInfo(); ?>
                                      <input id="username" name="username" type="hidden" value="<?php echo $mi['username']; ?>" placeholder="" class="form-control input-md">
                                  </fieldset>  
                                  
                                  <div class="alert alert-info" role="alert">Step 2: <br>Only doc, docx, xls, xlsx, ppt, pptx, or pdf 
                                      files are allowed to be uploaded.</div>
                                  <span class="btn btn-default btn-file">
                                      <input type = "file" name = "resubmitpost" accept="application/pdf"/>                        
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
                                                    if(($row['docid']=='15')||($row['docid']=='17')||($row['docid']=='19')||($row['docid']=='21')){
                                                        
                                                    }
                                                    else{
                                                        $doc = $row['docid'];
                                                        if ($doc == 1){
                                                            //NONE
                                                        }
                                                        else{
                                                        $id1 = "inst_id"; $id2 = "id"; $id3 = "secretary"; $id4 = "id"; $id5 = "id"; $id6 = "memberId";
                                                        $myrow2 = $obj->fetch_record_for_doctype("combased", "rec_list", "phrepuser", "document_control", $id, $id1, $id2, $id3, $id4, $id5, $id6, $doc);
                                                        $count = count($myrow2);
                                                        if ($count > 0){?>
                                                            <option value="<?php echo $row["docid"].",".$row["forfilename"]; ?>"><?php echo $row["doctype_desc"]; ?>*</option><?php
                                                        }
                                                        else{?>
                                                            <option value="<?php echo $row["docid"].",".$row["forfilename"]; ?>"><?php echo $row["doctype_desc"]; ?></option><?php
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
                                                <?php
                                                $where = array("username" => $mi['username']);
                                                $getUserID = $obj->getUser("phrepuser", $where);
                                                if ($getUserID) {
                                                    foreach ($getUserID as $user) {
                                                        echo '<input type="hidden" class="form-control" value="' . $user[id] . '" name="userid">';
                                                        $userid = $user[id];
                                                    }
                                                }
                                                ?>

                                            <?php $rq = $obj->getmaxtimes($subid); echo '<input id="rqtimes" name="rqtimes" type="hidden" value="'.$rq.'">'; ?>
                                            <input id="posttype" name="posttype" type="hidden" value="<?php echo $_GET['idp']; ?>">
                                            <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                            <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                                            <?php $mi = getMemberInfo(); ?>
                                            <input id="username" name="username" type="hidden" value="<?php echo $userid; ?>" placeholder="" class="form-control input-md">
                                        
                                        <span class="btn btn-default btn-file">
                                            <input type = "file" name = "addsupfilepost" required accept="application/pdf"/>                        
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
                                $getstataction = $obj->getstat($subid, $sa[sa]);
                                if($getstataction>0){
                                    foreach($getstataction as $stat){
                                        if ($stat[status_action] != '3'){
                                
                                 ?>
                                    
                                    <div class="row"><!--THIS IS THE BUTTON--><hr>
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

                                        <input id="fname" name="submid" type="hidden" value="<?php echo $subid;?>" placeholder="" class="form-control input-md">
                                          <div class="col-lg-2"></div>
                                          <div class="col-lg-8"><center><small>*Once clicked "Done Resubmit", it cannot be undone.</small>           
                                              <button type="submit" name="resubmitfiles" class="btn btn-info">Done Resubmit</button>
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


$('tr[data-href]').on("click", function() {
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