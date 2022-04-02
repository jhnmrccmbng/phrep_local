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
    height: 500px;}
    .questions{
        font-size: 13pt;
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
                    <div class="col-lg-6"><h1>'.$info['prop_ptitle'].'</h1></div>
                    <div class="col-lg-6">
                        <h3><small>
                                <p class="text-right">
                                    Submitted last ';?><?php $d = strtotime($info['date_submitted']);   echo date("F j, Y",$d); ?> <?php echo'<br><br>'.$info['rt_name'].'<br>
                                </p>
                        </small></h3>            
                    </div>
                    </div>';
                    $revtype = $info['rvid'];
                    $revname = $info['rt_name'];
                    
                    
                }
            }
            
            ?>
            
            <hr>
            <div class="row">
                <div class="col-lg-9">
                    
                    <form class="form-horizontal" role="form" action="rev_dashboard_action.php" method="POST">
                    <h4><b>Guide questions for reviewing the informed consent process and form</b></h4>
                    
                <?php
//                $donereview = $obj->checkifDonereview($id, $userid, "2");
//                if($donereview == '0'){ 
                  $where = array("sub_id" => $id, "revid" => $userid, "revform_id" => "2");
                  $getconsent = $obj->fetch_record_with_where("rev_answers", $where);
                  if($getconsent){
                      foreach($getconsent as $consent){                          
                          
                                   if($consent['idq']==13){ 
                                       $where = array("idq" => $consent['idq']);
                                       $que = $obj->fetch_record_with_where("rev_questions", $where);
                                       foreach($que as $q){
                                            echo "<p class='questions'>".$q['qdesc']."<br>";
                                            
                                            if($consent['ansdesc'] == 'Unable to assess'){
                                                echo '<label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio1'.$q['idq'].'" class="forremove" value="'.$q['idq'].',Unable to assess" required checked="checked"> Unable to assess
                                                    </label>
                                                    <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio2'.$q['idq'].'" value="'.$q['idq'].',Yes"> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio3'.$q['idq'].'" value="'.$q['idq'].',No"> No
                                                    </label>';
                                                
                                            }
                                            else if($consent['ansdesc'] == 'Yes'){
                                                echo '<label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio1'.$q['idq'].'" class="forremove" value="'.$q['idq'].',Unable to assess" required> Unable to assess
                                                    </label>
                                                    <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio2'.$q['idq'].'" value="'.$q['idq'].',Yes" checked="checked"> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio3'.$q['idq'].'" value="'.$q['idq'].',No"> No
                                                    </label>';
                                                
                                            }
                                            else if($consent['ansdesc'] == 'No'){
                                                echo '<label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio1'.$q['idq'].'" class="forremove" value="'.$q['idq'].',Unable to assess" required> Unable to assess
                                                    </label>
                                                    <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio2'.$q['idq'].'" value="'.$q['idq'].',Yes"> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['idq'].'();" name="inlineRadioOptions'.$q['idq'].'" id="inlineRadio3'.$q['idq'].'" value="'.$q['idq'].',No" checked="checked"> No
                                                    </label>';
                                                
                                                
                                            }
                                            
                                            echo "</p>";  
                                            $wheres = array("idq" => "13", "sub_id" => $id, "revid" => $userid);
                                            $getsubans = $obj->fetch_record_with_where("rev_subanswers", $wheres);
                                            foreach($getsubans as $ans){
                                                $subans = $ans['subansdesc'];
                                            }
                                            if($q['withtext'] == 1){
                                                echo '<div id="q'.$q['idq'].'" style="display:none">
                                                        <textarea class="form-control forremove" rows="3" id="t'.$q['idq'].'" name="text'.$q['idq'].'" required>'.$subans.'</textarea>
                                                </div><br>';
                                            } 
                                            
                                       }
                                                                           
                                   }                          
                      }
                                   
                                   
                           ?>
                    
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-11">
                            <div class="subyes questions" id="ifsubyes" style="display:none">
                                <p><strong>Are the participants provided with sufficient information regarding:</strong></p>
                                <?php
                                $getquest = $obj->fetchquestionsanswered("2", $id, $userid);
                                foreach($getquest as $q){
                                    if(($q['aidq'] >= 14)&&($q['aidq'] <= 25)){
                                         echo "<p class='questions'>".$q['qdesc']."<br>";
                                         
                                         if($q['idat'] == '5'){
                                             if($q['ansdesc'] == 'Yes'){
                                                    echo '<label class="radio-inline">
                                                    <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove" required checked="checked"> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                    <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No"> No
                                                    </label>';        
                                             }
                                             else if($q['ansdesc'] == 'No'){
                                                    echo '<label class="radio-inline">
                                                    <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove" required> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                    <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No" checked="checked"> No
                                                    </label>';   }
                                             else{
                                                    echo '<label class="radio-inline">
                                                    <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove" required> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                    <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No"> No
                                                    </label>';  }
                                         }
                                         
                                         if($q['idat'] == '2'){
                                             if($q['ansdesc'] == 'Not applicable'){
                                                    echo '<label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio1'.$q['aidq'].'" value="'.$q['aidq'].',Not applicable" class="forremove" required checked="checked"> Not applicable
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove"> Yes
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No"> No
                                                        </label>';                                                 
                                             }
                                             else if($q['ansdesc'] == 'Yes'){
                                                    echo '<label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio1'.$q['aidq'].'" value="'.$q['aidq'].',Not applicable" class="forremove" required> Not applicable
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove" checked="checked"> Yes
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No"> No
                                                        </label>';  
                                                 
                                             }
                                             else if($q['ansdesc'] == 'No'){
                                                    echo '<label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio1'.$q['aidq'].'" value="'.$q['aidq'].',Not applicable" class="forremove" required> Not applicable
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove"> Yes
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No" checked="checked"> No
                                                        </label>';  
                                                 
                                             }
                                             else{
                                                    echo '<label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio1'.$q['aidq'].'" value="'.$q['aidq'].',Not applicable" class="forremove" required> Not applicable
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove"> Yes
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No"> No
                                                        </label>'; 
                                                 
                                             }
                                         }                                        
                                         
                                         echo "</p><br>";
                                    }
                                }
                                
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                      
                        $getquest = $obj->fetchquestionsanswered("2", $id, $userid);
                                foreach($getquest as $q){
                                    if(($q['aidq'] >= 26)&&($q['aidq'] <= 28)){
                                        echo "<p class='questions'>" . $q['qdesc'] . "<br>";
                                        
                                            if($q['idat'] == '5'){
                                                if($q['ansdesc'] == 'Yes'){
                                                       echo '<label class="radio-inline">
                                                       <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove" required checked="checked"> Yes
                                                       </label>
                                                       <label class="radio-inline">
                                                       <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No"> No
                                                       </label>';        
                                                }
                                                else if($q['ansdesc'] == 'No'){
                                                       echo '<label class="radio-inline">
                                                       <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove" required> Yes
                                                       </label>
                                                       <label class="radio-inline">
                                                       <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No" checked="checked"> No
                                                       </label>';   }
                                                else{
                                                       echo '<label class="radio-inline">
                                                       <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio2'.$q['aidq'].'" value="'.$q['aidq'].',Yes" class="forremove" required> Yes
                                                       </label>
                                                       <label class="radio-inline">
                                                       <input type="radio" onclick="javascript:choices'.$q['aidq'].'();" name="inlineRadioOptions'.$q['aidq'].'" id="inlineRadio3'.$q['aidq'].'" value="'.$q['aidq'].',No"> No
                                                       </label>';  }       
                                            }
                                            
                                            else if($q['idat'] == '3'){
                                                if($q['ansdesc'] == null){
                                                    echo '<div id="q'.$q['aidq'].'" style="display:none">
                                                            <textarea class="form-control forremove" rows="3" id="t'.$q['aidq'].'" name="text'.$q['aidq'].'" required></textarea>
                                                    </div>';
                                                }
                                                else{
                                                    echo '<div id="q'.$q['aidq'].'" style="display:none">
                                                            <textarea class="form-control forremove" rows="3" id="t'.$q['aidq'].'" name="text'.$q['aidq'].'" required>'.$q['ansdesc'].'</textarea>
                                                    </div>';
                                                }
                                                
                                                
                                            }
                                        
                                        echo "</p><br>";
                                    }
                                }                     
                        
                        
                        
                    }
                  else{
                      
                      ///START SA UNA PALANG MAG INVITE.
                      ?>
                    
                        
                        <?php
                           $except = "AND idat != '4'";
                           $getq = $obj->fetchQuestionsReview('2', $except);
                           if($getq){
                               foreach($getq as $q){
                                   if($q['idq']==13){ 
                                        echo "<p class='questions'>".$q['qdesc']."<br>";
                                        echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType($q['idat']));
                                        if($q['withtext'] == 1){
                                            echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType("3"));
                                        }
                                        echo "</p><br>";                                      
                                   }
                               }
                           }
                        ?>
                    
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-11">
                            <div class="subyes questions" id="ifsubyes" style="display:none">
                                <p>Are the participants provided with sufficient information regarding:</p>
                                <?php
                                   $except = "AND idat != '4'";
                                   $getq = $obj->fetchQuestionsReview('2', $except);
                                   if($getq){
                                       foreach($getq as $q){
                                           if(($q['idq']>=14)&&($q['idq']<=25)){
                                                echo "<p class='questions'>".$q['qdesc']."<br>";
                                                echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType($q['idat']));
                                                if($q['withtext'] == 1){
                                                    echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType("3"));
                                                }
                                                echo "</p><br>";                                       
                                           }
                                       }
                                   }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                        <?php
                           $except = "AND idat != '4'";
                           $getq = $obj->fetchQuestionsReview('2', $except);
                           if($getq){
                               foreach($getq as $q){
                                   if(($q['idq']>=26)&&($q['idq']<=28)){ 
                                        echo "<p class='questions'>".$q['qdesc']."<br>";
                                        echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType($q['idat']));
                                        
                                        echo "</p><br>";                                      
                                   }
                               }
                           }
                        ?>
                  <?php                        
                  }
                    ?>
