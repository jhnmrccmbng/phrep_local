<?php
include "rev_dashboard_action.php";
date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));

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
	if(!in_array($mi['group'], array('Admins', 'Reviewer'))){
            header("location: ../index.php?signIn=1");
//		exit;
	}

       
include_once("$currDir/header.php");
?>
<!DOCTYPE html>
<html lang="en"><?php $id = (int) $_GET['id'];?>
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
         
        <div class="row"><!--THIS IS THE FORM AREA-->
            <?php 
            
            $where = array("subid" => $id);
            $checkifpostappr = $obj->fetch_record_with_where("proposal_post_approval", $where);
            if($checkifpostappr){$back = "approved";}else{$back = "new";}
            
            
            $maxrev = $obj->getmaxreviewer($id);
            $gettingInfo = $obj->exclusiveForAssignedProposal($userid, $id, $maxrev);
            if($gettingInfo){
                foreach($gettingInfo as $info){                    
                    echo '<div class="row">
                        <div class="col-lg-12">
                        <a class="btn btn-primary" href="rev_dashboard.php#'.$back.'" role="button">&larr; Back</a>
                        <h1>'.$info['prop_ptitle'].'</h1>
                        
                    By: ';
                    $where = array("id"=>$info['pi']);    
                    $gettingpi = $obj->fetch_record_with_where("phrepuser", $where);
                    if ($gettingpi) {
                        foreach ($gettingpi as $pi) {
                            echo $pi['fname'] . ' ' . $pi['mname'] . ' ' . $pi['lname'];
                        }
                    }

                    echo '<span class = "pull-right">';
                    echo $info['rt_name'].'</span>  
                        </div>
                    </div>';
                    $revtype = $info['rvid'];
                    $revname = $info['rt_name'];
                    
                    
                }
            }
            
            ?>
        </div>        
        <hr>
        
        <div class="row">
                <div class="col-lg-12">
                    
                    <div class="row">
                        <div class="col-lg-12">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a href="#document">Documents</a></li>
                            <li><a href="#evaluation"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> Evaluate <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span></a></li>
                            <?php 
