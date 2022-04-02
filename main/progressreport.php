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
          
          <div class="row"><h2>Submission of Continuing Report</h2><hr>
              <div class="col-lg-2"></div>
              <div class="col-lg-10">
                  
              </div>
          </div>
          
        
        <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
          <div class="row">
              <div class="col-lg-12">
                   <div class="panel panel-default">
                        <div class="panel-body">
                            <h2>1.) Select Report<small> Required</small></h2><hr>
                            
                                <?php
                                
                                    $maxpid = $obj->getMaxValueofppa($subid);
                                    echo '<input name="submid" type="hidden" value="'.$subid.'">';
                                
                                    $getsubr = $obj->fetch_record("sub_request_type");
                                    foreach($getsubr as $subr){
                                        if(($subr['id'] != '1') && ($subr['id']) != '2'){
                                            
                                            $where = array(
                                                "sreq_id" => $subr['id'],
                                                "pid" => $maxpid
                                            );
                                            if($obj->fetch_record_with_where("sub_request", $where)){
                                                echo '<div class="checkbox">';
                                                echo '<label><input type="checkbox" checked name="subramend[]" value="'.$subr['id'].'">'.$subr['srt_desc'].'</label>';
                                                echo '</div>';                                                
                                            }
                                            else{
                                                echo '<div class="checkbox">';
                                                echo '<label><input type="checkbox" name="subramend[]" value="'.$subr['id'].'">'.$subr['srt_desc'].'</label>';
                                                echo '</div>';  
                                            }
                                        }
                                    }
                                ?>
                            
                                <br><br><input type = "submit" name="savesubreq" value="Save" class="btn btn-success" id="checkBtn"/>    
                            
                        </div>
                    </div>
                  
                  
              </div>
          </div>
        </form>
          
          <hr>
          
          
         <div class="panel panel-default">
            <div class="panel-body">
          
          <div class="row">
              <div class="col-lg-12">
                  <h2>2.) Upload Request Form<small> Required</small></h2>
              </div>
          </div>
          
          
          
          
          
          <div class="row">             
                <div class="col-lg-6">
                    <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                            <input name="dlfile" type="hidden" value="NEC Form 12 - Progress Reports_ver1.docx">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="media">
                                        <div class="media-left">
                                          <button class="btn btn-primary" type="submit" name="download"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></button>
                                        </div>
                                        <div class="media-body">
                                          <h4 class="media-heading">NEC Form 12 - Progress Reports_ver1.docx</h4>
                                          <p>You can download the form and provide content, then, upload it.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>                
                </div>
              
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
                          <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].""; ?>
                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>">
                          
                         <?php $getmaxtimes = $obj->getmaxtimes($subid);
                            echo '<input type="hidden" name="maxreqtimes" value="'.$getmaxtimes.'">';
                            
                            $getmaxrevpost = $obj->getmaxpostapproval($subid);
                            $gmr = $getmaxrevpost + 1;
                            echo '<input id="maxrevpost" name="maxrevpost" type="hidden" value="'.$gmr.'">';
                          ?>
                            <input id="doctype" name="doctype" type="hidden" value="28,ProgressReportForm">
                            
                            <?php
                            $where = array(
                                "post_approval_type" => $obj->getMaxValueofppa($subid),
                                "kind" => "PRF"                                        
                            );
                            if($obj->fetch_record_with_where("document_postapproval", $where)){
                            ?>
                            
                            <div class="panel panel-default">
                              <div class="panel-body">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <strong>Successful!</strong> Check below your request form. Click its delete if you want to reupload it.
                                    </div>
                              </div>
                            </div>
                            
                            
                            <?php
                            }
                            else{
                            ?>                            
                            
                            <div class="panel panel-default">
                              <div class="panel-body">
                                <h4>Upload Your Form Here!</h4>
                                  <span class="btn btn-default btn-file">
                                      <input type = "file" name = "uploadprform" required="required"/>                        
                                  </span>
                                
                                <?php
                                $where = array(
                                    "pid" => $obj->getMaxValueofppa($subid)                                    
                                );
                                if($obj->fetch_record_with_where("sub_request", $where)){
                                    echo '<input type = "submit" class="btn btn-success"/>';
                                }
                                else{
                                    echo '<input type = "submit" class="btn btn-success" disabled/>';
                                }
                                ?>
                              </div>
                            </div>
                            
                            
                            <?php
                            }      
                            ?>
                            
                      
              </form>  
              </div>

              </div>
                
              </div>

              </div>  
                
              <hr>
          
         <div class="panel panel-default">
            <div class="panel-body">
           
              
         <div class="row">
              <div class="col-lg-12">
                  <h2>3.) Upload Documents<small> Required</small></h2>
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
                    
                    
                    <?php
                    
                            $fileid = array(29,33,34,35,36, 39, 40);
                            $count = count($fileid);
                    ?>
                    <select id="doctype" name="doctype" class="form-control" required="required">
                        <option value="" disabled selected>(Select file type to upload)</option>
                            <?php 
                            
                            for($i=0;$i<$count;$i++){
                                $where = array(
                                    "docid" => $fileid[$i]
                                );
                                if($doctypes = $obj->fetch_record_with_where("document_type", $where)){
                                    foreach($doctypes as $dt){
                                        
                                        echo ' <option value="'.$dt['docid'].','.$dt['forfilename'].'">'.$dt['doctype_desc'].'</option>';
                                        
                                    }
                                }
                            }
                            ?>
                            
                    </select>
                </div>
                <div class="col-lg-6">           
                        <fieldset> 
                           <input id="fname" name="submid" type="hidden" value="<?php echo $subid;?>" placeholder="" class="form-control input-md">
                                <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].""; ?>
                                <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                <?php $mi = getMemberInfo(); ?>
                                <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                        </fieldset>         
                    <div class="alert alert-info" role="alert">Step 2: <br> Accepted files: doc, docx, xls, xlsx, jpg, jpeg, ppt, pptx, or pdf</div>
                    <span class="btn btn-default btn-file">
                    <input type = "file" name = "uploadpr" required="required"/>                        
                    </span>
                    <input type = "submit" class="btn btn-success"/>
                </div>
            
            </div>
             
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
                                    echo '<td><center><a href="#" data-href="researcher_action.php?erase=1&id='.$row['file_id'].'&subid=';?><?php echo $_GET['id']; ?><?php echo'&loc=progressreport" class="btn btn-danger" data-toggle="modal" data-target="#confirm-deleted">Delete</a></center>';
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
                            $preports = array(29,33,34,35);
                            
                            $where1 = array(
                                "sub_id" => $subid,
                                "post_approval_type" => $ppaid,
                                "doctype" => "28"
                            );
                            if($obj->fetch_record_with_where("document_postapproval", $where1)){
                                $prform = true;
                            }else {$prform = false;}
                            
                            $preport = 0;
                            for($i=0;$i<count($preports);$i++){
                                $where2 = array(
                                "sub_id" => $subid,
                                "post_approval_type" => $ppaid,
                                "doctype" => $preports['$i']
                                ); 
                                
                                if($obj->fetch_record_with_where("document_postapproval", $where2)){
                                    $preport = $preport + 1;
                                }   
                            }  
                            
                            if(($prform == true)&&($preport>0)){
                                $bt = "";
                            }
                            else{
                                $bt = "disabled='disabled'";
                            }                          
                          ?>
                          
                          
                          <a class="btn btn-info" href="" data-href="researcher_action.php?savepreport=1&subid=<?php echo $subid;?>&u=<?php echo $userid;?>" data-toggle="modal" data-target="#confirm-savepr">Submit Report</a>
                          
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


<div class="modal fade" id="confirm-savepr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Please double check your submission.</h3>
            </div>
            <div class="modal-body">
                Are you sure all the documents required are uploaded?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-savepr').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<script type="text/javascript">
$(document).ready(function () {
    $('#checkBtn').click(function() {
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("You must check at least one report please.");
        return false;
      }

    });
});

</script>