<!--                    <h3><b>Recommendation:</b></h3>
                        <p class="questions">-->
                            <?php
//                            $getdecs = $obj->fetch_record("rev_decision");
//                            if($getdecs){
//                                foreach($getdecs as $ds){
//                                    echo '<label class="radio-inline">
//                                            <input type="radio" name="recommendation" id="inlineRadio'.$ds['id'].'" value="'.$ds['id'].'" required> '.$ds['dec_desc'].'
//                                            </label>';
//                                }
//                            }
                            ?>
                        <!--</p>-->
                        
                        <input id="subid" name="subid" type="hidden" value="<?php echo $id;?>">
                        <input id="revid" name="revid" type="hidden" value="<?php echo $userid;?>">
                        <input id="evaltype" name="evaltype" type="hidden" value="2">
                        <input id="evaldate" name="evaldate" type="hidden" value="<?php $date = date_create('now'); echo date_format($date, 'U');?>">
                        
                        <?php 
                            $maxEF = $obj->getmaxEFdoc($id);
                            echo '<input name="maxef" type="hidden" value="'.$maxEF.'">';
                        ?>
                    <div class="row"><br>
                        <div class="col-lg-12">
                        <!--<button class="btn btn-success btn-lg" type="submit" name="submitreviewconsent">Submit</button>-->
                        
                        <button class="btn btn-primary btn-lg" type="submit" name="savereviewconsent" id="savereviewconsent" data-toggle="tooltip" title="You can return later to submit">Save</button>
                        <button class="btn btn-success btn-lg" type="submit" name="submitreviewconsent" id="submitreviewconsent" data-toggle="tooltip" title="This will finalized your evaluation">Submit</button>
                        
                        <a class="btn btn-default btn-lg" href="<?php echo "reviewproposal.php?id=".$id."#evaluation";?>" role="button">Cancel</a>
                        </div>
                    </div> 
                    </form>
                
                <?php 
