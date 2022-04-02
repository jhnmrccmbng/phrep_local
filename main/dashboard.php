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
      }.tr_link {cursor:pointer} .cursor{cursor: pointer;}</style>
  </head>
  <body>
      <div class="container-fluid"><?php $id = (int) $_GET['id'];?>
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
              <div class="col-lg-2"><h2>Dashboard</h2></div>
              <div class="col-lg-10">
                  
                          <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a href="#active">Active</a></li>
                            <li><a href="#revise">Revise</a></li>
                            <li><a href="#decline">Resubmission</a></li>
                            <li><a href="#approved">Post-Approval</a></li>
                            <li class="pull-right"><a href="#completed">Completed</a></li>
                          </ul>                 
                  
              </div>
          </div>
          
          <div class="row">
                  <div class="col-lg-2">
                      <hr><center>
                      <a class="btn btn-lg btn-primary" href="submission-s1.php" role="button"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>New Proposal</a>
                      </center><hr>                    
                      <div class="list-group">
                          <a href="edit_profile.php?u=<?php $m = $obj->getmagicword(); echo $obj->encrypt($userid,$m);?>" class="list-group-item">My Profile</a>
                          <!--<a href="#" class="list-group-item active">Proposals</a>-->
                          <!--<a href="#" class="list-group-item">Letters</a>-->
                      </div>
                  </div>
              
              <div class="col-lg-10 tab-content">
                  <div id="active" class="tab-pane fade in active">   
                            <div class="panel panel-primary">
                              <!-- Default panel contents -->

                              <!-- Table -->
                              <table class="table table-bordered table-condensed table-hover tr_link">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  <th>PROPOSAL</th>
                                  <th class="text-center">STATUS/ACTION</th>
                                  </tr>
                                  
                                  <?php 
                                  $getnewsubmit = $obj->getNewSubmitted($userid);
                                  if($getnewsubmit){
                                      foreach($getnewsubmit as $newsubmit){
                                          $uc = strtoupper($newsubmit['prop_ptitle']);
                                          $strlen = strlen($newsubmit['prop_ptitle']);
                                          echo '<tr>';
                                          echo '<td>';
                                          echo '<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span>';
                                          echo '</td>';
                                          echo '<td>';
                                          
                                          if ($strlen>75){
                                                        echo '<a href="proposal_info.php?id='.$newsubmit['sub_id'].'">'.substr($uc, 0, 75).'...</a>';}
                                           else {
                                                        echo '<a href="proposal_info.php?id='.$newsubmit['sub_id'].'">'.substr($uc, 0, 75).'</a>'; }
                                          
                                          echo '<br><small>'.$newsubmit['code'].'<span class="pull-right">'.date("F j, Y",strtotime($newsubmit['date_submitted'])).'</span></small>';
                                          
                                          echo '</td>';                                         
                                          
                                          echo '<td>';
                                          echo '<center>No action yet<br><small><a href="#" data-href="researcher_action.php?d=1&p='.$newsubmit['sub_id'].'&u='.$userid.'" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#confirm-withdraw">Withdraw</a></small></center>';
                                          echo '</td>';
                                          echo '</tr>';
                                      }
                                  }
                                  ?>
                                  

                                  <div class="modal fade" id="confirm-withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h3>Warning!</h3>
                                        </div>
                                        <div class="modal-body">

                                          Are you sure you want to withdraw this proposal? Please upload withdrawal letter before proceeding. Thank you.

                                          <div class="">

                                            <form action="" method="POST">

                                              <?php echo $newsubmit['sub_id']; ?>

                                              <input type="file" name="withdrawalLetterFile" id="withdrawalLetterYes" required> <br>

                                              <div class="modal-footer">
                                                <a type="submit" class="btn btn-primary btn-ok" id="withdrawalYes">Yes</a>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                              </div>

                                            </form>
                                          </div>
                                        
                                        </div>
                                      

                                      </div>
                                    </div>
                                  </div>



                                  <?php 
                                  $maxVal = $obj->getMaxDate($userid);
                                  if($maxVal){
                                      foreach($maxVal as $maxvalue){
                                          
                                          
                                            $uc = strtoupper($maxvalue['prop_ptitle']);
                                            $strlen = strlen($maxvalue['prop_ptitle']);
                                          
                                          
                                          $where = array("id" => $maxvalue['sa']);
                                          $getstatid = $obj->fetch_record_with_where("proposal_status", $where);
                                          if($getstatid){
                                              foreach($getstatid as $statid){
                                                if(($statid['status_action']) == '0'){
                                                    
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){
                                                    foreach($getpost as $gp){
                                                        if($gp['pa_id']=='1'){$pp = "<span class='label label-default'>Ethical Clearance Request</span>";$g = $gp['pa_id'];}
                                                        else if($gp['pa_id']=='2'){$pp = "<span class='label label-default'>Request for Amendment</span>";$g = $gp['pa_id'];}
                                                        else if($gp['pa_id']=='3'){$pp = "<span class='label label-default'>Progress Report Submission</span>";$g = $gp['pa_id'];}
                                                        else{$pp="";}
                                                    }
                                                } else{$pp="";}
                                                
                                                echo '<tr>';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span>';
                                                echo '</td>';
                                                
                                                echo '<td>';
                                                                                            
                                                        if ($strlen>50){echo substr($uc, 0, 75).'...';}
                                                        else {echo substr($uc, 0, 75); }
                                                        
                                                echo '<br><small>'.$maxvalue['code'].'</small><br><small><span class="pull-right">'.date("F j, Y",strtotime($maxvalue['date_submitted'])).'</span></small><small>'.$pp.'</small></td>';
                                                               
                                                echo '<td>';
                                                echo '<center>Submitted Addt\'l Files</center>';
                                                echo '</td>';
                                                echo '</tr>';
                                              }  
                                                else if(($statid['status_action']) == '1'){
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "<span class='label label-default'>Ethical Clearance Request</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "<span class='label label-default'>Request for Amendment</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "<span class='label label-default'>Progress Report Submission</span>";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                                echo '<tr class="warning">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-check" aria-hidden="true"></span>';
                                                echo '</td>';
                                                
                                                                                          
                                                
                                                echo '<td>';
                                                if ($strlen>75){
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'...</a>';}
                                                 else {
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'</a>'; }

                                                echo '<br><small>'.$pp.' '.$maxvalue['code'].'<span class="pull-right">'.date("F j, Y",strtotime($maxvalue['date_submitted'])).'</span></small>';
                                                
                                                echo'</td>';
                                                        
                                                        
                                                echo '<td>';
                                                echo '<center>Requirements Completed</center>';
                                                echo '</td>';
                                                echo '</tr>';
                                              }
                                              else if(($statid['status_action']) == '2'){
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "<span class='label label-default'>Ethical Clearance Request</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "<span class='label label-default'>Request for Amendment</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "<span class='label label-default'>Progress Report Submission</span>";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                                echo '<tr class="danger clickable-row"  data-href="incomplete.php?id='.$maxvalue['sub_id'].'">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>';
                                                echo '</td>';
                                                
                                                
                                                
                                                echo '<td>';
                                                if ($strlen>75){
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'...</a>';}
                                                 else {
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'</a>'; }

                                                echo '<br><small>'.$maxvalue['code'].'<span class="pull-right">'.date("F j, Y",strtotime($maxvalue['date_submitted'])).'</span></small>';
                                                
                                                echo'</td>';
                                                
                                                        
                                                echo '<td>';
                                                echo '<center>Requirements Incomplete</center>';
                                                echo '</td>';
                                                echo '</tr>';

                                              }
                                              else if(($statid['status_action']) == '3'){
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "<span class='label label-default'>Ethical Clearance Request</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "<span class='label label-default'>Request for Amendment</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "<span class='label label-default'>Progress Report Submission</span>";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                                echo '<tr class="success">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>';
                                                echo '</td>';
                                                
                                                
                                                
                                                
                                                echo '<td>';
                                                if ($strlen>75){
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'...</a>';}
                                                 else {
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'</a>'; }

                                                echo '<br><small>'.$maxvalue['code'].'<span class="pull-right">'.date("F j, Y",strtotime($maxvalue['date_submitted'])).'</span></small>';
                                                
                                                
                                                
                                                
                                                echo '<br><small>'.$pp.'</small></td>';
                                                
                                                echo '<td>';
                                                echo '<center>Review On-going</center>';
                                                echo '</td>';
                                                echo '</tr>';

                                              }
                                              else if(($statid['status_action']) == '11'){
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "<span class='label label-default'>Ethical Clearance Request</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "<span class='label label-default'>Request for Amendment</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "<span class='label label-default'>Progress Report Submission</span>";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                                echo '<tr class="success">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>';
                                                echo '</td>';
                                                
                                                
                                                
                                                
                                                
                                                echo '<td>';
                                                if ($strlen>75){
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'...</a>';}
                                                 else {
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'</a>'; }

                                                echo '<br><small>'.$pp.' '.$maxvalue['code'].'<span class="pull-right">'.date("F j, Y",strtotime($maxvalue['date_submitted'])).'</span></small>';
                                                
                                                echo'</td>';
                                                
                                                        
                                                echo '<td>';
                                                echo '<center>Resubmitted</center>';
                                                echo '</td>';
                                                echo '</tr>';

                                              }
                                              else if(($statid['status_action']) == '8'){
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "<span class='label label-default'>Ethical Clearance Request</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "<span class='label label-default'>Request for Amendment</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "<span class='label label-default'>Progress Report Submission</span>";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                                echo '<tr class="success">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>';
                                                echo '</td>';
                                                
                                                
                                                
                                                
                                                
                                                echo '<td>';
                                                if ($strlen>75){
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'...</a>';}
                                                 else {
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'</a>'; }

                                                echo '<br><span class="label label-default">'.$pp.'</span><small> '.$maxvalue['code'].'<span class="pull-right">'.date("F j, Y",strtotime($maxvalue['date_submitted'])).'</span></small>';
                                                
                                                echo'</td>';
                                                
                                                        
                                                        
                                                echo '<td>';
                                                echo 'Ethical Clearance Request';
                                                echo '</td>';
                                                echo '</tr>';

                                              }
                                              else if(($statid['status_action']) == '14'){
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "<span class='label label-default'>Ethical Clearance Request</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "<span class='label label-default'>Request for Amendment</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "<span class='label label-default'>Progress Report Submission</span>";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                                echo '<tr class="warning">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>';
                                                echo '</td>';
                                                
                                                
                                                
                                                echo '<td>';
                                                
                                                if ($strlen>75){
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'...</a>';}
                                                 else {
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'</a>'; }

                                                echo '<br><small>'.$maxvalue['code'].'<span class="pull-right">'.date("F j, Y",strtotime($maxvalue['date_submitted'])).'</span></small>';
                                                
                                                echo '</td>';
                                                
                                                
                                                                  
                                                
                                                echo '<td class="text-center">';
                                                echo 'Resubmitted Files';
                                                echo '</td>';
                                                echo '</tr>';

                                              }
                                              else if(($statid['status_action']) == '26'){
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "<span class='label label-default'>Ethical Clearance Request</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "<span class='label label-default'>Request for Amendment</span>";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "<span class='label label-default'>Progress Report Submission</span>";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                                echo '<tr class="warning">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>';
                                                echo '</td>';
                                                
                                                
                                                
                                                echo '<td>';
                                                
                                                if ($strlen>75){
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'...</a>';}
                                                 else {
                                                    echo '<a href="proposal_info.php?id='.$maxvalue['sub_id'].'">'.substr($uc, 0, 75).'</a>'; }

                                                echo '<br><small>'.$maxvalue['code'].'<span class="pull-right">'.date("F j, Y",strtotime($maxvalue['date_submitted'])).'</span></small>';
                                                
                                                echo '</td>';
                                                
                                                
                                                                  
                                                
                                                echo '<td class="text-center">';
                                                echo 'Appeal Submitted';
                                                echo '</td>';
                                                echo '</tr>';

                                              }
                                              }
                                          }
                                      }
                                  }
                                  ?>
                                  
                                  
                                  
                              </table>
                            </div>
                      
                            
                            <hr>
                            <h3>Draft Submission(s)<small><br>Click continue button to continue submitting your proposal. Otherwise, click discard to delete it.</small></h3>
                            
                            <div class="panel panel-default">

                              <!-- Table -->
                              <table class="table table-bordered table-condensed table-hover">
                                  <tr>
                                      <th width="80%">Draft Submission(s)</th>
                                      <th width="20%" class="text-center">Action</th>
                                  </tr>
                                  <tr>
                                      <?php
                                      $step1 = $obj->getStep1($userid);
                                      if($step1){
                                          foreach($step1 as $one){
                                              
                                            $key = $obj->getmagicword();
                                            $dirtyid = $obj->encrypt($one['sub_id'], $key); 
                                            $dirtyuid = $obj->encrypt($userid,$key);
                                              
                                              echo '<tr>';
                                              if($one['prop_ptitle'] == ''){
                                                echo '<td><i>No proposal title yet.</i></td>';
                                              }
                                              else{
                                                echo '<td>'.$one['prop_ptitle'].'</td>';
                                              }
                                              echo '<td class="text-center"><a class="btn btn-default btn-sm" href="submission-s1.php?id='.$dirtyid.'" role="button">Continue</a> | <a class="btn btn-danger btn-sm" href="#" data-href="?deleteunf=1&id='.$dirtyid.'&u='.$dirtyuid.'" data-toggle="modal" data-target="#confirm-delete" role="button">Discard</a></td>';
                                              echo '</td>';
                                          }
                                      }
                                      $step2 = $obj->getStep2($userid);
                                      if($step2){
                                          foreach($step2 as $two){
                                              
                                            $key = $obj->getmagicword();
                                            $dirtyid = $obj->encrypt($two['sub_id'], $key); 
                                            $dirtyuid = $obj->encrypt($userid,$key);
                                              
                                              echo '<tr>';
                                              if($two['prop_ptitle'] == ''){
                                                echo '<td><i>No proposal title yet.</i></td>';
                                              }
                                              else{
                                                echo '<td>'.$two['prop_ptitle'].'</td>';
                                              }
                                              echo '<td class="text-center"><a class="btn btn-default btn-sm" href="submission-s1.php?id='.$dirtyid.'" role="button">Continue</a> | <a class="btn btn-danger btn-sm" href="#" data-href="?deleteunfp=1&id='.$dirtyid.'&u='.$dirtyuid.'" data-toggle="modal" data-target="#confirm-delete" role="button">Discard</a></td>';
                                              echo '</tr>';
                                          }
                                      }
