<?php
include "rev_dashboard_action.php";
date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));

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
	if(!in_array($mi['group'], array('Admins', 'Reviewer'))){
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
      } .tr_link{cursor:pointer}</style>
  </head>
  <body>
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
          
        <div class="container-fluid">
            <?php $id = (int) $_GET['id']; ?>
            <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">


            <a class="btn btn-default btn-lg" href="rev_dashboard.php#approved" role="button">&larr;Back</a>
            
            <h2>Site Visit Decision</h2>
            <div class="row">         
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <table class="table table-bordered">
                            <?php
                            $getproposal = $obj->getproposalinfo($id);
                            if ($getproposal) {
                                foreach ($getproposal as $prop) {
                                    $title = $prop['prop_ptitle'];
                                    $name = $prop['fname'] . ' ' . $prop['mname'] . ' ' . $prop['lname'];
                                }
                            }
                            echo '<div class="panel-body"><h3>' . $title . '</h3>By:' . $name . '</div>';
                            ?>
                        </table>
                    </div>
                </div>   
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    
                                    <?php                                    
                                    $ppaid = $obj->getmaxpropapp($id);
                                    
                                    $savesitedecision = array(
                                        "subid" => $id,
                                        "ppaid" => $ppaid,
                                        "username" => $userid,
                                        "final" => '1'
                                    );

                                    $savedsitedecision = $obj->fetch_record_with_where("sitevisit_decision", $savesitedecision);
                                    if($savedsitedecision){
                                        
                                            $ppaid = $obj->getmaxpropapp($id);
                                            $getsitevisit = array(
                                                'post_approval_type' => $ppaid,
                                                'sub_id' => $id,
                                                'username' => $userid,
                                                'finaldoc' => '1'
                                            );
                                            $sitevisit = $obj->fetch_record_with_where("document_sitevisit", $getsitevisit);

                                            foreach($sitevisit as $sv){
                                                $fileid = $sv['file_id'];
                                            ?>
                                                <div class="row"><br>
                                                    <div class="col-lg-12">
                                                        <h4>You have uploaded this file below.</h4>
                                                        <div class="alert alert-success alert-dismissible" role="alert">   
                                                            <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                                                            <?php echo "<strong>".$sv['orig_filename']."</strong>"; ?>

                                                        </div>
                                                    </div>
                                                </div>   
                                            <?php
                                            }
                                            
                                            $savesitedecision = array(
                                                "subid" => $id,
                                                "ppaid" => $ppaid,
                                                "username" => $userid,
                                                "final" => '1'
                                            );

                                            $savedsitedecision = $obj->fetch_record_with_where("sitevisit_decision", $savesitedecision);
                                            if($savedsitedecision){
                                                foreach($savedsitedecision as $saved){
                                                    $save = $saved['id'];
                                                    if($saved['decision'] == '1'){
                                                        echo "<h4>You have suggested that this study is <strong>acknowledged</strong>.</h4>";
                                                    }
                                                    else if($saved['decision'] == '2'){
                                                        echo "<h4>You have suggested that this study <strong>needs additional information</strong>.</h4>";                                                        
                                                    }
                                                    else if($saved['decision'] == '6'){
                                                        echo "<h4>You have suggested that this study is to be <strong>terminated</strong>.</h4>";                                                        
                                                    }                                                    
                                                }
                                            }
                                            echo '<a class="btn btn-default btn-lg" href="rev_dashboard.php#approved" role="button">&larr;Back</a>&nbsp;&nbsp;';
                                            echo "<a class='btn btn-danger btn-lg' href='#' role='button' data-href='rev_dashboard_action.php?undosv=1&fid=".$fileid."&subid=$id&des=$save' data-toggle='modal' data-target='#confirm-undositevisit'>Undo</a>";
                                            
                                    }
                                    else{
                                          
                                    ?>
                                    
                                    
                                    <form action = "rev_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form">
                                        <h4>1.) Attached Ethical Clearance:</h4>       
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
                                        <?php
                                        date_default_timezone_set('Asia/Manila');
                                        $date = date("mdyHis", strtotime("now"));
                                        ?>
                                        <input id="times" name="times" type="hidden" value="1" placeholder="" class="form-control input-md">
                                        <input id="docname" name="docname" type="hidden" value="SiteVisitDecision" placeholder="" class="form-control input-md">
                                        <input id="date" name="date" type="hidden" value="<?php $date; ?>" placeholder="" class="form-control input-md">
                                        <input id="doctype" name="doctype" type="hidden" value="42" placeholder="" class="form-control input-md">
                                        <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                                        <input id="kind" name="kind" type="hidden" value="SVD" placeholder="" class="form-control input-md">
                                        <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                                        <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                                        <input id="username" name="username" type="hidden" value="<?php echo $userid; ?>" placeholder="" class="form-control input-md">

                                        <?php