//                            $maxreview = $obj->getmaxreviewer($id);
//                            if($maxreview > 1){
//                                echo '<li><a href="#comment">Write Comment</a></li>';
//                            }
                            ?>
                            
                        </ul> 
                        </div>
                    </div>
                    
                    <br>
                        
                <div class="tab-content">    
                    <div id="document" class="tab-pane fade in active">
                        
                        <?php
                        $where = array("subid" => $id);
                        $checkifpostappr = $obj->fetch_record_with_where("proposal_post_approval", $where);
                        
                        
                        
                        
                        if($checkifpostappr){ // SHOWING DOCUMENTS IF UNDERGONE POST_APPROVAL REQUEST
                        ?>    
                            
                        <!--DITO START-->
                        
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Post Approval Documents</h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-condensed table-hover">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th><center>ACTION</center></th>
                                </tr>
                                <?php
                                $ppaid = $obj->getmaxpropapp($id);
                                $getmaxbatch = $obj->getmaxbatchpa($id, $ppaid);
                                for($i = $getmaxbatch; $i >= 1; $i--){
                                    
                                    if($getmaxbatch == $i){
                                        echo '<tr class="warning"><td colspan="2"><strong>'.$obj->ordinalize($i).' SUBMISSION </strong><span class="badge">New</span></td></tr>';
                                    }
                                    else{
                                        echo '<tr class="warning"><td colspan="2"><strong>'.$obj->ordinalize($i).' SUBMISSION</strong></td></tr>';
                                    }
                                    
                                    $getdocbybatch = $obj->getdocbybatchpa($id, $i, $ppaid);
                                    if($getdocbybatch){
                                        foreach($getdocbybatch as $b){
                                            
                                            if($getmaxbatch == $i){
                                                echo '<tr class="success">
                                                        <td><strong>
                                                            '.$b['doctype_desc'].'</strong> ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                                
                                            }
                                            else{
                                                echo '<tr>
                                                        <td><strong>
                                                            '.$b['doctype_desc'].'</strong> ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                                
                                            }
                                            
                                        }
                                    }
                                    
                                }
                                
                                ?>
                            </table>
                            </div>
                          </div>
                        
                        <?php 
                        $getpostapp = $obj->getpostapp($id);
                        if($getpostapp > 0){
                            $getmaxpostdoc = $obj->getmaxpost($id);
                            $getpostappdoc = $obj->getpostdoc($id, $getmaxpostdoc);
                            
                            $where = array("subid" => $id, "pa_status" => "onreview");
                            $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                            if($getpost){foreach($getpost as $gp){$req = $gp['pa_request']; if($gp['pa_id']=='1'){$pp = "Ethical Clearance Request";$g = "warning";}else if($gp['pa_id']=='2'){$pp = "Request for Amendment";$g = "success";}else if($gp['pa_id']=='3'){$pp = "Progress Report Submission";$g = "primary";}else{$pp="";}}}
                                                

                            if($getpostappdoc){
                                echo '<div class="panel panel-'.$g.'">
                                <!-- Default panel contents -->
                                <div class="panel-heading">Post-Approval - '.$pp.'<span class="pull-right">'.$obj->ordinalize($req).' Request</span></div>

                                <!-- Table -->
                                <table class="table">
                                    <tr>
                                        <th>FILE TYPE</th>
                                        <th>DATE UPLOADED</th>
                                        <th><center>ACTION</center></th>
                                    </tr>';
                                foreach($getpostappdoc as $pdoc){
                                    echo '<tr>';
                                    echo '<td>'.$pdoc['doctype_desc'].'<br><i><small>'.$obj->ordinalize($pdoc['post_revision']).' version</small></i></td>';
                                    echo '<td>'.date("M d, Y", strtotime($pdoc['date_uploaded'])).'</td>';
                                    echo '<td><center>
                                            <a target = "_blank" href="https://docs.google.com/gview?url=www.bocasystems.com/documents/fgl46.doc&embedded=true">
                                            <button data-toggle="tooltip" title="VIEW" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button></a> | 
                                            <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-primary btn-sm" href="'.$pdoc['path'].'" role="button"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a>
                                            </center></td>';
                                    echo '</tr>';
                                }
                                echo '</table>
                                    </div>';
                            }
                            
                        }
                        
                        
                        ?>
                                
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsePrev" aria-expanded="false" aria-controls="collapsePrev">
                        Show previous documents >>
                        </button>    
                        
                        <div class="collapse" id="collapsePrev"><br>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Primary Attachments</h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-condensed">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th><center>ACTION</center></th>
                                </tr>
                                <?php
                                $getmaxbatch = $obj->getmaxbatch($id);
                                for($i = 1; $i <= $getmaxbatch; $i++){
                                    echo '<tr class="warning"><td colspan="2"><strong>'.$obj->ordinalize($i).' SUBMISSION</strong></td></tr>';
                                    $getdocbybatch = $obj->getdocbybatch($id, $i);
                                    if($getdocbybatch){
                                        foreach($getdocbybatch as $b){
                                            if($b['doctype'] == '1'){
//                                                if($b['newsubmit'] == '1'){$n = "<span class='badge'>New</span>";} else{$n = "";}
                                                if($b['finaldoc'] == '1'){
                                                    $f = "";} 
                                                else{$f = "";}
                                                echo '<tr>
                                                        <td><strong>
                                                            '.$b['doctype_desc'].'</strong> ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                                
                                                
                                            }
                                            else{
//                                                if($b['newsubmit'] == '1'){$n = "<span class='badge'>New</span>";} else{$n = "";}
                                                if($b['finaldoc'] == '1'){
                                                    $f = "";} 
                                                else{$f = "";}
                                                echo '<tr>
                                                        <td>
                                                            '.$b['doctype_desc'].' ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
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
                        <a class="btn btn-primary" href="rev_dashboard.php#<?php echo $back;?>" role="button">&larr; Back</a> 
                        <!--DITO ANG LAST-->
                        
                        
                        
                        
                        <?php    
                        }
                        
                        else{ // SHOWING DOCUMENTS IF NOT UNDERGONE POST_APPROVAL REQUEST
                        ?>    
                            
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Primary Attachments</h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-condensed">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th><center>ACTION</center></th>
                                </tr>
                                <?php
                                $getmaxbatch = $obj->getmaxbatch($id);
                                for($i = 1; $i <= $getmaxbatch; $i++){
                                    echo '<tr class="warning"><td colspan="2"><strong>'.$obj->ordinalize($i).' SUBMISSION</strong></td></tr>';
                                    $getdocbybatch = $obj->getdocbybatch($id, $i);
                                    if($getdocbybatch){
                                        foreach($getdocbybatch as $b){
                                            if($b['doctype'] == '1'){
//                                                if($b['newsubmit'] == '1'){$n = "<span class='badge'>New</span>";} else{$n = "";}
                                                if($b['finaldoc'] == '1'){
                                                    $f = "";} 
                                                else{$f = "";}
                                                echo '<tr>
                                                        <td><strong>
                                                            '.$b['doctype_desc'].'</strong> ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                                
                                                
                                            }
                                            else{
//                                                if($b['newsubmit'] == '1'){$n = "<span class='badge'>New</span>";} else{$n = "";}
                                                if($b['finaldoc'] == '1'){
                                                    $f = "";} 
                                                else{$f = "";}
                                                echo '<tr>
                                                        <td>
                                                            '.$b['doctype_desc'].' ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                            }
                                        }
                                    }
                                    
                                }
                                
                                ?>
                            </table>
                            </div>
                          </div>
                        <a class="btn btn-primary" href="rev_dashboard.php#<?php echo $back;?>" role="button">&larr; Back</a> 
                        
                        
                        
                        <?php    
                        }
                        
                        ?>
                        
                        
                        
                    </div>
                    
                    <div id="evaluation" class="tab-pane fade">
                        
                        <table class="table table-condensed table-bordered ">
                            
                            <tr>
                                <?php 
                                $where = array("pid" => $ppaid);
                                $getpareq = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                foreach($getpareq as $pareq){
                                    if($pareq['pa_request'] == "2"){
                                        echo '<th>Reason for Ethical Review:</th>';
                                        echo '<th>For Amendment of Informed Consent<br>Revisions made involve which of the following?</th>';
                                    }
                                    else if($pareq['pa_request'] == "3"){
                                        echo '<th>Progress Report Submitted</th>';
                                    }
                                }                          
                                ?>
                                
                               
                            </tr>
                            
                            <tr>
                                <td>
                                <strong></strong> 
                                    <?php 
                                    echo '<ol>';

                                    $ppaid = $obj->getppaidmax($id);
                                    $where = array("pid" => $ppaid);
                                    $getreq = $obj->fetch_record_with_where("sub_request", $where);
                                    foreach($getreq as $req){
                                        $where = array("id" => $req['sreq_id']);
                                        $getdesc = $obj->fetch_record_with_where("sub_request_type", $where);

                                        foreach($getdesc as $desc){
                                            echo '<li>'.$desc['srt_desc'].'</li>';
                                        }
                                    }
                                    echo '</ol>';

                                    ?>
                                    
                                </td>
                                
                                
                                
                                    
                                <?php 
                                $where = array("pid" => $ppaid);
                                $getpareq = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                foreach($getpareq as $pareq){
                                    if($pareq['pa_request'] == "2"){
                                        echo '<td><ol>';
                                        $where = array("pid" => $ppaid);
                                        $geticf = $obj->fetch_record_with_where("amendment_icf", $where);
                                        foreach($geticf as $icf){
                                            $where = array("id" => $icf['aicf_id']);
                                            $geticfdesc = $obj->fetch_record_with_where("amendment_icf_type", $where);
                                            foreach($geticfdesc as $icfdesc){
                                                echo '<li>'.$icfdesc['aicf_desc'].'</li>';
                                            }
                                        }
                                        echo '</ol></td>';
                                    }
                                }                          
                                ?>
                                
                            </tr>
                            
                        </table>
                        
                        
                        <form action = "rev_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                            
                        <?php                            
                        if($_GET['edit']=='1'){ 
                            $where = array("id" => $_GET['idc']);
                            $comment = $obj->fetch_record("rev_commentpa", $where);
                            foreach($comment as $cc){
                                $comment = $cc['comment'];
                            }
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                            <input type="hidden" name="subid" value="<?php echo $id; ?>">
                            <input type="hidden" name="maxrev" value="<?php echo $gc['mxr']; ?>">
                            <input type="hidden" name="idc" value="<?php echo $_GET['idc']; ?>">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h3>Edit your evaluation</h3>
                                        <textarea class="form-control background" id="revcomment" name="revcomment" required><?php echo $comment;?></textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-10">
                                        <p class="questions">
                                                            <?php
                                                            //getting if progress or final report
                                                            
                                                            $report = $obj->getmaxpropapp($id);
                                                            $where = array("pid" => $report);
                                                            $getrep = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                            foreach($getrep as $rep){
                                                                if(($rep['pa_request'] == '3')||($rep['pa_request'] == '4')||($rep['pa_request'] == '2')||($rep['pa_request'] == '1')){
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio1" value="1" required> Acknowledged
                                                                          </label>';
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio2" value="2" required> Needs Additional Documents 
                                                                          </label>';
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio5" value="5" required> Disapproved
                                                                          </label>';
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio6" value="6" required> Terminate 
                                                                          </label>';
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio7" value="7" required> Conduct Site Visit
                                                                          </label>';
                                                                }
                                                                else{
                                                                    $getdecs = $obj->fetch_record("rev_decision");
                                                                    if($getdecs){
                                                                        foreach($getdecs as $ds){
                                                                            echo '<label class="radio-inline">
                                                                                    <input type="radio" name="recommendation" id="inlineRadio'.$ds['id'].'" value="'.$ds['id'].'" required> '.$ds['dec_desc'].'
                                                                                    </label>';
                                                                        }
                                                                    }                                                                    
                                                                }
                                                            }
                                                            ?>
                                        </p>                                                
                                    </div>
                                    <div class="col-lg-2"><input type="submit" class="btn btn-success pull-right" name="editcommentpa" value="Edit Comment"></div>
                                </div>
                                
                                <!--MAKITA EXCEPT ANG LATEST COMMENT-->
                                            <div class="row">
                                            <div class="col-lg-12">
                                                <?php

                                                echo '<h3>Previous Evaluations</h3>';
                                                echo '<div class="panel panel-default">';
                                                echo '<div class="panel-heading">Evaluations</div>';
                                                echo '<ul class="list-group">';

                                                $themaxidforpp = $obj->getMaxValueofppa($id); //GETTING MAX PPA                                      
                                                $thecountofcommentperrevv = $obj->getmaxvalueofcountpercommentrev($userid,$id,$themaxidforpp);

                                                if($thecountofcommentperrevv != 0){
                                                    for($i=0;$i<=$thecountofcommentperrevv;$i++){
                                                        $where = array("phrepuser_id" => $userid, "sub_id" => $id, "ppa_id" => $themaxidforpp, "version" => $i);
                                                        $thecountofcommentperrev = $obj->fetch_record_with_where("rev_commentpa", $where);

                                                        foreach($thecountofcommentperrev as $counp){
                                                            if($thecountofcommentperrevv == $i){        
                                                                if($thecountofcommentperrevv == 1){  
                                                                    echo '<li class="list-group-item"><i>No previous evaluation yet.</i></li>';
                                                                }
                                                                else if($thecountofcommentperrevv == $i){
                                                                    
                                                                }
                                                                else{
                                                                    echo '<li class="list-group-item">' . $counp['countcom'] . ' | ' . date("M j, Y", strtotime($counp['commentdate'])) . ' <br>' . $counp['comment'] . '</li>';
                                                                }                                                
                                                            }
                                                            else{
                                                                echo '<li class="list-group-item">' . $counp['countcom'] . ' | ' . date("M j, Y", strtotime($counp['commentdate'])) . ' <br>' . $counp['comment'] . '</li>';                                                
                                                            }


                                                        }

                                                    }                                    
                                                }
                                                else{
                                                    echo '<li class="list-group-item">No previous evaluations submitted.</li>';
                                                }


                                                echo '</ul>';
                                                echo '</div>';

                                                ?>
                                            </div>
                                        </div>
                                        <!--MAKITA EXCEPT ANG LATEST COMMENT-->
                            
                        <?php    
                        }
                        else{ //THIS IS IF NOT 
                        $maxppaid = $obj->getppaidmax($id);
                            
                        $getifcancomment = $obj->gettocommentpa($userid, $id, $maxppaid);
//                        echo $getifcancomment;
                        if($getifcancomment){
                            foreach($getifcancomment as $gc){
                                $maxr = $gc['mxr'];
                                    $wherenaa = array(
                                        "ppa_id" => $maxppaid,
                                        "sub_id" => $id,
                                        "review" => $maxr,
                                        "phrepuser_id" => $userid,
                                        "evaluation_submitted" => "1"
                                    );
                                    $checkifcommented = $obj->fetch_record_with_where("rev_groupspa", $wherenaa);
                                        if($checkifcommented){
                                                ?>
                                                <div class="alert alert-success">
                                                    A comment has been submitted already.
                                                </div> 
                            
                                            <div class="panel panel-default">
                                                <div class="panel-heading">New Evaluation</div>

                                                <table class="table">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Comment</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <?php
                                                        
                                                        $themaxidforpp = $obj->getMaxValueofppa($id); //GETTING MAX PPA       

                                                        $thecountofcommentperrevv = $obj->getmaxvalueofcountpercommentrev($userid,$id,$themaxidforpp);

                                                        $where = array("phrepuser_id" => $userid, "sub_id" => $id, "ppa_id" => $themaxidforpp, "countcom" => $thecountofcommentperrevv);
                                                        $thecountofcommentperrev = $obj->fetch_record_with_where("rev_commentpa", $where);
                                                        foreach($thecountofcommentperrev as $pr){
                                                            $d = strtotime($pr['commentdate']);
                                                            echo '<td>'.date("M j, Y",$d).'</td>';
                                                            echo '<td>'.$pr['comment'].'</td>';
                                                            echo '<td><a class="btn btn-warning" href="reviewproposalpostapproval.php?id='.$id.'&edit=1&idc='.$pr['id'].'#evaluation" role="button">EDIT</a></td>';
                                                        }
                                                        
                                                        ?>
                                                        
                                                    </tr>
                                                </table>
                                            </div>
                                            
                                            <!--MAKITA EXCEPT ANG LATEST COMMENT-->
                                            <div class="row">
                                            <div class="col-lg-12">
                                                <?php

                                                echo '<h3>Previous Evaluations</h3>';
                                                echo '<div class="panel panel-default">';
                                                echo '<div class="panel-heading">Evaluations</div>';
                                                echo '<ul class="list-group">';

                                                $themaxidforpp = $obj->getMaxValueofppa($id); //GETTING MAX PPA                                      
                                                $thecountofcommentperrevv = $obj->getmaxvalueofcountpercommentrev($userid,$id,$themaxidforpp);
                                                

                                                if($thecountofcommentperrevv != 0){
                                                    for($i=0;$i<=$thecountofcommentperrevv;$i++){
                                                        $where = array("phrepuser_id" => $userid, "sub_id" => $id, "ppa_id" => $themaxidforpp, "version" => $i);
                                                        $thecountofcommentperrev = $obj->fetch_record_with_where("rev_commentpa", $where);

                                                        foreach($thecountofcommentperrev as $counp){
                                                            if($thecountofcommentperrevv == $i){        
                                                                if($thecountofcommentperrevv == 1){
                                                                    echo '<li class="list-group-item"><i>No previous evaluation yet.</i></li>';
                                                                }
                                                                else if($thecountofcommentperrevv == $i){
                                                                    
                                                                }
                                                                else{
                                                                    echo '<li class="list-group-item">' . $counp['countcom'] . ' | ' . date("M j, Y", strtotime($counp['commentdate'])) . ' <br>' . $counp['comment'] . '</li>';
                                                                }                                                
                                                            }
                                                            else{
                                                                echo '<li class="list-group-item">' . $counp['countcom'] . ' | ' . date("M j, Y", strtotime($counp['commentdate'])) . ' <br>' . $counp['comment'] . '</li>';                                                
                                                            }


                                                        }

                                                    }                                    
                                                }
                                                else{
                                                    echo '<li class="list-group-item">No previous evaluations submitted.</li>';
                                                }


                                                echo '</ul>';
                                                echo '</div>';

                                                ?>
                                            </div>
                                        </div>
                                        <!--MAKITA EXCEPT ANG LATEST COMMENT-->
                                
                                        <?php
                                        }
                                        else{ ?>
                                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                                            <input type="hidden" name="subid" value="<?php echo $id;?>">
                                            <input type="hidden" name="maxrev" value="<?php echo $gc['mxr'];?>">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                <h3>Write your evaluation on this request</h3>
                                                <textarea class="form-control background" id="revcomment" name="revcomment" required></textarea>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                    <div class="col-lg-10">
                                                        <p class="questions">
                                                            <?php
                                                            //getting if progress or final report
                                                            
                                                            $report = $obj->getmaxpropapp($id);
                                                            $where = array("pid" => $report);
                                                            $getrep = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                            foreach($getrep as $rep){
                                                                if(($rep['pa_request'] == '3')||($rep['pa_request'] == '4')||($rep['pa_request'] == '2')||($rep['pa_request'] == '1')){
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio1" value="1" required> Acknowledged
                                                                          </label>';
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio2" value="2" required> Needs Additional Documents 
                                                                          </label>';
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio5" value="5" required> Disapproved
                                                                          </label>';
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio6" value="6" required> Terminate 
                                                                          </label>';
                                                                    echo '<label class="radio-inline">
                                                                          <input type="radio" name="recommendation" id="inlineRadio7" value="7" required> Conduct Site Visit
                                                                          </label>';
                                                                }
                                                                else{
                                                                    $getdecs = $obj->fetch_record("rev_decision");
                                                                    if($getdecs){
                                                                        foreach($getdecs as $ds){
                                                                            echo '<label class="radio-inline">
                                                                                    <input type="radio" name="recommendation" id="inlineRadio'.$ds['id'].'" value="'.$ds['id'].'" required> '.$ds['dec_desc'].'
                                                                                    </label>';
                                                                        }
                                                                    }                                                                    
                                                                }
                                                            }
                                                            ?>
                                                        </p>                                                
                                                    </div>
                                                <div class="col-lg-2"><input type="submit" class="btn btn-success pull-right" name="submitcommentpa" value="Submit Comment"></div>
                                            </div> 
                                            
                                            <!--MAKITA ANG TANAN NA COMMENTS-->
                                            <div class="row">
                                            <div class="col-lg-12">
                                                <?php

                                                echo '<h3>Previous Evaluations</h3>';
                                                echo '<div class="panel panel-default">';
                                                echo '<div class="panel-heading">Evaluations</div>';
                                                echo '<ul class="list-group">';

                                                $themaxidforpp = $obj->getMaxValueofppa($id); //GETTING MAX PPA                                      
                                                $thecountofcommentperrevv = $obj->getmaxvalueofcountpercommentrev($userid,$id,$themaxidforpp);

                                                if($thecountofcommentperrevv != 0){
                                                    for($i=0;$i<=$thecountofcommentperrevv;$i++){
                                                        $where = array("phrepuser_id" => $userid, "sub_id" => $id, "ppa_id" => $themaxidforpp, "version" => $i);
                                                        $thecountofcommentperrev = $obj->fetch_record_with_where("rev_commentpa", $where);

                                                        foreach($thecountofcommentperrev as $counp){
                                                                echo '<li class="list-group-item">' . $counp['countcom'] . ' | ' . date("M j, Y", strtotime($counp['commentdate'])) . ' <br>' . $counp['comment'] . '</li>';                                                
                                                        }

                                                    }                                    
                                                }
                                                else{
                                                    echo '<li class="list-group-item">No previous evaluations submitted.</li>';
                                                }


                                                echo '</ul>';
                                                echo '</div>';

                                                ?>
                                            </div>
                                        </div>
                                            <!--MAKITA ANG TANAN NA COMMENTS-->

                                        <?php }   
                               
                            }
                        }
                        }
                        ?>
                        
                        
                        
                     </form> 
                        
                        <hr>
                    <a class="btn btn-primary" href="rev_dashboard.php#<?php echo $back;?>" role="button">&larr; Back</a>    
                    </div>                  
                    
                    </div>
                    
                    </div> 
                </div>
                    
                    <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusaction" type="hidden" value="1" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusdate" type="hidden" value="<?php 
                        date_default_timezone_set('Asia/Manila');
                        $datetime = date("Y-m-d H:i:s",strtotime("now")); echo $datetime;?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statususername" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                    
                  
                </div>
            </div>
        </div>
        
        
         <!--THIS IS THE FORM AREA-->
        

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
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
<script>
function goBack() {
    window.history.back();
}
</script>

<?php 
$mailbody = '<!DOCTYPE html>
<html lang="it">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>MOSAICO Responsive Email Designer</title>
<!--


COLORE INTENSE  #9C010F
COLORE LIGHT #EDE8DA

TESTO LIGHT #3F3D33
TESTO INTENSE #ffffff 


 --><meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<style type="text/css">#ko_onecolumnBlock_3 .textintenseStyle a, #ko_onecolumnBlock_3 .textintenseStyle a:link, #ko_onecolumnBlock_3 .textintenseStyle a:visited, #ko_onecolumnBlock_3 .textintenseStyle a:hover {color: #fff;color: ;text-decoration: none;text-decoration: none;font-weight: bold;}
#ko_onecolumnBlock_3 .textlightStyle a, #ko_onecolumnBlock_3 .textlightStyle a:link, #ko_onecolumnBlock_3 .textlightStyle a:visited, #ko_onecolumnBlock_3 .textlightStyle a:hover {color: #3f3d33;color: ;text-decoration: none;text-decoration: ;font-weight: bold;}</style>
<style type="text/css">
    /* CLIENT-SPECIFIC STYLES */
    #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
    .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height: 100%;} /* Force Hotmail to display normal line spacing */
    body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
    img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

    /* RESET STYLES */
    body{margin:0; padding:0;}
    img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
    table{border-collapse:collapse !important;}
    body{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* iOS BLUE LINKS */
    .appleBody a{color:#68440a; text-decoration: none;}
    .appleFooter a{color:#999999; text-decoration: none;}

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        table[class="wrapper"]{
          width:100% !important;
          min-width:0px !important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        td[class="mobile-hide"]{
          display:none;}

        img[class="mobile-hide"]{
          display: none !important;
        }

        img[class="img-max"]{
          width:100% !important;
          max-width: 100% !important;
          height:auto !important;
        }

        /* FULL-WIDTH TABLES */
        table[class="responsive-table"]{
          width:100%!important;
        }

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        td[class="padding"]{
          padding: 10px 5% 15px 5% !important;
        }

        td[class="padding-copy"]{
          padding: 10px 5% 10px 5% !important;
          text-align: center;
        }

        td[class="padding-meta"]{
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        td[class="no-pad"]{
          padding: 0 0 0px 0 !important;
        }

        td[class="no-padding"]{
          padding: 0 !important;
        }

        td[class="section-padding"]{
          padding: 10px 15px 10px 15px !important;
        }

        td[class="section-padding-bottom-image"]{
          padding: 10px 15px 0 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        td[class="mobile-wrapper"]{
            padding: 10px 5% 15px 5% !important;
        }

        table[class="mobile-button-container"]{
            margin:0 auto;
            width:100% !important;
        }

        a[class="mobile-button"]{
            width:80% !important;
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
        }

    }
</style>
</head>
<body style="margin: 0; padding: 0;" bgcolor="#ffffff" align="center">

<!-- PREHEADER -->


<table border="0" cellpadding="0" cellspacing="0" width="100%" id="ko_titleBlock_7"><tbody><tr class="row-a">
<td bgcolor="#9C010F" align="center" class="section-padding" style="padding: 30px 15px 0px 15px;">
            <table border="0" cellpadding="0" cellspacing="0" width="500" style="padding: 0 0 20px 0;" class="responsive-table">
<!-- TITLE --><tbody><tr>
<td align="center" class="padding-copy" colspan="2" style="padding: 0 0 10px 0px; font-size: 25px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff;">Secretariat\'s Notification</td>
                </tr></tbody>
</table>
</td>
    </tr></tbody></table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="ko_onecolumnBlock_3"><tbody><tr class="row-a">
<td bgcolor="#EDE8DA" align="center" class="section-padding" style="padding-top: 30px; padding-left: 15px; padding-bottom: 30px; padding-right: 15px;">
            <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table"><tbody><tr>
<td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>
<tr>
<td>
                                    <!-- COPY -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>
<tr>
<td align="center" class="padding-copy" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #3F3D33; padding-top: 0px;">Reviewer Suggestion</td>
                                        </tr>
<tr>
<td align="center" class="padding-copy textlightStyle" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #3F3D33;">
<p style="text-align: left;" data-mce-style="text-align: left;">Dear REC,</p>
<p style="text-align: left;" data-mce-style="text-align: left;">As I have read through documents, I have concluded that this type of review should be <strong>Type of Review</strong>.</p>
<p style="text-align: left;" data-mce-style="text-align: left;">Please let me know what are your thoughts on this suggestion and I am open for discussion. Thank you.</p>
<p style="text-align: left;" data-mce-style="text-align: left;">Truly yours,</p>
<p style="text-align: left;" data-mce-style="text-align: left;"><strong>Name of the Reviewer</strong></p>
</td>
                                        </tr>
</tbody></table>
</td>
                            </tr>
<tr>
<td>
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container"><tbody><tr>
<td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                                                <table border="0" cellspacing="0" cellpadding="0" class="responsive-table"><tbody><tr>
<td align="center"><a target="_new" class="mobile-button" style="display: inline-block; font-size: 18px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #9C010F; padding-top: 15px; padding-bottom: 15px; padding-left: 25px; padding-right: 25px; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-bottom: 3px solid #5f0109;" href=""><strong>Show</strong> More →</a></td>
                                                    </tr></tbody></table>
</td>
                                        </tr></tbody></table>
</td>
                            </tr>
</tbody></table>
</td>
                </tr></tbody></table>
</td>
    </tr></tbody></table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="ko_socialBlock_4"><tbody><tr class="row-a">
<td bgcolor="#9C010F" align="center" class="section-padding" style="padding: 10px 15px 0px 15px;">
            <table border="0" cellpadding="0" cellspacing="0" width="500" style="padding: 0 0 0px 0;" class="responsive-table"><tbody><tr>
<td align="center" style="padding: 10 10 10px 10px; font-size: 25px;" class="padding-copy">
                    <a target="_new" href="http://www.facebook.com"><img src="https://mosaico.io/templates/versafluid/img/social/facebook.png" alt="Seguici su facebook" width="48" height="48" style="padding: 0 5px 0px 0px;"></a>
                    <a target="_new" href="http://www.twitter.com"><img src="https://mosaico.io/templates/versafluid/img/social/twitter.png" alt="Seguici su twitter" width="48" height="48" style="padding: 0 5px 0px 5px;"></a>
                    <a target="_new" href="http://plus.google.com"><img src="https://mosaico.io/templates/versafluid/img/social/google-plus.png" alt="Seguici su G+" width="48" height="48" style="padding: 0 5px 0px 5px;"></a>
                    <a target="_new" href="http://www.linkedin.com"><img src="https://mosaico.io/templates/versafluid/img/social/linkedin.png" alt="Seguici su linkedin" width="48" height="48" style="padding: 0 5px 0px 5px;"></a>
                    <a target="_new" href="http://www.instagram.com"><img src="https://mosaico.io/templates/versafluid/img/social/instagram.png" alt="Seguici su instagram" width="48" height="48" style="padding: 0 0px 0px 5px;"></a>
</td>
                </tr></tbody></table>
</td>
    </tr></tbody></table>
<!-- FOOTER --><table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 500px;" id="ko_footerBlock_2"><tbody><tr>
<td bgcolor="#ffffff" align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr>
<td style="padding: 20px 0px 20px 0px;">
                        <!-- UNSUBSCRIBE COPY -->
                        <table width="500" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table"><tbody>
<tr>
<td align="center" valign="middle" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color: #3F3D33;">
                                    <span class="appleFooter" style="color: #3F3D33;">Main address and city</span><br><a class="original-only" href="%5Bprofile_link%5D" style="color: #3F3D33; text-decoration: none;" target="_new">Unsubscribe</a><span class="original-only" style="font-family: Arial, sans-serif; font-size: 12px; color: #444444;">   |   </span><a href="%5Bshow_link%5D" style="color: #3F3D33; text-decoration: none;" target="_new">View on web browser</a>
                                </td>
                            </tr>
<tr style="text-align: center;">
<td>
                                <a target="_new" style=";" href="https://mosaico.io/?footerbadge"><img border="0" hspace="0" vspace="0" src="https://mosaico.io/img/mosaico-badge.gif" alt="MOSAICO Responsive Email Designer" style="margin-top: 10px;"></a>
                            </td>
                            </tr>
</tbody></table>
</td>
                </tr></tbody></table>
</td>
    </tr></tbody></table>
</body>
</html>
';

?>

<div id="dataModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><center>Information</center></h4>
            </div>
            <div class="modal-body" id="comment_detail">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
$('#myTab a').click(function(e) {
  e.preventDefault();
  $(this).tab('show');
  history.pushState( null, null, $(this).attr('href') );
});

// store the currently selected tab in the hash value
$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
  var id = $(e.target).attr("href").substr(1);
  window.location.hash = id;
});

// on load of the page: switch to the currently selected tab
var hash = window.location.hash;
$('#myTab a[href="' + hash + '"]').tab('show');
</script>

<!--WYSIWYG editor-->
<script>
    CKEDITOR.replace( 'revcomment' );
</script>
<!--WYSIWYG editor-->
<script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var pid = $(this).attr("pid");
  var uid = $(this).attr("uid");
  var cid = $(this).attr("cid");
  $.ajax({
   url:"selectallcomment.php",
   method:"POST",
   data:{pid:pid, uid:idu, cid:cid},
   success:function(data){
    $('#comment_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
</script>


<script>    
//    var list = document.getElementsByTagName("li");
//    list[1].id = "evaluate";
//    
//    document.getElementById('evaluate').style.backgroundColor = '#f00';
//    document.getElementById('evaluate').style.fontColor = '#fff';
//$("form").submit(function() {
//    $(this).find('input[type="submit"]').prop("disabled", true);
//});
</script>