//                }
//                else{
//                    echo '<div class="alert alert-warning" role="alert">You have submitted your evaluation already. Thank you.</div>';
//                }
                ?>
                </div>  
                
                
                <div class = "col-lg-3">
                    <div class="panel panel-default">
                    <div class="panel-heading"><b>Details</b></div>
                        <?php 
                        $maxrev = $obj->getmaxreviewer($id);
                        $gettingInfo = $obj->exclusiveForAssignedProposal($userid, $id,$maxrev);
                        if($gettingInfo){
                            foreach($gettingInfo as $info){  
                                echo '<div class="list-group">
                                    <a href="#" class="list-group-item" data-toggle="tooltip" title="PROPOSAL CODE"><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span> '.$info['code'].'</a>
                                    <a href="#" class="list-group-item" data-toggle="tooltip" title="PRIMARY INVESTIGATOR"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> ';
                                    $where = array("id"=>$info['pi']);    
                                    $gettingpi = $obj->fetch_record_with_where("phrepuser", $where);
                                    if($gettingpi){
                                        foreach($gettingpi as $pi){
                                            echo $pi['fname'].' '.$pi['mname'].' '.$pi['lname'];
                                        }
                                    }
                                echo '</a>
                                    <a href="#" class="list-group-item" data-toggle="tooltip" title="REVIEW DEADLINE"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>';?><?php $d = strtotime($info['rt_duedate']);   echo ' '.date("F j, Y",$d); ?> <?php echo'</a>
                                    <a class="btn btn-success list-group-item" href="'.PREPEND_PATH.'rev_proposalview.php?id='.$info['sub_id'].'" role="button" data-toggle="tooltip" title="SEE MORE DETAILS">View More</a>
                                </div>';                                   
                            }
                        }

                        ?>
                    </div>
                    <br>
                    <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Documents</h4>
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
                                                                <br><small class="filename">'.$obj->ordinalize($b['revision']).' version | '; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-success btn-xs" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" onclick="newwin(this.href)" class="btn btn-primary btn-xs" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
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
                                                                <br><small class="filename">'.$obj->ordinalize($b['revision']).' version | '; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-success btn-xs" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a data-toggle="tooltip" title="VIEW" target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" onclick = "newwin(this.href)" class="btn btn-primary btn-xs" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
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

