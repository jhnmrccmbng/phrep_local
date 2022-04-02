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
              <div class="col-lg-6">
                  <h2>Disapproval Letter from Secretariat</h2>
                  <div class="panel panel-default">
                    <div class="panel-body">
                        
                        <?php 
                        $getrl = $obj->getmaxrl($subid);
                        $where = array("sub_id" => $subid, "kind" => "DAL", "finaldoc" => "1");
                        $getrlfile = $obj->fetch_record_with_where("document", $where);
                        foreach($getrlfile as $rl){
                            echo '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> '.$rl['file_name'];
                            echo '<a href = "uploads/main/'.$rl['orig_filename'].'" class="pull-right">Download</a>';
                        }
                        ?>
                        
                    </div>
                  </div>
                  
                  <hr>
                  <h2>Upload the Appeal Documents</h2>   
                        <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">    
                          
                          
                          <div class="row">
                              <div class="col-lg-12">
                                  <div class="panel panel-default">
                                      <div class="panel-body">
                                        <div class="alert alert-warning">
                                            You can upload as many appeal documents as possible. <br>Only doc, docx, xls, xlsx, ppt, pptx, or pdf files are allowed to be uploaded.
                                        </div>

                                            <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="APL" placeholder="" class="form-control input-md">
                                            <input name="doctype" type="hidden" value="38,AppealDocument">
                                            <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                                            <?php $mi = getMemberInfo(); ?>
                                            <input id="username" name="username" type="hidden" value="<?php echo $userid; ?>" placeholder="" class="form-control input-md">
                                            <?php 
//                                            $maxappeal = ($obj->getversionappeal($_GET['id'])) + 1;
//                                            $where = array("sub_id" => $_GET['id'], "revision" => $maxappeal, "finaldoc" => "1", "kind" => "APL");
                                           
                                            ?>
                                            <input id="userid" name="userid" type="hidden" value="<?php echo $userid;?>">
                                        <span class="btn btn-default btn-file">
                                            <input type = "file" name = "appeal" required/>                        
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
                                        <input id="submid" name="submid" type="hidden" value="<?php echo $subid;?>">
                                          <div class="col-lg-2"></div>   
                                          
                                          <?php
                                            if(count($ds[docid]) >= '1'){$d = "disabled";} else{$d = '';}
                                          ?>
                                          
                                          <div class="col-lg-8"><center><small>*Once clicked "Done Resubmit", it cannot be undone.</small>           
                                              <button type="submit" name="submitappeal" class="btn btn-info" <?php echo $d;?>>Done Resubmit</button>
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
              <div class="col-lg-6"><h2>Appeal Documents</h2>
                  <div class="row">
                      <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Files</div>

                        <!-- Table -->
                        <table class="table">
                            
                            <tr>
                                <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                <th>VER</th>
                                <th>Type</th>
                                <th>Date Uploaded</th>
                                <th class="text-center"><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></th>
                                
                            </tr>
                            
                            <?php 
                            $where = array("sub_id"=>$subid);
                            $getfiles = $obj->fetch_record_with_where("document", $where);
                            if($getfiles > 0){
                                foreach($getfiles as $files){
                                    if($files[kind] == 'APL'){    
                                        if($files[finaldoc] == '1'){
                                            echo '<tr class="success">
                                                <td><span class="glyphicon glyphicon-ok" aria-hidden="true"></td>';
                                            echo '<td>'.$files[revision].'</td>';
                                            
                                            if($files[newsubmit] == '1'){
                                                echo '<td>'.$files[orig_filename].'</td>';
                                            }
                                            else {echo '<td>'.$files[orig_filename].'</td>';}
                                            
                                            echo'<td>'.date("M j, Y",strtotime($files["date_uploaded"])).'</td>';
                                            
                                            echo'<td class="text-center"><a class="btn btn-xs btn-danger" href="researcher_action.php?erase=1&subid='.$subid.'&id='.$files[file_id].'&loc=appeal" role="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
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