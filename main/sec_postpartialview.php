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
              <ul class="pager"><li class="previous"><a href="sec_dashboard_active.php#postapproval"><span aria-hidden="true">&larr;</span> Back</a></li></ul>
              <div class="col-lg-2"><h2>Dashboard</h2></div>
              <div class="col-lg-6"><h2>Decision</h2></div>
              <div class="col-lg-4"><h2>Documents</h2></div>
              
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
              <div class="col-lg-6">
                  <div class="row">
                      <div class="col-lg-12">
                          
                        <?php
                          $where = array("sub_id" => $id);
                          $getprop = $obj->fetch_record_with_where("proposal", $where);
                          foreach ($getprop as $prop) {
                              $ptitle = $prop['prop_ptitle'];
                              $code = $prop['code'];
                              $where = array("id" => $prop['username']);
                              $getuser = $obj->fetch_record_with_where("phrepuser", $where);
                              foreach ($getuser as $user) {
                                  $title = $user['title'];
                                  $fname = $user['fname'];
                                  $mname = $user['mname'];
                                  $lname = $user['lname'];
                              }
                          }
                          ?>
                          
                          
                            <div class="panel panel-default">
                                <div class="panel-body">
                                   <?php echo $code;?>
                                   <h3><?php echo $ptitle;?><br><small><?php echo $title.' '.$fname.' '.$mname.' '.$lname;?></small></h3>
                                    
                                </div>
                            </div>  
                          
                          <h2>Post-Approval Decisions</h2>
                          <div class="panel panel-default">
                            <table class="table table-condensed table-bordered table-hover">
                                <tr>
                                    <th>Reviewer</th>
                                    <th>Decision</th>
                                </tr>
                                
                                    
                                <?php 
                                //GETTING MAX PPAID
                                $maxppaid = $obj->getmaxpropapp($id);
                                //GETTING MAX PPAID
                                
                                $maxrev = $obj->getmaxreviewerpaa($id, $maxppaid);
                                
                                for($i=$maxrev;$i>=1;$i--){
                                    
                                    if($i == $maxrev){
                                        echo '<tr class="success"><td colspan="3"><strong>'.$obj->ordinalize($i).' Decision </strong><span class="badge">New</span></td></tr>';                                        
                                    }
                                    else{
                                        echo '<tr><td colspan="3"><strong>'.$obj->ordinalize($i).' Decision</strong></td></tr>';                                          
                                    }
                                    
                                    
                                    $getevaluators = $obj->getevaluationsfromreviewerspa($userid, $id, $i, $maxppaid);
                                    if($getevaluators){
                                        foreach($getevaluators as $ge){
                                            if($ge['desid'] == '1'){
                                                echo '<tr class="success">';
                                                echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                                echo '<td><a href="crf.php?id='.$id.'&idu='.$ge['phrepuser_id'].'&r='.$i.'">Acknowledged</a></td>';
                                                echo '</tr>';                                              
                                            }
                                            else if(($ge['desid'] == '2')||($ge['desid'] == '3')){
                                                echo '<tr class="warning">';
                                                echo '<td>'.$ge['fname'].' '.$ge['mname'].' '.$ge['lname'].'</td>';
                                                echo '<td><a href="crf.php?id='.$id.'&idu='.$ge['phrepuser_id'].'&r='.$i.'">Needs Additional Documents</a></td>';
                                                echo '</tr>';                                                
                                            }
                                        }   
                                    }       
                                    else{
                                        
                                                echo '<tr>';
                                                echo '<td class="text-center" colspan="2"><i>No evaluation submitted yet.</i></td>';
                                                echo '</tr>';  
                                    }
                                }
                                
                                
                                ?>
                                
                                
                            </table>
                        </div>
                          
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsePreviousEval" aria-expanded="false" aria-controls="collapseExample">
                            Show previous evaluations &rarr;
                            </button>
                                <?php 
//                                $maxrev = $obj->getmaxreviewer($id);
                                $getevaluators = $obj->getprevreviewersevalpa($userid, $id);
                                if($getevaluators){ ?>
                                <hr>
                                <div class="collapse" id="collapsePreviousEval">
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
                                </div>
                                    
                            <?php    }
                                
                                ?>
                                
                          
                          
                      </div>                        
                  </div>
                  
              
              </div>  
              
              <div class="col-lg-4">
                  
                  <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapsePostAppDoc" aria-expanded="false" aria-controls="collapsePrev">
                        Show post-approval documents >>
                  </button> 
                  
                  <div class="collapse" id="collapsePostAppDoc"><br>
                  <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title"><a href="" data-toggle="collapse" data-target="#collapsePostAppDoc" aria-expanded="false" aria-controls="collapsePrev">Post Approval Documents</a></h4>
                            </div>
                            <div class="panel-body" id="collapsePostAppDoc">
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
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-xs btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-xs btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
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
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-xs btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-xs btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
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
                  <hr>
                  
                        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapsePrevdoc" aria-expanded="false" aria-controls="collapsePrev">
                        Show pre-approval documents >>
                        </button>    
                        
                        <div class="collapse" id="collapsePrevdoc"><br>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Pre-Approval Documents</h4>
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
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-xs btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-xs btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
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
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-xs btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-xs btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
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