//                                        $getmaxec = $obj->getMaxRevisionEC($id, $userid) + 1;
                                        $ppaid = $obj->getmaxpropapp($id);
                                        $getmaxdocsv = $obj->fetchmaxdocsitevisit($id, $ppaid);
                                        if($getmaxdocsv){
                                            echo '<input name="revision" type="hidden" value="' . $getmaxdocsv . '">';
                                        }
                                        else{
                                            echo '<input name="revision" type="hidden" value="1">';
                                        }
                                        
                                        ?>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <span class="btn btn-default btn-file">
                                                    <input id="attach" type = "file" name = "sitevisit" />  
                                                </span>                      
                                                <input id="attached" type = "submit" name="sitevisit" value="Upload" class="btn btn-primary"/>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                    $where = array("sub_id" => $id, "doctype" => "15", "kind" => "EC", "finaldoc" => "1");
                                    $getclerance = $obj->fetch_record_with_where("document", $where);
                                    if ($getclerance) {
                                        foreach ($getclerance as $clear) {
                                            echo '<br><a class="btn btn-default btn-xs" href="sec_dashboard_action.php?deleteccc=1&id=' . $clear['file_id'] . '&subid=' . $clear['sub_id'] . '" role="button"><span data-toggle="tooltip" title="DELETE" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> ' . $clear['orig_filename'];
                                            echo ' <input id="ecpath" name="ecpath" type="hidden" value="' . $clear['path'] . '">';
                                            $ecpath = $clear['path'];
                                        }
                                    }
                                    ?>
                                    
                                    <?php
                                    $ppaid = $obj->getmaxpropapp($id);
                                    $getsitevisit = array(
                                        'post_approval_type' => $ppaid,
                                        'sub_id' => $id,
                                        'username' => $userid,
                                        'finaldoc' => '1'
                                    );
                                    $sitevisit = $obj->fetch_record_with_where("document_sitevisit", $getsitevisit);
                                    
                                    foreach($sitevisit as $sv){
                                    ?>
                                        <div class="row"><br>
                                            <div class="col-lg-12">
                                                <div class="alert alert-success alert-dismissible" role="alert">                                                    
                                                    <?php echo "<strong>".$sv['orig_filename']."</strong>"; $fileid = $sv['file_id'];?>
                                                    <a class="btn btn-danger btn-xs pull-right" role="button" href="#" data-href="rev_dashboard_action.php?deletesv=1&fid=<?php echo $fileid;?>&subid=<?php echo $id;?>" data-toggle="modal" data-target="#confirm-deletesvdoc"><span aria-hidden="true">&times;</span></a>
                                                </div>
                                            </div>
                                        </div>   
                                    <?php
                                    }
                                    
                                    ?>
                                    
                                    
                                    <hr>
                                    
                                    <form role="form" method="POST" action = "rev_dashboard_action.php">  
                                    <input name="subid" type="hidden" value="<?php echo $id; ?>">
                                    <input name="username" type="hidden" value="<?php echo $userid; ?>">
                                    <input name="ppaid" type="hidden" value="<?php echo $ppaid; ?>">
                                    <input name="fileid" type="hidden" value="<?php echo $fileid; ?>">

                                    <h4>2.) What will be your decision?</h4>  
                                    <?php
                                    $getdecisions = $obj->fetch_record("rev_decision");
                                    foreach($getdecisions as $decision){
                                        if($decision['id'] == '1'){
                                            echo '<label class="radio-inline"><input type="radio" name="sitevisitdecision" value="'.$decision['id'].'" required>Acknowledged</label>';
                                        }
                                        else if($decision['id'] == '2'){
                                            echo '<label class="radio-inline"><input type="radio" name="sitevisitdecision" value="'.$decision['id'].'" required>Additional Info</label>';
                                        }
                                        else if($decision['id'] == '6'){
                                            echo '<label class="radio-inline"><input type="radio" name="sitevisitdecision" value="'.$decision['id'].'" required>Terminate</label>';
                                        }
                                    }
                                    ?>
                                    
                                    
                                    <hr>      
                                    
                                    
                                    <h4>3.) Click "Submit" button to forward your decision to the secretariat</h4>      
                                        <div class="row">
                                            <div class="col-lg-12"><div class="alert alert-danger" role="alert">Please make sure you have uploaded successfully the file by clicking "Upload" button.</div>
                                                <?php
//                                                $whereec = array("sub_id" => $id, "post_approval_type" => $ppaid, "finaldoc" => "1", "username" => $userid);
//                                                $ec = $obj->fetch_record_with_where("document_sitevisit", $whereec);
//                                                if ($ec) {
//                                                    $ecc = "";
//                                                } else {
//                                                    $ecc = "disabled";
//                                                }
                                                ?>    
                                                <button name="submitsitevisitreport" type="submit" class="btn btn-success btn-lg" <?php #echo $ecc; ?>>Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <?php 
                                    }
                                    ?>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>             
            </div>
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
$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

<div class="modal fade" id="confirm-deletesvdoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this file?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-deletesvdoc').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-undositevisit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Do you want to undo your submission?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-undositevisit').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>