<script type="text/javascript">
document.getElementById('q28').style.display = 'block';

$(document).ready(function() {
  choices13();
});  

function choices13() {
    if (document.getElementById('inlineRadio313').checked) {
        document.getElementById('q13').style.display = 'block';
//        document.getElementById('t13').value = '';
        document.getElementById('t13').required = true;
        document.getElementById('ifsubyes').style.display = 'none';
                
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = false;
//            document.getElementById('inlineRadio2'+i).checked = false;
//            document.getElementById('inlineRadio3'+i).checked = false;           
        }
        document.getElementById('inlineRadio119').required = false; 
        document.getElementById('inlineRadio121').required = false; 
//        document.getElementById('inlineRadio119').checked = false; 
//        document.getElementById('inlineRadio121').checked = false; 
    }
    else if (document.getElementById('inlineRadio213').checked) {
        document.getElementById('ifsubyes').style.display = 'block';
//        document.getElementById('t13').value = '';
        document.getElementById('t13').required = false;
        document.getElementById('q13').style.display = 'none';
                
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = true;        
        }
        document.getElementById('inlineRadio119').required = true; 
        document.getElementById('inlineRadio121').required = true;
    }
    else{
        document.getElementById('q13').style.display = 'none';
        document.getElementById('ifsubyes').style.display = 'none';
        document.getElementById('t13').required = false;        
        
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = false;
//            document.getElementById('inlineRadio2'+i).checked = false;
//            document.getElementById('inlineRadio3'+i).checked = false;            
        }
        document.getElementById('inlineRadio119').required = false; 
        document.getElementById('inlineRadio121').required = false;
