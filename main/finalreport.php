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
          
          <div class="row"><h2><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Submission of Final Report</h2><hr>
              <div class="col-lg-2"></div>
              <div class="col-lg-10">
                  
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                  <h2>Upload Final Report Form<small> Required</small></h2>
              </div>
          </div>
          <div class="row">             

                  <div class="col-lg-6">
                      <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                          <input id="fname" name="submid" type="hidden" value="<?php echo $subid;?>" placeholder="" class="form-control input-md">
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
                          <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>">
                          
                         <?php $getmaxtimes = $obj->getmaxtimes($subid);
                            echo '<input type="hidden" name="maxreqtimes" value="'.$getmaxtimes.'">';
                            
                            $getmaxrevpost = $obj->getmaxpostapproval($subid);
                            $gmr = $getmaxrevpost + 1;
                            echo '<input id="maxrevpost" name="maxrevpost" type="hidden" value="'.$gmr.'">';
                          ?>
                            <input id="doctype" name="doctype" type="hidden" value="31,FinalReportForm">
                            <div class="panel panel-default">
                              <div class="panel-body">
                                <h4>Upload Your Form Here!</h4>
                                  <span class="btn btn-default btn-file">
                                      <input type = "file" name = "uploadfrform" required="required"/>                        
                                  </span>
                                  <input type = "submit" class="btn btn-success"/>
                              </div>
                            </div>
                      </form>
                  </div>
              <div class="col-lg-6">                  
                      <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                          <input name="dlfile" type="hidden" value="NEC Form 14 - Final Report_ver1.docx">
                          <div class="panel panel-default">
                              <div class="panel-body">
                                  <h4 class="pull-left">Final Report Form<br><small>You can download the form and provide content, then, upload it.</small></h4>
                                  <br><p class="pull-right"><button class="btn btn-primary" type="submit" name="download"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></button></p>
                                    
                              </div><br>
                          </div>
                      </form>
              </div>

              </div>
              <hr>
          <div class="row">
              <div class="col-lg-12">
                  <h2>Upload Additional Document<small> Optional</small></h2>
              </div>
          </div>
         <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">   
              <div class="row">
                                        
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
                <div class="col-lg-6">
                    <div class="col-lg-12 alert alert-info" role="alert">Step 1: <br><p class="pull-left">Please select for a file type.</p><span class="glyphicon glyphicon-arrow-right pull-right" aria-hidden="true"></span><br></div>
                    
                    <select id="doctype" name="doctype" class="form-control" required="required">
                        <option value="" disabled selected>(Select file type to upload)</option>
                            <?php 
                                                                
                                    $where = array(
                                        "docid" => "19"
                                    );
                                    if($getft = $obj->fetch_record_with_where("document_type", $where)){
                                        foreach($getft as $ft){
                                            echo '<option value="'.$ft['docid'].",".$ft['forfilename'].'">'.$ft['doctype_desc'].'</option>';
                                        }
                                    }
                            ?>  
                            
                    </select>
                </div>
                <div class="col-lg-6">           
                        <fieldset> 
                           <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                                <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                <?php $mi = getMemberInfo(); ?>
                                <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                        </fieldset>         
                    <div class="alert alert-info" role="alert">Step 2: <br> Accepted files: doc, docx, xls, xlsx, jpg, jpeg, ppt, pptx, or pdf</div>
                    <span class="btn btn-default btn-file">
                    <input type = "file" name = "uploadfr" required="required"/>                        
                    </span>
                    <input type = "submit" class="btn btn-success"/>
                </div>
            
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <h2>Uploaded Files</h2>
                    <table class="table table-striped">
                        <tr>
                            <th>TITLE</th>
                            <th>ORIGINAL FILE NAME</th>
                            <th>DATE UPLOADED</th>
                            <th><center>ACTION</center></th>
                        </tr>
                        
                        <?php 
                        
//                        $getmaxrqtimes = $obj->getmaxtimes($subid);
//                        echo '<input id="maxreqtimes" name="maxreqtimes" type="hidden" value="'.$getmaxrqtimes.'">';
//                        $getmaxrevpost = $obj->getmaxpostapproval($subid);
//                        $gmr = $getmaxrevpost + 1;
//                        echo '<input id="maxrevpost" name="maxrevpost" type="hidden" value="'.$gmr.'">';
                        $ppaid = $obj->getMaxValueofppa($subid);
                        $myrow = $obj->showingUploadedFilesECE($subid, $userid, $ppaid);
                        $num = count($myrow);
                            if($num>0){
                            foreach ($myrow as $row) {
                                $idd = $row['doctype'];
                                if ($idd == '1'){
                                    
                                }
                                else{
                                    echo '<tr>';
                                    echo '<td>'.$row['doctype_desc'].'</td>';
                                    echo '<td>'.$row['orig_filename'].'</td>';
                                    echo '<td>'.date("F j, Y",strtotime($row['date_uploaded'])).'</td>';
                                    #echo '<td><a href="researcher_action.php?deleteece=1&id='.$row['file_id'].'&subid=';?><?php #echo $_GET['id']; ?><?php #echo'" class="btn btn-danger">Delete</a></td>';
                                    echo '<td><center><a href="#" data-href="researcher_action.php?erase=1&id='.$row['file_id'].'&subid=';?><?php echo $_GET['id']; ?><?php echo'&loc=finalreport" class="btn btn-danger" data-toggle="modal" data-target="#confirm-deleted">Delete</a></center>';
                                    echo '</tr>';
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
        </form>
              
            <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">     
              <input id="submid" name="submid" type="hidden" value="<?php echo $subid; ?>">
              <input id="userid" name="userid" type="hidden" value="<?php echo $userid; ?>">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-10"><center><small>*Once clicked "Done Resubmit", it cannot be undone.</small> <br>    
                          <?php
                          
                          $ppaid = $obj->getMaxValueofppa($subid);
                          
                          $where = array("kind" => "FRF", "doctype" => 31, "newsubmit" => '1', "post_approval_type" => $ppaid);
                          $getbutton = $obj->fetch_record_with_where("document_postapproval", $where);
                          if(count($getbutton) == 0){
                                $bt = "disabled='disabled'";
                          }
                          
                          ?>
                          
                          
                          
                          <button type="submit" name="finalreport" class="btn btn-info" <?php echo $bt; ?> >Submit Report</button>
                          <button type="submit" class="btn btn-danger" onclick='goBack()'>Cancel</button></center>
                  </div>
                  <div class="col-lg-1"></div>
            </form>
              
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

<div class="modal fade" id="confirm-deleted" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Reminder!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to delete your recent upload? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-deleted').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

var u = document.getElementById("userid").value;
$("#idp").attr("href", "extendec.php?id="+u);

</script>