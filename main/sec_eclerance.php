<?php
include "sec_dashboard_action.php";
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
          <form class="form-horizontal" role="form" action="sec_dashboard_action.php" method="POST">
        <!--<form class="form-horizontal" role="form" action="submission_action.php" method="POST">-->  

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

        
              <?php $id = (int) $_GET['id'];?>
              
                <?php $getmaxreq = $obj->getmaxreq($id);?>
              
                      <?php
                        $where = array("sub_id" => $id);
                        $getpropinfo = $obj->fetch_record_with_where("proposal", $where);
                        foreach ($getpropinfo as $p) {
                            $ptitle = $p['prop_ptitle'];
                            $pcode = $p['code'];
                            $pbg = $p['prop_background'];
                            $pobj = $p['prop_obj'];
                            $pout = $p['prop_outcomes'];
                            $pdsub = $p['date_submitted'];
                            $pcom = $p['commentforsec'];
                            $puname = $p['username'];
                            $pps = $p['propdet_primspon'];
                            
                            $where = array("id" => $p['username']);
                              $getuser = $obj->fetch_record_with_where("phrepuser", $where);
                              foreach ($getuser as $user) {
                                  $title = $user['title'];
                                  $fname = $user['fname'];
                                  $mname = $user['mname'];
                                  $lname = $user['lname'];
                              }
                        }
                        ?>
        
        
            <div class="row">
                <div class="col-lg-12">
                    
                </div>
            </div>
        
        
          <div class="row"><!--THIS IS THE FORM AREA-->
              <?php
              $req = $obj->getmaxpropapp($id);
              $where = array("pid" => $req);
              $requ = $obj->fetch_record_with_where("proposal_post_approval", $where);
              foreach($requ as $r){
                  $pid = $r['pa_id'];
                  $where2 = array("id" => $r['pa_request']);
                  $rtime = $obj->fetch_record_with_where("post_approval_reqtype",$where2);
                  foreach($rtime as $rti){$timee = $rti['par_desc'];}
              }
              
              ?>
              
              
              
              
              <h1><?php echo $ptitle; ?><br><small>By: <?php echo $title.' '.$fname.' '.$mname.' '.$lname;?></small></h1><hr>
              
              
              
              <h3>
                  
                  <?php
                  $maxid = $obj->getmaxpropapp($id);
                  $where = array("pid"=>$maxid);
                  $getpp = $obj->fetch_record_with_where("proposal_post_approval", $where);
                  foreach($getpp as $pp){
                      if($pp['pa_request'] == '1'){
                      }
                      else{
                          echo 'Request for '.$timee;
                          $seen = true;
                      }
                  }
                  
                  
                  ?>   
              <span class="pull-right"><small><?php echo $obj->ordinalize($pid);?> Request</small></span></h3>
              <div class="row">
                  <div class="col-lg-4">
                      
                      <?php
                      
                      if($seen == true){
                      ?>    
                      
                      
                      <div class="panel panel-default">
                        <div class="panel-body">
                          Reason for Ethical Review: 
                          <?php 
                          echo '<ol>';
                          
                          $ppaid = $obj->getmaxpropapp($id);
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
                          
                          
                          <?php 
                          $where = array("pid" => $ppaid);
                          $getpareq = $obj->fetch_record_with_where("proposal_post_approval", $where);
                          foreach($getpareq as $pareq){
                              if($pareq['pa_request'] == "2"){
                                  echo '<hr>';
                                  echo '<p>For Amendment of Informed Consent<br>';
                                  echo 'Revisions made involve which of the following?</p>';
                                  echo '<ol>';
                                  $where = array("pid" => $ppaid);
                                  $geticf = $obj->fetch_record_with_where("amendment_icf", $where);
                                  foreach($geticf as $icf){
                                      $where = array("id" => $icf['aicf_id']);
                                      $geticfdesc = $obj->fetch_record_with_where("amendment_icf_type", $where);
                                      foreach($geticfdesc as $icfdesc){
                                          echo '<li>'.$icfdesc['aicf_desc'].'</li>';
                                      }
                                  }
                                  echo '</ol>';
                              }
                          }
                          
                          ?>
                          
                          
                        </div>
                      </div>
                      
                      <?php } ?>
                      <hr>
                      <?php 
                      $ppaid = $obj->getmaxpropapp($id);
                      $stat = $obj->getmaxpropstat($id);   
                      
                      $where = array(
                          "id" => $stat
                      );
                      $getstat = $obj->fetch_record_with_where("proposal_status", $where);
                      foreach($getstat as $s){
                          if($s['status_action'] == 32){$appeal = "Ethical Clearance Extension"; $doctype = "47";}
                          if($s['status_action'] == 33){$appeal = "Amendments"; $doctype = "48";}
                          if($s['status_action'] == 34){$appeal = "Progress Report"; $doctype = "49";}
                          if($s['status_action'] == 35){$appeal = "Final Report"; $doctype = "50";}
                                                   
                          
                          if(($s['status_action'] == 32)||($s['status_action'] == 33)||($s['status_action'] == 34)||($s['status_action'] == 35)){
                              
                              
                          $wheres = array("sub_id" => $id, "post_approval_type" => $ppaid, "doctype" => $doctype);
                          $getvaluemax = $obj->getmaxvalue_only("revision", "document_postapproval", $wheres);
                          
                          
                              
                              echo '<div class="panel panel-default">
                                        <div class="panel-body">
                                        Letter of Appeal for '.$appeal.'                                            
                                        </div>
                                        
                                        <table class="table table-condensed table-bordered">
                                        <tr>
                                        <th>File Name</th>
                                        <th>Action</th>
                                        </tr>';
                              
                                for($i=$getvaluemax;$i>=0;$i--){
                                    $where = array("sub_id" => $id, "post_approval_type" => $ppaid, "revision" => $i, "doctype" => $doctype);
                                    $getdoc = $obj->fetch_record_with_where("document_postapproval", $where);
                                    foreach($getdoc as $doc){
                                        if($doc['revision'] == $getvaluemax){
                                            echo '        
                                                <tr class="success" data-toggle="tooltip" title="Latest">
                                                <td>'.$doc['orig_filename'].'</td>
                                                <td><center>
                                                <input name="dlfile" type="hidden" value="' . $doc['file_name'] . '">
                                                <a class="btn btn-success btn-xs" href="' . $doc['path'] . '" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/' . $doc['file_name'] . '&embedded=true" class="btn btn-primary btn-xs" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                </center>
                                                </td>
                                                </tr>';                                            
                                        }
                                        else{
                                        echo '        
                                            <tr>
                                            <td>'.$doc['orig_filename'].'</td>
                                            <td><center>
                                            <input name="dlfile" type="hidden" value="' . $doc['file_name'] . '">
                                            <a class="btn btn-success btn-xs" href="' . $doc['path'] . '" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                            <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/' . $doc['file_name'] . '&embedded=true" class="btn btn-primary btn-xs" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                            </center>
                                            </td>
                                            </tr>';                                            
                                        }                                        
                                    }                                    
                                }
                              
                                
                                echo '        
                                        </table>
                                    </div>';
                          }
                      }
                      
                      ?>
                      
                      
                      
                    
                        <p><strong>Background: </strong><?php echo $pbg; ?></p>
                        <p><strong>Objective: </strong><?php echo $pobj; ?></p>
                        <p><strong>Outcome: </strong><?php echo $pout; ?></p>
                  </div>
                  <div class="col-lg-8">
                      <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>Submitted Documents</h4>
                          
                          <table class="table table-condensed table-bordered table-hover">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                                
                                <?php 
                                $ppaid = $obj->getmaxpropapp($id);
                                $maxbatch = $obj->getmaxbatchpa($id, $ppaid);
                                
                                $etform = $obj->showingEthicalForm($id, $ppaid, $maxbatch);
                                echo '<tr><td colspan="2">'.$obj->ordinalize($maxbatch).' Submission</td></tr>';
                                foreach($etform as $ec){//LATEST SUBMISSION
                                    echo '<tr class="success">
                                         <td>
                                         <strong>' . $ec['doctype_desc'] . ' (' . $ec['doctypetimes'] . ')</strong> 
                                         <br><small class="filename">' . $ec['file_name'] . ' | ' . $obj->ordinalize($ec['revision']) . ' version <br>';
                                        ?>
                                        <?php $d = strtotime($ec['date_uploaded']);
                                        echo date("M j, Y", $d); ?><?php
                                        echo '</small>
                                        </td>';
                                        
                                        echo'<td><center>
                                        <input name="dlfile" type="hidden" value="' . $ec['file_name'] . '">
                                        <a class="btn btn-success" href="' . $ec['path'] . '" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                        <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/' . $ec['file_name'] . '&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                        </center>
                                        </td>
                                        </tr>';
                                        }//LATEST SUBMISSION
                                    
                                    
                                    if($maxbatch != '1'){
                                        echo '<tr><td colspan="2"><h4>Previous documents ';
                                        echo '<button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target=".collapsePrevious" aria-expanded="false" aria-controls="collapsePrevious">
                                                View
                                               </button><h4>';
                                        echo '</td></tr>';

                                    }    
                                        
                                        
                                
                                    
                                //PREVIOUS SUBMISSION
                                $ppaid = $obj->getmaxpropapp($id);
                                $maxbatch = $obj->getmaxbatchpa($id, $ppaid);
                                $maxbatch = $maxbatch - 1;
                                for($i=1;$i<=$maxbatch;$i++){
                                            echo '<tr class="collapse collapsePrevious">';
                                            echo '<td colspan="4">'.$obj->ordinalize($i).' Batch';
                                            echo '</td>';
                                            echo '</tr>';
                                    $etform = $obj->showingEthicalFormpa($id, $ppaid, $i);
                                        foreach($etform as $ec){
                                            echo '<tr class="collapse collapsePrevious">
                                            <td>
                                            <strong>' . $ec['doctype_desc'] . ' (' . $ec['doctypetimes'] . ')</strong> 
                                            <br><small class="filename">' . $ec['file_name'] . ' | ' . $obj->ordinalize($ec['revision']) . ' version <br>';
                                           ?>
                                           <?php $d = strtotime($ec['date_uploaded']);
                                           echo date("M j, Y", $d); ?><?php
                                           echo '</small>
                                           </td>';

                                           echo'<td><center>
                                           <input name="dlfile" type="hidden" value="' . $ec['file_name'] . '">
                                           <a class="btn btn-success" href="' . $ec['path'] . '" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                           <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/' . $ec['file_name'] . '&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                           </center>
                                           </td>
                                           </tr>';
                                        }
                                    
                                }
                              
                                ?>
                                
                            </table>
                        </div>
                      </div>
                      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Show pre-approval documents >>
                      </button>
                      
                      <div class="collapse" id="collapseExample">
                      <br>
                      <div class="panel panel-default">
                        <div class="panel-body">
                         <table class="table table-striped">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th>ACTION</th>
                                </tr>
                                
                                <?php
                                $getmaxbatch = $obj->getmaxbatch($id);
                                for($i = 1; $i <= $getmaxbatch; $i++){
                                    echo '<tr><td class="warning" colspan="2"><strong>'.$obj->ordinalize($i).' SUBMISSION</strong></td></tr>';
                                    $getdocbybatch = $obj->getdocbybatch($id, $i);
                                    if($getdocbybatch){
                                        foreach($getdocbybatch as $b){
                                            if($b['doctype'] == '1'){
                                                if($b['newsubmit'] == '1'){$n = "<span class='badge'>New</span>";} else{$n = "";}
                                                if($b['finaldoc'] == '1'){$f = "<span class='badge' data-toggle='tooltip' title='FINALDOC'><span class='glyphicon glyphicon-pushpin' aria-hidden='true'></span></span>";} else{$f = "";}
                                                echo '<tr>
                                                        <td>
                                                            '.$b['doctype_desc'].' <span class="badge"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span></span> ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                                
                                                
                                            }
                                            else{
                                                if($b['newsubmit'] == '1'){$n = "<span class='badge'>New</span>";} else{$n = "";}
                                                if($b['finaldoc'] == '1'){$f = "<span class='badge' data-toggle='tooltip' title='FINALDOC'><span class='glyphicon glyphicon-pushpin' aria-hidden='true'></span></span>";} else{$f = "";}
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
                                                        <a class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
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
                      
                      
                  </div>
              </div>
              
              
              
              
              
              
            
            <div class="row">
                <div class="col-lg-12">
                    <?php $id = (int) $_GET['id'];?>
                    <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="sub_id">
                    
                    <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusaction" type="hidden" value="3" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statusdate" type="hidden" value="<?php 
                        date_default_timezone_set('Asia/Manila');
                        $datetime = date("Y-m-d H:i:s",strtotime("now")); echo $datetime;?>" placeholder="" class="form-control input-md">
                     <input id="statusaction" name="statususername" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                    
                     <?php
                     $ppaid = $obj->getmaxpropapp($id);
                     $maxpparequest = $obj->getmaxpropapp($id);
                     $maxrev = $obj->getmaxreviewer($id);
                     $maxrevpa = $obj->getmaxreviewerpa($id,$ppaid);
                     
                     //get max revision
                     $revision = $obj->getMaxRevisionperpa($id, $ppaid);
                     
                     echo '<input name="revision" type="hidden" value="'.$revision.'">';
                     echo '<input id="subid" name="ppaids" type="hidden" value="'.$maxpparequest.'">';
                     
                     if($maxrevpa == 0){
                         $nextmax = 1;
                         $allRev = $obj->getAllReviewer($id, 1);
                     
                            if($allRev > 0){
                                foreach($allRev as $revs){
                                    echo '<input id="subid" name="subids[]" type="hidden" value="'.$id.'">';
                                    echo '<input id="subid" name="ppaid[]" type="hidden" value="'.$maxpparequest.'">';
                                    echo '<input id="review" name="review[]" type="hidden" value="'.$nextmax.'">';
                                    echo '<input id="puserid" name="puserid[]" type="hidden" value="'.$revs['phrepuser_id'].'">';
                                    echo '<input id="primerev" name="primerev[]" type="hidden" value="'.$revs['primary_reviewer'].'">';  
                                    echo '<input id="confirm" name="confirm[]" type="hidden" value="0">';                            
                                }
                            }
                         
                     }
                     else{
                        
                        $nextmax = $maxrevpa + 1;
                        $allRev = $obj->getAllReviewerpaa($id, $maxrevpa);
                     
                            if($allRev > 0){
                                foreach($allRev as $revs){
                                    echo '<input id="subid" name="subids[]" type="hidden" value="'.$id.'">';
                                    echo '<input id="ppaid" name="ppaid[]" type="hidden" value="'.$maxpparequest.'">';
                                    echo '<input id="review" name="review[]" type="hidden" value="'.$nextmax.'">';
                                    echo '<input id="puserid" name="puserid[]" type="hidden" value="'.$revs['phrepuser_id'].'">';
                                    echo '<input id="primerev" name="primerev[]" type="hidden" value="'.$revs['primary_reviewer'].'">';  
                                    echo '<input id="confirm" name="confirm[]" type="hidden" value="'.$revs['confirmation'].'">';                            
                                }
                            }                         
                     }
                     ?>
                     <?php 
//                        $maxrev = $obj->getmaxreviewerpa($id);
//                        $maxp = $maxrev + 1;
//                        $getChairman = $obj->pullChairman($id, $userid, '1');
//                        if($getChairman>0){
//                            foreach($getChairman as $cm){
//                                echo '<input id="subidss" name="subidss" type="hidden" value="'.$id.'">'; 
//                                echo '<input id="reviews" name="reviews" type="hidden" value="'.$maxp.'">'; 
//                                echo '<input id="puserids" name="puserids" type="hidden" value="'.$cm['phrepuser_id'].'">'; 
//                            }
//                        }
                     ?>
                     
                     <hr>
                     <div class="row">
                         <div class="col-lg-4"></div>
                         <div class="col-lg-4">
                             <h3 class="text-center">Set New Due Date</h3>
                             <div id="sandbox-container">
                                 <input type="text" class="form-control" id="newduedate" name="newduedate"  placeholder="New Due Date" required readonly>
                             </div>
                         </div>                         
                         <div class="col-lg-4"></div>
                     </div><hr><br><br><br>
                     
                    <?php 
                    $getmax = $obj->getMax($id);
                    if(count($getmax)>0){
                        foreach($getmax as $maxid){
                            $where = array("sub_id"=>$id, "id"=>$maxid['maxid']);
                            $myrow = $obj->fetch_record_with_where("proposal_status", $where);
                            $num = count($myrow);
                               if($num>0){    
                                   foreach($myrow as $row){
                                       if (($row['status_action']=='7')||($row['status_action']=='8')||($row['status_action']=='9')||($row['status_action']=='19')||($row['status_action']=='32')||($row['status_action']=='33')||($row['status_action']=='34')||($row['status_action']=='35')||($row['status_action']=='40')||($row['status_action']=='41')||($row['status_action']=='42')||($row['status_action']=='43')){                          
                                            echo '<div class="row">
                                                        <div class="col-lg-4"></div>
                                                        <div class="col-lg-4"><center>           
                                                            <button type="submit" name="completepa" class="btn btn-success submitbut">Complete</button>
                                                            <button type="button" class="btn btn-danger" onclick="incompletedpa()">Incomplete</button>
                                                            <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button></center>
                                                        </div>
                                                        <div class="col-lg-4"></div>              
                                                  </div> ';                                             
                                       }
                                       
                                       else{
                                            echo '<div class="row">
                                                        <div class="col-lg-12">
                                                                <div class="alert alert-danger">
                                                                    <strong>Sorry!</strong> This proposal has been tagged as complete already.
                                                                </div>
                                                        </div>            
                                                  </div> ';              
                                           
                                       }
                                   }    
                               }
                               else{                           
                                   echo '<div class="row">
                                               <div class="col-lg-4"></div>
                                               <div class="col-lg-4"><center>           
                                                   <button type="submit" name="completepa" class="btn btn-success submitbut">Complete</button>
                                                   <button type="button" class="btn btn-danger" onclick="incompletedpa()">Incomplete</button>
                                                   <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button></center>
                                               </div>
                                               <div class="col-lg-4"></div>              
                                         </div> ';                                  
                               }
                               
                        }
                    }

                    
                    ?> 
                            
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8" id="incompletediv" style="display:none;">
                            <h3>Write your reasons why incomplete? </h3>
                            <textarea class="form-control incompletemsg" id="incompletemsg" name="incompletemsg"></textarea><br>
                            <button type="submit" name="incompletepa" class="btn btn-danger">Incomplete</button>
                            <button type="button" class="btn btn-default" onclick="cancelincompa()">Cancel</button>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                      
                  
                  
                </div>
            </div>
          </div>
          </form> 
          </div><!--THIS IS THE FORM AREA-->
        

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
function goBack() {
    window.history.back();
}
</script>
<script>
    $(document).ready(function(){ 
        $('#sandbox-container input').datepicker({
            orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
    });
    
    
    $(document).ready(function(){
    $('.submitbut').prop('disabled',true);
    $('#newduedate').change(function(){
        
        $('.submitbut').prop('disabled', this.value == "" ? true : false);     
    })
});
    
        
function incompletedpa() {
    var x = document.getElementById("incompletediv");
    if (x.style.display === "none") {
        x.style.display = "block";
        document.getElementById("complete").disabled = true;
        document.getElementById("cancel").disabled = true;
        
    } else {
        x.style.display = "none";
        document.getElementById("complete").disabled = false;
        document.getElementById("cancel").disabled = false;
    }
}    

function cancelincompa() {
    var x = document.getElementById("incompletediv");
    if (x.style.display === "none") {
        x.style.display = "block";
        document.getElementById("complete").disabled = true;
        document.getElementById("cancel").disabled = true;
        
    } else {
        x.style.display = "none";
        document.getElementById("complete").disabled = false;
        document.getElementById("cancel").disabled = false;
    }
}
    
CKEDITOR.replace( 'incompletemsg' );
    
    
</script>