//        document.getElementById('inlineRadio119').checked = false; 
//        document.getElementById('inlineRadio121').checked = false;
    } 

}

function removeC() {
    if(document.getElementById('inlineRadio113').checked) {
        
        document.getElementById('t13').required = false;  
        document.getElementById('t13').value = '';        
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = false;
            document.getElementById('inlineRadio2'+i).checked = false;
            document.getElementById('inlineRadio3'+i).checked = false;           
        }
        document.getElementById('inlineRadio119').required = false; 
        document.getElementById('inlineRadio121').required = false; 
        document.getElementById('inlineRadio119').checked = false; 
        document.getElementById('inlineRadio121').checked = false; 
    }
    
    else if(document.getElementById('inlineRadio313').checked) {
        
        document.getElementById('t13').required = true;             
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = false;
            document.getElementById('inlineRadio2'+i).checked = false;
            document.getElementById('inlineRadio3'+i).checked = false;           
        }
        document.getElementById('inlineRadio119').required = false; 
        document.getElementById('inlineRadio121').required = false; 
        document.getElementById('inlineRadio119').checked = false; 
        document.getElementById('inlineRadio121').checked = false;        
    }    
    
    else if(document.getElementById('inlineRadio213').checked) {
        
        document.getElementById('t13').required = false;   
        document.getElementById('t13').value = '';               
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = true;           
        }
        document.getElementById('inlineRadio119').required = true; 
        document.getElementById('inlineRadio121').required = true;      
    }
}

function saveC() {
    if(document.getElementById('inlineRadio113').checked) {
        
        document.getElementById('t13').required = false;  
        document.getElementById('t13').value = '';        
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = false;
            document.getElementById('inlineRadio2'+i).checked = false;
            document.getElementById('inlineRadio3'+i).checked = false;           
        }
        document.getElementById('inlineRadio119').required = false; 
        document.getElementById('inlineRadio121').required = false; 
        document.getElementById('inlineRadio119').checked = false; 
        document.getElementById('inlineRadio121').checked = false; 
    }
    
    else if(document.getElementById('inlineRadio313').checked) {
        
        document.getElementById('t13').required = false;             
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = false;
            document.getElementById('inlineRadio2'+i).checked = false;
            document.getElementById('inlineRadio3'+i).checked = false;           
        }
        document.getElementById('inlineRadio119').required = false; 
        document.getElementById('inlineRadio121').required = false; 
        document.getElementById('inlineRadio119').checked = false; 
        document.getElementById('inlineRadio121').checked = false;        
    }    
    
    else if(document.getElementById('inlineRadio213').checked) {
        
        document.getElementById('t13').required = false;   
        document.getElementById('t13').value = '';               
        for(i = 14; i <= 25; i++){
            document.getElementById('inlineRadio2'+i).required = false;           
        }
        document.getElementById('inlineRadio119').required = false; 
        document.getElementById('inlineRadio121').required = false;      
    }
}

function newwin(a) {
    window.open(a, 'newwindow', 'width=1024, height=768'); 
    return true;
}

$('#savereviewconsent').on('click',function(){
   $('.forremove').removeAttr('required'); 
   
   saveC();
   
});

$('#submitreviewconsent').on('click',function(){
   $('.forremove').prop('required','true');
   
   removeC();   
   
});

</script>