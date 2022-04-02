<?php
include "sec_dashboard_action.php";
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
	if(!in_array($mi['group'], array('Admins', 'Secretary'))){
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
      } .tr_link{cursor:pointer}
      .font-rev {font-size: 8pt;}
      .prime-submitted{font-size: 8pt; color: green; font-weight: bolder;}
      .underline {text-decoration: underline;}
      
    @media screen and (max-width: 1020px) {
            .nav {
                padding-left:2px;
                padding-right:2px;
            }
            .nav li {
                display:block !important;
                width:100%;
                margin:0px;
            }
            .nav li.active {
                border-bottom:1px solid #ddd!important;
                margin: 0px;
            }
        }
    
    </style>
  </head>
  <body>
      <form action = "sec_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
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
          
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          
          <div class="row">
              <div class="col-lg-12">
                  <h2>
                      <?php
                      $where = array("secretary" => $userid);
                      $getRECname = $obj->fetch_record("rec_list", $where);
                      foreach($getRECname as $rec){
                          echo $rec['erc_name'];
                      }
                      ?>
                  </h2>
              </div>
          </div>
          <hr>          
          <div class="row">
              <div class="col-lg-2">
                  <h2>Dashboard</h2>
                  <?php include("$currDir/main/sec_dashboard_pane.php"); ?>
                  
              </div>
              
              <div class="col-lg-10 tab-content">

                  <ul class="nav nav-tabs" id="myTab">
                      <li class="active"><a href="#new">New <span class="badge"><?php echo $obj->getCountBadge("addedfile", $userid); ?></span></a></li>
                      <li><a href="#incomplete">Incomplete <span class="badge"><?php echo $obj->getCountBadge("inc", $userid); ?></span></a></li>
                      <li><a href="#review">On Review <span class="badge"><?php echo $obj->getCountBadge("onrev", $userid); ?></span></a></li>
                      <li><a href="#resubmitted">For Resubmission <span class="badge"><?php echo $obj->getCountBadge("resub", $userid); ?></span></a></li>
                      <li><a href="#approved">Approved <span class="badge"><?php echo $obj->getCountBadge("appr", $userid); ?></span></a></li>
                      <li><a href="#postapproval">Post-Approval Request <span class="badge"></span></a></li>

                       

                      <li class="pull-right"><a href="#reviewers" data-toggle="tooltip" title="SUMMARY"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a></li>
                  </ul>
                  <br>
                  
