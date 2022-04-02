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
              <div class="col-lg-12">
                  <h1>Request for Appeal</h1><hr>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-6">
                  <?php
                  $proposal = array(
                      "sub_id" => $subid
                  );
                  
                  $fetch = $obj->fetch_record_with_where("proposal", $proposal);
                  foreach($fetch as $p){
                      $title = $p["prop_ptitle"];
                      
                      $user = array(
                          "id" => $p["username"]
                      );
                      $fetchuser = $obj->fetch_record_with_where("phrepuser", $user);
                      foreach($fetchuser as $u){$name = $u["title"].' '.$u["fname"].' '.$u["mname"].' '.$u["lname"];}
                  }
                  
                  $maxstat = $obj->getmaxpropstat($subid);
                  $fetchstat = array(
                      "id" => $maxstat
                  );
                  $fetchstat = $obj->fetch_record_with_where("proposal_status", $fetchstat);
                  foreach($fetchstat as $stat){
                      $date = $stat["status_date"];
                      $statname = array(
                          "id" => $stat["status_action"]
                      );
                      $statname = $obj->fetch_record_with_where("proposal_status_action", $statname);
                      foreach($statname as $sname){
                          $namestat = $sname["action_name"];
                      }
                  }
                  
                  $maxpropid = $obj->getmaxpastat($subid);
                  $fetchpid = array("pid" => $maxpropid);
                  $ppaidfetch = $obj->fetch_record_with_where("proposal_post_approval", $fetchpid);
                  foreach($ppaidfetch as $ppid){
                      $pptype = $ppid["pa_request"];
                      
                      if($pptype == 1){$type_req = "55"; $type_name = "Appeal-TerminateEthicalClearance"; $kind = '1'; $status = '40';}
                      else if($pptype == 2){$type_req = "56"; $type_name = "Appeal-TerminateAmendments"; $kind = '2'; $status = '41';}
                      else if($pptype == 3){$type_req = "57"; $type_name = "Appeal-TerminateProgressReport"; $kind = '3'; $status = '42';}
                      else if($pptype == 4){$type_req = "58"; $type_name = "Appeal-TerminateFinalReport"; $kind = '4'; $status = '43';}
                      
                  }
                  
                  $getletter = array(
                      "sub_id" => $subid,
                      "post_approval_type" => $maxpropid,
                      "finaldoc" => "1",
                      "kind" => "T$pptype"
                  );
                  $gotletter = $obj->fetch_record_with_where("document_postapproval",$getletter);
                  foreach($gotletter as $let){$docpath = $let["path"]; $docname=$let["orig_filename"];}
                  
                  ?>
                  
                  
                  <div class="panel panel-default">
                    <div class="panel-body">
                        <h4><?php echo strtoupper($title);?><br><small><?php echo $name;?></small></h4>
                            <hr>
                            
                            <div class="row">
                                <div class="col-xs-6">
                                    <dl>
                                        <dt>Status</dt>
                                        <dd><?php echo $namestat;?></dd>
                                    </dl>
                                </div>
                                <div class="col-xs-6">
                                    <dl>
                                        <dt>Date Disapproved</dt>
                                        <dd><?php echo date("M j, Y", strtotime($date)); ?></dd>
                                    </dl>
                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12">
                                    <dl>
                                        <dt>Disapproval Letter</dt>
                                        <dd><a href="<?php echo $docpath;?>"><?php echo $docname;?></a></dd>
                                    </dl>
                                </div>
                            </div>
                    </div>
                  </div>
              </div>
              <div class="col-lg-6">
                    <?php
                    $statuses = $obj->getmaxpropstat($subid);
                    $where = array(
                        "id" => $statuses
                    );
                    
                    $statss = $obj->fetch_record_with_where("proposal_status", $where);

                    foreach($statss as $sts){
                        if(($sts["status_action"] == '40')||($sts["status_action"] == '41')||($sts["status_action"] == '42')||($sts["status_action"] == '43')){
                            echo '<div class="alert alert-success" role="alert">
                                      You have submitted your appeal <strong>successfully</strong>! Please wait for the secretariat\'s response. Click
                                      <a href="dashboard.php#approved" class="alert-link">here</a> to go back dashboard.
                                  </div>';
                        }
                        else{
                    ?>
                  
                  <h2>Upload File</h2>
                  <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">  
                        <div class="row">    
                              <div class="col-lg-12">           
                                  <fieldset> 
                                      <?php
                                      $ppaid = $obj->getMaxValueofppa($subid);                                     
                                      
                                      ?>
                                        <input name="ppaid" type="hidden" value="<?php echo $ppaid; ?>">
                                        <input name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" >
                                        <input name="kind" type="hidden" value="A<?php echo $kind; ?>">
                                        <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                                        <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>">
                                        <?php $mi = getMemberInfo(); ?>
                                        <input name="username" type="hidden" value="<?php echo $mi['username']; ?>">
                                        <input name="request_type" type="hidden" value="<?php echo $type_req; ?>">
                                        <input name="doctype" type="hidden" value="<?php echo $type_req.",".$type_name; ?>">
                                        <input name="userid" type="hidden" value="<?php echo $userid; ?>">
                                        <input name="page" type="hidden" value="rev_appeal_postapproval_terminate">
                                        
                                  </fieldset>  
                                  
                                  <span class="btn btn-default btn-file">
                                      <input type = "file" name = "appealpost" />                        
                                  </span>
                                  <input type = "submit" class="btn btn-success"/>
                                  <br>
                                  
                                        <?php 
                                        $where = array("sub_id" => $subid, "doctype" => "$type_req", "kind" => "A$kind", "finaldoc" => "1");
                                        $getclerance = $obj->fetch_record_with_where("document_postapproval", $where);
                                        if($getclerance){
                                            $disable = "";
                                            foreach($getclerance as $clear){
                                               echo '<br>';
//                                               echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$clear[path].'">';
                                               
                                               echo '<div class="alert alert-success" role="alert">
                                                        <strong><span class="glyphicon glyphicon-file" aria-hidden="true"></span> '.$clear["orig_filename"].'</strong>
                                                        <a href="#" data-toggle="modal" data-target="#confirm-deletesitedoc" data-href="researcher_action.php?erase=1&id='.$clear["file_id"].'&subid='.$subid.'&loc=rev_appeal_postapproval_terminate"><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></a>
                                                    </div>';
                                               
//                                               $ecpath = $clear[path];
                                            }
                                        }
                                        else{
                                            $disable = "disabled";
                                        }

                                        ?>
                                  
                                 
                                  
                              </div>


                          </div>
                          <br>
                            <?php
                          echo '<a class="btn btn-primary btn-lg '.$disable.'" href="#" data-toggle="modal" data-target="#confirm-appeal" data-href="researcher_action.php?appeal=1&subid='.$subid.'&statact='.$status.'&u='.$userid.'&loc=rev_appeal_postapproval_terminate" role="button">Appeal</a>';
                            ?>
                        </form>
                  
                    <?php
                        }
                    }

                    ?>
                  
                                    
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
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this document? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>


<div class="modal fade" id="confirm-deletesitedoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Do you want to delete the file?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-deletesitedoc').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>


<div class="modal fade" id="confirm-appeal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Are you sure to submit your appeal?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-appeal').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>