//                                      $step3 = $obj->getStep3($userid);
                                      ?>
                                  </tr>
                              </table>
                            </div>    
                      
                      </div>
                  <div id="revise" class="tab-pane fade">
                      <div class="panel panel-primary">
                              <!-- Default panel contents -->
                              <div class="panel-heading">For Revision</div>

                              <!-- Table -->
                              <table class="table table-bordered table-condensed table-hover tr_link">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  <th>ID</th>
                                  <th>Submitted</th>
                                  <th>Title</th>
                                  <th>Status</th>
                                  </tr>
                                                                    
                                  <?php 
                                  $maxVal = $obj->getMaxDate($userid);
                                  if($maxVal){
                                      foreach($maxVal as $maxvalue){
                                          $where = array("id" => $maxvalue['sa']);
                                          $getstatid = $obj->fetch_record_with_where("proposal_status", $where);
                                          if($getstatid){
                                              foreach($getstatid as $statid){
                                                if(($statid['status_action']) == '5'){
                                                echo '<tr data-href="reupload.php?id='.$statid['sub_id'].'">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>';
                                                echo '</td>';
                                                echo '<td>';
                                                
                                                echo $maxvalue['code'];
                                                echo '</td>';
                                                echo '<td>';
                                                echo date("F j, Y",strtotime($maxvalue['date_submitted']));
                                                echo '</td>';                                            
                                                    $uc = ucwords($maxvalue['prop_ptitle']);
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                        else {echo'<td>'.substr($uc, 0, 40).'</td>'; }                                            
                                                echo '<td>';
                                                echo 'To be revised';
                                                echo '</td>';
                                                echo '</tr>';
                                              }  
                                                else if(($statid['status_action']) == '13'){
                                                $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "Ethical Clearance Request";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "Request for Amendment";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "Progress Report Submission";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                                $getdoctimes = $obj->getmaxtimes($statid['sub_id']);
                                                
                                                echo '<tr data-href="reuploadpost.php?id='.$statid['sub_id'].'&idt='.$getdoctimes.'">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>';
                                                echo '</td>';
                                                echo '<td>';
                                                echo $maxvalue['code'];
                                                echo '<br><small>'.$pp.'</small>';
                                                echo '</td>';
                                                echo '<td>';
                                                echo date("F j, Y",strtotime($maxvalue['date_submitted']));
                                                echo '</td>';                                            
                                                    $uc = ucwords($maxvalue['prop_ptitle']);
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                        else {echo'<td>'.substr($uc, 0, 40).'</td>'; }                                            
                                                echo '<td>';
                                                echo 'To be revised';
                                                echo '</td>';
                                                echo '</tr>';
                                              }  
                                              }
                                          }
                                      }
                                  }
                                  ?>
                                  
                                  
                                  
                              </table>
                            </div>
                  </div>
                  <div id="decline" class="tab-pane fade">
                      <div class="panel panel-danger">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Declined</div>

                              <!-- Table -->
                              <table class="table table-bordered table-condensed table-hover tr_link">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  <th>ID</th>
                                  <th>Submitted</th>
                                  <th>Title</th>
                                  <th>Status</th>
                                  </tr>
                                                                    
                                  <?php 
                                  $maxVal = $obj->getMaxDate($userid);
                                  if($maxVal){
                                      $i = 0;
                                      foreach($maxVal as $maxvalue){
                                          $where = array("id" => $maxvalue['sa']);
                                          $getstatid = $obj->fetch_record_with_where("proposal_status", $where);
                                          if($getstatid){
                                              foreach($getstatid as $statid){
                                                if(($statid['status_action']) == '10'){
                                                $i = $i + 1;
                                                echo '<tr data-href="assignrec.php?id='.$statid['sub_id'].'">';
                                                echo '<td>';
                                                echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
                                                echo '</td>';
                                                echo '<td>';
                                                echo $maxvalue['code'];
                                                echo '</td>';
                                                echo '<td>';
                                                echo date("F j, Y",strtotime($maxvalue['date_submitted']));
                                                echo '</td>';                                            
                                                    $uc = ucwords($maxvalue['prop_ptitle']);
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                        else {echo'<td>'.substr($uc, 0, 40).'</td>'; }                                            
                                                echo '<td>';
                                                echo 'Declined';
                                                echo '</td>';
                                                echo '</tr>';
                                              }  
                                              }
                                          }
                                      }
                                      if($i == 0){
                                            echo '<tr>';
                                            echo '<td class="text-center" colspan="5">';
                                            echo '<i>There are no declined proposal yet.</i>';
                                            echo '</td>';
                                            echo '</tr>';                                          
                                      }
                                  }
                                  ?>
                                  
                                  
                                  
                              </table>
                            </div>
                      
                      <div class="panel panel-default">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Withdrawn</div>

                              <!-- Table -->
                              <table class="table table-bordered table-condensed">
                                  <tr>
                                  <th>Title</th>
                                  <th>Action</th>
                                  </tr>
                                                                    
                                  <?php 
                                  $maxVal = $obj->getMaxDate($userid);
                                  if($maxVal){
                                      $i = 0;
                                      foreach($maxVal as $maxvalue){
                                          $where = array("id" => $maxvalue['sa']);
                                          $getstatid = $obj->fetch_record_with_where("proposal_status", $where);
                                          if($getstatid){
                                              foreach($getstatid as $statid){
                                                if(($statid['status_action']) == '17'){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>';
                                                
                                                    $uc = strtoupper(ucwords($maxvalue['prop_ptitle']));
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>50){echo substr($uc, 0, 40).'...';}
                                                        else {echo substr($uc, 0, 40).''; } 
                                                echo '<br>'; 
                                                echo '<small>'.$maxvalue['code'].' | Withdrawn last <span>'.date("F j, Y",strtotime($statid['status_date'])).'</span></small>';
                                                echo '</td>';
                                                echo '<td>';
                                                echo '</td>';
                                                
                                                echo '</tr>';
                                              }  
                                              }
                                          }
                                      }
                                      if($i == 0){
                                            echo '<tr>';
                                            echo '<td class="text-center" colspan="5">';
                                            echo '<i>There are no withdrawn proposal yet.</i>';
                                            echo '</td>';
                                            echo '</tr>';                                          
                                      }
                                  }
                                  ?>
                                  
                                  
                                  
                              </table>
                            </div>
                      
                      <div class="panel panel-default">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Disapproved</div>

                              <!-- Table -->
                              <table class="table table-bordered table-condensed">
                                  <tr>
                                  <th>Title</th>
                                  <th class="text-center">Action</th>
                                  </tr>
                                                                    
                                  <?php 
                                  $maxVal = $obj->getMaxDate($userid);
                                  if($maxVal){
                                      $i = 0;
                                      foreach($maxVal as $maxvalue){
                                          $where = array("id" => $maxvalue['sa']);
                                          $getstatid = $obj->fetch_record_with_where("proposal_status", $where);
                                          if($getstatid){
                                              foreach($getstatid as $statid){
                                                if(($statid['status_action']) == '25'){
                                                    $where = array("kind" => 'DAL', "sub_id" =>$statid['sub_id']);
                                                    $a = 0;
                                                    $getcountdisapproved = $obj->fetch_record_with_where("document", $where);
                                                    foreach($getcountdisapproved as $disap){
                                                        $a = $a + 1;
                                                    }
                                                    
                                                    
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>';
                                                
                                                    $uc = strtoupper(ucwords($maxvalue['prop_ptitle']));
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>70){echo substr($uc, 0, 70).'...';}
                                                        else {echo substr($uc, 0, 70).''; } 
                                                echo '<br>'; 
                                                echo '<small>'.$maxvalue['code'].' | Disapproved last <span>'.date("F j, Y",strtotime($statid['status_date'])).'</span></small>';
                                                echo '</td>';
                                                echo '<td class="text-center">';
                                                
                                                if($a > 1){
                                                    echo '<span data-toggle="tooltip" title="Disapproved twice already">Totally Disapproved<span>';                                                    
                                                }
                                                else{
                                                    echo '<a class="btn btn-primary" href="appeal.php?id='.$statid['sub_id'].'" role="button">Appeal</a>';                                                    
                                                }
                                                echo '</td>';
                                                
                                                echo '</tr>';
                                              }  
                                              }
                                          }
                                      }
                                      if($i == 0){
                                            echo '<tr>';
                                            echo '<td class="text-center" colspan="5">';
                                            echo '<i>There are no withdrawn proposal yet.</i>';
                                            echo '</td>';
                                            echo '</tr>';                                          
                                      }
                                  }
                                  ?>
                                  
                                  
                                  
                              </table>
                            </div>
                      
                  </div>

                  <div id="approved" class="tab-pane fade">
                      <form action = "researcher_action.php" method = "POST" class="form-horizontal">
                          <input id="fname" name="submid" type="hidden" value="<?php echo $id;?>" placeholder="" class="form-control input-md">
                      <div class="panel panel-primary">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Submission Approved</div>

                              <!-- Table -->
                              <table class="table table-bordered table-condensed table-hover tr_link">
                                  <tr>
                                  <th><center><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></center></th>
                                  <th>ID</th>
                                  <th>Title</th>
                                  <th>Approved Date</th>
                                  <th><center>Action</center></th>
                                  </tr>
                                                                    
                                  <?php 
                                  $maxVal = $obj->getMaxDate($userid);
                                  if($maxVal){
                                      foreach($maxVal as $maxvalue){
                                          $where = array("id" => $maxvalue['sa']);
                                          $getstatid = $obj->fetch_record_with_where("proposal_status", $where);
                                          if($getstatid){
                                              foreach($getstatid as $statid){
                                                if(($statid['status_action']) == '22'){
                                                echo '<tr>';
                                                //echo '<tr data-href="reupload.php?id='.$statid['sub_id'].'">';
                                                echo '<td><center>';
                                                echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                                                echo '</center></td>';
                                                echo '<td class="view_data" id="'.$statid['sub_id'].'">';
                                                echo $maxvalue['code'];
                                                echo '</td>';                                     
                                                    $uc = ucwords($maxvalue['prop_ptitle']);
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                        else {echo'<td>'.substr($uc, 0, 40).'</td>'; } 
                                                echo '<td>';
                                                echo date("F j, Y",strtotime($statid['status_date']));
                                                echo '</td>';
                                                echo '<td class="text-center">';
                                                    
                                                    $where = array("subid" => $statid['sub_id'], "pa_status" => "onreview");
                                                    $getstat = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                    foreach($getstat as $stat){
                                                        
                                                        $getmax = $obj->getmaxpropstat($statid['sub_id']);     
                                                        $getmaxpaa = $obj->getmaxpastat($statid['sub_id']);
                                                        
                                                        
                                                        
                                                        if($stat['pa_request'] == "1"){
                                                            echo '<a class="btn btn-warning" href="extendec.php?id='.$statid['sub_id'].'" role="button">Continue</a> | ';
                                                            echo '<a class="btn btn-danger" role="button" data-href="researcher_action.php?cancelpostapproval=1&subid='.$maxvalue['sub_id'].'&id='.$getmax.'&pid='.$getmaxpaa.'" data-toggle="modal" data-target="#confirm-postapprovalcancel"><span data-toggle="tooltip" title="CANCEL REQUEST?"  class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                                                        }
                                                        else if($stat['pa_request'] == "2"){
                                                            echo '<a class="btn btn-warning" href="amend.php?id='.$statid['sub_id'].'" role="button">Continue</a> | ';
                                                            echo '<a class="btn btn-danger" role="button" data-href="researcher_action.php?cancelpostapproval=1&subid='.$maxvalue['sub_id'].'&id='.$getmax.'&pid='.$getmaxpaa.'" data-toggle="modal" data-target="#confirm-postapprovalcancel"><span data-toggle="tooltip" title="CANCEL REQUEST?"  class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                                                        }
                                                        else if($stat['pa_request'] == "3"){
                                                            echo '<a class="btn btn-warning" href="progressreport.php?id='.$statid['sub_id'].'" role="button">Continue</a> | ';
                                                            echo '<a class="btn btn-danger" role="button" data-href="researcher_action.php?cancelpostapproval=1&subid='.$maxvalue['sub_id'].'&id='.$getmax.'&pid='.$getmaxpaa.'" data-toggle="modal" data-target="#confirm-postapprovalcancel"><span data-toggle="tooltip" title="CANCEL REQUEST?"  class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                                                        }
                                                        else if($stat['pa_request'] == "4"){
                                                            echo '<a class="btn btn-warning" href="finalreport.php?id='.$statid['sub_id'].'" role="button">Continue</a> | ';
                                                            echo '<a class="btn btn-danger" role="button" data-href="researcher_action.php?cancelpostapproval=1&subid='.$maxvalue['sub_id'].'&id='.$getmax.'&pid='.$getmaxpaa.'" data-toggle="modal" data-target="#confirm-postapprovalcancel"><span data-toggle="tooltip" title="CANCEL REQUEST?"  class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                                                        }
                                                    }
                                                
                                                echo '</td>';       
                                                echo '</tr>';
                                              }
                                                else if(($statid['status_action']) == '6'){

                                                    $ppaid = $obj->getmaxpastat($statid['sub_id']);
                                                    $getfile = $obj->getapprovalletterinpa($statid['sub_id'], $ppaid);
                                                    if($getfile){
                                                        foreach($getfile as $filee){
                                                            $path = $filee['path'];
                                                            $filename = $filee['orig_filename'];
                                                        }
                                                    }

                                                echo '<tr>';
                                                //echo '<tr data-href="reupload.php?id='.$statid['sub_id'].'">';
                                                echo '<td><center>';
                                                echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                                                echo '</center></td>';
                                                echo '<td class="view_data" id="'.$statid['sub_id'].'">';
                                                echo $maxvalue['code'];
                                                echo '</td>';                                     
                                                    $uc = ucwords($maxvalue['prop_ptitle']);
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>50){
                                                            echo'<td>'.substr($uc, 0, 40).'...
                                                            <br><small>Approval Letter:</br><a href="'.$path.'">'.$filename.'</a></small></td>';}
                                                        else {
                                                            echo'<td>'.substr($uc, 0, 40).'
                                                            <br><small>Approval Letter:</br><a href="'.$path.'">'.$filename.'</a></small></td>'; } 
                                                echo '<td>';
                                                echo date("F j, Y",strtotime($statid['status_date']));
                                                echo '</td>';
                                                echo '<td><center>';
                                                
//                                                echo 'Service Not Available Yet';
                                                echo '<a class="btn btn-primary" role="button" data-href="researcher_action.php?extendec=1&subid='.$maxvalue['sub_id'].'" data-toggle="modal" data-target="#confirm-postapprovalextend"><span data-toggle="tooltip" title="CLEARANCE EXTENSION"  class="glyphicon glyphicon-calendar" aria-hidden="true"></span></a> | ';
                                                
                                                echo '<a class="btn btn-primary" role="button" data-href="researcher_action.php?amendpa=1&subid='.$maxvalue['sub_id'].'&uid='.$userid.'" data-toggle="modal" data-target="#confirm-postapprovalamend"><span data-toggle="tooltip" title="AMENDMENT"  class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> | ';
                                                
                                                
                                                echo '<a class="btn btn-primary" role="button" data-pr="researcher_action.php?prpa=1&subid='.$maxvalue['sub_id'].'" data-fr="researcher_action.php?frpa=1&subid='.$maxvalue['sub_id'].'&uid='.$userid.'" data-toggle="modal" data-target="#confirm-postapprovalprogress"><span data-toggle="tooltip" title="REPORT"  class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>';
                                                
                                                #echo '<a data-toggle="tooltip" title="SUBMIT FINAL RESULT" class="btn btn-primary" href="finalreport.php?id='.$maxvalue['sub_id'].'" role="button"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>';
                                                                                               
                                                echo '</center></td>';       
                                                echo '</tr>';
                                              }  
                                              if(($statid['status_action']) == '12'){
                                                echo '<tr>';
                                                //echo '<tr data-href="reupload.php?id='.$statid['sub_id'].'">';
                                                echo '<td><center>';
                                                echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                                                echo '</center></td>';
                                                echo '<td>';
                                                echo $maxvalue['code'];
                                                echo '</td>';                                     
                                                    $uc = ucwords($maxvalue['prop_ptitle']);
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                        else {echo'<td>'.substr($uc, 0, 40).'</td>'; } 
                                                echo '<td>';
                                                echo date("F j, Y",strtotime($statid['status_date']));
                                                echo '</td>';
                                                echo '<td><center>';
                                                
                                                
                                                echo 'Service Not Available Yet';
                                                
//                                                echo '<a data-toggle="tooltip" title="EXTEND ETHICAL CLEARANCE" class="btn btn-primary" href="extendec.php?id='.$maxvalue['sub_id'].'" role="button"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></a> | ';
//                                                echo '<a data-toggle="tooltip" title="AMEND PROPOSAL" class="btn btn-primary" href="amend.php?id='.$maxvalue['sub_id'].'" role="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> | ';
//                                                echo '<a data-toggle="tooltip" title="SUBMIT PROGRESS REPORT" class="btn btn-primary" href="finalreport.php?id='.$maxvalue['sub_id'].'" role="button"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>';
                                                                                                
                                                echo '</center></td>';       
                                                echo '</tr>';
                                              }
                                              if(($statid['status_action']) == '15'){
//                                                echo '<tr>';
                                                echo '<tr data-href="submitig.php?id='.$statid['sub_id'].'">';
                                                echo '<td><center>';
                                                echo '<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span>';
                                                echo '</center></td>';
                                                echo '<td>';
                                                echo $maxvalue['code'];
                                                echo '</td>';                                     
                                                    $uc = ucwords($maxvalue['prop_ptitle']);
                                                    $strlen = strlen($maxvalue['prop_ptitle']);
                                                        if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                        else {echo'<td>'.substr($uc, 0, 40).'</td>'; } 
                                                echo '<td>';
                                                echo '<center>-</center>';
                                                echo '</td>';
                                                echo '<td><center>';
                                                
                                                $whered = array("sub_id" => $statid['sub_id'], "kind" => "ICC");
                                                $getpath = $obj->fetch_record_with_where("document", $whered);
                                                foreach($getpath as $gp){
                                                    $p = $gp['path'];
                                                }
                                                
                                                echo 'Need to submit FPIC Approval | <a href="'.$p.'" data-toggle="tooltip" title="ENDORSEMENT LETTER">DOWNLOAD</a>';
                                                
//                                                echo '<a data-toggle="tooltip" title="EXTEND ETHICAL CLEARANCE" class="btn btn-primary" href="extendec.php?id='.$maxvalue['sub_id'].'" role="button"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></a> | ';
//                                                echo '<a data-toggle="tooltip" title="AMEND PROPOSAL" class="btn btn-primary" href="amend.php?id='.$maxvalue['sub_id'].'" role="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> | ';
//                                                echo '<a data-toggle="tooltip" title="SUBMIT PROGRESS REPORT" class="btn btn-primary" href="finalreport.php?id='.$maxvalue['sub_id'].'" role="button"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>';
                                                                                                
                                                echo '</center></td>';       
                                                echo '</tr>';
                                              }
                                              }
                                          }
                                      }
                                  }
                                  ?>
                                  
                                  
                                  
                              </table>
                            </div>
                            
                            <h2>Post-Approval Request</h2>
                            <div class="panel panel-default">

                                <!-- Table -->
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Title</th>
                                        <th class="text-center">Request</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                    <?php 
                                    $allprop = $obj->getlistproposal($userid);
                                    $i = 0;
                                    foreach($allprop as $prop){
                                        
                                        if($prop['dp'] != null){
                                            
                                            //GETTING PROPOSAL INFORMATION
                                            $where1 = array("sub_id" => $prop['sid']);
                                            $propin = $obj->fetch_record_with_where("proposal", $where1);
                                            foreach($propin as $po){$ptitle = $po['prop_ptitle'];}
                                            //GETTING PROPOSAL INFORMATION
                                            
                                            //GETTING MAX ID OF POST PROPOSAL REQUEST
                                            $maxppaid = $obj->getMaxValueofppa($prop['sid']);
//                                            echo $prop['dp'].'-'.$maxppaid.'<br>';
                                            //GETTING MAX ID OF POST PROPOSAL REQUEST
                                            
                                            //GETTING TYPE OF POST-APPROVAL REQUEST
                                            $where2 = array("pid" => $maxppaid, "subid" => $prop['sid']);
                                            $reqtype = $obj->fetch_record_with_where("proposal_post_approval", $where2);
                                            foreach($reqtype as $rt){
                                                $where3 = array("id" => $rt['pa_request']);
                                                $rrt = $obj->fetch_record_with_where("post_approval_reqtype", $where3);
                                                foreach($rrt as $t){$rtype = $t['par_desc'];}
                                            }
                                            //GETTING TYPE OF POST-APPROVAL REQUEST
                                            
                                            
                                            $maxps = $obj->getmaxstatuspa($prop['dp']);
                                            $where4 = array("id" => $maxps);
                                            $status = $obj->fetch_record_with_where("proposal_status_action", $where4);
                                            foreach($status as $stat){$statt = $stat['action_name'];}
                                            
                                            //
                                            
                                            if($maxps == 18){
                                                $i = $i + 1;
                                                echo '<tr class="clickable-row cursor warning" data-href="reuploadpa.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class="text-center">'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }    
                                            
                                            else if($maxps == 19){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class="text-center">'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 7){
                                                $i = $i + 1;
                                                echo '<tr class="success">';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 8){
                                                $i = $i + 1;
                                                echo '<tr class="success">';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 9){
                                                $i = $i + 1;
                                                echo '<tr class="success">';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 21){
                                                $i = $i + 1;
                                                echo '<tr class="success">';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 3){
                                                $i = $i + 1;
                                                echo '<tr class="success">';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Request Accepted">Review On-going</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 24){
                                                $i = $i + 1;
                                                echo '<tr class="danger clickable-row cursor warning" data-href="incompletepa.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Request Incomplete">Incomplete</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 27){
                                                
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "41"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                $maxsv = $obj->maxsitevisit($prop['sid'],$ppaid);
                                                
                                                $getsvdate = array(
                                                    "repeatition" => $maxsv,
                                                    "subid" => $prop['sid'],
                                                    "post_approval_type" => $ppaid
                                                );
                                                $gotsvdate = $obj->fetch_record_with_where("sitevisit", $getsvdate);
                                                foreach($gotsvdate as $gotd){
                                                    $datein = date("M j, Y", strtotime($gotd['startdate']));
                                                    $dateout = date("M j, Y", strtotime($gotd['enddate']));
                                                }
                                                
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a><small class="pull-right"><i>Site Visit Date: '.$datein.' - '.$dateout.'</i></small></td>';
                                                                                                
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Conducting Site Visit">Under Site Visit</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 28){
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "44"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                echo '<tr class="danger clickable-row cursor" data-href="rev_appeal_postapproval.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a></td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Disapproved">Disapproved Ethical Clearance</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 29){
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "44"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                echo '<tr class="danger clickable-row cursor" data-href="rev_appeal_postapproval.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a></td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Disapproved">Disapproved Amendments</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 30){
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "44"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                echo '<tr class="danger clickable-row cursor" data-href="rev_appeal_postapproval.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a></td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Disapproved">Disapproved Progress Report</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 31){
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "44"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                echo '<tr class="danger clickable-row cursor" data-href="rev_appeal_postapproval.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a></td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Disapproved">Disapproved Final Report</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            else if($maxps == 32){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            else if($maxps == 33){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            else if($maxps == 34){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            else if($maxps == 35){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 36){
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "51"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                echo '<tr class="danger clickable-row cursor" data-href="rev_appeal_postapproval_terminate.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a></td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Terminate">Terminate Ethical Clearance</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 37){
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "52"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                echo '<tr class="danger clickable-row cursor" data-href="rev_appeal_postapproval_terminate.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a></td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Terminate">Terminate Amendments</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 38){
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "53"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                echo '<tr class="danger clickable-row cursor" data-href="rev_appeal_postapproval_terminate.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a></td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Terminate">Terminate Progress Report</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else if($maxps == 39){
                                                $i = $i + 1;
                                                
                                                $ppaid = $obj->getmaxpastat($prop['sid']);
                                                $file = array(
                                                    "sub_id" => $prop['sid'],
                                                    "post_approval_type" => $ppaid,
                                                    "finaldoc" => "1",
                                                    "doctype" => "54"
                                                );
                                                $getfile = $obj->fetch_record_with_where("document_postapproval", $file);
                                                foreach($getfile as $got){
                                                    $path = $got['path'];
                                                    $filename = $got['orig_filename'];
                                                }
                                                
                                                echo '<tr class="danger clickable-row cursor" data-href="rev_appeal_postapproval_terminate.php?id='.$prop['sid'].'">';
                                                echo '<td>'.$ptitle.'<br><small><span class="glyphicon glyphicon-download" aria-hidden="true"></span></small> <a href="'.$path.'"><span class="label label-primary">'.$filename.'</span></a></td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center><span data-toggle="tooltip" title="Terminate">Terminate Final Report</span></td>';
                                                echo '</tr>';                                                 
                                            }
                                            else if($maxps == 40){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            else if($maxps == 41){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            else if($maxps == 42){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            else if($maxps == 43){
                                                $i = $i + 1;
                                                echo '<tr>';
                                                echo '<td>'.$ptitle.'</td>';
                                                echo '<td class="text-center">'.$rtype.'</td>';
                                                echo '<td class=text-center>'.$statt.'</td>';
                                                echo '</tr>';                                                 
                                            }
                                            
                                            else{
//                                                echo '<tr>';
//                                                echo '<td>'.$ptitle.'</td>';
//                                                echo '<td class="text-center">'.$rtype.'</td>';
//                                                echo '<td>'.$statt.'</td>';
//                                                echo '</tr>'; 
                                            
                                            }
                                            
                                        }                                        
                                    }
                                    
                                    if($i == 0){
                                        echo '<tr>';
                                        echo '<td colspan="3" class="text-center"><i>There are no Post-Approval Request has been submitted yet.</i></td>';
                                        echo '</tr>';                                         
                                    }
                                    ?>
                                </table>
                            </div>
                          
                          
                   </form>
                  </div>
                  
                  <div id="completed" class="tab-pane fade">
                            <h2>COMPLETED</h2>
                            <div class="panel panel-default">
                                <!-- Table -->
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        <th></th>
                                        <th>PROPOSAL</th>
                                    </tr>
                                    
                                    <?php
                                    $allprop = $obj->getallpostapprovalrequest($userid);
                                    // print_r($allprop);
                                    $c = 0;
                                        foreach($allprop as $prop){
                                            if($prop['dp'] != null){  
                                                $maxp = $obj->getMaxProposalStatus($prop['sub_id']);
                                                $status_id = array('id' => $maxp);
                                                $get_status = $obj->fetch_record_with_where('proposal_status', $status_id);
                                                foreach($get_status as $stattpp){
                                                    if($stattpp['status_action'] == 23){
                                                        $proposal = array('sub_id' => $prop['sub_id']);
                                                        $get_proposals = $obj->fetch_record_with_where('proposal', $proposal);
                                                        foreach($get_proposals as $g_prop){
                                                            $c = $c + 1;
                                                            
                                                            echo '<tr class="clickable-row cursor" data-href="proposal_info.php?id='.$prop['sub_id'].'">';
                                                            echo '<td>'. $c .'</td>';
                                                            echo "<td>";
                                                            echo  "<a href='proposal_info.php?id=".$prop['sub_id']."'>". $g_prop['prop_ptitle'] ."</a>";
                                                            echo "</td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                }

                                            } 
                                        }

                                        if ($c == 0) {
                                            echo '<tr>';
                                            echo '<td colspan="3" class="text-center">';
                                            echo '<i>No proposals yet.</i>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
?>
                                    
                                </table>
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
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

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



<div id="dataModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Proposal</h4>
            </div>
            <div class="modal-body" id="proposal_detail">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var subid = $(this).attr("id");
  $.ajax({
   url:"selectproposal.php",
   method:"POST",
   data:{sid:subid},
   success:function(data){
    $('#proposal_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
 
</script>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Attention!</h1>
                </div>
                <div class="modal-body">
                    Are you sure to delete unfinished submission?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>

<script>
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var subid = $(this).attr("id");
  $.ajax({
   url:"selectapproved.php",
   method:"POST",
   data:{sid:subid},
   success:function(data){
    $('#proposal_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
</script>


<script>
$('#confirm-withdraw').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});

</script>


<div class="modal fade" id="confirm-postapprovalamend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Are you requesting for amendments?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-postapprovalamend').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>


<div class="modal fade" id="confirm-postapprovalextend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Are you requesting for ethical clearance extension?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-postapprovalextend').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>


<div class="modal fade" id="confirm-postapprovalprogress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                What report are you submitting?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-pr">Continuing Monitoring Report</a>
                <a class="btn btn-success btn-fr">Final Report</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-postapprovalprogress').on('show.bs.modal', function(e) {
    $(this).find('.btn-pr').attr('href', $(e.relatedTarget).data('pr'));
    $(this).find('.btn-fr').attr('href', $(e.relatedTarget).data('fr'));
});
</script>

<div class="modal fade" id="confirm-postapprovalcancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel your post approval request?
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-postapprovalcancel').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>