<!--                          start of NEW  -->
                          <div id="new" class="tab-pane fade in active">
                            <div class="panel panel-success">
                              <!-- Default panel contents -->
                              <div class="panel-heading">New Submitted Proposal(s)</div>

                              
                              <!-- Table -->
                              <table class="table table-bordered table-condensed">
                                  <tr>
                                  <th>ID</th>
                                  <th>Submitted</th>
                                  <th>Title</th>
                                  <th>Classification</th>
                                  <th><center>Action</center></th>
                                  </tr>
                                  <?php

                                  $myrow = $obj->gettingNewlySubmitted($userid);
                                  $num = count($myrow);
                                  if ($num > 0) {
                                      foreach ($myrow as $row) {
                                         if($row['rc_id']=='1'){
                                          echo '<tr>
                                                    <td>'.$row['code'].'</td>                                                        
                                                    <td>'.date("F j, Y",strtotime($row['date_submitted'])).'</td>';
                                                    $strlen = strlen($row['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($row['prop_ptitle'], 0, 50).'...</td>';}
                                                    else echo'<td>'.substr($row['prop_ptitle'], 0, 50).'</td>'; 
                                          echo '<td>Institution Based</td>';
                                          echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-warning btn-xs" href="viewproposal.php?id='.$row['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                          #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$row['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                          echo '<a data-toggle="tooltip" title="DECLINE" class="btn btn-danger btn-xs" href="sec_decline.php?id='.$row['sub_id'].'" role="button"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a>';
                                          echo '</center></td>';
                                          echo '</tr>';
                                             
                                         }
                                         else if($row['rc_id']=='2'){
                                          echo '<tr>
                                                    <td>'.$row['code'].'</td>                                                        
                                                    <td>'.date("F j, Y",strtotime($row['date_submitted'])).'</td>';
                                                    $strlen = strlen($row['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($row['prop_ptitle'], 0, 50).'...</td>';}
                                                    else echo'<td>'.substr($row['prop_ptitle'], 0, 50).'</td>';   
                                          echo '<td>Community Based</td>';
                                          echo '<td><center><a href="viewproposal.php?id='.$row['sub_id'].'"><button type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button></a> | ';
                                          #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$row['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                          echo '<a data-toggle="tooltip" title="DECLINE" class="btn btn-danger btn-xs" href="sec_decline.php?id='.$row['sub_id'].'" role="button"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a>';
                                          echo '</center></td>';    
                                          echo '</tr>';
                                             
                                         }
                                         else{
                                          echo '<tr>
                                                    <td>'.$row['code'].'</td>                                                        
                                                    <td>'.date("F j, Y",strtotime($row['date_submitted'])).'</td>';
                                                    $strlen = strlen($row['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($row['prop_ptitle'], 0, 50).'...</td>';}
                                                    else echo'<td>'.substr($row['prop_ptitle'], 0, 50).'</td>';   
                                          echo '<td>No Classification</td>';
                                          echo '<td><center><a href="viewproposal.php?id='.$row['sub_id'].'"><button type="button" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button></a> | ';
                                          #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$row['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                          echo '<a data-toggle="tooltip" title="DECLINE" class="btn btn-danger btn-xs" href="sec_decline.php?id='.$row['sub_id'].'" role="button"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a>';
                                          echo '</center></td>';    
                                          echo '</tr>';
                                             
                                         }
                                      }
                                  }
                                  else{
                                    echo '<tr><td colspan="5"><i><center>No new submissions yet.</center></i></td></tr>';
                                    }
                                  ?>
                                  
                              </table>
                            </div>
                              
                              
                                  <div class="panel panel-success">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">Additional Files Added</div>


                                    <!-- Table -->
                                    <table class="table table-bordered">
                                        <tr>
                                        <th>ID</th>
                                        <th>SUBMITTED</th>
                                        <th>TITLE</th>
                                        <th><center>ACTION</center></th>
                                        
                                        <?php 
                                        $getmaxsec = $obj->getMaxPerSec($userid);//kwaon sah ang maximum id sa proposal_status na table
                                        if($getmaxsec){
                                            foreach($getmaxsec as $maxs){
                                                $getresubmit = $obj->getproposalview($maxs['maxid']);//kwaon ang sub_id sa proposal linked with maxid sa proposal_status
                                                if($getresubmit>0){
                                                    foreach($getresubmit as $prop){
                                                        if($prop['status_action']=='0'){//kung 0 ang status action sa proposal_status
                                                            echo '<tr>
                                                                      <td>'.$prop['code'].'</td>                                                        
                                                                      <td>'.date("F j, Y",strtotime($prop['date_submitted'])).'</td>';
                                                                      $strlen = strlen($prop['prop_ptitle']);
                                                                      if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...</td>';}
                                                                      else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'</td>';   
                                                            echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-warning btn-xs" href="viewproposal.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';

                                                            // to view main info
                                                            echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                            
                                                            echo '<a data-toggle="tooltip" title="DECLINE" class="btn btn-danger btn-xs" href="sec_decline.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a>';
                                                            echo '</tr>';                                                            
                                                        }
//                                                    else{//kung dili 0 ang status action sa proposal status
//                                                         echo '<tr><td colspan="4"><i><center>No resubmissions of incomplete files yet.</center></i></td></tr>';
//                                                    }
                                                    }
                                                }
                                                else{//kung dili 0 ang status action sa proposal status
                                                         echo '<tr><td colspan="4"><i><center>No resubmissions of incomplete files yet.</center></i></td></tr>';
                                                    }
                                                    
                                            }
                                        }
                                        ?>
                                    </table>
                                  </div>
                              
                              
                                <div class="panel panel-success">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">Resubmitted</div>


                                    <!-- Table -->
                                    <table class="table table-bordered">
                                        <tr>
                                        <th>ID</th>
                                        <th>SUBMITTED</th>
                                        <th>TITLE</th>
                                        <th><center>ACTION</center></th>
                                        
                                        <?php 
                                        $getmaxsec = $obj->getMaxPerSec($userid);//kwaon sah ang maximum id sa proposal_status na table
                                        if($getmaxsec){
                                            foreach($getmaxsec as $maxs){
                                                $getresubmit = $obj->getproposalview($maxs['maxid']);//kwaon ang sub_id sa proposal linked with maxid sa proposal_status
                                                if($getresubmit>0){
                                                    foreach($getresubmit as $prop){
                                                        if($prop['status_action']=='14'){//kung 0 ang status action sa proposal_status
                                                            echo '<tr>
                                                                      <td>'.$prop['code'].'</td>                                                        
                                                                      <td>'.date("F j, Y",strtotime($prop['date_submitted'])).'</td>';
                                                                      $strlen = strlen($prop['prop_ptitle']);
                                                                      if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...</td>';}
                                                                      else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'</td>';   
                                                            echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-warning btn-xs" href="viewproposal.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                                            #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                            echo '<a data-toggle="tooltip" title="DECLINE" class="btn btn-danger btn-xs" href="sec_decline.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        if($prop['status_action']=='26'){//kung 0 ang status action sa proposal_status
                                                            echo '<tr>
                                                                      <td>'.$prop['code'].'</td>                                                        
                                                                      <td>'.date("F j, Y",strtotime($prop['date_submitted'])).'</td>';
                                                                      $strlen = strlen($prop['prop_ptitle']);
                                                                      if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<span class="pull-right label label-primary"><i>Appeal</i></span></td>';}
                                                                      else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<span class="pull-right label label-primary"><i>Appeal</i></span></td>';   
                                                            echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-warning btn-xs" href="viewproposal.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                                            #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                            echo '<a data-toggle="tooltip" title="DECLINE" class="btn btn-danger btn-xs" href="sec_decline.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a>';
                                                            echo '</tr>';                                                            
                                                        }
//                                                    else{//kung dili 0 ang status action sa proposal status
//                                                         echo '<tr><td colspan="4"><i><center>No resubmissions of incomplete files yet.</center></i></td></tr>';
//                                                    }
                                                    }
                                                }
                                                else{//kung dili 0 ang status action sa proposal status
                                                         echo '<tr><td colspan="4"><i><center>No resubmissions of incomplete files yet.</center></i></td></tr>';
                                                    }
                                                    
                                            }
                                        }
                                        ?>
                                    </table>
                                  </div>
                              
                              
                          </div>  

<!--                          end of NEW  --> 
                          
                          <!--start of incomplete-->
                          <div id="incomplete" class="tab-pane fade">
                              <div class="panel panel-success">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">Incomplete Proposals</div>


                                    <!-- Table -->
                                    <table class="table table-bordered">
                                        <tr>
                                        <th>ID</th>
                                        <th>SUBMITTED</th>
                                        <th>TITLE</th>
                                        <th><center>STATUS</center></th>
                                        <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                        
                                        <?php 
                                        $getmaxsec = $obj->getMaxPerSec($userid);//kwaon sah ang maximum id sa proposal_status na table
                                        if($getmaxsec){
                                            foreach($getmaxsec as $maxs){
                                                $getresubmit = $obj->getproposalview($maxs['maxid']);//kwaon ang sub_id sa proposal linked with maxid sa proposal_status
                                                
                                                if($getresubmit>0){
                                                    foreach($getresubmit as $prop){
                                                        $wheres = array("sub_id" => $prop['sub_id']);
                                                        $getcode = $obj->fetch_record_with_where("submission", $wheres);
                                                        foreach($getcode as $gcc){
                                                            if($gcc['coding'] == ''){
                                                                $wheress = array("sub_id" => $prop['sub_id']);
                                                                $getcodep = $obj->fetch_record_with_where("proposal", $wheress);
                                                                foreach($getcodep as $gpr){
                                                                    $cod = $gpr['code'];
                                                                }
                                                            }
                                                            else{
                                                                $cod = $gcc['coding'];
                                                            }
                                                        }
                                                        
                                                        
                                                        if($prop['status_action']=='2'){//kung 0 ang status action sa proposal_status
                                                            echo '<tr>
                                                                      <td>'.$cod.'</td>                                                        
                                                                      <td>'.date("F j, Y",strtotime($prop['date_submitted'])).'</td>';
                                                                      $strlen = strlen($prop['prop_ptitle']);
                                                                      if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...</td>';}
                                                                      else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'</td>';   
                                                            echo'     <td><center>Waiting to be completed</td>  
                                                                      <td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>
                                                                  </tr>';                                                            
                                                        }
                                                    else{//kung dili 0 ang status action sa proposal status
//                                                         echo '<tr><td colspan="4"><i><center>No resubmissions of incomplete files yet.</center></i></td></tr>';
                                                    }
                                                    }
                                                }
                                                    
                                            }
                                        }
                                        ?>
                                    </table>
                                  </div>
                          </div>
                          <!--start of incomplete-->
                          
                    <!--start of review-->
                           
                    <div id="review" class="tab-pane fade">
                            <div class="panel panel-info">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">Type of Review Assignment</div>


                                    <!-- Table -->
                                    <table class="table table-bordered tr_link">
                                        <tr>
                                        <th>ID</th>
                                        <th>SUBMITTED</th>
                                        <th>TITLE</th>
                                        <th>SUGGESTION</th>
                                        <th><center>REVIEW TYPE</center></th>
                                        <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                        
                                        <?php 
                                        $getmaxsec = $obj->getMaxPerSec($userid);//kwaon sah ang maximum id sa proposal_status na table
                                        if($getmaxsec){
                                            foreach($getmaxsec as $maxs){
                                                $getresubmit = $obj->getproposalview($maxs['maxid']);//kwaon ang sub_id sa proposal linked with maxid sa proposal_status
                                                if($getresubmit>0){
                                                    foreach($getresubmit as $prop){
                                                        if($prop['status_action']=='1'){//kung 1 ang status action sa proposal_status
                                                            echo '<tr>';
                                                                echo '<td class="view_data" id="'.$prop['sub_id'].'">'.$prop['coding'].'</td>';                                                        
                                                                
                                                                echo '<td class="view_data" id="'.$prop['sub_id'].'">'.date("F j, Y",strtotime($prop['date_submitted'])).'</td>';
                                                                
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                        if ($strlen>50){echo'<td class="view_data" id="'.$prop['sub_id'].'">'.substr($prop['prop_ptitle'], 0, 50).'...</td>';}
                                                                        else {echo'<td class="view_data" id="'.$prop['sub_id'].'">'.substr($prop['prop_ptitle'], 0, 50).'</td>'; }  
                                                                
                                                                if($prop['chair_suggest']=="1"){echo '<td class="view_data" id="'.$prop['sub_id'].'">Expedited Review</td>'; }  
                                                                else if($prop['chair_suggest']=="2"){echo '<td class="view_data" id="'.$prop['sub_id'].'">Exempted from Review</td>'; } 
                                                                else if($prop['chair_suggest']=="3"){echo '<td class="view_data" id="'.$prop['sub_id'].'">Full Review</td>'; }
                                                                else{echo '<td class="view_data" id="'.$prop['sub_id'].'">None</td>';}
                                                                
                                                                $where = array("sub_id" => $prop['sub_id']);    
                                                                $naaytypeofreview = $obj->fetch_record_with_where("review_type", $where);
                                                                $pila = count($naaytypeofreview);
                                                                        if($pila>0){
                                                                            $id1 = "rt_id"; $id2 = "id";
                                                                            $where1 = array("sub_id" => $prop['sub_id']);
                                                                            $nametype = $obj->fetch_record_with_where_and_join("review_type", "review_type_list", $id1, $id2, $where1);
                                                                            $counttype = count($nametype);
                                                                                if($counttype>0){
                                                                                    foreach($nametype as $rowtype){
                                                                                        if($rowtype['rt_id']=='1'){
                                                                                            echo'<td><center>
                                                                                            <a href="sec_expedited_confirm.php?id='.$prop['sub_id'].'" class="btn btn-primary btn-xs">Confirm '.$rowtype['rt_name'].'?</a>
                                                                                            <a href="sec_dashboard_action.php?undo=1&id='.$prop['sub_id'].'" class="btn btn-info btn-xs">Undo</a>
                                                                                            </center></td>';
                                                                                        }
                                                                                        else if($rowtype['rt_id']=='2'){
                                                                                            echo'<td><center>
                                                                                            <a href="sec_exempt_initial.php?id='.$prop['sub_id'].'" class="btn btn-warning btn-xs">Confirm '.$rowtype['rt_name'].'?</a>
                                                                                            <a href="sec_dashboard_action.php?undo=1&id='.$prop['sub_id'].'" class="btn btn-info btn-xs">Undo</a>
                                                                                            </center></td>';
                                                                                        }
                                                                                        else if($rowtype['rt_id']=='3'){
                                                                                            echo'<td><center>
                                                                                            <a href="sec_full_confirm.php?id='.$prop['sub_id'].'" class="btn btn-danger btn-xs">Confirm '.$rowtype['rt_name'].'?</a>
                                                                                            <a href="sec_dashboard_action.php?undo=1&id='.$prop['sub_id'].'" class="btn btn-info btn-xs">Undo</a>
                                                                                            </center></td>';
                                                                                        }
                                                                                    }
                                                                                }   
                                                                        } 
                                                                        else{
                                                                            echo'<td><center>';
                                                                            echo '<a href="#" data-href="sec_dashboard_action.php?expedited=1&id='.$prop['sub_id'].'&u='.$userid.'" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#confirm-expedited"><span data-toggle="tooltip" title="Expedited">EXP</span></a>
                                                                                  <a href="#" data-href="sec_dashboard_action.php?exempted=1&id='.$prop['sub_id'].'&u='.$userid.'" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#confirm-exempted"><span data-toggle="tooltip" title="Exempted">EXM</span></a>
                                                                                  <a href="#" data-href="sec_dashboard_action.php?full=1&id='.$prop['sub_id'].'&u='.$userid.'" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirm-full"><span data-toggle="tooltip" title="Full">FUL</span></a>';
                                                                            echo'</center></td>';//1    
                                                                        }
                                                                        echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                                        echo '</tr>';
                                                        }
//                                                        else{//kung dili 1 ang status action sa proposal status
//                                                         echo '<tr><td colspan="4"><i><center>No proposal to assign for its review type yet.</center></i></td></tr>';}
                                                    }
                                                }
                                                else{//kung dili 1 ang status action sa proposal status
                                                         echo '<tr><td colspan="4"><i><center>No proposal to assign for its review type yet.</center></i></td></tr>';}
                                                    
                                                
                                                    
                                            }
                                        }
                                        ?>
                                    </table>
                                  </div>
                            
                            <hr>
                            
                            <div class="panel panel-primary">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Unconfirmed for Review</div>

                              <table class="table table-bordered tr_link">
                                  <tr>
                                  <th>ID</th>
                                  <th>REVIEW TYPE</th>
                                  <th>TITLE</th>
                                  <th>RESPONDED</th>
                                  <th><center><span class="glyphicon glyphicon-file" aria-hidden="true" data-toggle="tooltip" title="VIEW PARTIAL REVIEW"></span></center></th>
                                  <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                  </tr>
                                  
                                  <?php 
                                  $gatherUnconfirmed = $obj->gatherUnconfirmed($userid);
                                  if($gatherUnconfirmed == ''){
                                      echo '<tr><td colspan="4"><center>No proposal has been reviewed yet.</center></td></tr>';
                                  }
                                  else{
                                    foreach($gatherUnconfirmed as $prop){
                                        if(($prop['status_action']=='3') || ($prop['status_action']=='11')){//kung 3 ang status action sa proposal_status

                                            $wherert = array("sub_id" => $prop['sub_id']);
                                                $get_rev = $obj->fetch_record_with_where("review_type", $wherert);
                                                if($get_rev){
                                                    foreach($get_rev as $revt){
                                                        $whereps = array("sub_id" => $prop['sub_id']);
                                                        $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                        foreach ($getcodd as $cc) {
                                                            if ($cc['coding'] == "") {
                                                                $wherepp = array("sub_id" => $prop['sub_id']);
                                                                $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                                foreach ($getcoddd as $vv) {
                                                                    $code = $vv['code'];
                                                                }
                                                            } else {
                                                                $code = $cc['coding'];
                                                            }
                                                        }

                                                        $maxrev = $obj->getmaxreviewer($prop['sub_id']); 
                                                        if($revt['rt_id'] == '1'){//Expedited
                                                            
                                                            $unconfirm = $obj->getifconfirmed($prop['sub_id'], $maxrev);  //ADDITIONAL PARA MAKITA ANG GI-REREVIEW
                                                            if ($unconfirm != 0) {      
                                                                //ADDITIONAL PARA MAKITA ANG GI-REREVIEW                                                                  
                                                                
                                                                echo '<tr class="view_data" id="'.$prop['sub_id'].'">'; //1
                                                                echo '<td>' . $code . '</td>';

                                                                echo '<td>' . $prop['rt_name'] . '</td>';

                                                                $uc = ucwords($prop['prop_ptitle']);
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen > 50) {
                                                                    echo'<td>' . substr($uc, 0, 40) . '...</td>';
                                                                } else {
                                                                    echo'<td>' . substr($uc, 0, 40) . '</td>';
                                                                }

                                                                $maxrev = $obj->getmaxreviewer($prop['sub_id']);

                                                                $where = array("sub_id" => $prop['sub_id'], "review" => $maxrev);
                                                                $naaytypeofreview = $obj->fetch_record_with_where("rev_groups", $where);
                                                                $pila = count($naaytypeofreview);

                                                                if ($pila > 0) {
                                                                    echo '<td>';
                                                                    $where2 = array("sub_id" => $prop['sub_id'], "confirmation" => "1", "review" => $maxrev);
                                                                    $naaytypeofreview1 = $obj->fetch_record_with_where("rev_groups", $where2);
                                                                    $pila1 = count($naaytypeofreview1);
                                                                    echo '<b>' . $pila1 . '</b>';
                                                                    echo' / <b>' . $pila . '</b> reviewers</td>';
                                                                } else {

                                                                }
                                                                //BYPASS to get reviews if others are not yet submitting
                                                                echo '<td>';
                                                                    $getbypassrev = $obj->getpassrev($prop['sub_id']);
                                                                    if($getbypassrev){
                                                                        echo '<center><a class="btn btn-default btn-xs" href="sec_bypassreviews.php?i='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center>';
                                                                    }    
                                                                    else{
                                                                        echo '';
                                                                    }
                                                                
                                                                
                                                                echo '</td>';
                                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                                //BYPASS to get reviews if others are not yet submitting
                                                                echo '</tr>';

                                                            }//ADDITIONAL PARA MAKITA ANG GI-REREVIEW   
                                                        }
                                                        else if($revt['rt_id'] == '3'){//FULL BOARD                                                               
                                                            $unconfirm = $obj->getifconfirmedfull($prop['sub_id'], $maxrev);  //ADDITIONAL PARA MAKITA ANG GI-REREVIEW
                                                            if ($unconfirm != 0) {                                   //ADDITIONAL PARA MAKITA ANG GI-REREVIEW    
                                                                echo '<tr>'; //1
                                                                echo '<td class="view_data" id="'.$prop['sub_id'].'">' . $code . '</td>';

                                                                echo '<td class="view_data" id="'.$prop['sub_id'].'">' . $prop['rt_name'] . '</td>';

                                                                $uc = ucwords($prop['prop_ptitle']);
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen > 50) {
                                                                    echo'<td class="view_data" id="'.$prop['sub_id'].'">' . substr($uc, 0, 40) . '...</td>';
                                                                } else {
                                                                    echo'<td class="view_data" id="'.$prop['sub_id'].'">' . substr($uc, 0, 40) . '</td>';
                                                                }

                                                                $maxrev = $obj->getmaxreviewer($prop['sub_id']);

                                                                $where = array("sub_id" => $prop['sub_id'], "review" => $maxrev, "primary_reviewer" => "1");
                                                                $naaytypeofreview = $obj->fetch_record_with_where("rev_groups", $where);
                                                                $pila = count($naaytypeofreview);

                                                                if ($pila > 0) {
                                                                    echo '<td class="view_data" id="'.$prop['sub_id'].'">';
                                                                    $where2 = array("sub_id" => $prop['sub_id'], "confirmation" => "1", "review" => $maxrev, "primary_reviewer" => "1");
                                                                    $naaytypeofreview1 = $obj->fetch_record_with_where("rev_groups", $where2);
                                                                    $pila1 = count($naaytypeofreview1);
                                                                    echo '<b>' . $pila1 . '</b>';
                                                                    echo' / <b>' . $pila . '</b> reviewers</td>';
                                                                } else {

                                                                }
                                                                //BYPASS to get reviews if others are not yet submitting
                                                                echo '<td>';
                                                                    $getbypassrev = $obj->getpassrev($prop['sub_id']);
                                                                    if($getbypassrev){
                                                                        echo '<center><a class="btn btn-default btn-xs" href="sec_bypassreviews.php?i='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center>';
                                                                    }    
                                                                    else{
                                                                        echo '';
                                                                    }
                                                                
                                                                
                                                                echo '</td>';
                                                                //BYPASS to get reviews if others are not yet submitting
                                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                                echo '</tr>';

                                                            }//ADDITIONAL PARA MAKITA ANG GI-REREVIEW   
                                                        }
                                                        else{}
                                                    }                                                        
                                                }
                                                        
                                            
                                                            
                                        }
                                    }
                                      
                                  }
                                  
                                  ?>
                              </table>
                              
                            </div>
                            <hr>
                           
                            <div class="panel panel-danger">
                              <!-- Default panel contents -->
                              <div class="panel-heading">On-Going Review</div>

                              <table class="table table-bordered tr_link">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  <th>ID</th>
                                  <th>REVIEW TYPE</th>
                                  <th>TITLE</th>
                                  <th><center>DUE DATE</center></th>
                                  <th><center><span data-toggle="tooltip" title="No. of DECLINE" class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></center></th>
                                  <th><center>ACTION</center></th>
                                  <th><center><span class="glyphicon glyphicon-file" aria-hidden="true" data-toggle="tooltip" title="VIEW PARTIAL REVIEW"></span></center></th>
                                  <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                  </tr>
                                    <?php
                                    $allprop = $obj->getlistproposal($userid);
                                    foreach($allprop as $p){
//                                        echo $p['sid'].'<br>';
                                        if($p['dp'] == null){ //I FILTER DRI ANG DILI POST APPROVAL
                                            
                                        
                                        $wherert = array("sub_id" => $p['sid']);
                                        $get_rev = $obj->fetch_record_with_where("review_type", $wherert);
                                        foreach($get_rev as $revt){
                                            if ($revt['rt_id'] == '1') {//Expedited
                                                $getitsmax = $obj->getmaxid($revt['sub_id']);
                                                        if($getitsmax == ''){
              //                                              echo '<tr><td colspan="7"><center>No proposal has been reviewed yet.</center></td></tr>';
                                                        }
                                                        else{
                                                              foreach($getitsmax as $pp){
                                                                  if(($pp['status_action'] == 3)||($pp['status_action'] == 11)){
                                                                      $ongoing = $obj->ongoingreview($pp['sub_id']);
                                                                      foreach($ongoing as $og){
                                                                          if($og['cid'] == 0){
                                                                              $submitted = $obj->evaluated($pp['sub_id']);
                                                                              foreach($submitted as $submit){
                                                                                 
                                                                                  if($submit['mited'] == 0){
                                                                                      $fetchprop = $obj->fetchproposal($pp['sub_id']);
                                                                                        foreach($fetchprop as $prop){
                                                                                            
                                                                                            //CODE
                                                                                            $whereps = array("sub_id" => $prop['sub_id']);
                                                                                            $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                                                            foreach ($getcodd as $cc) {
                                                                                                if ($cc['coding'] == "") {
                                                                                                    $wherepp = array("sub_id" => $prop['sub_id']);
                                                                                                    $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                                                                    foreach ($getcoddd as $vv) {
                                                                                                        $code = $vv['code'];
                                                                                                    }
                                                                                                } else {
                                                                                                    $code = $cc['coding'];
                                                                                                }
                                                                                            }
                                                                                            //CODE
                                                                                            
                                                                                            $where = array("subid" => $prop['sub_id'], "pa_status" => "onreview");
                                                                                            $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                                                            if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "Ethical Clearance Request";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "Request for Amendment";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "Progress Report Submission";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp = "";}

                                                                                            echo '<tr class="success" data-href="sec_tagging.php?id='.$prop['sub_id'].'" data-toggle="tooltip" title="READY FOR TAGGING, CLICK HERE">';//1
                                                                                            echo '<td><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>'; 
                                                                                            echo '<td>'.$code.'<br><small>'.$pp.'</small></td>';  

                                                                                            echo '<td>'.$prop['rt_name'].'</td>';

                                                                                            $uc = ucwords($prop['prop_ptitle']);
                                                                                            $strlen = strlen($prop['prop_ptitle']);
                                                                                            if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                                                            else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  

                                                                                            $d = strtotime($prop['rt_duedate']);
                                                                                            echo'<td>'.date("F j, Y",$d).'</td>'; 

                                                                                            echo '<td><center>';
                                                                                            echo $getdeclined = $obj->getDeclined($prop['sub_id']);                                                            
                                                                                            echo '</center></td>';

                                                                                            echo '<td><center>-</center></td>';
                                                                                            
                                                                                            //BYPASS to get reviews if others are not yet submitting
                                                                                            echo '<td>';
                                                                                            echo '</td>';
                                                                                            //BYPASS to get reviews if others are not yet submitting
                                                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                                                            echo '</tr>';

                                                                                        }                                                                  

                                                                                  }
                                                                                  else{


                                                                                    $fetchprop = $obj->fetchproposal($pp['sub_id']);
                                                                                        foreach($fetchprop as $prop){
                                                                                            
                                                                                            //CODE
                                                                                            $whereps = array("sub_id" => $prop['sub_id']);
                                                                                            $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                                                            foreach ($getcodd as $cc) {
                                                                                                if ($cc['coding'] == "") {
                                                                                                    $wherepp = array("sub_id" => $prop['sub_id']);
                                                                                                    $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                                                                    foreach ($getcoddd as $vv) {
                                                                                                        $code = $vv['code'];
                                                                                                    }
                                                                                                } else {
                                                                                                    $code = $cc['coding'];
                                                                                                }
                                                                                            }
                                                                                            //CODE
                                                                                            
                                                                                            $where = array("subid" => $prop['sub_id'], "pa_status" => "onreview");
                                                                                            $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                                                            if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "Ethical Clearance Request";$g = $gp['pa_id'];}else if($gp['pa_id']=='2'){$pp = "Request for Amendment";$g = $gp['pa_id'];}else if($gp['pa_id']=='3'){$pp = "Progress Report Submission";$g = $gp['pa_id'];}else{$pp="";}}}else{$pp = "";}

                                                                                          echo '<tr>';//1
                                                                                          echo '<td class="view_data" id="'.$prop['sub_id'].'"><span class="glyphicon glyphicon-book" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR EVALUATION"></span></td>';   

                                                                                          echo '<td class="view_data" id="'.$prop['sub_id'].'">'.$code.'<br><small>'.$pp.'</small></td>'; 

                                                                                          echo '<td class="view_data" id="'.$prop['sub_id'].'">'.$prop['rt_name'].'</td>';

                                                                                              $uc = ucwords($prop['prop_ptitle']);
                                                                                              $strlen = strlen($prop['prop_ptitle']);
                                                                                              if ($strlen>50){echo'<td class="view_data" id="'.$prop['sub_id'].'">'.substr($uc, 0, 40).'...</td>';}
                                                                                              else {echo'<td class="view_data" id="'.$prop['sub_id'].'">'.substr($uc, 0, 40).'</td>'; }  

                                                                                          $d = strtotime($prop['rt_duedate']);
                                                                                          echo'<td class="view_data" id="'.$prop['sub_id'].'">'.date("F j, Y",$d).'</td>'; 

                                                                                          echo '<td class="view_data" id="'.$prop['sub_id'].'"><center>';
                                                                                          echo $getdeclined = $obj->getDeclined($prop['sub_id']);                                                            
                                                                                          echo '</center></td>';

                                                                                          echo '<td><center><a class="btn btn-primary" href="sec_add_reviewer.php?id='.$prop['sub_id'].'" role="button">Add Primary Reviewer<br><small>[Exp]</small></a></center></td>';
                                                                                            
                                                                                            //BYPASS to get reviews if others are not yet submitting
                                                                                            echo '<td>';
                                                                                                $getbypassrev = $obj->getpassrev($prop['sub_id']);
                                                                                                if($getbypassrev){
                                                                                                    echo '<center><a class="btn btn-default btn-xs" href="sec_bypassreviews.php?i='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center>';
                                                                                                }    
                                                                                                else{
                                                                                                    echo '<center>-</center>';
                                                                                                }


                                                                                            echo '</td>';
                                                                                            //BYPASS to get reviews if others are not yet submitting
                                                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';

                                                                                          echo '</tr>';

                                                                                        }     

                                                                                  }
                                                                              }                                                          
                                                                          }
                                                                      }
                                                                  }
                                                                  else if(($pp['status_action'] == 15)){
                                                                    
                                                                    //CODE
                                                                    $whereps = array("sub_id" => $pp['sub_id']);
                                                                    $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                                    foreach ($getcodd as $cc) {
                                                                        if ($cc['coding'] == "") {
                                                                            $wherepp = array("sub_id" => $pp['sub_id']);
                                                                            $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                                            foreach ($getcoddd as $vv) {
                                                                                $code = $cc['coding'];
                                                                            }
                                                                        } else {
                                                                            $code = $cc['coding'];
                                                                            $wherert = $obj->fetch_record_with_where('review_type', $whereps);
                                                                            foreach($wherert as $rtt){
                                                                                $wherertname = array('id' => $rtt['rt_id']);
                                                                                $getrtname = $obj->fetch_record_with_where('review_type_list', $wherertname);
                                                                                foreach($getrtname as $rtnammee)
                                                                                {
                                                                                    $rtttnamee = $rtnammee['rt_name'];
                                                                                }
                                                                            }

                                                                            
                                                                            $wherepp = array("sub_id" => $pp['sub_id']);
                                                                            $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                                            foreach ($getcoddd as $vv) {
                                                                                $proposaltitle = $vv['prop_ptitle'];
                                                                            }

                                                                        }
                                                                    }
                                                                    //CODE

                                                                    echo '<tr data-toggle="tooltip" title="WAITING FOR ICC LETTER">'; //1
                                                                    echo '<td><span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span></td>';
                                                                    echo '<td>' . $code . '<br><small>Waiting FPIC Approval</small></td>';

                                                                    echo '<td>' . $rtttnamee . '</td>';

                                                                    $uc = ucwords($proposaltitle);
                                                                    $strlen = strlen($proposaltitle);
                                                                    if ($strlen > 50) {
                                                                        echo'<td>' . substr($uc, 0, 40) . '...</td>';
                                                                    } else {
                                                                        echo'<td>' . substr($uc, 0, 40) . '</td>';
                                                                    }

                                                                    echo'<td><center>-</center></td>';

                                                                    echo '<td><center>';
                                                                    echo $getdeclined = $obj->getDeclined($pp['sub_id']);
                                                                    echo '</center></td>';

                                                                    echo '<td><center>-</center></td>';
                                                                    
                                                                    //BYPASS to get reviews if others are not yet submitting
                                                                    echo '<td>';
                                                                    echo '</td>';
                                                                    //BYPASS to get reviews if others are not yet submitting
                                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$pp['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';

                                                                    echo '</tr>';
                                                                }
                                                              }

                                                        }
                                            }
                                            else if($revt['rt_id'] == '3') {//Full
                                                
                                                $getitsmax = $obj->getmaxid($revt['sub_id']);                                                
                                                
                                                        if($getitsmax == ''){
              //                                              echo '<tr><td colspan="7"><center>No proposal has been reviewed yet.</center></td></tr>';
                                                        }
                                                        else{
                                                              foreach($getitsmax as $pp){
                                                                  if($pp['status_action'] == 3){
                                                                      $ongoing = $obj->ongoingreviewfull($pp['sub_id']);
                                                                      foreach($ongoing as $og){
                                                                          if($og['cid'] == 0){
                                                                              $submitted = $obj->evaluatedfull($pp['sub_id']);
                                                                              foreach($submitted as $submit){
                                                                                  if($submit['mited'] == 0){
                                                                                      $fetchprop = $obj->fetchproposal($pp['sub_id']);
                                                                                        foreach($fetchprop as $prop){
                                                                                            
                                                                                            //CODE
                                                                                            $whereps = array("sub_id" => $prop['sub_id']);
                                                                                            $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                                                            foreach ($getcodd as $cc) {
                                                                                                if ($cc['coding'] == "") {
                                                                                                    $wherepp = array("sub_id" => $prop['sub_id']);
                                                                                                    $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                                                                    foreach ($getcoddd as $vv) {
                                                                                                        $code = $vv['code'];
                                                                                                    }
                                                                                                } else {
                                                                                                    $code = $cc['coding'];
                                                                                                }
                                                                                            }
                                                                                            //CODE
                                                                                            
                                                                                            echo '<tr class="success" data-href="sec_tagging.php?id='.$prop['sub_id'].'" data-toggle="tooltip" title="READY FOR TAGGING, CLICK HERE">';//1
                                                                                            echo '<td><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>'; 
                                                                                            echo '<td>'.$code.'</td>';  

                                                                                            echo '<td>'.$prop['rt_name'].'</td>';

                                                                                            $uc = ucwords($prop['prop_ptitle']);
                                                                                            $strlen = strlen($prop['prop_ptitle']);
                                                                                            if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                                                            else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  

                                                                                            $d = strtotime($prop['rt_duedate']);
                                                                                            echo'<td>'.date("F j, Y",$d).'</td>'; 

                                                                                            echo '<td><center>';
                                                                                            echo $getdeclined = $obj->getDeclined($prop['sub_id']);                                                            
                                                                                            echo '</center></td>';

                                                                                            echo '<td><center>-</center></td>';
                                                                    
                                                                                            //BYPASS to get reviews if others are not yet submitting
                                                                                            echo '<td>';
                                                                                            echo '</td>';
                                                                                            //BYPASS to get reviews if others are not yet submitting
                                                                                            
                                                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';

                                                                                            echo '</tr>';

                                                                                        }                                                                  

                                                                                  }
                                                                                  else{


                                                                                    $fetchprop = $obj->fetchproposal($pp['sub_id']);
                                                                                        foreach($fetchprop as $prop){
                                                                                            
                                                                                            //CODE
                                                                                            $whereps = array("sub_id" => $prop['sub_id']);
                                                                                            $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                                                            foreach ($getcodd as $cc) {
                                                                                                if ($cc['coding'] == "") {
                                                                                                    $wherepp = array("sub_id" => $prop['sub_id']);
                                                                                                    $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                                                                    foreach ($getcoddd as $vv) {
                                                                                                        $code = $vv['code'];
                                                                                                    }
                                                                                                } else {
                                                                                                    $code = $cc['coding'];
                                                                                                }
                                                                                            }
                                                                                            //CODE
                                                                                            
                                                                                          echo '<tr>';//1
                                                                                          echo '<td class="view_data" id="'.$prop['sub_id'].'"><span class="glyphicon glyphicon-book" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR EVALUATION"></span></td>';   

                                                                                          echo '<td class="view_data" id="'.$prop['sub_id'].'">'.$code.'</td>'; 

                                                                                          echo '<td class="view_data" id="'.$prop['sub_id'].'">'.$prop['rt_name'].'</td>';

                                                                                              $uc = ucwords($prop['prop_ptitle']);
                                                                                              $strlen = strlen($prop['prop_ptitle']);
                                                                                              if ($strlen>50){echo'<td class="view_data" id="'.$prop['sub_id'].'">'.substr($uc, 0, 40).'...</td>';}
                                                                                              else {echo'<td class="view_data" id="'.$prop['sub_id'].'">'.substr($uc, 0, 40).'</td>'; }  

                                                                                          $d = strtotime($prop['rt_duedate']);
                                                                                          echo'<td class="view_data" id="'.$prop['sub_id'].'">'.date("F j, Y",$d).'</td>'; 

                                                                                          echo '<td class="view_data" id="'.$prop['sub_id'].'"><center>';
                                                                                          echo $getdeclined = $obj->getDeclined($prop['sub_id']);                                                            
                                                                                          echo '</center></td>';

                                                                                          echo '<td><center><a class="btn btn-primary" href="sec_add_reviewer.php?id='.$prop['sub_id'].'" role="button">Add Primary Reviewer<br><small>[Full]</small></a></center></td>';
                                                                    
                                                                                            //BYPASS to get reviews if others are not yet submitting
                                                                                            echo '<td>';
                                                                                            
                                                                                            $getbypassrev = $obj->getpassrev($prop['sub_id']);
                                                                                            if ($getbypassrev) {
                                                                                                echo '<center><a class="btn btn-default btn-xs" href="sec_bypassreviews.php?i=' . $prop['sub_id'] . '" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center>';
                                                                                            } else {
                                                                                                echo '<center>-</center>';
                                                                                            }


                                                                                            echo '</td>';
                                                                                            //BYPASS to get reviews if others are not yet submitting  
                                                                                            echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';

                                                                                          echo '</tr>';

                                                                                        }     

                                                                                  }
                                                                              }                                                          
                                                                          }
                                                                      }
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
                            </div>
                          <!--end of review-->

                          
                          <!--start of resubmitted-->
                          <div id="resubmitted" class="tab-pane fade">
                              
                              <div class="panel panel-warning">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Resubmissions</div>

                              <table class="table table-bordered tr_link">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  <th>ID</th>
                                  <th>REVIEW TYPE</th>
                                  <th>TITLE</th>
                                  <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                  </tr>
                                  <?php 
                                    $getmaxdoc = $obj->getMaxDocumentStat($userid);
                                    if($getmaxdoc == ''){
                                        echo '<tr><td colspan="4"><center>No proposal has been reviewed yet.</center></td></tr>';
                                    }
                                    else{
                                        foreach($getmaxdoc as $prop){
                                            if($prop['status_action'] == '5'){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                echo '<tr>';
                                                echo '<td><span class="glyphicon glyphicon-hourglass" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR RESUBMISSION"></span></td>';   
                                                echo '<td>'.$code.'</td>';

                                                echo '<td>'.$prop['rt_name'].'</td>';

                                                $uc = ucwords($prop['prop_ptitle']);
                                                $strlen = strlen($prop['prop_ptitle']);
                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                
                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                echo '<a class="btn btn-primary btn-xs" href="sec_bypassreviews.php?i=' . $prop['sub_id'] . '" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center></td>';
                                                echo '</tr>';
                                            }
                                            if($prop['status_action'] == '13'){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                echo '<tr>';
                                                echo '<td><span class="glyphicon glyphicon-hourglass" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR RESUBMISSION"></span></td>';   
                                                $where = array("subid" => $prop['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "Ethical Clearance Request";}else if($gp['pa_id']=='2'){$pp = "Request for Amendment";}else if($gp['pa_id']=='3'){$pp = "Progress Report Submission";}else{$pp="";}}}
                                                echo '<td>'.$code.'<br><small>'.$pp.'</small></td>';

                                                echo '<td>'.$prop['rt_name'].'</td>';

                                                $uc = ucwords($prop['prop_ptitle']);
                                                $strlen = strlen($prop['prop_ptitle']);
                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                
                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                echo '<a class="btn btn-primary btn-xs" href="sec_bypassreviews.php?i=' . $prop['sub_id'] . '" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center></td>';

                                                echo '</tr>';
                                            }
                                            if($prop['status_action'] == '15'){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                echo '<tr>';
                                                echo '<td><span class="glyphicon glyphicon-hourglass" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR RESUBMISSION"></span></td>';   
                                                $where = array("subid" => $prop['sub_id'], "pa_status" => "onreview");
                                                $getpost = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                if($getpost){foreach($getpost as $gp){if($gp['pa_id']=='1'){$pp = "Ethical Clearance Request";}else if($gp['pa_id']=='2'){$pp = "Request for Amendment";}else if($gp['pa_id']=='3'){$pp = "Progress Report Submission";}else{$pp="";}}}
                                                echo '<td>'.$code.'<br><small>'.$pp.'</small></td>';

                                                echo '<td>'.$prop['rt_name'].'</td>';

                                                $uc = ucwords($prop['prop_ptitle']);
                                                $strlen = strlen($prop['prop_ptitle']);
                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                
                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                echo '<a class="btn btn-primary btn-xs" href="sec_bypassreviews.php?i=' . $prop['sub_id'] . '" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center></td>';

                                                echo '</tr>';
                                            }
                                        }                                        
                                    }
                                  ?>
                              </table>
                            </div>
                              
                            <hr>
                              
                            <div class="panel panel-default">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Withdrawn</div>

                              <table class="table table-bordered">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  <th>ID</th>
                                  <th>TITLE</th>
                                  <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                  </tr>     
                                  
                                  <?php 
                                    $getmaxdoc = $obj->getMaxDocumentStatw($userid);
                                    if($getmaxdoc == ''){
                                        echo '<tr><td colspan="4"><center>No proposal has been reviewed yet.</center></td></tr>';
                                    }
                                    else{
                                        foreach($getmaxdoc as $prop){
                                            if($prop['status_action'] == '17'){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                echo '<tr>';
                                                echo '<td><span class="glyphicon glyphicon-hourglass" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR RESUBMISSION"></span></td>';   
                                                echo '<td>'.$code.'</td>';

                                                $uc = ucwords($prop['prop_ptitle']);
                                                $strlen = strlen($prop['prop_ptitle']);
                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                
                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                echo '<a class="btn btn-primary btn-xs" href="sec_bypassreviews.php?i=' . $prop['sub_id'] . '" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center></td>';
                                                echo '</tr>';
                                            }
                                        }                                        
                                    }
                                  ?>
                              </table>
                            </div>
                            
                            <hr>
                              
                            <div class="panel panel-danger">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Disapproved</div>

                              <table class="table table-bordered">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  <th>ID</th>
                                  <th>TITLE</th>
                                  <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                  </tr>     
                                  
                                  <?php 
                                    $getmaxdoc = $obj->getMaxDocumentStatw($userid);
                                    if($getmaxdoc == ''){
                                        echo '<tr><td colspan="4"><center>No proposal has been reviewed yet.</center></td></tr>';
                                    }
                                    else{
                                        foreach($getmaxdoc as $prop){
                                            if($prop['status_action'] == '25'){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                echo '<tr>';
                                                echo '<td><span class="glyphicon glyphicon-hourglass" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR RESUBMISSION"></span></td>';   
                                                echo '<td>'.$code.'</td>';

                                                $uc = ucwords($prop['prop_ptitle']);
                                                $strlen = strlen($prop['prop_ptitle']);
                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                
                                                echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                echo '<a class="btn btn-primary btn-xs" href="sec_bypassreviews.php?i=' . $prop['sub_id'] . '" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></center></td>';
                                                echo '</tr>';
                                            }
                                        }                                        
                                    }
                                  ?>
                              </table>
                            </div>
                              
                              
                          </div>
                          <!--start of resubmitted-->



 <!--start of approved-->
 <div id="approved" class="tab-pane fade">
                              <div class="panel panel-success">
                              <!-- Default panel contents -->
                              <div class="panel-heading">Approved</div>

                              <table class="table table-bordered table-hover">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                  
                                  <th>ID</th>
                                  <th>REVIEW TYPE</th>
                                  <th>TITLE</th>
                                  <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                  </tr>
                                  
                                  <?php
                                  $getmaxdoc = $obj->getMaxDocumentStat($userid);
                                    if($getmaxdoc == ''){
                                        echo '<tr><td colspan="4"><center>No proposal has been reviewed yet.</center></td></tr>';
                                    }
                                    else{
                                        foreach($getmaxdoc as $id => $prop){

                                             
//                                            if(($subm['pa_status'] == "onreview")||$subm['pa_status'] == null){
                                                if(($prop['status_action'] == '6')){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    
                                                    echo '<td>';
                                                    echo '<a class="btn btn-default btn-xs" href="sec_sendemail.php?id='.$prop['sub_id'].'" role="button" data-toggle="tooltip" title="Request for Report">';
                                                    echo '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Email';
                                                    echo '</td></a>';   
                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    
                                                    echo '<td><center><a class="btn btn-default btn-xs" href="sec_bypassreviews.php?i='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a> | <a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                    echo '</tr>';
                                                }
                                                
                                                else if(($prop['status_action'] == '23')){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    echo '<td><button class="btn btn-default btn-xs" type="button">DONE</button></center></td></td>';   
                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    
                                                    echo '<td><center><a class="btn btn-default btn-xs" href="sec_bypassreviews.php?i='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a> | <a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a>';
                                                    echo '</tr>';
                                                }
                                                
                                                
                                                
                                                else if($prop['status_action'] == '12'){
                                                    //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    echo '<td><span class="glyphicon glyphicon-ok" aria-hidden="true" data-toggle="tooltip" title="EXEMPTED"></span></td>';   
                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                    echo '</tr>';
                                                }
                                                
//                                            }
                                        }       
                                    }
                                  ?>
                              </table>
                            </div>
                          </div>
                          <!--start of approved-->





















































                          
                         




























                          
                          <div id="postapproval" class="tab-pane fade">
<!--                              <div class="panel panel-success">
                               Default panel contents 
                              <div class="panel-heading">Post-Approval</div>

                              <table class="table table-bordered tr_link">
                                  <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  <th>ID</th>
                                  <th>REVIEW TYPE</th>
                                  <th>TITLE</th>
                                  <th><center>REQUEST</center></th>
                                  </tr>
                                  <?php 
//                                  $checkifconfirmed = $obj->checkifconfirmed($userid);
//                                        if($checkifconfirmed>0){
//                                            echo '<tr><td colspan="5"><i><center>No proposal has been on the process of review.</center></i></td></tr>';
//                                        }
//                                        else{
//                                            $getmaxsec = $obj->getMaxPerSecpost($userid);//kwaon sah ang maximum id sa proposal_status na table
//                                        if($getmaxsec){
//                                            foreach($getmaxsec as $maxs){
//                                                $getresubmit = $obj->getproposalviewunconfirmed($maxs['maxid']);//kwaon ang sub_id sa proposal linked with maxid sa proposal_status
//                                                if($getresubmit>0){
//                                                    foreach($getresubmit as $prop){
//                                                        if($prop['status_action']=='7'){//kung 3 ang status action sa proposal_status
//                                                            
//                                                                echo '<tr>';//1
//                                                                echo '<td><span class="glyphicon glyphicon-ok" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR RESUBMISSION"></span></td>';   
//                                                                echo '<td>'.$prop['code'].'</td>';                                                        
//                                                                
//                                                                echo '<td>'.$prop['rt_name'].'</td>';
//                                                                
//                                                                $uc = ucwords($prop['prop_ptitle']);
//                                                                $strlen = strlen($prop['prop_ptitle']);
//                                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
//                                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
//                                                                
//                                                                
//                                                                echo '<td><center><a class="btn btn-primary" href="sec_okamend.php?id='.$prop['sub_id'].'" role="button">Amendments</a></center></td>';
//                                                                
//                                                                echo '</tr>';//1
//                                                            
//                                                        }
//                                                        else if($prop['status_action']=='8'){
//                                                                echo '<tr>';//1
//                                                                echo '<td><span class="glyphicon glyphicon-ok" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR RESUBMISSION"></span></td>';   
//                                                                echo '<td>'.$prop['code'].'</td>';                                                        
//                                                                
//                                                                echo '<td>'.$prop['rt_name'].'</td>';
//                                                                
//                                                                $uc = ucwords($prop['prop_ptitle']);
//                                                                $strlen = strlen($prop['prop_ptitle']);
//                                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
//                                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
//                                                                
//                                                                echo '<td><center><a class="btn btn-warning" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Clearance Extension</a></center></td>';
//                                                                
//                                                                echo '</tr>';//1
//                                                        }
//                                                        else if($prop['status_action']=='9'){
//                                                                echo '<tr>';//1
//                                                                echo '<td><span class="glyphicon glyphicon-ok" aria-hidden="true" data-toggle="tooltip" title="WAITING FOR RESUBMISSION"></span></td>';   
//                                                                echo '<td>'.$prop['code'].'</td>';                                                        
//                                                                
//                                                                echo '<td>'.$prop['rt_name'].'</td>';
//                                                                
//                                                                $uc = ucwords($prop['prop_ptitle']);
//                                                                $strlen = strlen($prop['prop_ptitle']);
//                                                                if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
//                                                                else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
//                                                                
//                                                                echo '<td><center><a class="btn btn-success" href="sec_finalreport.php?id='.$prop['sub_id'].'" role="button">Final Result Submission</a></center></td>';
//                                                                
//                                                                echo '</tr>';//1
//                                                        }
//                                                    }
//                                                }
//                                                    
//                                            }
//                                        }
//                                    }
                                        ?>
                                  
                              </table>
                            </div>-->
                              
                            <div class="panel panel-success">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">Post-Approval Request(s)</div>


                                    <!-- Table -->
                                    <table class="table table-bordered">
                                        <tr>
                                        <th>ID</th>
                                        <th>TITLE</th>
                                        <th><center>ACTION</center></th>
                                        
                                        <?php 
                                        $getmaxsec = $obj->getMaxPerSec($userid);//kwaon sah ang maximum id sa proposal_status na table
                                        $i = 0;
                                        if($getmaxsec){
                                            foreach($getmaxsec as $maxs){
                                                $getresubmit = $obj->getproposalview($maxs['maxid']);//kwaon ang sub_id sa proposal linked with maxid sa proposal_status
                                                if($getresubmit>0){
                                                    foreach($getresubmit as $prop){
                                                        
                                                        //GETTING THE NAME OF REQUEST
                                                        $maxpaid = $obj->getmaxpropapp($prop['sub_id']);
                                                        $ppaid = array("pid" => $maxpaid);
                                                        $getreq = $obj->fetch_record_with_where("proposal_post_approval", $ppaid);
                                                        foreach($getreq as $req){
                                                            $pareq = array("id" => $req['pa_request']);
                                                            $getnamereq = $obj->fetch_record_with_where("post_approval_reqtype", $pareq);
                                                            foreach($getnamereq as $nreq){
                                                                if($nreq['id'] == '1'){
                                                                    $ndesc = $nreq['par_desc'];
                                                                    $btn = "warning";
                                                                }
                                                                else if($nreq['id'] == '2'){
                                                                    $ndesc = $nreq['par_desc'];
                                                                    $btn = "info";
                                                                }
                                                                else if($nreq['id'] == '3'){
                                                                    $ndesc = $nreq['par_desc'];
                                                                    $btn = "primary";
                                                                }
                                                                else if($nreq['id'] == '4'){
                                                                    $ndesc = $nreq['par_desc'];
                                                                    $btn = "primary";
                                                                }
                                                            }
                                                        }
                                                        //GETTING THE NAME OF REQUEST

                                                        
                                                        
                                                        if($prop['status_action']=='8'){//kung 0 ang status action sa proposal_status
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-warning btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                                            #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                            echo '<a class="btn btn-warning btn-xs" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Clearance Extension</a></center></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        
                                                        if($prop['status_action']=='7'){//kung 0 ang status action sa proposal_status AMENDMENTS
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-info btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                                            #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                            echo '<a class="btn btn-info btn-xs" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Amendments</a></center></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        
                                                        
                                                        if($prop['status_action']=='9'){//kung 0 ang status action sa proposal_status PROGRESS REPORT
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                                            #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                            echo '<a class="btn btn-primary btn-xs" href="sec_progressreport.php?id='.$prop['sub_id'].'" role="button">Progress Report</a></center></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        
                                                        
                                                        if($prop['status_action']=='21'){//kung 0 ang status action sa proposal_status FINAL REPORT
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                                            #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                            echo '<a class="btn btn-primary btn-xs" href="sec_finalreport.php?id='.$prop['sub_id'].'" role="button">Final Report</a></center></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        
                                                        else if($prop['status_action']=='18'){//kung 0 ang status action sa proposal_status
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><span data-toggle="tooltip" title="Waiting for Researcher\'s Resubmission">Advised to resubmit</span></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                                            
                                                        else if($prop['status_action']=='19'){//kung 0 ang status action sa proposal_status
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' | <span class="badge">Resubmission</span></small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td><center><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-'.$btn.' btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                                            #echo '<a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> | ';
                                                            echo '<a class="btn btn-'.$btn.' btn-xs" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">'.$ndesc.'</a></center></td>';
                                                            echo '</tr>';                                                            
                                                        }       
                                                        
                                                        else if($prop['status_action']=='24'){//kung 0 ang status action sa proposal_status
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Incomplete</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                                                                                
                                                        else if($prop['status_action']=='27'){//kung 0 ang status action sa proposal_status
                                                            $i = $i + 1;
                                                            
                                                            $ppaid = $obj->getmaxpropapp($prop['sub_id']);
                                                            $getresponse = array(
                                                                "subid" => $prop['sub_id'],
                                                                "ppaid" => $ppaid,
                                                                "final" => '1'
                                                            );
                                                            $response = $obj->fetch_record_with_where("sitevisit_decision", $getresponse);
                                                            if($response){
                                                                echo '<tr>';
                                                                echo '<td>'.$prop['coding'].'</td>';                                      
                                                                    $strlen = strlen($prop['prop_ptitle']);
                                                                    if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                    else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                                echo '<td class="text-center"><a data-toggle="tooltip" title="DOCUMENTS" class="btn btn-warning btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | ';
                                                                echo '<a class="btn btn-default btn-xs" href="sec_tagging_sitevisit.php?id='.$prop['sub_id'].'" role="button">Site Visit Decision</a></td>';
                                                                echo '</tr>';
                                                            }
                                                            else{
                                                                echo '<tr>';
                                                                echo '<td>'.$prop['coding'].'</td>';                                      
                                                                    $strlen = strlen($prop['prop_ptitle']);
                                                                    if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                    else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                                echo '<td class="text-center">Site Visit Conducted</td>';
                                                                echo '</tr>';                                                                
                                                            }
                                                            
                                                                                                                     
                                                        }   
                                                        
                                                        else if($prop['status_action']=='29'){//Disapproved Amendments
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Disapproved Amendments</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        
                                                        else if($prop['status_action']=='28'){//Disapproved Ethical Clearance
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Disapproved Ethical Extension</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='30'){//Disapproved Progress Report
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Disapproved Progress Report</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='31'){//Disapproved Final Report
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Disapproved Final Report</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        
                                                        else if($prop['status_action']=='32'){//Appeal-Disapproval Ethical Clearance
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><a class="btn btn-default" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Appeal for Ethical Clearance (Disapproved)</a></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='33'){//Appeal-Disapproval Amendments
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><a class="btn btn-default" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Appeal for Amendments (Disapproved)</a></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='34'){////Appeal-Disapproval Progress Report
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><a class="btn btn-default" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Appeal for Progress Report (Disapproved)</a></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='35'){////Appeal-Disapproval Final Report
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><a class="btn btn-default" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Appeal for Final Report (Disapproved)</a></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='36'){//Terminate Ethical Clearance
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Terminate Ethical Clearance</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='37'){//Terminate Amendments
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Terminate Amendments</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='38'){//Terminate Progress Report
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Terminate Progress Report</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='39'){//Terminate Final Report
                                                            $i = $i + 1;
                                                            echo '<tr class="danger">';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center">Terminate Final Report</td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='40'){//Appeal-Terminate Ethical Clearance
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><a class="btn btn-default" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Appeal for Ethical Clearance (Terminated)</a></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='41'){//Appeal-Terminate Amendments
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><a class="btn btn-default" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Appeal for Amendments (Terminated)</a></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='42'){//Appeal-Terminate Ethical Clearance
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><a class="btn btn-default" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Appeal for Progress Report(Terminated)</a></td>';
                                                            echo '</tr>';                                                            
                                                        }
                                                        else if($prop['status_action']=='43'){//Appeal-Terminate Ethical Clearance
                                                            $i = $i + 1;
                                                            echo '<tr>';
                                                            echo '<td>'.$prop['coding'].'</td>';                                      
                                                                $strlen = strlen($prop['prop_ptitle']);
                                                                if ($strlen>50){echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'...<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).' </small></td>';}
                                                                else echo'<td>'.substr($prop['prop_ptitle'], 0, 50).'<br><small>'.date("F j, Y",strtotime($prop['date_submitted'])).'</small></td>';   
                                                            echo '<td class="text-center"><a class="btn btn-default" href="sec_eclerance.php?id='.$prop['sub_id'].'" role="button">Appeal for Final Report (Terminated)</a></td>';
                                                            echo '</tr>';                                                            
                                                        }
//                                                    else{//kung dili 0 ang status action sa proposal status
//                                                         echo '<tr><td colspan="4"><i><center>No resubmissions of incomplete files yet.</center></i></td></tr>';
//                                                    }
                                                    }
                                                }
                                                    
                                            }
                                        }
                                        
                                        if($i == 0){
                                            echo '<tr>';
                                            echo '<td colspan="3" class="text-center">';
                                                echo '<i>No proposals yet.</i></td>';     
                                            echo '</tr>'; 
                                        }
                                        
                                        
                                        
                                        
                                        ?>
                                    </table>
                                  </div>
                                <div class="panel panel-default">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">On-Going Request</div>

                                    <!-- Table -->
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Title</th>
                                            <th>Review Type</th>
                                            <th>Confirmed</th>
                                            <th>Info</th>
                                        </tr>
                                        <?php
                                        $allprop = $obj->getlistproposal($userid);
                                        foreach($allprop as $prop){
                                            
                                            
                                                //GETTING PROPOSAL INFORMATION
                                                $where1 = array("sub_id" => $prop['sid']);
                                                $propin = $obj->fetch_record_with_where("proposal", $where1);
                                                foreach($propin as $po){$ptitle = '<b>'.strtoupper($po['prop_ptitle'].'</b>');}
                                                //GETTING PROPOSAL 
                                                
                                                
                                                //GETTING REVIEW TYPE
                                                $where2 = array("sub_id" => $prop['sid']);
                                                $reviewtype = $obj->fetch_record_with_where("review_type", $where2);
                                                foreach($reviewtype as $rt){
                                                    $where3 = array("id" => $rt['rt_id']);
                                                    $rtname = $obj->fetch_record_with_where("review_type_list", $where3);
                                                    foreach($rtname as $rtn){$rtnm = $rtn['rt_name'];}
                                                }                                                
                                                //GETTING REVIEW TYPE
                                                
                                                //GETTING MAX PROPOSAL POST APPROVAL
                                                $maxpropppa = $obj->getmaxpropapp($prop['sid']);  
                                                
                                               
                                                //GETTING MAX PROPOSAL POST APPROVAL
                                                
                                                //GETTING MAX REVIEW
                                                $maxreview = $obj->maxreviewerpa($prop['sid'], $maxpropppa);
                                                //GETTING MAX REVIEW
                                                
                                                //KNOWING IF CONFIRMED
                                                $where5 = array("ppa_id" => $maxpropppa, "sub_id" => $prop['sid'], "review" => $maxreview);
                                                $getconfirmed = $obj->fetch_record_with_where("rev_groupspa", $where5);
                                                $i = 0;
                                                foreach($getconfirmed as $confirmed){                                                    
                                                    if($confirmed['confirmation'] == 1){
                                                        $i++;
                                                    }
                                                    else{$i = $i + 0;}
                                                }                                                
                                                //KNOWING IF CONFIRMED
                                            
                                            
                                            if($prop['dp'] != null){
                                                
                                                
                                                
                                                $maxstatus = $obj->getMaxpa($prop['sid']);
                                                
                                                if($maxstatus == 3){ // ONLY ASSIGNED SHOULD BE SEEN
                                                    
                                                //CHECKING IF ALL PRIMARY REVIEWERS WERE ALREADY SUBMITTED THEIR REVIEW
                                                $where20 = array("ppa_id" => $maxpropppa, "sub_id" => $prop['sid'], "review" => $maxreview, "primary_reviewer" => "1");
                                                
                                                $getprimaryrevdone = $obj->fetch_record_with_where("rev_groupspa", $where20);
                                                $v = 0;
                                                foreach($getprimaryrevdone as $prev){
                                                    
                                                    if($prev['evaluation_submitted'] == 0){
                                                        $v++;
                                                    }
                                                else{$v = $v + 0;}
                                                }
                                                //CHECKING IF ALL PRIMARY REVIEWERS WERE ALREADY SUBMITTED THEIR REVIEW
                                                    
                                                    
                                                    
                                                //CHECKING IF ALL REVIEWERS OF THE PROPOSAL HAS SUBMITTED EVALUATIONS ALREADY
                                                $where6 = array("ppa_id" => $maxpropppa, "sub_id" => $prop['sid'], "review" => $maxreview);
                                                
                                                $getactualconfirm = $obj->fetch_record_with_where("rev_groupspa", $where6);
                                                $a = 0;
                                                foreach($getactualconfirm as $yesconfirm){
                                                    if($yesconfirm['evaluation_submitted'] == 0){
                                                        $a++;
                                                    }
                                                    else{$a = $a + 0;}
                                                }
                                                //CHECKING IF ALL REVIEWERS OF THE PROPOSAL HAS BEEN CONFIRMED ALREADY
                                                
                                                //CHECKING HOW MANY HAVE SUBMITTED EVALUATIONS                                                
                                                $where7 = array("ppa_id" => $maxpropppa, "sub_id" => $prop['sid'], "review" => $maxreview);
                                                $getactualconfirm = $obj->fetch_record_with_where("rev_groupspa", $where7);
                                                $b = 0;
                                                foreach($getactualconfirm as $yesconfirm){
                                                    if($yesconfirm['evaluation_submitted'] == 1){
                                                        $b++;
                                                    }
                                                    else{$b = $b + 0;}
                                                }
                                                //CHECKING HOW MANY HAVE SUBMITTED EVALUATIONS
                                                
                                                
                                                //ADDING REQUEST TYPE                                                
                                                $req = $obj->getmaxpropapp($prop['sid']);
                                                $where = array("pid" => $req);
                                                $requ = $obj->fetch_record_with_where("proposal_post_approval", $where);
                                                foreach($requ as $r){
                                                    $pid = $r['pa_id'];
                                                    $where2 = array("id" => $r['pa_request']);
                                                    $rtime = $obj->fetch_record_with_where("post_approval_reqtype",$where2);
                                                    foreach($rtime as $rti){$timee = $rti['par_desc'];}
                                                }                                                
                                                //ADDING REQUEST TYPE
                                                
                                                
                                                //GETTING REVIEWERS
                                                
                                                $whereee = array("sub_id" => $prop['sid'], "review" => $maxreview, "ppa_id" => $maxpropppa);
                                                $getit = $obj->fetch_record_with_where("rev_groupspa", $whereee);
                                                
                                                $names = "";
                                                foreach($getit as $revv){
                                                    $wheredd = array("id" => $revv['phrepuser_id']);
                                                    $gettt = $obj->fetch_record_with_where("phrepuser", $wheredd);
                                                    
                                                    foreach($gettt as $nnn){
                                                        $n = strtoupper($nnn['fname']);
                                                        $l = strtoupper($nnn['lname']);
                                                        $fulls = $n['0'].$l;
                                                        $full = str_replace(" ", "", $fulls);
                                                                
                                                        if($revv['primary_reviewer'] == 1){
                                                            if($revv['confirmation'] == 1){
                                                                if($revv['evaluation_submitted'] == 1){
                                                                    $names .= '<span class="prime-submitted underline">'.$full.'*</span>, ';                                                                 
                                                                }
                                                                else{
                                                                    $names .= '<span class="underline">'.$full.'*</span>, '; 
                                                                }                                                                
                                                            }
                                                            else{
                                                                    $names .= $full.'*, '; 
                                                                }   
                                                        }
                                                        else{
                                                            if($revv['confirmation'] == 1){
                                                                if($revv['evaluation_submitted'] == 1){
                                                                    $names .= '<span class="prime-submitted underline">'.$full.'</span>, ';                                                                 
                                                                }
                                                                else{
                                                                    $names .= '<span class="underline">'.$full.'</span>, '; 
                                                                }                                                                
                                                            }
                                                            else{
                                                                $names .= $full.', ';
                                                            }
                                                        }
                                                        
                                                                                                               
                                                    }                                                    
                                                }
                                                $names = "<small class='font-rev' data-toggle='tooltip' title='Primary Reviewer with asterisk, Confirmed with underline and Submitted when green'>REVIEWERS: ".substr($names, 0, -2)."</small>";
                                                
                                                //GETTING REVIEWERS
                                                
                                                
                                                
                                                
                                                 if($v == 0){       
                                                    
                                                    //CHECKING IF PROPOSAL STATUS IS 18 (DONE TAGGING BY SECRETARY)
                                                    $maxid = $obj->getMaxpa($prop['sid']);
                                                    if($maxid == 18){
                                                        //CHECKING IF PROPOSAL STATUS IS 18 (DONE TAGGING BY SECRETARY)
                                                        echo '<tr>';
                                                        echo '<td>'.$ptitle.'<p>'.$timee.'</p></td>';
                                                        echo '<td class="text-center">'.$rtnm.'</td>';
                                                        echo '<td colspan="2" class="text-center">To be revised</td>';
                                                        echo '</tr>';                                                          
                                                    }
                                                    else{
                                                        
                                                        echo '<tr class="success clickable-row tr_link" data-href="sec_taggingpa.php?id='.$prop['sid'].'">';
                                                        echo '<td>'.$ptitle.'<p>Requesting for <strong>'.$timee.'</strong>';
                                                        echo '<br>';
                                                        echo $names;
                                                        
                                                        echo'</p></td>';
                                                        echo '<td class="text-center">'.$rtnm.'</td>';
                                                        echo '<td class="text-center">'.$i.'/'.count($getconfirmed).'</td>';
                                                        echo '<td class="pull-center"><a class="btn btn-primary btn-xs" href="sec_postpartialview.php?id='.$prop['sid'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td>';
                                                        echo '</tr>';                                                           
                                                    }                                                 
                                                }
                                                
                                                else{     
                                                    if($maxreview == 1){
                                                        echo '<tr>';
                                                        echo '<td>'.$ptitle.'<p>'.$obj->ordinalize($maxreview).' submission | '.$b.' submitted evaluation';
                                                        echo ' | '.$obj->ordinalize($pid).' request of '.$timee;
                                                        echo '<br>';
                                                        echo $names;
                                                        
                                                        echo'</p></td>';
                                                        
                                                        echo '<td class="text-center">'.$rtnm.'</td>';
                                                        echo '<td class="text-center">'.$i.'/'.count($getconfirmed).'</td>';
                                                        echo '<td class="pull-center"><a class="btn btn-primary btn-xs" href="sec_postpartialview.php?id='.$prop['sid'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td>';
                                                        echo '</tr>';                                                        
                                                    }
                                                    else{
                                                        echo '<tr>';
                                                        echo '<td>'.$ptitle.'<p>'.$obj->ordinalize($maxreview).' submission | '.$b.' submitted evaluation';
                                                        echo ' | '.$obj->ordinalize($pid).' request of '.$timee;
                                                        echo '<br>';
                                                        echo $names;
                                                        
                                                        echo'</p></td>';
                                                        echo '<td class="text-center">'.$rtnm.'</td>';
                                                        echo '<td class="text-center">'.$i.'/'.count($getconfirmed).'</td>';
                                                        echo '<td class="pull-center"><a class="btn btn-primary btn-xs" href="sec_postpartialview.php?id='.$prop['sid'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></td>';
                                                        echo '</tr>';  
                                                        
                                                    }
                                                }
                                            }                                                    
                                        }
                                        }
                                        
                                        ?>
                                        
                                    </table>
                                </div>
                              

<!--<table class="table">
    <tr>
        <td>
            Name
        </td>-->
        <?php
//        $getproposal = $obj->fetch_record_all("proposal");
//        foreach($getproposal as $gtp){
//            echo '<th>'.$gtp['sub_id'].'</th>';
//        }
        ?>
    <!--</tr>-->
        
        
        <?php
//        $where = array("rec_list_id" => 1);
//        $getresearcher = $obj->fetch_record_with_where("rec_groups", $where);
//        foreach($getresearcher as $rs){
//            $where = array("id" => $rs['phrepuser_id']);
//            $getname = $obj->fetch_record_with_where("phrepuser", $where);
//            foreach($getname as $gn){
//                echo '<tr>';
//                echo '<td>'.$gn['lname'].'</td>';    
//                
//                    $getproposal = $obj->fetch_record_all("proposal");
//                    foreach($getproposal as $gtp){
//                        $where = array("sub_id" => $gtp['sub_id'], "phrepuser_id" => $gn['id']);
//                        $getifassigned = $obj->fetch_record_with_where("rev_groups", $where);
//                        if($getifassigned){
//                            echo '<td>1</td>';  
//                        }
//                        else{
//                            echo '<td>0</td>';
//                        }
//                    }
//                
//                echo '</tr>';                
//            }
//            
//        }
//        
//    
//        
//        
//        
//        $getproposal = $obj->fetch_record_all("proposal");
//        foreach($getproposal as $gtp){
//            echo '<p>'.$gtp['id'].') '.$gtp['prop_ptitle'].'<p>';
//        }
       
        
        ?>
            
<!--</table>-->
                                




                              
                          </div>
                          
                          <div id="reviewers" class="tab-pane fade">
                              <div class="row">


                           



 <!-- start of new approval -->
                                                    <!--start of approved-->
                                                    <div id="" class="col-md-12">
                              <div class="panel panel-success">
                              <!-- Default panel contents -->
                              <div class="panel-heading"><h1>P R E   -   TOTAL NUMBER OF PROPOSALS</h1></div>

                              <table class="table table-condensed table-bordered table-hover">
                                
                                  <th>Year</th>
                                  
                                  <th>Submitted</th>
                                  <th>Accepted</th>
                                  <th>Underwent Full Review</th>
                                  <th>Underwent Expedited Review</th>
                                  <th>Exempted from Review</th>
                                  <th>Approved</th>
                                  <th>Disapproved</th>
                                  <th>For Revision</th>

                                  <!-- <th><center>Action</center></th> -->
                                  
                                  <?php
                                    $getyear = $obj->getYearOnly_pre($userid);

                                    foreach($getyear as $id => $getyears){
                                       
                                      $id  = $id + 1;
                                      
                                      echo '<tr>';
                                     
                                      echo '<td><b><h4>'.$getyears['yearName'].'</h4></b></td>';


                                      echo '<td> <a href="sec_dashboard_approved_pre.php?year='.$getyears['yearName'].'&status=submitted"> <h6>'.$getyears['submitted'].'</h6> </a> </td>';


                                      echo '<td> <a href="sec_dashboard_approved_pre.php?year='.$getyears['yearName'].'&status=accepted"> <h6>'.$getyears['accepted'].'</h6> </a> </td>';
                                      
                                      echo '<td> <a href="sec_dashboard_approved_pre.php?year='.$getyears['yearName'].'&status=underwentfullreview"> <h6>'.$getyears['full'].'</h6> </a> </td>';

                                      echo '<td> <a href="sec_dashboard_approved_pre.php?year='.$getyears['yearName'].'&status=underwentexpeditedreview"> <h6>'.$getyears['expedited'].'</h6> </a> </td>'; 

                                      echo '<td> <a href="sec_dashboard_approved_pre.php?year='.$getyears['yearName'].'&status=underwentexemptedreview"> <h6>'.$getyears['exempted'].'</h6> </a> </td>';
                                      
                                      echo '<td> <a href="sec_dashboard_approved_pre.php?year='.$getyears['yearName'].'&status=approved"> <h6>'.$getyears['approved'].'</h6> </a> </td>';
                                      // echo '<td><a href="sec_dashboard_approved_pre.php?year='.$getyears['year_accepted'].'">'.$getyears['total_approved_proposal'].'</a></td>';
                                      echo '<td> <a href="sec_dashboard_approved_pre.php?year='.$getyears['yearName'].'&status=disapproved"> <h6>'.$getyears['disapproved'].'</h6> </a> </td>';

                                      echo '<td> <a href="sec_dashboard_approved_pre.php?year='.$getyears['yearName'].'&status=revision"> <h6>'.$getyears['revision'].'</h6> </a> </td>';
                                    
                                      
                                    //   echo '<td><a href="sec_dashboard_approved_pre.php?year='.$getyears['year_accepted'].'"><span class="glyphicon glyphicon-file btn btn-success btn-block">'.' '.'</span></td>';

                                      echo '</tr>';


                                    }

                                    
                                  ?>
                              
                              </table> 


                               <div class="panel-heading"><h1>P O S T   -   TOTAL NO. OF PROPOSALS</h1></div>

                               <table class="table table-condensed table-bordered table-hover">
                                 
                                  <th>Year</th>
                                  
                                 
                                  <th>Total no. of Requests</th>
                                  <th>Approved</th>
                                  <th>Disapproved</th>
                                  <th>For Revision</th>
                                  
                                  <?php
                                    $getyear = $obj->getYearOnly_post($userid);

                                    foreach($getyear as $id => $getyears){
                                       
                                      $id  = $id + 1;
                                      
                                     echo '<tr>';
                                     
                                      echo '<td><b><h3>'.$getyears['yearName'].'</h3></b></td>';

                                      echo '<td><h6>'.$getyears['submitted'].'</h6></td>';
                                      echo '<td><h6>'.$getyears['post_approved'].'</h6></td>';
                                      echo '<td><h6>'.$getyears['post_disapproved'].'</h6></td>';
                                      echo '<td><h6>'.$getyears['post_revision'].'</h6></td>';
                                      
                                      // echo '<td><h6>'.$getyears['full'].'</h6></td>';
                                      // echo '<td><h6>'.$getyears['expedited'].'</h6></td>'; 
                                      // echo '<td><h6>'.$getyears['exempted'].'</h6></td>';
                                      // echo '<td><h6>'.$getyears['approved'].'</h6></td>';
                                      // // echo '<td><a href="sec_dashboard_approved_pre.php?year='.$getyears['year_accepted'].'">'.$getyears['total_approved_proposal'].'</a></td>';
                                      // echo '<td><h6>'.$getyears['disapproved'].'</h6></td>';
                                      // echo '<td><h6>'.$getyears['revision'].'</h6></td>';
                                    
                                      // echo '<td><a href="sec_dashboard_approved_post.php?year='.$getyears['year_accepted'].'"><span class="glyphicon glyphicon-file btn btn-success btn-block">'.' '.'</span></td>';

                                      echo '</tr>';
                                      
                                    }

                                    
                                  ?>
                              
                              </table>

                            </div>
                          </div>

                    

                            







                                  <div class="col-lg-12">
                                    <?php
                                    $where = array("secretary" => $userid);

                                    $getrec = $obj->fetch_record_with_where("rec_list", $where);
                                    foreach($getrec as $recc){$rec = $recc['id'];}

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

<div class="modal fade" id="confirm-exempted" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1>REMINDER!</h1>
            </div>
            <div class="modal-body">
                This submission will be immediately APPROVE without assigning to reviewers.
                Are you sure you want to tag this submission as "Exempted Review"? 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-warning btn-ok">Okay</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-expedited" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1>REMINDER!</h1>
            </div>
            <div class="modal-body">
                Are you sure you want to assign this to an Expedited Review? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-full" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>REMINDER!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to assign this to a Full Review?  
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>


<script>
$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});

</script>

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
$('#confirm-exempted').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('#confirm-expedited').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('#confirm-full').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

var u = document.getElementById("userid").value;
$("#idp").attr("href", "sec_personal_info.php?id="+u);
</script>

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

<script>
jQuery(document).ready(function($) {
    $("tr .clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>

<?php 
include_once("$currDir/footer.php");
?>