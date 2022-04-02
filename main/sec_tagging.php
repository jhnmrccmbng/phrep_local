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
      } .tr_link{cursor:pointer}</style>
  </head>
  <body>
      <form action = "sec_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
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
          
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          <div class="row">
              <div class="col-lg-2"><h2>Dashboard</h2></div>
              <div class="col-lg-10"><h2>Decision</h2></div>
              
          </div>
          <div class="row">
              <div class="col-lg-2">
                  
                  <div class="row">
                      <div class="list-group">
                        <a href="#" class="list-group-item active">
                          Submissions
                        </a>
                        <a href="#" class="list-group-item">My Profile</a>
                        <a href="#" class="list-group-item">Messages</a>
                        <a href="#" class="list-group-item">Documents</a>
                        <a href="#" class="list-group-item">Certificates</a>
                      </div>
                  </div>
                  
              </div>
              <div class="col-lg-8">
                  <div class="row">
                      <div class="col-lg-12">
                                                    
                          <div class="panel panel-default">
                              
                                <table class="table table-bordered">
                                    <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span></center></td><td>'.$prop['code'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-file" aria-hidden="true"></span></center></td><td>'.$prop['prop_ptitle'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-user" aria-hidden="true"></span></center></td><td>'.$prop['fname'].' '.$prop['mname'].' '.$prop['lname'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr ><td><center><span class="glyphicon glyphicon-calendar" aria-hidden="true" data-toggle="tooltip" title="DUE DATE"></span></center></td><td>'.$prop['rt_duedate'].'</td></tr>';}}
                                  ?>
                                    
                                </table>
                          </div>
                          
                          <div class="panel panel-primary">
                            <div class="panel-heading">Reviewer's Decision</div>
                            <table class="table table-condensed table-bordered table-hover table-striped table-responsive">
                                <tr>
                                    <th>Review #</th>
                                    <th>Reviewer</th>
                                    <th>Decision</th>
                                    <th>Evaluation</th>
                                </tr>
                                
                                    
                                <?php 
                                $maxrev = $obj->getmaxreviewer($id);
                                $getevaluators = $obj->getevaluationsfromreviewers($userid, $id, $maxrev);
                                if($getevaluators){
                                    foreach($getevaluators as $ge){
                                        if($ge['evaltype_id'] == '3'){

                                            if($ge['desid'] == '1'){
                                            echo '<tr class="success">';
                                            echo '<td>'.$obj->ordinalize($ge['review']).'</td>';
                                            echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                            echo '<td>'.$ge['dec_desc'].'</td>';
                                            echo '<td><a href="form2.php?id='.$id.'&idu='.$ge['phrepuser_id'].'&r='.$ge['review'].'">'.$ge['evaltype_desc'].'</a></td>';
                                            echo '</tr>';                                            
                                            }
                                            else if(($ge['desid'] == '2')||($ge['desid'] == '3')){
                                            echo '<tr class="warning">';
                                            echo '<td>'.$obj->ordinalize($ge['review']).'</td>';
                                            echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                            echo '<td>'.$ge['dec_desc'].'</td>';
                                            echo '<td><a href="form2.php?id='.$id.'&idu='.$ge['phrepuser_id'].'&r='.$ge['review'].'">'.$ge['evaltype_desc'].'</a></td>';
                                            echo '</tr>';                                            
                                            }
                                            
                                            else if($ge['desid'] == '5'){
                                            echo '<tr class="danger">';
                                            echo '<td>'.$obj->ordinalize($ge['review']).'</td>';
                                            echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                            echo '<td>'.$ge['dec_desc'].'</td>';
                                            echo '<td><a href="form2.php?id='.$id.'&idu='.$ge['phrepuser_id'].'&r='.$ge['review'].'">'.$ge['evaltype_desc'].'</a></td>';
                                            echo '</tr>';                                            
                                            }
                                            else{
                                            echo '<tr>';
                                            echo '<td>'.$obj->ordinalize($ge['review']).'</td>';
                                            echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                            echo '<td>'.$ge['dec_desc'].'</td>';
                                            echo '<td>'.$ge['evaltype_desc'].'</td>';
                                            echo '</tr>';                                            
                                            }
                                            
                                        }
                                        else{
                                            if($ge['desid'] == '1'){

                                            echo '<tr class="success">';
                                            echo '<td>'.$obj->ordinalize($ge['review']).'</td>';
                                            echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                            echo '<td>'.$ge['dec_desc'].'</td>';
                                            echo '<td>';

                                                

                                                $gevals = $obj->getDistinctEval($ge['phrepuser_id'], $ge['sub_id']);
                                                
                                               foreach($gevals as $ev){

                                                    // added by JM xxxxx---
                                                    if($ev['revform_id'] == 3){
                                                      $newVersion    = "Reviewer's Worksheet";
                                                    }else{
                                                      $newVersion    = $ev['evaltype_desc'];
                                                    }


                                                    

                                                    echo '<a href="form1.php?id='.$ge['sub_id'].'&idu='.$ge['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$newVersion.'</a><br>';
                                                }
                                            
                                            echo '</td>';
                                            echo '</tr>';                                            
                                            }
                                            else if(($ge['desid'] == '2')||($ge['desid'] == '3')){
                                            echo '<tr class="warning">';
                                            echo '<td>'.$obj->ordinalize($ge['review']).'</td>';
                                            echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                            echo '<td>'.$ge['dec_desc'].'</td>';
                                            echo '<td>';
                                                $gevals = $obj->getDistinctEval($ge['phrepuser_id'], $ge['sub_id']);
                                                // foreach($gevals as $ev){
                                                //     echo '<a href="form1.php?id='.$ge['sub_id'].'&idu='.$ge['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                // }

                                                // added by JM 

                                                 foreach($gevals as $ev){

                                                    // added by JM xxxxx---
                                                    if($ev['revform_id'] == 3){
                                                      $newVersion    = "Reviewer's Worksheet";
                                                    }else{
                                                      $newVersion    = $ev['evaltype_desc'];
                                                    }


                                                    

                                                    echo '<a href="form1.php?id='.$ge['sub_id'].'&idu='.$ge['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$newVersion.'</a><br>';
                                                }
                                            
                                            echo '</td>';
                                            echo '</tr>';                                            
                                            }
                                            
                                            else if($ge['desid'] == '5'){
                                            echo '<tr class="danger">';
                                            echo '<td>'.$obj->ordinalize($ge['review']).'</td>';
                                            echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                            echo '<td>'.$ge['dec_desc'].'</td>';
                                            echo '<td>';
                                                $gevals = $obj->getDistinctEval($ge['phrepuser_id'], $ge['sub_id']);
                                                // foreach($gevals as $ev){
                                                //     echo '<a href="form1.php?id='.$ge['sub_id'].'&idu='.$ge['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                // }

                                                // added by JM

                                                 foreach($gevals as $ev){

                                                    // added by JM xxxxx---
                                                    if($ev['revform_id'] == 3){
                                                      $newVersion    = "Reviewer's Worksheet";
                                                    }else{
                                                      $newVersion    = $ev['evaltype_desc'];
                                                    }


                                                    

                                                    echo '<a href="form1.php?id='.$ge['sub_id'].'&idu='.$ge['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$newVersion.'</a><br>';
                                                }
                                            
                                            echo '</td>';
                                            echo '</tr>';                                            
                                            }
                                            
                                            else{
                                            echo '<tr>';
                                            echo '<td>'.$obj->ordinalize($ge['review']).'</td>';
                                            echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                            echo '<td>'.$ge['dec_desc'].'</td>';
                                            echo '<td>'.$ge['evaltype_desc'].'</td>';
                                            echo '<td>';
                                                $gevals = $obj->getDistinctEval($ge['phrepuser_id'], $ge['sub_id']);
                                                // foreach($gevals as $ev){



                                                //     echo '<a href="form1.php?id='.$ge['sub_id'].'&idu='.$ge['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                // }

                                                // added by JM
                                                 foreach($gevals as $ev){

                                                    // added by JM xxxxx---
                                                    if($ev['revform_id'] == 3){
                                                      $newVersion    = "Reviewer's Worksheet";
                                                    }else{
                                                      $newVersion    = $ev['evaltype_desc'];
                                                    }


                                                    

                                                    echo '<a href="form1.php?id='.$ge['sub_id'].'&idu='.$ge['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$newVersion.'</a><br>';
                                                }
                                            
                                            echo '</td>';
                                            echo '</tr>';                                            
                                            }
                                        }
                                    }
                                }
                                else{
                                    #echo "Wala";
                                    
                                }
                                
                                ?>
                                
                                
                            </table>
                        </div>
                                <?php 
                                $maxrev = $obj->getmaxreviewer($id);
                                $getevaluators = $obj->getprevreviewerseval($userid, $id, $maxrev);
                                if($getevaluators){ ?>
                                
                                <div class="panel panel-primary">
                                     <div class="panel-heading">Reviewer's Previous Decisions</div>
                                     <table class="table table-condensed table-bordered table-hover table-striped table-responsive">
                                         <tr>
                                             <th>Review #</th>
                                             <th>Reviewer</th>
                                             <th>Decision</th>
                                             <th>Evaluation</th>
                                         </tr>    
                                    
                                    
                                    
                                    <?php
                                    foreach($getevaluators as $eval){
                                        if($eval['review'] != '1'){
                                            if($eval['evaltype_id']=='3'){
                                                if($eval['desid'] == '1'){
                                                echo '<tr class="success">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td><a href="form2.php?id='.$id.'&idu='.$eval['phrepuser_id'].'&r='.$eval['review'].'">'.$eval['evaltype_desc'].'</a></td>';
                                                echo '</tr>';                                            
                                                }
                                                else if(($eval['desid'] == '2')||($eval['desid'] == '3')){
                                                echo '<tr class="warning">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td><a href="form2.php?id='.$id.'&idu='.$eval['phrepuser_id'].'&r='.$eval['review'].'">'.$eval['evaltype_desc'].'</a></td>';
                                                echo '</tr>';                                            
                                                }
                                                else if($eval['desid'] == '5'){
                                                echo '<tr class="danger">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td><a href="form2.php?id='.$id.'&idu='.$eval['phrepuser_id'].'&r='.$eval['review'].'">'.$eval['evaltype_desc'].'</a></td>';
                                                echo '</tr>';                                            
                                                }
                                                else{
                                                echo '<tr>';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td>'.$eval['evaltype_desc'].'</td>';
                                                echo '</tr>';                                            
                                                }

                                            }
                                            else{
                                                if($eval['desid'] == '1'){
                                                echo '<tr class="success">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                
                                                echo '<td>';
                                                    $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                                    foreach($gevals as $ev){
                                                        echo '<a href="form1.php?id='.$eval['sub_id'].'&idu='.$eval['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                    }

                                                echo '</td>';
                                                
                                                echo '</tr>';                                            
                                                }
                                                else if(($eval['desid'] == '2')||($eval['desid'] == '3')){
                                                echo '<tr class="warning">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                
                                                echo '<td>';
                                                    $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                                    foreach($gevals as $ev){
                                                        echo '<a href="form1.php?id='.$eval['sub_id'].'&idu='.$eval['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                    }

                                                echo '</td>';
                                                
                                                echo '</tr>';                                            
                                                }
                                                
                                                else if($eval['desid'] == '5'){
                                                echo '<tr class="danger">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                
                                                echo '<td>';
                                                    $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                                    foreach($gevals as $ev){
                                                        echo '<a href="form1.php?id='.$eval['sub_id'].'&idu='.$eval['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                    }

                                                echo '</td>';
                                                
                                                echo '</tr>';                                            
                                                }
                                                
                                                else{
                                                echo '<tr>';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td>'.$eval['evaltype_desc'].'</td>';
                                                echo '</tr>';                                            
                                                }
                                            }}
                                        else{
                                            if($eval['evaltype_id']=='3'){
                                                if($eval['desid'] == '1'){
                                                echo '<tr class="success">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td><a href="form2.php?id='.$id.'&idu='.$eval['phrepuser_id'].'&r='.$eval['review'].'">'.$eval['evaltype_desc'].'</a></td>';
                                                echo '</tr>';                                            
                                                }
                                                else if(($eval['desid'] == '2')||($eval['desid'] == '3')){
                                                echo '<tr class="warning">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td><a href="form2.php?id='.$id.'&idu='.$eval['phrepuser_id'].'&r='.$eval['review'].'">'.$eval['evaltype_desc'].'</a></td>';
                                                echo '</tr>';                                            
                                                }
                                                
                                                else if($eval['desid'] == '5'){
                                                echo '<tr class="warning">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td><a href="form2.php?id='.$id.'&idu='.$eval['phrepuser_id'].'&r='.$eval['review'].'">'.$eval['evaltype_desc'].'</a></td>';
                                                echo '</tr>';                                            
                                                }
                                                else{
                                                echo '<tr>';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td>'.$eval['evaltype_desc'].'</td>';
                                                echo '</tr>';                                            
                                                }

                                            }
                                            else{
                                                if($eval['desid'] == '1'){
                                                echo '<tr class="success">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                
                                                echo '<td>';
                                                    $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                                    foreach($gevals as $ev){
                                                        echo '<a href="form1.php?id='.$eval['sub_id'].'&idu='.$eval['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                    }

                                                echo '</td>';
                                                
                                                echo '</tr>';                                            
                                                }
                                                else if(($eval['desid'] == '2')||($eval['desid'] == '3')){
                                                echo '<tr class="warning">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                
                                                echo '<td>';
                                                    $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                                    foreach($gevals as $ev){
                                                        echo '<a href="form1.php?id='.$eval['sub_id'].'&idu='.$eval['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                    }

                                                echo '</td>';
                                                
                                                echo '</tr>';                                            
                                                }
                                                
                                                else if($eval['desid'] == '5'){
                                                echo '<tr class="warning">';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                
                                                echo '<td>';
                                                    $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                                    foreach($gevals as $ev){
                                                        echo '<a href="form1.php?id='.$eval['sub_id'].'&idu='.$eval['phrepuser_id'].'&et='.$ev['revform_id'].'"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.$ev['evaltype_desc'].'</a><br>';
                                                    }

                                                echo '</td>';
                                                
                                                echo '</tr>';                                            
                                                }
                                                else{
                                                echo '<tr>';
                                                echo '<td>'.$obj->ordinalize($eval['review']).'</td>';
                                                echo '<td>'.$eval['fname'].' '.$eval['mname'].' '.$eval['lname'].'</td>';
                                                echo '<td>'.$eval['dec_desc'].'</td>';
                                                echo '<td>'.$eval['evaltype_desc'].'</td>';
                                                echo '</tr>';                                            
                                                }
                                            }
                                    }
                                        
                                    } ?>
                                        </table>
                                    </div>   
                                    
                            <?php    }
                                
                                ?>
                                
                          
                          
                      </div>                        
                  </div>
                  
              
              </div>    
                <div class="col-lg-2">  
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <center>
                                    
                                    <?php 
                                      $revised = $obj->getMax($id);
                                      if($revised){
                                          foreach($revised as $revi){
                                              $stat = $obj->getStat($revi['maxid']);
                                              if($stat){
                                                  foreach($stat as $status){
                                                      if($status['status_action']=='5'){
                                                          echo'<h5>REVISED</h5>';
                                                      }
                                                      else{ 
                                                      $postapproval = $obj->getstatuspost($id);
                                                      if($postapproval){ ?>
                                                          
                                                    <h5>Tag Proposal</h5>
                                                    <a class="btn btn-primary btn-lg btn-block" href="sec_approve_post.php?id=<?php echo $id;?>" role="button">Approve</a>
                                                    <a class="btn btn-warning btn-lg btn-block" href="sec_revise_post.php?id=<?php echo $id;?>" role="button">Revise</a>
                                                    <button type="button" class="btn btn-default btn-lg btn-block" onclick="goBack()">Cancel</button>
                                                    
                                                  <?php }
                                                      else{ ?>
                                                    <h5>Tag Proposal</h5>
                                                    <a class="btn btn-primary btn-lg btn-block" href="sec_approve.php?id=<?php echo $id;?>" role="button">Approve</a>
                                                    <a class="btn btn-warning btn-lg btn-block" href="sec_revise.php?id=<?php echo $id;?>" role="button">Revise</a>
                                                    <a class="btn btn-success btn-lg btn-block" href="sec_exempt.php?id=<?php echo $id;?>" role="button">Exempted</a>
                                                    <a class="btn btn-danger btn-lg btn-block" href="sec_disapproved.php?id=<?php echo $id;?>" role="button">Disapproved</a>
                                                    <button type="button" class="btn btn-default btn-lg btn-block" onclick="goBack()">Cancel</button>
                                        
                                                      <?php  }
                                            
                                                      }
                                              }
                                          }
                                      }
                                  }
                                  ?>
                                        
                                </center>
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

<script>
function goBack() {
    window.history.back();
}
</script>