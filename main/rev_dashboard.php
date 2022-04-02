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
      }
      .tr_link{cursor:pointer}
      .namerts{font-size: 12px;}
    </style>
  </head>
  <body>
      <form action = "rev_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
          
                    <?php 
                    $where = array("username" => $mi['username']);
                    $getUserID = $obj->getUser("phrepuser", $where);
                    if($getUserID){
                        foreach($getUserID as $user){
                            echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid" id="userid">';
                            $userid = $user['id'];
                        }
                    }
                    ?>
                    <?php
                    $getchairman1 = $obj->gettingChairman($userid);
                    if($getchairman1 != null){
                        foreach($getchairman1 as $gcm){
                            $cmname = $gcm['cid'];
                        }
                    }
                    ?>
          
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          
          <div class="row">
              <div class="col-lg-10"></div>
              <div class="col-lg-2"></div>
          </div>
          
          <div class="row">
              <div class="col-lg-2"><h1>Dashboard</h1></div>
              <div class="col-lg-8"><br>
                          <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a href="#new">Research (<?php echo $obj->revincomingcount($userid);?>)</a></li>
                            <!--<li><a href="#review">Review</a></li>-->
                            <!--<li><a href="#resubmitted">Resubmitted</a></li>-->
                            <li><a href="#approved">Approved (<?php echo $obj->revapprovedcount($userid);?>)</a></li>
                            <!--<li><a href="#postapproval">Post-Approval</a></li>-->
                            <?php 
                            if($cmname == '1'){ ?>
                            <li class="pull-right"><a href="#reviewers" data-toggle="tooltip" title="REVIEWERS SUMMARY"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a></li>
                            <li class="pull-right"><a href="#all">All</a></li>
                            <?php    
                            }
                            ?>
                          </ul>
              </div>
              
              <div class="col-lg-2"></div>
          </div>
          
          <div class="row">
              
              <div class="col-lg-10 tab-content">
                  
                  <div id="new" class="tab-pane fade in active">
                            
                            
                                    <?php
                                    $forconfirm = $obj->gettingCompleteProposal($userid);
                                    if($forconfirm){
                                        echo '<div class="panel panel-info">
                                                <!-- Default panel contents -->
                                                <div class="panel-heading">Proposal with Complete Document <small>| Please suggest for its review type</small></div>

                                                <!-- Table --> 
                                                <table class="table table-bordered">
                                                    <tr>
                                                    <th>ID</th>
                                                    <th>TITLE</th>
                                                    <th class="namerts"><center>REVIEW TYPE SUGGESTION</center></th>
                                                    <th><center><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></center></th>
                                                    
                                                    </tr>';
                                        foreach($forconfirm as $subm){
                                            if($subm['status_action'] == 1){
                                                echo '<tr>';
                                                echo '<td>'.$subm['code'].'</td>';
                                                    $uc = ucwords($subm['prop_ptitle']);
                                                    $strlen = strlen($subm['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }                                               
                                                
//starthere  
                                                $where = array("sub_id" => $subm['sub_id']);
                                                $getpropsug = $obj->fetch_record_with_where("proposal", $where);
                                                if($getpropsug){ 
                                                    foreach($getpropsug as $sugg){
                                                        if($sugg['chair_suggest'] == '0'){
                                                            
                                                            echo '<td><center>';
                                                            #echo '<a href="rev_dashboard_action.php?sed=1&id='.$subm['sub_id'].'&str=1" class="btn btn-info btn-sm" role="button">EPD</a> | ';
                                                            #echo '<a href="rev_dashboard_action.php?sfl=1&id='.$subm['sub_id'].'&str=3" class="btn btn-primary btn-sm" role="button">FUL</a> | ';
                                                            #echo '<a href="rev_dashboard_action.php?sep=1&id='.$subm['sub_id'].'&str=2" class="btn btn-warning btn-sm" role="button">EXP</a>';
                                                            echo '<a href="rev_chairman_suggestion.php?id='.$subm['sub_id'].'&str=1" class="btn btn-info btn-sm" role="button">EPD</a> | ';
                                                            echo '<a href="rev_chairman_suggestion.php?id='.$subm['sub_id'].'&str=3" class="btn btn-primary btn-sm" role="button">FUL</a> | ';
                                                            // echo '<a href="rev_chairman_suggestion.php?sep=1&id='.$subm['sub_id'].'&str=2" class="btn btn-warning btn-sm" role="button">EXP</a>';
                                                            echo '<a href="rev_chairman_suggestion-exempted.php?id='.$subm['sub_id'].'&str=2" class="btn btn-warning btn-sm" role="button">EXP</a>';
                                                            echo '</center></td>';                                                            
                                                            
                                                        }
                                                        else{
                                                            $whereq = array("id" => $subm['chair_suggest']);
                                                            $getrevtyp = $obj->fetch_record_with_where("review_type_list", $whereq);
                                                            if($getrevtyp){
                                                                foreach($getrevtyp as $rtyp){
                                                                    echo '<td><center>';
                                                                    echo $rtyp['rt_name'].' | <a href="rev_dashboard_action.php?undosug=1&id='.$subm['sub_id'].'" class="btn btn-danger btn-sm" role="button">UNDO</a>';
                                                                    echo '</center></td>';
                                                                }
                                                            }
                                                            
                                                        }
                                                    }
                                                }
                                                
                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$subm['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                
                                                
                                                //endhere 
                                                echo '</tr>';  
                                               
                                            } 
                                        }
                                    echo '</table>                              
                                        </div>';
                                     
                                    }
                                    ?> 
                                 
<!--                            <div class="panel panel-success">
                               Default panel contents 
                              <div class="panel-heading">Accept Proposal for Review</div>

                               Table 
                              <table class="table table-bordered">
                                  <tr>
                                  <th>TITLE</th>
                                  <th>DUE DATE</th>
                                  <th><span data-toggle="tooltip" title="Primary Reviewer">PR</span></th>
                                  <th>REVIEW TYPE</th>
                                  <th><center>ACTION</center></th>
                                  <th><center><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></center></th>
                                  </tr>
                                    <?php
//                                    $forconfirm = $obj->gettingDocToBeConfirmed($userid);
//                                    if($forconfirm){
//                                        foreach($forconfirm as $subm){
//                                            if(($subm['status_action'] == 11) || $subm['status_action'] == 3){
//                                                echo '<tr>';
//                                                    $uc = ucwords($subm['prop_ptitle']);
//                                                    $strlen = strlen($subm['prop_ptitle']);
//                                                    
//                                                    $getmaxrev = $obj->getmaxreviewer($subm['sub_id']);
//                                                    if($getmaxrev > 1){$submit = 'Resubmitted';}
//                                                    else{$submit = 'New Submission';}
//                                                    
//                                                    if ($strlen>50){
//                                                        echo'<td>'.substr($uc, 0, 40).'...<br><small><span class="label label-default">'.$submit.'</span></small></td>';}
//                                                    else {
//                                                        echo'<td>'.substr($uc, 0, 40).'<br><small><span class="label label-default">'.$submit.'</span></small></td>'; }
//                                                    
//                                                echo '<td>'.date("F j, Y",strtotime($subm['rt_duedate'])).'</td>'; 
//                                                if($subm['primary_reviewer'] == 1){$pr = 'YES';} else{$pr = 'NO';}
//                                                echo '<td>'.$pr.'</td>';
//                                                if($subm['rt_id'] == 1){$rt = 'Expedited';} else if($subm['rt_id'] == 3){$rt = 'Full';} else if($subm['rt_id'] == 2){$rt = 'Exempted';}
//                                                echo '<td>'.$rt.'</td>';
//                                                echo '<td><center><a href="#" data-href="rev_dashboard_action.php?confirm=1&id='.$subm['sub_id'].'&u='.$subm['phrepuser_id'].'" class="btn btn-primary" data-toggle="modal" data-target="#confirm-accept">Confirm</a> | ';
//                                                echo '<a href="rev_dashboard_action.php?decline=1&id='.$subm['sub_id'].'&u='.$subm['phrepuser_id'].'" class="btn btn-danger">Decline</a>';
//                                                echo '</center></td>';
//                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$subm['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
//                                                echo '</tr>';                                                  
//                                            } 
//                                        }
//                                    }
//                                    else{
//                                        echo '<tr><td colspan="6"><i><center>No new submissions yet.</center></i></td></tr>';
//                                    }
                                    ?>                                  
                              </table>
                              
                            </div>-->
                      
                    <!--THIS PART IS FOR FULL LIST-->  
                    <div class="panel panel-default">
                              <!-- Default panel contents -->
                              <!--<div class="panel-heading">List of Research Proposals</div>-->
                              
                              <!-- Table -->
                              <table class="table table-bordered">
<!--                                  <tr> -->
                                  <th>RESEARCH INFORMATION</th>
<!--                                  <th>DUE DATE</th>
                                  <th><span data-toggle="tooltip" title="Primary Reviewer">PR</span></th>
                                  <th>REVIEW TYPE</th>-->
                                  <th><center>STATUS/ACTION</center></th>
                                  </tr>
                                    <?php
                                    

                                    /// THIS IS FOR PROPOSALS NA KAKAPASOK PA LANG
                                    
                                    
                                    
                                    $forconfirm = $obj->gettingDocToBeConfirmed($userid);
                                    if($forconfirm){
                                        $a = 0;
                                        foreach($forconfirm as $subm){
                                            if(($subm['status_action'] == 11) || $subm['status_action'] == 3){
                                                $a = $a + 1;
                                                
                                                echo '<tr class="success">';
                                                    $uc = strtoupper($subm['prop_ptitle']);
                                                    $strlen = strlen($subm['prop_ptitle']);
                                                    $duedate = 'Due on <strong>'.date("F j, Y",strtotime($subm['rt_duedate'])).'</strong>';
                                                    
                                                    if($subm['primary_reviewer'] == 1){$pr = 'You are a <strong>Primary Reviewer</strong> on this';} else{$pr = '';}
                                                    
                                                    if($subm['rt_id'] == 1){$rt = 'Expedited Review';} else if($subm['rt_id'] == 3){$rt = 'Full Review';} else if($subm['rt_id'] == 2){$rt = 'Exempted Review';}
                                                    
                                                    $getmaxrev = $obj->getmaxreviewer($subm['sub_id']);
                                                    if($getmaxrev > 1){$submit = '<span class="label label-warning"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span></span>';}
                                                    else{$submit = '<span class="label label-success" data-toggle="tooltip" title="NEW"><span class="glyphicon glyphicon-import" aria-hidden="true"></span></span>';}
                                                    
                                                    if ($strlen>80){
                                                        echo'<td>'.$submit.' <a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'...</a><br><small>'.$pr.' '.$rt.' <span class="pull-right">'.$duedate.'</span></small></td>';}
                                                    else {
                                                        echo'<td>'.$submit.' <a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'</a><br><small>'.$pr.' '.$rt.' <span class="pull-right">'.$duedate.'</small></td>'; }
                                                
                                                
                                                echo '<td><center><a href="#" data-href="rev_dashboard_action.php?confirm=1&id='.$subm['sub_id'].'&u='.$subm['phrepuser_id'].'" class="btn btn-primary" data-toggle="modal" data-target="#confirm-accept"><span data-toggle="tooltip" title="CONFIRM" class="glyphicon glyphicon-ok" aria-hidden="true"></span></a> ';
                                                
                                                echo ' <a href="rev_dashboard_action.php?decline=1&id='.$subm['sub_id'].'&u='.$subm['phrepuser_id'].'" class="btn btn-danger"><span data-toggle="tooltip" title="DECLINE" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                                                echo '</center></td>';
                                                
                                                echo '</tr>';                                                  
                                            } 
                                        }
                                    }
//                                    else{
//                                        echo '<tr><td colspan="6"><i><center>No new submissions yet.</center></i></td></tr>';
//                                    }
                                    ?>
                                  
                                  
                                    <?php 
                                    
                                    ////PROPOSALS THAT HAS BEEN CONFIRMED
                                    
                                    $confirmed = $obj->gettingConfirmedDoc($userid);
                                    if($confirmed){
                                        $b = 0;
                                        foreach($confirmed as $subm){
                                            
                              

                                            $where = array("subid" => $subm['sub_id']);
                                            $getproposalnotinpa = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                            if($getproposalnotinpa){
                                                
                                            }
                                            else{
                                                if(($subm['pa_status'] == "onreview")||$subm['pa_status'] == null){
                                                    
                                                    if($subm['evaluation_submitted'] == 0 && $subm['decision'] == 0){
                                                        $b = $b + 1;


                                                        //THIS IS TO GET IF UNFINISHED EVALUTAION
                                                        $wherea = array("sub_id" => $subm['sub_id'], "revid" => $userid);
                                                        $getdone = $obj->fetch_record_with_where("rev_answers", $wherea);
                                                        if($getdone){
                                                            $whereb = array("sub_id" => $sub['sub_id'], "review" => "1", "phrepuser_id" => $userid, "evaluation_submitted" => "1");
                                                            $getconf = $obj->fetch_record_with_where("rev_groups", $whereb);
                                                            if($getconf){
                                                                $colorred = "warning";
                                                            }
                                                            else{
                                                                $colorred = "danger";
                                                            }                                                        
                                                        }
                                                        else{
                                                            $colorred = "warning";
                                                        }
                                                        //THIS IS TO GET IF UNFINISHED EVALUTAION

                                                        $getmaxrev = $obj->getmaxreviewer($subm['sub_id']);
                                                        if($getmaxrev > 1){$submit = '<span class="label label-warning"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span></span>';}
                                                        else{$submit = '<span class="label label-success" data-toggle="tooltip" title="NEW"><span class="glyphicon glyphicon-import" aria-hidden="true"></span></span>';}

                                                        if($subm['primary_reviewer']==1){$pr = 'You are a <strong>Primary Reviewer</strong> on this ';}else{$pr='';}

                                                        $duedate = '<span class="pull-right">Due on <strong>'.date("F j, Y",strtotime($subm['rt_duedate'])).'</strong></span>';

    //                                                  $propstat = $obj->getMaxProposalStatus($subm['sub_id']);  
                                                        if($subm['review'] >= 1){
                                                            echo '<tr class="'.$colorred.'">';
                                                            echo '<td>'.$submit.' ';

                                                                $uc = strtoupper($subm['prop_ptitle']);
                                                                $strlen = strlen($subm['prop_ptitle']);                                                            
                                                                if ($strlen>80){echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'...</a><br><small>'.$pr.' '.$subm['rt_name'].''.$duedate.'</small>';}
                                                                else {echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'</a><br><small>'.$pr.' '.$subm['rt_name'].''.$duedate.'</small>'; }

                                                            echo '</td>';                                           

                                                            echo'<td><center><a href="reviewproposal.php?id='.$subm['sub_id'].'" class="btn btn-success">Review</a>';
                                                            echo '</center></td></tr>'; 
                                                        }
                                                        else{
                                                            $checkRL = $obj->getrlifdonesubmitfiles($subm['sub_id']);
                                                            if ($checkRL > 0) {
                                                                }
                                                            else{
                                                                
                                                                //TO CHECK IF IT IS POST-APPROVAL

                                                                $where2 = array("subid" => $subm['sub_id']);
                                                                $getpostreq = $obj->fetch_record_with_where("proposal_post_approval", $where2);
                                                                if($getpostreq){
                                                                    foreach($getpostreq as $propreq){
                                                                        if($propreq['pa_id']=='1'){$pa="| Ethical Clearance Request";} else if($propreq['pa_id']=='2'){$pa="Amendment Request";}else if($propreq['pa_id']=='3'){$pa="Progress Report Submission";}else{$pa="";}
                                                                    }                                                
                                                                }
                                                                else{ $pa=""; }

                                                                $resub = $subm['review'] - 1;
                                                                echo '<tr class="warning">';

                                                                echo '<td><span class="label label-warning"><span data-toggle="tooltip" title="' . $obj->ordinalize($resub) . ' RESUBMISSION" class="glyphicon glyphicon-repeat" aria-hidden="true"></span></span> ';

                                                                $uc = strtoupper($subm['prop_ptitle']);
                                                                $strlen = strlen($subm['prop_ptitle']);                                                            
                                                                if ($strlen>80){echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'...</a><br><small>'.$pr.' '.$subm['rt_name'].' '.$pa.''.$duedate.'</small>';}
                                                                else {echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'</a><br><small>'.$pr.' '.$subm['rt_name'].' '.$pa.''.$duedate.'</small>'; }

                                                                echo '</td>';

                                                                echo'<td><center><a href="reviewproposal.php?id=' . $subm['sub_id'] . '" class="btn btn-success">Review</a>';
                                                                echo '</center></td></tr>';

                                                            }
                                                        }
                                                    }
                                                }
                                                
                                            }
                                            
                                            
                                            
                                                                                        
                                        }
                                    }                                 
                                  ?>
                                  
                                    <?php 
                                    
                                   ///PROPOSALS THAT HAS BEEN EVALUATED ALREADY
                                    
                                    $confirmed = $obj->gettingConfirmedDoc($userid);
                                    if($confirmed){
                                        $c = 0;
                                        foreach($confirmed as $subm){
                                            
                                            $where = array("subid" => $subm['sub_id']);
                                            $getproposalnotinpa = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                            if($getproposalnotinpa){
                                                
                                            }
                                            else{
                                                if($subm['evaluation_submitted'] == 1){   
                                                        $propstat = $obj->getMaxProposalStatus($subm['sub_id']);    
                                                        $where2 = array("id" => $propstat);
                                                        $getstat = $obj->fetch_record_with_where("proposal_status", $where2);
                                                        if($getstat){
                                                            foreach($getstat as $gt){
                                                                if($subm['primary_reviewer']==1){$pr = 'You were a <strong>Primary Reviewer</strong> on this ';}else{$pr='';}


                                                                if($subm['eval_date'] == '0'){
                                                                    $duedate = '';
                                                                }
                                                                else{
                                                                    $duedate = '<span class="pull-right"><i>Done last '.date("F j, Y",$subm['eval_date']).'</i></span>';
                                                                }



                                                                if($gt['status_action'] == "3"){
                                                                    $c = $c + 1;
                                                                  //TO ADD ANOTHER EVALUATION FORM IF NEEDED
                                                                  $checkform = $obj->checkformsevaluated($subm['sub_id'], $userid);

                                                                      if($checkform >= '2'){
                                                                          $formlink = "";
                                                                      }
                                                                      else{                                                                    
                                                                          $checkreview = $obj->checkreview($subm['sub_id'], $userid);
                                                                          foreach ($checkreview as $cr) {
                                                                              if ($cr['evaluation_type'] == '1') {
                                                                                  $formlink = '<br><a class="btn btn-primary btn-xs" href="evaluationconsent.php?id=' . $subm['sub_id'] . '" role="button"><span data-toggle="tooltip" title="Add Checklist to Informed Consent Form and Process" class="glyphicon glyphicon-file" aria-hidden="true"></span></a>';
                                                                              } 
                                                                              else if($cr['evaluation_type'] == '2'){
                                                                                  $formlink = '<br><a class="btn btn-primary btn-xs" href="evaluationform.php?id='.$subm['sub_id'].'" role="button"><span data-toggle="tooltip" title="Add Reviewer\'s Worksheet" class="glyphicon glyphicon-file" aria-hidden="true"></span></a>';
                                                                              }
                                                                              else {
                                                                                  $formlink = '';
                                                                              }
                                                                          }
                                                                      }
                                                                  //TO ADD ANOTHER EVALUATION FORM IF NEEDED 

                                                                    echo '<tr>';

                                                                    echo '<td>';        

                                                                        $uc = strtoupper($subm['prop_ptitle']);
                                                                        $strlen = strlen($subm['prop_ptitle']);                                                                         
                                                                        if ($strlen>80){echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'...</a><br><small>'.$pr.' '.$subm['rt_name'].$duedate.'</small>';}
                                                                        else {echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'</a><br><small>'.$pr.' '.$subm['rt_name'].$duedate.'</small>'; }

                                                                    echo '</td>';


                                                                  echo '<td><center><small data-toggle="tooltip" title="To be tagged by Secretariat">Queuing</small>'.$formlink.'</center></td>';

                                                                  echo '</tr>'; 
                                                              }


                                                              else if($gt['status_action'] == "5"){
                                                                  $c = $c + 1;
                                                                    echo '<tr>';
                                                                    echo '<td>';

                                                                        $uc = strtoupper($subm['prop_ptitle']);
                                                                        $strlen = strlen($subm['prop_ptitle']);                                                            
                                                                        if ($strlen>80){echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'...</a><br><small>'.$pr.' '.$subm['rt_name'].''.$duedate.'</small>';}
                                                                        else {echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'</a><br><small>'.$pr.' '.$subm['rt_name'].''.$duedate.'</small>'; }

                                                                    echo '</td>';


                                                                  echo '<td><center><small data-toggle="tooltip" title="Waiting for Researcher\'s Resubmission">Waiting for Resubmission</small></center></td>';
                                                                  echo '</tr>'; 
                                                              }


                                                              else if($gt['status_action'] == "14"){
                                                                  $c = $c + 1;
                                                                    echo '<tr>';

                                                                    echo '<td>';

                                                                        $uc = strtoupper($subm['prop_ptitle']);
                                                                        $strlen = strlen($subm['prop_ptitle']);                                                            
                                                                        if ($strlen>80){echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'...</a><br><small>'.$pr.' '.$subm['rt_name'].''.$duedate.'</small>';}
                                                                        else {echo '<a href="proposal_info.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 80).'</a><br><small>'.$pr.' '.$subm['rt_name'].''.$duedate.'</small>'; }

                                                                    echo '</td>';

                                                                  echo '<td><center><small data-toggle="tooltip" title="Waiting for Secretariat to open another evaluation">Researcher Resubmitted</small></center></td>';
                                                                  echo '</tr>'; 
                                                              }
                                                          }
                                                        }                             
                                                }
                                                 
                                            }
                                             
                                        }
                                    }
                                  ?>
                                  
                              </table>
                              
                    </div>  
                    <!--THIS PART IS FOR FULL LIST-->    
                      
                      </div>
                  
                  <div id="review" class="tab-pane fade">
                      
                            <div class="panel panel-danger" id="reviewtype">
                              <!-- Default panel contents -->
                              <div class="panel-heading">On-going Review</div>
                              
                              <!-- Table -->
                              <table class="table table-bordered">
                                  <tr>
                                  <th><center><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></center></th>
                                  <th>TITLE</th>
                                  <th>*PR</th>
                                  <th>REVIEW TYPE</th>
                                  <th>DUE DATE</th>
                                  <th><center>ACTION</center></th>
                                  </tr>
                                  
                                <?php 
                                  $confirmed = $obj->gettingConfirmedDoc($userid);
                                  if($confirmed){
                                      foreach($confirmed as $subm){
                                          if(($subm['pa_status'] == "onreview")||$subm['pa_status'] == null){
                                          if($subm['evaluation_submitted'] == 0 && $subm['decision'] == 0){  
                                              
                                                $getmaxrev = $obj->getmaxreviewer($subm['sub_id']);
                                                if($getmaxrev > 1){$submit = 'Resubmitted';}
                                                else{$submit = 'New Submission';}
                                              
//                                                $propstat = $obj->getMaxProposalStatus($subm['sub_id']);  
                                                if($subm['review'] <= 1){
                                                  echo '<tr>';
                                                  echo '<td><center><span data-toggle="tooltip" title="REVIEWING" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span><center></td>';
                                                  
                                                      $uc = ucwords($subm['prop_ptitle']);
                                                      $strlen = strlen($subm['prop_ptitle']);
                                                      if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...<br><small><span class="label label-default">'.$submit.'</span></small></td>';}
                                                      else {echo'<td>'.substr($uc, 0, 40).'<br><small><span class="label label-default">'.$submit.'</span></small></td>'; }
                                                  echo '<td>';if($subm['primary_reviewer']==1){echo 'Yes';} else{echo 'No';} echo'</td>';
                                                  echo '<td>'.$subm['rt_name'].'</td>';
                                                  echo '<td>'.date("F j, Y",strtotime($subm['rt_duedate'])).'</td>';                                                

                                                  echo'<td><center><a href="reviewproposal.php?id='.$subm['sub_id'].'" class="btn btn-success">View</a>';
                                                  echo '</center></td></tr>'; 
                                                }
                                                else{
                                                    $checkRL = $obj->getrlifdonesubmitfiles($subm['sub_id']);
                                                      if ($checkRL > 0) {
                                                      }
                                                      else{
                                                          $resub = $subm['review'] - 1;
                                                          echo '<tr>';
                                                          echo '<td><center><span class="badge">' . $obj->ordinalize($resub) . ' <span data-toggle="tooltip" title="' . $obj->ordinalize($resub) . ' RESUBMISSION" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></span></center></td>';
                                                          if($subm['pa_id']=='1'){$pa="Ethical Clearance Request";} else if($subm['pa_id']=='2'){$pa="Amendment Request";}else if($subm['pa_id']=='3'){$pa="Progress Report Submission";}else{$pa="";}
                                                          
                                                          $uc = ucwords($subm['prop_ptitle']);
                                                          $strlen = strlen($subm['prop_ptitle']);
                                                          if ($strlen > 50) {
                                                              echo'<td>' . substr($uc, 0, 40) . '...<br><small><span class="label label-default">'.$submit.'</span></small></td>';
                                                          } else {
                                                              echo'<td>' . substr($uc, 0, 40) . '<br><small><span class="label label-default">'.$submit.'</span></small></td>';
                                                          }
                                                          echo '<td>';
                                                          if ($subm['primary_reviewer'] == 1) {
                                                              echo 'Yes';
                                                          } else {
                                                              echo 'No';
                                                          } echo'</td>';
                                                          
                                                          echo '<td>'.$subm['rt_name'].'</td>';
                                                          echo '<td>' . date("F j, Y", strtotime($subm['rt_duedate'])) . '</td>';

                                                          echo'<td><center><a href="reviewproposal.php?id=' . $subm['sub_id'] . '" class="btn btn-success">View</a>';
                                                          echo '</center></td></tr>';

                                                      }
                                              }
                                            }
                                              
                                          }
                                            
                                      }
                                  }
//                                  
                                  ?>
                                  
                              </table>
                            </div>
                              <small>*PR - Primary Reviewer</small> <hr>
                             <div class="panel panel-success" id="reviewtype">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Evaluated</div>

                              
                              <!-- Table -->
                              <table class="table table-bordered">
                                  <tr>
                                  <th>ID</th>
                                  <th>TITLE</th>
                                  <th>*PR</th>
                                  <th>EVALUATED</th>
                                  <th><center>STATUS</center></th>
                                  <th><center><span data-toggle="tooltip" title="Add Evaluation" class="glyphicon glyphicon-plus" aria-hidden="true"></span></center></th>
                                  <th><center><span data-toggle="tooltip" title="Proposal Information" class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></center></th>
                                  </tr>
                                  <?php 
                                  $confirmed = $obj->gettingConfirmedDoc($userid);
                                  if($confirmed){
                                      foreach($confirmed as $subm){
                                          if($subm['evaluation_submitted'] == 1){   
                                                $propstat = $obj->getMaxProposalStatus($subm['sub_id']);    
                                                $where2 = array("id" => $propstat);
                                                $getstat = $obj->fetch_record_with_where("proposal_status", $where2);
                                                if($getstat){
                                                    foreach($getstat as $gt){
                                                        if($gt['status_action'] == "3"){
                                                            echo '<tr>';
                                                            echo '<td>'.$subm['code'].'</td>';
                                                                $uc = ucwords($subm['prop_ptitle']);
                                                                $strlen = strlen($subm['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }
                                                            echo '<td>';if($subm['primary_reviewer']==1){echo 'Yes';} else{echo 'No';} echo'</td>';
                                                            
                                                            if($subm['eval_date'] !='0'){
                                                            echo '<td>'.date("F d, Y", substr($subm['eval_date'], 0, 10)).'</td>';                                                                  
                                                            }else{echo '<td>Not Reviewing</td>';}                                     

                                                            echo '<td><center>Queuing</center></td>';
                                                            
                                                            //TO ADD ANOTHER EVALUATION FORM IF NEEDED
                                                            $checkform = $obj->checkformsevaluated($subm['sub_id'], $userid);
                                                                                                                                
                                                                if($checkform >= '2'){
                                                                    $formlink = "-";
                                                                }
                                                                else{                                                                    
                                                                    $checkreview = $obj->checkreview($subm['sub_id'], $userid);
                                                                    foreach ($checkreview as $cr) {
                                                                        if ($cr['evaluation_type'] == '1') {
                                                                            $formlink = '<a class="btn btn-primary btn-xs" href="evaluationconsent.php?id=' . $subm['sub_id'] . '" role="button"><span data-toggle="tooltip" title="Add Checklist to Informed Consent Form and Process" class="glyphicon glyphicon-file" aria-hidden="true"></span></a>';
                                                                        } 
                                                                        else if($cr['evaluation_type'] == '2'){
                                                                            $formlink = '<a class="btn btn-primary btn-xs" href="evaluationform.php?id='.$subm['sub_id'].'" role="button"><span data-toggle="tooltip" title="Add Reviewer\'s Worksheet" class="glyphicon glyphicon-file" aria-hidden="true"></span></a>';
                                                                        }
                                                                        else {
                                                                            $formlink = '-';
                                                                        }
                                                                    }
                                                                }
                                                            //TO ADD ANOTHER EVALUATION FORM IF NEEDED                                                           
                                                            
                                                            echo '<td><center>'.$formlink.'</center></td>';
                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$subm['sub_id'].'" role="button"><span data-toggle="tooltip" title="Click for Full Information" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                            echo '</tr>'; 
                                                        }
                                                        else if($gt['status_action'] == "5"){
                                                            echo '<tr>';
                                                            echo '<td>'.$subm['code'].'</td>';
                                                                $uc = ucwords($subm['prop_ptitle']);
                                                                $strlen = strlen($subm['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }
                                                            echo '<td>';if($subm['primary_reviewer']==1){echo 'Yes';} else{echo 'No';} echo'</td>';
                                                            
                                                            if($subm['eval_date'] !='0'){
                                                            echo '<td>'.date("F d, Y", substr($subm['eval_date'], 0, 10)).'</td>';                                                                  
                                                            }else{echo '<td>Not Reviewing</td>';}                                     

                                                            echo '<td><center>Waiting for Resubmission</center></td>';
                                                            echo '<td><center>-</center></td>';
                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$subm['sub_id'].'" role="button"><span data-toggle="tooltip" title="Click for Full Information" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                            echo '</tr>'; 
                                                        }
                                                        else if($gt['status_action'] == "14"){
                                                            echo '<tr>';
                                                            echo '<td>'.$subm['code'].'</td>';
                                                                $uc = ucwords($subm['prop_ptitle']);
                                                                $strlen = strlen($subm['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }
                                                            echo '<td>';if($subm['primary_reviewer']==1){echo 'Yes';} else{echo 'No';} echo'</td>';
                                                            
                                                            if($subm['eval_date'] !='0'){
                                                            echo '<td>'.date("F d, Y", substr($subm['eval_date'], 0, 10)).'</td>';                                                                  
                                                            }else{echo '<td>Not Reviewing</td>';}                                     

                                                            echo '<td><center>Researcher Resubmitted</center></td>';
                                                            echo '<td><center>-</center></td>';
                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$subm['sub_id'].'" role="button"><span data-toggle="tooltip" title="Click for Full Information" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                            echo '</tr>'; 
                                                        }
                                                    }
                                                }                             
                                          } 
                                      }
                                  }
                                  ?>
                                  
                                </table>
                            </div>
                  
                  </div>
<!--                  
                  <div id="resubmitted" class="tab-pane fade">
                      panel here
                  </div>-->
                  <div id="approved" class="tab-pane fade">
                      <h2>APPROVED</h2>
                            <div class="panel panel-success" id="reviewtype">

                              
                               <!--Table--> 
                              <table class="table table-hover table-bordered">
                                  <tr class="success">
                                  <th>RESEARCH INFORMATION</th>
                                  <th><center><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></center></th>
                                  </tr>
                                  
                                  <?php 
                                  $confirmed = $obj->gettingConfirmedDoc($userid);
                                  if($confirmed){
                                      $a = 0;
                                      foreach($confirmed as $subm){
                                          if($subm['evaluation_submitted'] == 1){                                                
                                                $propstat = $obj->getMaxProposalStatus($subm['sub_id']); 
                                                $where2 = array("id" => $propstat);
                                                $getstat = $obj->fetch_record_with_where("proposal_status", $where2);
                                                if($getstat){
                                                    foreach($getstat as $gt){

                                                        if($gt['status_action'] == "6"){
                                                            
                                                            $a = $a + 1;
                                                            echo '<tr>';
                                                            
                                                            $maxps = $obj->getmaxpspersidsa($subm['sub_id'], "6");
                                                            $where3 = array("id" => $maxps);
                                                            $getps = $obj->fetch_record_with_where("proposal_status", $where3);
                                                            foreach($getps as $ps){$adate = date("F d, Y", strtotime($ps['status_date']));}
                                                            
                                                            if($subm['primary_reviewer']==1){$pm = 'You are a <i>Primary Reviewer </i> | ';} else{$pm = '';}
                                                            
                                                            //GETTING THE RESEARCHER
                                                            $where = array("id" => $subm['username']);
                                                            $getun = $obj->fetch_record_with_where("phrepuser", $where);
                                                            foreach($getun as $un){$rname = ucwords($un['title'].' '.$un['fname'].' '.$un['mname'].' '.$un['lname']);}
                                                            
                                                            //GETTING THE RESEARCHER
                                                            
                                                            $uc = ucwords($subm['prop_ptitle']);
                                                            $strlen = strlen($subm['prop_ptitle']);
                                                                if ($strlen>100){
                                                                    $tit = substr($uc, 0, 100).'...';}
                                                                else {
                                                                    $tit = $uc;}
                                                            
                                                            echo '<td>';
                                                            echo '<strong>'.strtoupper($tit).'</strong>';
                                                            echo '<br>'.$rname;
                                                            echo '<span class="pull-right">'.$pm.' Approved last '.$adate.'</span>';
                                                            echo '</td>';
                                                                
//                                                            echo '<td>';if($subm['primary_reviewer']==1){echo 'Yes';} else{echo 'No';} echo'</td>';
                                                            
//                                                            echo '<td>'.date("F d, Y", strtotime($gt['status_date'])).'</td>';                                                

                                                            
                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$subm['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                            echo '</tr>'; 
                                                        }
                                                        else if($gt['status_action'] == "12"){
                                                            $a = $a + 1;

                                                            $maxps = $obj->getmaxpspersidsa($subm['sub_id'], "12");
                                                            $where3 = array("id" => $maxps);
                                                            $getps = $obj->fetch_record_with_where("proposal_status", $where3);
                                                            foreach($getps as $ps){$adate = date("F d, Y", strtotime($ps['status_date']));}

                                                            if($subm['primary_reviewer']==1){$pm = 'You are a <i>Primary Reviewer </i> | ';} else{$pm = '';}
                                                            
                                                            //GETTING THE RESEARCHER
                                                            $where = array("id" => $subm['username']);
                                                            $getun = $obj->fetch_record_with_where("phrepuser", $where);
                                                            foreach($getun as $un){$rname = ucwords($un['title'].' '.$un['fname'].' '.$un['mname'].' '.$un['lname']);}
                                                            
                                                            $uc = ucwords($subm['prop_ptitle']);
                                                            $strlen = strlen($subm['prop_ptitle']);
                                                                if ($strlen>100){
                                                                    $tit = substr($uc, 0, 100).'...';}
                                                                else {
                                                                    $tit = $uc;}

                                                            echo '<tr>';
                                                            
                                                            echo '<td>';
                                                            echo '<strong>'.strtoupper($tit).'</strong>';
                                                            echo '<br>'.$rname;
                                                            echo '<span class="pull-right">'.$pm.' Approved last '.$adate.'</span>';
                                                            echo '</td>';     

                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$subm['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                            echo '</tr>'; 
                                                        }
                                                    }
                                                }                                       
                                          } 
                                      }
                                      
                                      if($a == 0){
                                          echo '<tr>';
                                          echo '<td colspan="2" class="text-center"><i>No approved researches yet.</i></td>';
                                          echo '</tr>';
                                      }
                                  }
                                  ?>
                                  
                              </table>
                            </div>
                      
                            <hr>
                            <h2>POST-APPROVAL</h2>
                            <div class="panel panel-default">
                                <!-- Table -->
                                <table class="table table-bordered">
                                    <tr>
                                        <th>POST-APPROVAL REQUEST</th>
                                        <th class="text-center">TYPE OF REQUEST</th>
                                        <th class="text-center">STATUS/ACTION</th>
                                    </tr>
                                    
                                    <?php
                                    $allprop = $obj->getallpostapprovalrequest();
                                    $b = 0;
                                        foreach($allprop as $prop){
                                            if($prop['dp'] != null){
                                                
//                                                GETTING TYPE OF REQUEST                                                
                                                $req = $obj->getmaxpropapp($prop['sub_id']);
                                                $where = array("pid" => $req);
                                                
                                                $requ = $obj->fetch_record_with_where("proposal_post_approval", $where);                                                
                                                foreach($requ as $r){
                                                    $pid = $r['pa_id'];
                                                    $where2 = array("id" => $r['pa_request']);
                                                    $rtime = $obj->fetch_record_with_where("post_approval_reqtype",$where2);
                                                    foreach($rtime as $rti){$timee = $rti['par_desc'];}
                                                }              
                                                //GETTING TYPE OF REQUEST
                                                
                                                //GETTING MAX POST-APPROVAL REQUEST
                                                $maxpid = $obj->getmaxpostrequest($prop['sub_id']);
                                                //GETTING MAX POST-APPROVAL REQUEST
                                                
                                                //GETTING MAX REVIEW PER PID AND SUBID
                                                $maxrevp = $obj->getmaxrevperpidandsubid($prop['sub_id'], $maxpid);
                                                //GETTING MAX REVIEW PER PID AND SUBID
//                                                
//                                                echo $prop['sub_id'].'-'.$maxpid.'-'.$maxrevp.'<br>';
                                                
                                                //GETTING SPECIFIC REQUEST FOR CONFIRMATION OR FOR REVIEW
                                                $where = array("ppa_id" => $maxpid, "sub_id" => $prop['sub_id'], "review" => $maxrevp, "phrepuser_id" => $userid);
                                                $getallassigned = $obj->fetch_record_with_where("rev_groupspa", $where);
                                                
                                                foreach($getallassigned as $assigned){
                                                    
                                                    if($assigned['primary_reviewer'] == 1){$vv = "| You are a Primary Reviewer";}
                                                    
                                                    //GETTING THE PROPOSAL INFORMATION
                                                    $where2 = array("sub_id" => $prop['sub_id']);
                                                    $proposal = $obj->fetch_record_with_where("proposal", $where2);
                                                    foreach($proposal as $propp){
                                                        
                                                        $uc = ucwords($propp['prop_ptitle']);
                                                        $strlen = strlen($propp['prop_ptitle']);
                                                        if($strlen > 70){
                                                            $title = '<strong>'.strtoupper(substr($uc, 0, 70)).'...</strong>';
                                                        }
                                                        else{
                                                            $title = '<strong>'.strtoupper(substr($uc, 0, 70)).'</strong>';
                                                        }
                                                        
                                                        
                                                        
                                                        $where3 = array("id" => $propp['username']);
                                                        $getname = $obj->fetch_record_with_where("phrepuser", $where3);
                                                        foreach($getname as $n){$tname = $n['title'];$fname = $n['fname'];$mname = $n['mname'];$lname = $n['lname'];}
                                                    }
                                                    //GETTING THE PROPOSAL INFORMATION
                                                    
                                                    if($assigned['confirmation'] == 0){   
                                                        $maxxid = $obj->getMaxProposalStatus($prop['sub_id']);
                                                        
                                                        $where11 = array("id" => $maxxid);
                                                        $getproposals = $obj->fetch_record_with_where("proposal_status", $where11);
                                                        foreach($getproposals as $pppp){
                                                            if($pppp['status_action'] == '3'){
                                                                $b = $b + 1;
                                                                echo '<tr>';
                                                                echo '<td>';
                                                                echo $title;
                                                                echo '<p>By: '.$tname.' '.$fname.' '.$lname.' '.$vv.'</p>';
                                                                echo '</td>';
                                                                echo '<td class="text-center">'.$timee.'</td>';
                                                                echo '<td class="text-center">';
                                                                echo '<a href="#" data-href="rev_dashboard_action.php?upd=1&p='.$maxpid.'&r='.$maxrevp.'&s='.$prop['sub_id'].'&u='.$userid.'" class="btn btn-primary" data-toggle="modal" data-target="#confirm-postapprovalrequest">Confirm</a>';
                                                                echo '</td>';
                                                                echo '</tr>';                                                                
                                                            }
                                                        }  
                                                    }
                                                    else if($assigned['confirmation'] == 1){   
                                                        
                                                        if($assigned['evaluation_submitted'] == 0){
                                                            
                                                            $maxxid = $obj->getMaxProposalStatus($prop['sub_id']);

                                                            $where11 = array("id" => $maxxid);
                                                            $getproposals = $obj->fetch_record_with_where("proposal_status", $where11);
                                                            foreach($getproposals as $pppp){
                                                                if($pppp['status_action'] == '3'){                                                            
                                                                    $b = $b + 1;
                                                                    echo '<tr>';
                                                                    echo '<td>';
                                                                    echo $title;
                                                                    echo '<p>By: '.$tname.' '.$fname.' '.$lname.' '.$vv.'</p>';
                                                                    echo '</td>';
                                                                    echo '<td class="text-center">'.$timee.'</td>';
                                                                    echo '<td class="text-center">';
                                                                    echo '<a class="btn btn-default" href="reviewproposalpostapproval.php?id='.$prop['sub_id'].'" role="button">Review</a>';
                                                                    echo '</td>';
                                                                    echo '</tr>';                                                                     
                                                                    }
                                                                }                                                        
                                                        }
                                                        else{
                                                            
                                                            $getmaxid = $obj->getMaxProposalStatus($assigned['sub_id']);
                                                            $where = array("id" => $getmaxid);
                                                            $statf = $obj->fetch_record_with_where("proposal_status", $where);
                                                            foreach($statf as $fstat){
                                                                if(($fstat['status_action'] == "6")||($fstat['status_action'] == "23")){
                                                                    
                                                                }
                                                                else if($fstat['status_action'] == "27"){
                                                                    $b = $b + 1;
                                                                    
                                                                    
                                                                    //CHECKING IF DOCUMENTS ARE ALREADY SUBMITTED
                                                                    $ppaid = $obj->getmaxpropapp($assigned['sub_id']);
                                                                    $savesitedecision = array(
                                                                        "subid" => $assigned['sub_id'],
                                                                        "ppaid" => $ppaid,
                                                                        "username" => $userid
                                                                    );
                                                                    $savedsitedecision = $obj->fetch_record_with_where("sitevisit_decision", $savesitedecision);
                                                                    if($savedsitedecision){
                                                                            echo '<tr class="success">';
                                                                            echo '<td>';
                                                                            echo '<span class="label label-default">Site Visit</span> | '.$title;
                                                                            echo '<p>By: '.$tname.' '.$fname.' '.$lname.' '.$vv.'</p>';
                                                                            echo '</td>';
                                                                            echo '<td class="text-center">'.$timee.'</td>';
                                                                            echo '<td class="text-center">';                                                                       
                                                                            echo '<a class="btn btn-success" href="rev_sitevisit.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> VIEW</a>';    
                                                                            echo '</td>';
                                                                            echo '</tr>';  
                                                                    }
                                                                    else{
                                                                    
                                                                            echo '<tr>';
                                                                            echo '<td>';
                                                                            echo '<span class="label label-default">Site Visit</span> | '.$title;
                                                                            echo '<p>By: '.$tname.' '.$fname.' '.$lname.' '.$vv.'</p>';
                                                                            echo '</td>';
                                                                            echo '<td class="text-center">'.$timee.'</td>';
                                                                            echo '<td class="text-center">';                                                                       
                                                                            echo '<a class="btn btn-warning" href="rev_sitevisit.php?id='.$prop['sub_id'].'" role="button">Report</a>';    
                                                                            echo '</td>';
                                                                            echo '</tr>'; 
                                                                        

                                                                    } 
                                                                }
                                                                else{
                                                                    $b = $b + 1;
                                                                    echo '<tr class="success">';
                                                                    echo '<td>';
                                                                    echo $title;
                                                                    echo '<p>By: '.$tname.' '.$fname.' '.$lname.' '.$vv.'</p>';
                                                                    echo '</td>';
                                                                    echo '<td class="text-center">'.$timee.'</td>';
                                                                    echo '<td class="text-center">';
                                                                    echo '<span class="glyphicon glyphicon-ok" data-toggle="tooltip" title="Evaluation Submitted" aria-hidden="true"></span>';
                                                                    echo '</td>';
                                                                    echo '</tr>';  
                                                                    
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                
                                                
                                                
                                            } 
                                           
                                        }
                                        
                                        if ($b == 0) {
                                            echo '<tr>';
                                            echo '<td colspan="3" class="text-center">';
                                            echo '<i>No Post-Approval Request Has Been Submitted.</i>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
?>
                                    
                                </table>
                            </div>
                            
                            <hr>
                            <h2>COMPLETED</h2>
                            <div class="panel panel-default">
                                <!-- Table -->
                                <table class="table table-bordered">
                                    <tr>
                                        <th>POST-APPROVAL REQUEST</th>
                                        <th class="text-center">TYPE OF REQUEST</th>
                                        <th class="text-center">STATUS/ACTION</th>
                                    </tr>
                                    
                                    <?php
                                    $allprop = $obj->getallpostapprovalrequest();
                                    $c = 0;
                                        foreach($allprop as $prop){
                                            if($prop['dp'] != null){
                                                
//                                                GETTING TYPE OF REQUEST                                                
                                                $req = $obj->getmaxpropapp($prop['sub_id']);
                                                $where = array("pid" => $req);
                                                
                                                $requ = $obj->fetch_record_with_where("proposal_post_approval", $where);                                                
                                                foreach($requ as $r){
                                                    $pid = $r['pa_id'];
                                                    $where2 = array("id" => $r['pa_request']);
                                                    $rtime = $obj->fetch_record_with_where("post_approval_reqtype",$where2);
                                                    foreach($rtime as $rti){$timee = $rti['par_desc'];}
                                                }              
                                                //GETTING TYPE OF REQUEST
                                                
                                                //GETTING MAX POST-APPROVAL REQUEST
                                                $maxpid = $obj->getmaxpostrequest($prop['sub_id']);
                                                //GETTING MAX POST-APPROVAL REQUEST
                                                
                                                //GETTING MAX REVIEW PER PID AND SUBID
                                                $maxrevp = $obj->getmaxrevperpidandsubid($prop['sub_id'], $maxpid);
                                                //GETTING MAX REVIEW PER PID AND SUBID
//                                                
//                                                echo $prop['sub_id'].'-'.$maxpid.'-'.$maxrevp.'<br>';
                                                
                                                //GETTING SPECIFIC REQUEST FOR CONFIRMATION OR FOR REVIEW
                                                $where = array("ppa_id" => $maxpid, "sub_id" => $prop['sub_id'], "review" => $maxrevp, "phrepuser_id" => $userid);
                                                $getallassigned = $obj->fetch_record_with_where("rev_groupspa", $where);
                                                
                                                foreach($getallassigned as $assigned){
                                                    
                                                    if($assigned['primary_reviewer'] == 1){$vv = "| You are a Primary Reviewer";}
                                                    
                                                    //GETTING THE PROPOSAL INFORMATION
                                                    $where2 = array("sub_id" => $prop['sub_id']);
                                                    $proposal = $obj->fetch_record_with_where("proposal", $where2);
                                                    foreach($proposal as $propp){
                                                        
                                                        $uc = ucwords($propp['prop_ptitle']);
                                                        $strlen = strlen($propp['prop_ptitle']);
                                                        if($strlen > 70){
                                                            $title = '<strong>'.strtoupper(substr($uc, 0, 70)).'...</strong>';
                                                        }
                                                        else{
                                                            $title = '<strong>'.strtoupper(substr($uc, 0, 70)).'</strong>';
                                                        }
                                                        
                                                        
                                                        
                                                        $where3 = array("id" => $propp['username']);
                                                        $getname = $obj->fetch_record_with_where("phrepuser", $where3);
                                                        foreach($getname as $n){$tname = $n['title'];$fname = $n['fname'];$mname = $n['mname'];$lname = $n['lname'];}
                                                    }
                                                    //GETTING THE PROPOSAL INFORMATION
                                                    
                                                    if(($assigned['confirmation'] == 1)&&($assigned['evaluation_submitted'] == 1)){  
                                                        
                                                            $getmaxid = $obj->getMaxProposalStatus($assigned['sub_id']);
                                                            $where = array("id" => $getmaxid);
                                                            $statf = $obj->fetch_record_with_where("proposal_status", $where);
                                                            
                                                            foreach($statf as $fstat){
                                                                if($fstat['status_action'] == "23"){
                                                                    $c = $c + 1;
                                                                    echo '<tr class="success">';
                                                                    echo '<td>';
                                                                    echo $title;
                                                                    echo '<p>By: '.$tname.' '.$fname.' '.$lname.' '.$vv.'</p>';
                                                                    echo '</td>';
                                                                    echo '<td class="text-center">'.$timee.'</td>';
                                                                    echo '<td class="text-center">';
                                                                    echo '<span class="glyphicon glyphicon-ok" data-toggle="tooltip" title="Evaluation Submitted" aria-hidden="true"></span>';
                                                                    echo '</td>';
                                                                    echo '</tr>'; 
                                                                    
                                                                }
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

                <div id="all" class="tab-pane fade">
                    <div class="panel panel-primary">
                        <!-- Default panel contents -->
                        <div class="panel-heading">All Proposal Submitted</div>

                    <!-- Table -->
                    <table class="table table-bordered table-condensed table-striped">
                        <tr>
                            <th>CODE</th>
                            <th>TITLE</th>
                            <th>SUBMITTED</th>
                            <th>STATUS</th>
                            <th><center><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></center></th>
                        </tr>
                        
                            <?php
                            $getall = $obj->getallprop($userid);
                            if($getall){
                                foreach($getall as $ga){
                                    $where = array("subid" => $ga['sub_id'], "pa_status" => "onreview");
                                    $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                    if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "Ethical Clearance Request";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "Request for Amendment";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "Progress Report Submission";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp="";}
                                                
                                    echo '<tr>';
                                    echo '<td>'.$ga['code'].'<br><small>'.$pp.'</small></td>';
                                    
                                    $uc = ucwords($ga['prop_ptitle']);
                                    $strlen = strlen($ga['prop_ptitle']);
                                    if ($strlen>50){echo'<td><a href="rev_proposalview.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 40).'...</a></td>';}
                                    else {echo'<td><a href="rev_proposalview.php?id='.$subm['sub_id'].'">'.substr($uc, 0, 40).'</a></td>'; }
                                    
                                    echo '<td>'.date("M. d, Y",strtotime($ga['date_submitted'])).'</td>'; 
                                    echo '<td>'.$ga['action_name'].'</td>';
                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$ga['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        
                    </table>
                  </div>
                </div>
                
                <div id="reviewers" class="tab-pane fade">
                              <div class="row">
                                  <div class="col-lg-6">
                                    <?php
                                    $where = array("phrepuser_id" => $userid);
                                    $getrec = $obj->fetch_record_with_where("rec_groups", $where);
                                    foreach($getrec as $recc){$rec = $recc['rec_list_id'];}

                                    ?>
                                      <div class="panel panel-default">
                                        <div class="panel-body">
                                            <h2><center>Reviewer's Assignment</center></h2>

                                        </div>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="text-center"># of Assigned Proposals</th>
                                                </tr>
                                                <?php
                                                
                                                $where = array("rec_list_id" => $rec);
                                                $getrevs = $obj->fetch_record_with_where("rec_groups", $where);
                                                foreach($getrevs as $revs){
                                                    echo "<tr>";
                                                    echo "<td>";
                                                    $where = array("id" => $revs['phrepuser_id']);
                                                    $getname = $obj->fetch_record_with_where("phrepuser", $where);
                                                    foreach($getname as $n){
                                                        echo $n['title'].' '.$n['fname'].' '.$n['mname'].' '.$n['lname'];
                                                    }
                                                    echo "</td>";
                                                    echo "<td class='text-center'>";
                                                    echo "<a href='#' class='view_review' uid='".$revs['phrepuser_id']."'>".$obj->getcountprop($revs['phrepuser_id'])."</a>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }

                                                ?>


                                            </table>
                                    </div>
                                  </div>
                              </div>
                              
                            
                        </div> 
                          
          </div>
          
        <div class="col-lg-2">
        <?php include "rev_dashboard_pane.php"; ?>          
        </div>
              
      </div> <!--row-->
    </div>
<?php
function addition() {
    $GLOBALS['d'] = $GLOBALS['a'] + $GLOBALS['b'] + $GLOBALS['c'];
}



?>          
          
<div id="dataModals" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><center>Information</center></h4>
            </div>
            <div class="modal-body" id="proposal_details">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
    </form>
  </body>
  
</html>

<div id="dataModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><center>Information</center></h4>
            </div>
            <div class="modal-body" id="proposal_detail">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php 
include_once("$currDir/footer.php");
?>


<script>
 $(document).on('click', '.view_review', function(){
//  $('#dataModal').modal();
  var userid = $(this).attr("uid");
  $.ajax({
   url:"selectassignedprop.php",
   method:"POST",
   data:{uid:userid},
   success:function(data){
    $('#proposal_details').html(data);
    $('#dataModals').modal('show');
   }
  });
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

<script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var subid = $(this).attr("id");
  var idu = $(this).attr("idu");
  $.ajax({
   url:"selectproposalforrev.php",
   method:"POST",
   data:{sid:subid, idu:idu},
   success:function(data){
    $('#proposal_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
</script>

<div class="modal fade" id="confirm-accept" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Reminder!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to accept this proposal? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-accept').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

var u = document.getElementById("userid").value;
$("#idp").attr("href", "rev_info.php?id="+u);

</script>

<div class="modal fade" id="confirm-postapprovalrequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div class="modal-body">
                Do you accept this request? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-postapprovalrequest').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>