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
            $maxrev = $obj->getmaxreviewer($id);
            $gettingInfo = $obj->exclusiveForAssignedProposal($userid, $id, $maxrev);
            if($gettingInfo){
                foreach($gettingInfo as $info){                    
                    echo '<div class="row">
                        <div class="col-lg-12">
                        <a class="btn btn-primary" href="rev_dashboard.php#new" role="button">&larr; Back</a>
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
            
                <div class="col-lg-9">
                    
                    <div class="row">
                        <div class="col-lg-12">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a href="#document">Documents</a></li>
                            <li><a href="#evaluation"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> Evaluate <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span></a></li>
                                                       
                        </ul> 
                        </div>
                    </div>
                    
                    <br>
                        
                <div class="tab-content">    
                    <div id="document" class="tab-pane fade in active">
                                 
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
                        
                        <?php
                $where = array("sub_id" => $id, "kind" => 'DAL', "doctype" => '37');

                //getting its file history
                $getdecletter = $obj->fetch_record_with_where("document", $where);
                if($getdecletter){
                ?>
                <hr>
                <h1>Request For Appeal</h1>
                <table class="table table-striped">                                  
                    <?php 
                        $where = array("sub_id" => $id, "doctype" => '37');

                        //getting its file history
                        $getdecletter = $obj->fetch_record_with_where("document", $where);
                            if($getdecletter){
                                foreach($getdecletter as $b){
                                    echo '<tr>';
                                    echo '<td>'.$b['orig_filename'].'<br><small><small>'; ?>
                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php echo '</small></small></td>';
                                       echo '<td><div class="pull-right">
                                            <a class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                            <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['orig_filename'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                                        echo '</div></td></tr>';
                                        }
                                    }   
                                    ?>
                </table>
                <?php
                }
                ?>
                        
                        
                    </div>
                    
                    <div id="evaluation" class="tab-pane fade">
                        
                        <?php 
                        $getbm = $obj->getmaxbatch($id);
                        if($getbm <= 1){ ?>
                        <div class="row">
                            <form class="form-horizontal" role="form" action="rev_dashboard_action.php" method="POST">
                            <div class="col-lg-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">EVALUATIONS</div>
                                      
                                <?php 
                                
                                $subbb = $obj->checkwithsub($id, $userid, "1");
                                
                                $geteval = $obj->getifunfinishedform("1", $id, $userid, "1");
                                $getevalsub = $obj->getifunfinishedformsub($id, $userid, "1");

                                // added by jm ---------------------------------------
                                
                                //added new form version
                                $sub_newversion = $obj->checkwithsub_newversion($id, $userid, '3');

                                $geteval_newversion = $obj->getifunfinishedform_newversion("3", $id, $userid, "3");
                                $getevalsub_newversion = $obj->getifunfinishedformsub_newversion($id, $userid, '3');

                                //echo $sub_newversion,'<br>'.$geteval_newversion.'<br>'.$getevalsub_newversion;

                                if(($geteval_newversion == 0)&&($getevalsub_newversion == 18)){
                                                                      
                                    echo '<li class="list-group-item">Review Form (New Version)- <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationform_newversion.php?id='.$id.'" class="pull-right">EDIT</a></li>';

                                    $adone = true;

                                }
                                
                                // else if(($geteval_newversion == 0)&&(($getevalsub_newversion >= 1)&&($getevalsub_newversion <= 5))){                                    
                                //         echo '<li class="list-group-item">Review Form (New Versiom) - <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationform_newversion.php?id='.$id.'" class="pull-right">EDIT</a></li>';
                                //         $adone = true; 
                                // }
                                
                                // else if((($geteval_newversion >= 1)&&($geteval_newversion <= 11))||(($getevalsub_newversion >= 1)&&($getevalsub_newversion <= 5))){
                                //     echo '<li class="list-group-item"><a data-toggle="tooltip" title="Need to finish this form please" href="evaluationform_newversion.php?id='.$id.'">Review Form (New Version) - Unfinished</a></li>';
                                //     $adone = false;
                                // }

                                // else if(($geteval_newversion == 0)&&($getevalsub_newversion == 0)){

                                //     if($sub_newversion == 46){                                        
                                //         echo '<li class="list-group-item">Review Form (New Version) - <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationform_newversion.php?id='.$id.'" class="pull-right">EDIT</a></li>';
                                //         $adone = true;                                        
                                //     }
                                //     else{
                                //         echo '<li class="list-group-item"><a data-toggle="tooltip" title="Need to finish this form please" href="evaluationform_newversion.php?id='.$id.'">Review Form (New Version) - Unfinished</a></li>';
                                //         $adone = false;                                       
                                //     }
                                
                                // }

                                else{

                                    echo '<li class="list-group-item"><a href="evaluationform_newversion.php?id='.$id.'">Review Form (New Version)</a></li>';
                                    $adone = false;
                                }

                                //---------------------------------//




                                
                                // if(($geteval == 0)&&($getevalsub == 6)){
                                                                      
                                //     echo '<li class="list-group-item">Review Form - <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationform.php?id='.$id.'" class="pull-right">EDIT</a></li>';
                                //     $adone = true;  
                                // }
                                
                                // else if(($geteval == 0)&&(($getevalsub >= 1)&&($getevalsub <= 5))){                                    
                                //         echo '<li class="list-group-item">Review Form - <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationform.php?id='.$id.'" class="pull-right">EDIT</a></li>';
                                //         $adone = true; 
                                // }
                                
                                // else if((($geteval >= 1)&&($geteval <= 11))||(($getevalsub >= 1)&&($getevalsub <= 5))){
                                //     echo '<li class="list-group-item"><a data-toggle="tooltip" title="Need to finish this form please" href="evaluationform.php?id='.$id.'">Review Form - Unfinished</a></li>';
                                //     $adone = false;
                                // }



                                // else if(($geteval == 0)&&($getevalsub == 0)){
                                    
                                //     if($subbb == 6){                                        
                                //         echo '<li class="list-group-item">Review Form - <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationform.php?id='.$id.'" class="pull-right">EDIT</a></li>';
                                //         $adone = true;                                        
                                //     }
                                //     else{
                                //         echo '<li class="list-group-item"><a data-toggle="tooltip" title="Need to finish this form please" href="evaluationform.php?id='.$id.'">Review Form - Unfinished</a></li>';
                                //         $adone = false;                                       
                                //     }
                                // }
                                
                                // else{
                                //     echo '<li class="list-group-item"><a href="evaluationform.php?id='.$id.'">Review Form</a></li>';
                                //     $adone = false;
                                // }


                                //added by JM
                               


                                ?>      
                                
                                    
                                <?php
                                $where = array("idq" => "13", "sub_id" => $id, "revid" => $userid, "revform_id" => '2');
                                $getconsent = $obj->fetch_record_with_where("rev_answers", $where);
                                if($getconsent){
                                    foreach($getconsent as $csnt){
                                        if($csnt['ansdesc'] == "Unable to assess"){
                                            $check = $obj->checkereval("(26, 27, 28)", $id, $userid, "2");
                                                if($check == '3'){
                                                    echo '<li class="list-group-item">ICF Checklist - <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationconsent.php?id='.$id.'" class="pull-right">EDIT</a></li>  ';
                                                    $bdone = true;
                                                }
                                                else{echo '<li class="list-group-item"><a href="evaluationconsent.php?id='.$id.'">ICF Checklist - Unfinished</a></li>';$bdone = false;}
                                                
                                        }
                                        else if($csnt['ansdesc'] == "Yes"){
                                            $check = $obj->checkereval("(14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28)", $id, $userid, "2");
                                                if($check == '15'){
                                                    echo '<li class="list-group-item">ICF Checklist - <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationconsent.php?id='.$id.'" class="pull-right">EDIT</a></li>  ';
                                                    $bdone = true;
                                                }
                                                else{echo '<li class="list-group-item"><a href="evaluationconsent.php?id='.$id.'">ICF Checklist - Unfinished</a></li>';$bdone = false;}                                                
                                        }
                                        else if($csnt['ansdesc'] == "No"){
                                            $where = array("idq" => "13", "sub_id" => $id, "revid" => $userid, "revform_id" => '2');
                                            $getconsent = $obj->fetch_record_with_where("rev_subanswers", $where);
                                            if($getconsent){$sub = true;}else{$sub = false;}
                                                
                                            $check = $obj->checkereval("(26, 27, 28)", $id, $userid, "2");
                                                if(($check == '3')&&($sub == true)){
                                                    echo '<li class="list-group-item">ICF Checklist - <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Done<a href="evaluationconsent.php?id='.$id.'" class="pull-right">EDIT</a></li>  ';
                                                    $bdone = true;
                                                }
                                                else{echo '<li class="list-group-item"><a href="evaluationconsent.php?id='.$id.'">ICF Checklist - Unfinished</a></li>';$bdone = false;}
                                            
                                        }
                                    }
                                }
                                else{
                                    echo '<li class="list-group-item"><a href="evaluationconsent.php?id='.$id.'">ICF Checklist</a></li>';$bdone = false;
                                }
                                
                                
                                ?>    
                                    
                                
                                
                            </div>
                        </div>
                            
                            <div class="col-lg-6">
                                <?php
                            
                            $where = array("sub_id" => $id, "phrepuser_id" => $userid, "review" => '1', "evaluation_submitted" => '1');
                            $getifdoneevalaute = $obj->fetch_record_with_where("rev_groups", $where);
                            if($getifdoneevalaute){

                                ?>

                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>All set!</strong> You have submitted your evaluation(s) and your recommendation has been recorded already.</h4>
                                    <p class="text-center"><a href="rev_dashboard.php#new" class="btn btn-primary btn-block" role="button">Back</a></p>
                                </div>
                                
                                <?php
                            }
                            else{ 
                                
//                                $geteval = $obj->getifunfinishedform("1", $id, $userid, "1");
//                                $getevalsub = $obj->getifunfinishedformsub($id, $userid, "1");
                                // var_dump($adone);

                                if(($adone == true)||($bdone == true)){ ?>
                                    <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="alert alert-danger" role="alert">
                                            Please submit your recommendation by choosing items below and press submit button.
                                        </div>
                                        <h3><b>Recommendation:</b></h3>
                                        <p class="questions">
                                                    <?php
                                                    $getdecs = $obj->fetch_record("rev_decision");
                                                    if($getdecs){
                                                        foreach($getdecs as $ds){
                                                            if(($ds['id'] == '1')||($ds['id'] == '2')||($ds['id'] == '3')||($ds['id'] == '4')){
                                                                echo '<label class="radio-inline">
                                                                        <input type="radio" name="recommendation" id="inlineRadio'.$ds['id'].'" value="'.$ds['id'].'" required> '.$ds['dec_desc'].'
                                                                      </label>';                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                            </p>
                                    </div>
                                </div>
                                <?php
                                }
                                else{
                                    echo '';
                                }
                            }
                                ?>
                                
                                
                                
                            </div>
                            <?php
                            
                            $where = array("sub_id" => $id, "phrepuser_id" => $userid, "review" => '1', "evaluation_submitted" => '1');
                            $getifdoneevalaute = $obj->fetch_record_with_where("rev_groups", $where);

                            if($getifdoneevalaute){
                                    
                            }
                            else{                         
//                                $geteval = $obj->getifunfinishedform("1", $id, $userid, "1");
//                                $getevalsub = $obj->getifunfinishedformsub($id, $userid, "1");
                                if(($adone == true)||($bdone == true)){ ?>
                                    <div class="row">
                                        <input type="hidden" name="userid" value="<?php echo $userid;?>">
                                        <input type="hidden" name="subid" value="<?php echo $id;?>">
                                        <input id="evaldate" name="evaldate" type="hidden" value="<?php $date = date_create('now'); echo date_format($date, 'U');?>">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-8">
                                            <center>
                                                    <button class="btn btn-success btn-lg" type="submit" name="submitrecommendation" id="submitrecommendation" data-toggle="tooltip" title="Submits your recommendation">Submit Recommendation</button>
                                                    <a href="rev_dashboard.php#new" class="btn btn-default btn-lg" role="button">Cancel</a>
                                            </center>
                                        </div>
                                        <div class="col-lg-2"></div>
                                    </div>
                                <?php    
                                }
                        
                                else{
                                    echo '';
                                }
                            }
                            ?>
                    </form>                                               
                    </div>
                        
                        <?php    
                        }
                        else{ ?>
                        
                        
                        
                        <div class="row">
                            <div class="col-lg-12">
                        <h3>Evaluation(s)</h3>
                        <div class="panel panel-danger">
                            <div class="panel-body">
                                <ol>
                                <!--<i>Submitting evaluation at this stage is unavailable. Please proceed to "Write Comment" tab for your feedback. Thank you.</i>-->
                                <?php 
                                    $getbypassrev = $obj->getpassrev($id, $userid);
                                    if ($getbypassrev) {
                                        foreach($getbypassrev as $p){
                                            echo '<li>';
                                            echo '<a href="form1.php?id='.$id.'&idu='.$userid.'&et='.$p['rft_id'].'">'.$p['rft_desc'].'</a>';
                                            echo '</li>';                            
                                        }


                                    } else {
                                        echo '';
                                    }
                                ?>        
                                </ol>
                            </div>
                        </div>
                        
                        <h3>Committee's Recommendation</h3>
                         <!--List group--> 
                        <ul class="list-group">
                            <?php
                            $getmaxcol = $obj->getmaxcol($id);
                            
                            for($i=1; $i<=$getmaxcol; $i++){
                                $getsuggestions = $obj->getsuggestioncol($id,$i);
                                foreach($getsuggestions as $s){
                                    echo '<li class="list-group-item">';
                                    echo '<h4>'.$obj->ordinalize($s['rev'])." Recommendation</h4><br>";
                                    echo $s['collated_desc'];
                                    echo '</li>';
                                }
                            }
                            
                            ?>
                        </ul>
                        
                        <?php
                        }
                        
                        ?>
                        <hr>
                        <form action = "rev_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                            
                        <?php                            
                            
                        $getifcancomment = $obj->gettocomment($userid, $id);
//                        print_r($getifcancomment);
                        if($getifcancomment){
                            foreach($getifcancomment as $gc){
                                $maxr = $gc['mxr'];
                                if($gc['mxr'] != '1'){
                                    $checkifcommented = $obj->checkifcommented($userid, $id, $gc['mxr']);
                                if($checkifcommented){
                                        ?>
                                        <div class="alert alert-success">
                                            A comment has been submitted already.
                                        </div> 
                                <?php
                                }
                                else{ ?>
                                    <input type="hidden" name="userid" value="<?php echo $userid;?>">
                                    <input type="hidden" name="subid" value="<?php echo $id;?>">
                                    <input type="hidden" name="maxrev" value="<?php echo $gc['mxr'];?>">
                                    <div class="row">
                                        <div class="col-lg-12">
                                        <h3>Write your evaluation</h3>
                                        <textarea class="form-control background" id="revcomment" name="revcomment" required></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                            <div class="col-lg-12">
                                                <p class="questions">
                                                    <?php
                                                    $getdecs = $obj->fetch_record("rev_decision");
                                                    if($getdecs){
                                                        foreach($getdecs as $ds){
                                                            if(($ds['id'] == '1')||($ds['id'] == '2')||($ds['id'] == '3')||($ds['id'] == '4')){
                                                                echo '<label class="radio-inline">
                                                                        <input type="radio" name="recommendation" id="inlineRadio'.$ds['id'].'" value="'.$ds['id'].'" required> '.$ds['dec_desc'].'
                                                                        </label>';                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </p>                                                
                                            </div>
                                        <div class="col-lg-9">
                                        </div>
                                        <div class="col-lg-3"><input type="submit" class="btn btn-success pull-right" name="submitcomment" value="Submit Comment"></div>
                                    </div> 

                                <?php }   
                                }
                                else{ ?>
                        
                                        <!--<div class="alert alert-warning">-->
                                            <!--Comment is not available for this time.--> 
                                        <!--</div>-->        

                                <?php
                                    
                                }
                                
                                                           
                                
                            
                            
                            }
                        }
                        ?>
                        
                        
                        
                     </form> 
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-lg-12"><?php
                                $listcomment = $obj->listcomments($userid, $id);
                                if ($listcomment) {
                                    echo '<h3>Previous Evaluations</h3>';
                                    echo '<div class="panel panel-default">';
                                    echo '<div class="panel-heading">Evaluations</div>';
                                    echo '<ul class="list-group">';
                                    foreach ($listcomment as $lc) {
                                        $v = $lc['version'] - 1;
                                        echo '<li class="list-group-item view_data" pid="' . $lc['sub_id'] . '" uid="' . $lc['phrepuser_id'] . '" cid="' . $lc['id'] . '">' . $v . ' | ' . date("M j, Y", strtotime($lc['commentdate'])) . ' <br>' . $lc['comment'] . '</li>';
                                    }
                                    echo '</ul>';
                                    echo '</div>';
                                }
                                ?>    
                            </div>
                        </div>
                        
                        
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



CKEDITOR.replace( 'revcomment' );

 
 
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
