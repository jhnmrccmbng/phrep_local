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
	if(!in_array($mi['group'], array('Admins', 'Secretary', 'Reviewer', 'Researcher'))){
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
    .filename{
        font-size:11px;
    }
    .history{
        font-size:13px; 
        font-weight: bold;
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

        
        <?php $id = (int) $_GET['id']; ?>

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
        }
        $where1 = array("id" => $pps);
        $getsponsor = $obj->fetch_record_with_where("sponsorlist", $where1);
        foreach ($getsponsor as $sp) {
            $psp = $sp['spon_desc'];
        }
        ?>
        
        <?php
        $where1 = array("sub_id" => $id);
        $getprop = $obj->fetch_record_with_where("proposal", $where1);
        if($getprop){
            foreach($getprop as $prp){
                $where2 = array("id" => $prp['username']);
                $getprp = $obj->fetch_record_with_where("phrepuser", $where2);
                if($getprp){
                    foreach($getprp as $phrp){
                        $fullname = $phrp['title'].' '.$phrp['fname'].' '.$phrp['mname'].' '.$phrp['lname'];
                        $insti = $phrp['institution'];
                        
                        $where3 = array("memberID" => $phrp['username']);
                        $getmm = $obj->fetch_record_with_where("membership_users", $where3);
                        if($getmm){
                            foreach($getmm as $mm){
                                $email = $mm['email'];
                            }
                        }
                    }
                }
            }
        }
                
        ?>
        
        
        <div class="row">
            <div class="col-lg-12">
                <center>
                    <h1><?php echo $ptitle;?><br><small><?php echo $fullname.' | '.$insti.' | '.$email;?></small></h1>
                </center>
            </div>
        </div>
        
        
        <div class="row"><!--THIS IS THE FORM AREA-->
            <div class="row">
                
                <!--Right pane-->
                <div class="col-lg-6">   
                <h1>Information</h1><hr>
                               
                <dl>
                    <dt>Background</dt>
                    <dd><?php echo $pbg;?></dd>
                    <dt>Objective</dt>
                    <dd><?php echo $pobj;?></dd>
                    <dt>Outcomes</dt>
                    <dd><?php echo $pout;?></dd>
                    <dt>Date of Submission</dt>
                    <dd><?php $d = strtotime($pdsub);   echo date("F j, Y",$d);?></dd>
                    <dt>Primary Sponsor</dt>
                    <dd><?php echo $psp;?></dd>
                </dl>
                              
                <hr>
                
                
                <?php
                
                $where = array("id" => $userid);
                $getusername = $obj->fetch_record_with_where("phrepuser", $where);
                if($getusername){
                    foreach($getusername as $username){
                        $where = array("memberID" => $username['username'], "groupID" => '3');
                        $gettypeuser = $obj->fetch_record_with_where("membership_users", $where);
                        if($gettypeuser){
                            
                        }
                        else{
                            ?>
                
                <h1>Reviewers</h1>
                <div class="panel panel-default">
                <div class="panel-body">
                <?php
                $where = array("sub_id" => $id);
                $getrevt = $obj->fetch_record_with_where("review_type", $where);
                foreach($getrevt as $rv){
                    $where = array("id" => $rv['rt_id']);
                    $getrtype = $obj->fetch_record_with_where("review_type_list", $where);
                    if($getrtype){
                        foreach($getrtype as $rtp){
                            $reviewtype = $rtp['rt_name'];
                        }
                    }
                    else{$reviewtype = 'Not yet assigned.';}
                    
                    $where1 = array("sub_id" => $id, "review" => '1');
                    $getreviewer = $obj->fetch_record_with_where("rev_groups", $where1);                   
                    
                }
                ?>
                
                <p>Type of Review: <b><?php echo $reviewtype; ?></b></p>
                <ol>
                    <?php
                    if($getreviewer){
                        foreach($getreviewer as $gr){
                            $where = array("id" => $gr['phrepuser_id']);
                            $getname = $obj->fetch_record_with_where("phrepuser", $where);
                            foreach($getname as $n){
                                $wheres = array("sub_id" => $id, "review" => '1', "phrepuser_id" => $n['id']);
                                $getprev = $obj->fetch_record_with_where("rev_groups", $wheres);
                                foreach($getprev as $rtype){
                                    $previw = $rtype['primary_reviewer'];
                                    if($previw == '1'){$pp = '- Primary Reviewer';}else{$pp = '';}
                                }
                                echo '<li>'.$n['title'].' '.$n['fname'].' '.$n['mname'].' '.$n['lname'].''.$pp.'</li>';                            
                            }
                        }                        
                    }
                    else{
                        echo '<i>Not yet assigned to any reviewers yet.</i>';
                    }                    
                    ?>
                </ol>
                
                <?php 
                $wheresugrev = array("sub_id" => $id);
                $getpropid = $obj->fetch_record_with_where("proposal", $wheresugrev);
                foreach($getpropid as $ppp){
                    $whereii = array("id" => $ppp['chair_suggest']);
                    $getchairsugg = $obj->fetch_record_with_where("review_type_list", $whereii);
                    foreach($getchairsugg as $ggg){
                        $reviewtypesugg = $ggg['rt_name'];
                        if($ggg['id'] == '3'){
                            $stat = "Primary Reviewers";
                        }
                    }
                }
                
                $wheresug = array("sub_id" => $id);
                $getsuggestionsrev = $obj->fetch_record_with_where("suggest_reviewers", $wheresug);
                if($getsuggestionsrev){
                    echo "<hr><p>Chairman's Reviewers Suggestion: <strong>".$reviewtypesugg."</strong></p><p>".$stat."</p><ol>";
                    foreach($getsuggestionsrev as $grr){
                        $where = array("id" => $grr['phrepuser_id']);
                        $getnamer = $obj->fetch_record_with_where("phrepuser", $where);
                        foreach($getnamer as $nm){
                            echo "<li>";
                            echo $nm['title'].' '.$nm['fname'].' '.$nm['mname'].' '.$nm['lname'];
                            echo "</li>";
                        }
                    }
                    echo "</ol>";
                }
                
                ?>
                </div> 
              </div>                   
                
                <h1>List of Reviews</h1>   
                  <!-- Table -->
                <table class="table table-bordered">
                        <?php
                        $where = array("sub_id" => $id, "phrepuser_id" => $userid);
                        $evals = $obj->fetch_record_with_where("rev_groups", $where);
                        if($evals){
                            $where = array("sub_id" => $id, "revid" => $userid, "revform_id" => "1");
                            $form1 = $obj->fetch_record_with_where("rev_answers", $where);
                            if($form1){
                                echo "<tr>";
                                echo "<td>";
                                echo "Review Form";
                                echo "</td>";
                                echo "<td>";
                                echo '<a class="btn btn-success" href="form1.php?id='.$id.'&idu='.$userid.'&et=1" role="button">VIEW</a>';
                                echo "</td>";
                                echo "</tr>";
                            }
                            
                            $where = array("sub_id" => $id, "revid" => $userid, "revform_id" => "2");
                            $form2 = $obj->fetch_record_with_where("rev_answers", $where);
                            if($form2){
                                echo "<tr>";
                                echo "<td>";
                                echo "ICF Checklist";
                                echo "</td>";
                                echo "<td>";
                                echo '<a class="btn btn-success" href="form1.php?id='.$id.'&idu='.$userid.'&et=2" role="button">VIEW</a>';
                                echo "</td>";
                                echo "</tr>";
                              
                            }
                        }
                        ?>
                </table>
                             
                
                <hr>
                
                
                <?php    
                        }
                    }
                }
                
                ?>
                
                <?php
                
                $where = array("id" => $userid);
                $getusername = $obj->fetch_record_with_where("phrepuser", $where);
                if($getusername){
                    foreach($getusername as $username){
                        $where = array("memberID" => $username['username'], "groupID" => '3');
                        $gettypeuser = $obj->fetch_record_with_where("membership_users", $where);
                        if($gettypeuser){
                            
                        }
                        else{
                ?>
                   
                <h1>History</h1>
                 <div class="panel-group" id="accordions" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOnes">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordions" href="#collapseOnes" aria-expanded="true" aria-controls="collapseOnes">
                                        All list
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOnes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOnes">
                                <div class="panel-body">                                    
                                    <?php 
                                    $where = array("sub_id" => $id);

                                    //getting its file history
                                    $gethistory = $obj->fetch_record_with_where("proposal_status", $where);
                    //                if($gethistory){
//                                        echo '<h1>History</h1>';
                                    if($gethistory){
                                        foreach($gethistory as $gh){
                                            $hid = array("id" => $gh['status_action']);
                                            $gethname = $obj->fetch_record_with_where("proposal_status_action", $hid);

                                            if($gethname){
                                                foreach($gethname as $ghn){
                                                    echo '<span class="history">'.$ghn['action_name'].'</span>';
                                                    echo '<small class="pull-right">'.date("M j, Y @ g:ia",strtotime($gh['status_date'])).'</small><hr>';
                                                }
                                            }
                                        }                                          
                                    }
                                    else{
                                        echo '<span class="history"><i>No information yet.</i></span>';
                                    }                  
                    //                }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                
                <hr>
                 
                <?php    
                        }
                    }
                }
                
                ?>
                
                <h1>Decision Letters</h1>
                 <div class="panel-group" id="accordions" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                                
                            <div class="panel-heading" role="tab" id="headingOness">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordionss" href="#collapseOness" aria-expanded="true" aria-controls="collapseOness">
                                        All list
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOness" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOness">
                                <div class="panel-body">  
                                    
                                    <table class="table table-striped">                                  
                                    <?php 
                                    $where = array("sub_id" => $id, "kind" => 'RL', "doctype" => '16');

                                    //getting its file history
                                    $getdecletter = $obj->fetch_record_with_where("document", $where);
                                    if($getdecletter){
                                        foreach($getdecletter as $b){
                                            echo '<tr>';
                                            echo '<td>'.$b['orig_filename'].'<br><small><small>'; ?>
                                            <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php echo '</small></small></td>';
                                            echo '<td><div class="pull-right">
                                                  <a class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                  <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                                            echo '</div></td></tr>';
                                        }
                                    }
                                    else{
                                        echo '<tr><td colspan="2"><i>No letters yet.</i></td></tr>';
                                    }    
                                    ?>
                                        
                                                                   
                                    <?php 
                                    $where = array("sub_id" => $id, "kind" => 'EC', "doctype" => '15');

                                    //getting its file history
                                    $getecletter = $obj->fetch_record_with_where("document", $where);
                                    if($getecletter){
                                        foreach($getecletter as $b){
                                            $where = array("docid" => $b['doctype']);
                                            $getdoctype = $obj->fetch_record_with_where("document_type", $where);
                                            foreach($getdoctype as $dc){$dctype = $dc['doctype_desc'];}
                                            
                                            echo '<tr>';
                                            echo '<td><strong>'.$dctype.'</strong><br>'.$b['orig_filename'].'<br><small><small>'; ?>
                                            <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php echo '</small></small></td>';
                                            echo '<td><div class="pull-right">
                                                  <a class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                  <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                                            echo '</div></td></tr>';
                                        }
                                    }
                                    ?>    
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                
                <?php                 
                    $where = array("subid" => $id, "notefor" => "inc");

                    //getting its file history
                    $gethistory = $obj->fetch_record_with_where("message", $where);
                    
                    if($gethistory){
                ?>
                
                
                <hr>
                <h1>Reasons for Incomplete</h1>
                 <div class="panel-group" id="accordions" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOnesss">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordionsss" href="#collapseOnesss" aria-expanded="true" aria-controls="collapseOnesss">
                                        All list
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOnesss" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOnesss">
                                <div class="panel-body">                                    
                                    <?php 
                                    $where = array("subid" => $id, "notefor" => "inc");

                                    //getting its file history
                                    $gethistory = $obj->fetch_record_with_where("message", $where);
                    //                if($gethistory){
//                                        echo '<h1>History</h1>';
                                        if($gethistory){
                                                foreach ($gethistory as $gh) {
                                                echo $gh['times'].'<hr>';
                                                echo '<span class="history">' . $gh['message'] . '</span>';
                                                echo '<hr>';
                                            }
                                        } else{
                                        echo '<span class="history"><i>No information yet.</i></span>';
                                    }                  
                    //                }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <?php }?>
                
                
                <?php
                $where = array("phrepuser_id" => $userid);
                $getchair = $obj->fetch_record_with_where("rec_groups", $where);
                foreach($getchair as $ch){
                    if($ch['type_id'] == '1'){
                echo'<hr>';
                echo '<h1>Evaluations</h1>';
                        
                        
                        $geteval = $obj->getevaluations($id);
                        
                        foreach($geteval as $eva){
                            if($eva['puid'] != $userid){
                                
                                $where1 = array("id" => $eva['puid']);
                                $getuser = $obj->fetch_record_with_where("phrepuser", $where1);
                                foreach($getuser as $us){
                                    
                                    $where = array("sub_id" => $id, "phrepuser_id" => $eva['puid'], "evaluation_submitted" => "1");
                                    $getreviewer = $obj->fetch_record_with_where("rev_groups", $where);
                                    if($getreviewer){
                                        echo $us['title'].' '.$us['fname'].' '.$us['mname'].' '.$us['lname'].'<br>';
                                    }
                                }
                                
                                
                                $where = array("sub_id" => $id, "phrepuser_id" => $eva['puid']);
                                $getreview = $obj->fetch_record_with_where("rev_groups", $where);
                                
                                $i = 1;
                                foreach($getreview as $grev){
                                    if($grev['evaluation_type'] != 0){
                                        if($grev['evaluation_type'] == 1){
                                            echo $i.".) <a href='form1.php?id=".$id."&idu=".$eva['puid']."&et=1'>Review Form</a><br>";
                                        }
                                        else if($grev['evaluation_type'] == 2){
                                            echo $i.".) <a href='form1.php?id=".$id."&idu=".$eva['puid']."&et=2'>ICF Checklist</a><br>";
                                        }
                                        else if($grev['evaluation_type'] == 3){
                                            echo $i.".) <a href='form2.php?id=".$id."&idu=".$eva['puid']."&r=3'>Comments</a><br>";
                                        }
                                        
                                    }
                                    else{}
                                    $i++;
                                    
                                }
                                
                                       
                            }
                        }echo '<hr>';
                        
                        
//                        $where = array("sub_id" => $id);
//                        $getev = $obj->fetch_record_with_where("rev_groups", $where);
//                        foreach($getev as $eva){
//                            if($eva['phrepuser_id'] != $userid){
//                                echo $eva['id']." ";
//                            }
//                        }
                        
                        
                    }
                }
                
                
                ?>
                <hr>
                <?php                
                $getevaluators = $obj->getprevreviewersevalsummary($userid, $id);
                if ($getevaluators) {
                    ?>

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
                                
                                $getbypassrev = $obj->getpassrev($id);
                                if ($getbypassrev) {
                                    foreach ($getbypassrev as $p) {
                                        echo '<tr>';
                                        echo '<td>';
                                        echo $obj->ordinalize("1");
                                        echo '</td>';
                                        echo '<td>';
                                        echo $p['fname'] . ' ' . $p['mname'] . ' ' . $p['lname'];
                                        echo '</td>';
                                        echo '<td colspan = "2">';
                                        echo '<a href="form1.php?id=' . $p['sub_id'] . '&idu=' . $p['id'] . '&et=' . $p['rft_id'] . '">' . $p['rft_desc'] . '</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>



                            <?php
                            foreach ($getevaluators as $eval) {
                                if ($eval['review'] != '1') {
                                    if ($eval['evaltype_id'] == '3') {
                                        if ($eval['desid'] == '1') {
                                            echo '<tr class="success">';
                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
                                            echo '<td>' . $eval['dec_desc'] . '</td>';
                                            echo '<td><a href="form2.php?id=' . $id . '&idu=' . $eval['phrepuser_id'] . '&r=' . $eval['review'] . '">' . $eval['evaltype_desc'] . '</a></td>';
                                            echo '</tr>';
                                        } else if (($eval['desid'] == '2') || ($eval['desid'] == '3')) {
                                            echo '<tr class="warning">';
                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
                                            echo '<td>' . $eval['dec_desc'] . '</td>';
                                            echo '<td><a href="form2.php?id=' . $id . '&idu=' . $eval['phrepuser_id'] . '&r=' . $eval['review'] . '">' . $eval['evaltype_desc'] . '</a></td>';
                                            echo '</tr>';
                                        } else if ($eval['desid'] == '5') {
                                            echo '<tr class="danger">';
                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
                                            echo '<td>' . $eval['dec_desc'] . '</td>';
                                            echo '<td><a href="form2.php?id=' . $id . '&idu=' . $eval['phrepuser_id'] . '&r=' . $eval['review'] . '">' . $eval['evaltype_desc'] . '</a></td>';
                                            echo '</tr>';
                                        } else {
                                            echo '<tr>';
                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
                                            echo '<td>' . $eval['dec_desc'] . '</td>';
                                            echo '<td>' . $eval['evaltype_desc'] . '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        if ($eval['desid'] == '1') {
                                            echo '<tr class="success">';
                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
                                            echo '<td>' . $eval['dec_desc'] . '</td>';

                                            echo '<td>';
                                            $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                            foreach ($gevals as $ev) {
                                                echo '<a href="form1.php?id=' . $eval['sub_id'] . '&idu=' . $eval['phrepuser_id'] . '&et=' . $ev['revform_id'] . '"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ' . $ev['evaltype_desc'] . '</a><br>';
                                            }

                                            echo '</td>';

                                            echo '</tr>';
                                        } else if (($eval['desid'] == '2') || ($eval['desid'] == '3')) {
                                            echo '<tr class="warning">';
                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
                                            echo '<td>' . $eval['dec_desc'] . '</td>';

                                            echo '<td>';
                                            $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                            foreach ($gevals as $ev) {
                                                echo '<a href="form1.php?id=' . $eval['sub_id'] . '&idu=' . $eval['phrepuser_id'] . '&et=' . $ev['revform_id'] . '"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ' . $ev['evaltype_desc'] . '</a><br>';
                                            }

                                            echo '</td>';

                                            echo '</tr>';
                                        } else if ($eval['desid'] == '5') {
                                            echo '<tr class="danger">';
                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
                                            echo '<td>' . $eval['dec_desc'] . '</td>';

                                            echo '<td>';
                                            $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
                                            foreach ($gevals as $ev) {
                                                echo '<a href="form1.php?id=' . $eval['sub_id'] . '&idu=' . $eval['phrepuser_id'] . '&et=' . $ev['revform_id'] . '"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ' . $ev['evaltype_desc'] . '</a><br>';
                                            }

                                            echo '</td>';

                                            echo '</tr>';
                                        } else {
                                            echo '<tr>';
                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
                                            echo '<td>' . $eval['dec_desc'] . '</td>';
                                            echo '<td>' . $eval['evaltype_desc'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                } 
//                                else {
//                                    if ($eval['evaltype_id'] == '3') {
//                                        if ($eval['desid'] == '1') {
//                                            echo '<tr class="success">';
//                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
//                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
//                                            echo '<td>' . $eval['dec_desc'] . '</td>';
//                                            echo '<td><a href="form2.php?id=' . $id . '&idu=' . $eval['phrepuser_id'] . '&r=' . $eval['review'] . '">' . $eval['evaltype_desc'] . '</a></td>';
//                                            echo '</tr>';
//                                        } else if (($eval['desid'] == '2') || ($eval['desid'] == '3')) {
//                                            echo '<tr class="warning">';
//                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
//                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
//                                            echo '<td>' . $eval['dec_desc'] . '</td>';
//                                            echo '<td><a href="form2.php?id=' . $id . '&idu=' . $eval['phrepuser_id'] . '&r=' . $eval['review'] . '">' . $eval['evaltype_desc'] . '</a></td>';
//                                            echo '</tr>';
//                                        } else if ($eval['desid'] == '5') {
//                                            echo '<tr class="warning">';
//                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
//                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
//                                            echo '<td>' . $eval['dec_desc'] . '</td>';
//                                            echo '<td><a href="form2.php?id=' . $id . '&idu=' . $eval['phrepuser_id'] . '&r=' . $eval['review'] . '">' . $eval['evaltype_desc'] . '</a></td>';
//                                            echo '</tr>';
//                                        } else {
//                                            echo '<tr>';
//                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
//                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
//                                            echo '<td>' . $eval['dec_desc'] . '</td>';
//                                            echo '<td>' . $eval['evaltype_desc'] . '</td>';
//                                            echo '</tr>';
//                                        }
//                                    } else {
//                                        if ($eval['desid'] == '1') {
//                                            echo '<tr class="success">';
//                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
//                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
//                                            echo '<td>' . $eval['dec_desc'] . '</td>';
//
//                                            echo '<td>';
//                                            $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
//                                            foreach ($gevals as $ev) {
//                                                echo '<a href="form1.php?id=' . $eval['sub_id'] . '&idu=' . $eval['phrepuser_id'] . '&et=' . $ev['revform_id'] . '"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ' . $ev['evaltype_desc'] . '</a><br>';
//                                            }
//
//                                            echo '</td>';
//
//                                            echo '</tr>';
//                                        } else if (($eval['desid'] == '2') || ($eval['desid'] == '3')) {
//                                            echo '<tr class="warning">';
//                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
//                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
//                                            echo '<td>' . $eval['dec_desc'] . '</td>';
//
//                                            echo '<td>';
//                                            $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
//                                            foreach ($gevals as $ev) {
//                                                echo '<a href="form1.php?id=' . $eval['sub_id'] . '&idu=' . $eval['phrepuser_id'] . '&et=' . $ev['revform_id'] . '"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ' . $ev['evaltype_desc'] . '</a><br>';
//                                            }
//
//                                            echo '</td>';
//
//                                            echo '</tr>';
//                                        } else if ($eval['desid'] == '5') {
//                                            echo '<tr class="warning">';
//                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
//                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
//                                            echo '<td>' . $eval['dec_desc'] . '</td>';
//
//                                            echo '<td>';
//                                            $gevals = $obj->getDistinctEval($eval['phrepuser_id'], $eval['sub_id']);
//                                            foreach ($gevals as $ev) {
//                                                echo '<a href="form1.php?id=' . $eval['sub_id'] . '&idu=' . $eval['phrepuser_id'] . '&et=' . $ev['revform_id'] . '"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ' . $ev['evaltype_desc'] . '</a><br>';
//                                            }
//
//                                            echo '</td>';
//
//                                            echo '</tr>';
//                                        } else {
//                                            echo '<tr>';
//                                            echo '<td>' . $obj->ordinalize($eval['review']) . '</td>';
//                                            echo '<td>' . $eval['fname'] . ' ' . $eval['mname'] . ' ' . $eval['lname'] . '</td>';
//                                            echo '<td>' . $eval['dec_desc'] . '</td>';
//                                            echo '<td>' . $eval['evaltype_desc'] . '</td>';
//                                            echo '</tr>';
//                                        }
//                                    }
//                                }
                            }
                            ?>
                        </table>
                    </div>   

                        <?php }
                        ?>
                
                
                </div>
                
                <div class="col-lg-6">            
                <h1>Files</h1><hr>
                    <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="sub_id">
                    <div class="col-lg-12">
                    
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Attachments</h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-striped">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th></th>
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
                    <hr>
                    <h2>Risk Assessment</h2>
                    
                    <div class="panel panel-default">
                        <div class="panel-body">
                    
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        About Research Subjects
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <table class="table table-condensed table-bordered">
                                        <th>Does the proposed research include research subjects:</th><th>Response</th>

                                        <?php
                                        $partid = "1";
                                        $fid = "loa_id";
                                        $myrow = $obj->get_confirmation_joining_two_for_assess("proposal", "assessment_ans", "listofassessement", $id, $fid, $partid);
                                        $num = count($myrow);
                                        if ($num > 0) {
                                            foreach ($myrow as $row) {
                                                echo "<tr><td>" . $row['loa_desc'] . "</td>";
                                                if ($row['loa_ans'] == '1')
                                                    echo "<td>Yes</td>";
                                                else
                                                    echo "<td>No</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?> 
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Research Inclusion
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <table class="table table-condensed table-bordered">
                                        <th>Does the research include:</th><th>Response</th>
                                        <?php
                                        $partid = "2";
                                        $fid = "loa_id";
                                        $myrow = $obj->get_confirmation_joining_two_for_assess("proposal", "assessment_ans", "listofassessement", $id, $fid, $partid);
                                        $num = count($myrow);
                                        if ($num > 0) {
                                            foreach ($myrow as $row) {
                                                echo "<tr><td>" . $row['loa_desc'] . "</td>";
                                                if ($row['loa_ans'] == '1')
                                                    echo "<td>Yes</td>";
                                                else
                                                    echo "<td>No</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Potential Risk
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body">
                                    <table class="table table-condensed table-bordered">
                                        <th>Potential Risks</th><th>Response</th>
                                        <tr>
                                            <td>Level of the risk involved in Research:</td>
                                            <td>
                                                <?php
                                                $fid = "risklevel_id";
                                                $myrow = $obj->get_confirmation_joining_two("proposal", "risklevel", "risklevellist", $id, $userid, $fid);
                                                $num = count($myrow);
                                                if ($num > 0) {
                                                    foreach ($myrow as $row) {
                                                        echo $row['risklevel_desc'];
                                                    }
                                                }
                                                ?> 
                                            </td>                                
                                        </tr>

                                        <tr>
                                            <td>Risk apply to:</td>
                                            <td>
                                                <?php
                                                $fid = "riskapp_id";
                                                $myrow = $obj->get_confirmation_joining_two("proposal", "riskapply", "riskapplist", $id, $userid, $fid);
                                                $num = count($myrow);
                                                if ($num > 0) {
                                                    foreach ($myrow as $row) {
                                                        echo "- " . $row['riskapp_desc'] . "<br>";
                                                    }
                                                }
                                                ?> 
                                            </td>                                
                                        </tr>
                                    </table>    
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingFour">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Potential Benefits
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                <div class="panel-body">
                                    <table class="table table-condensed table-bordered">
                                        <th>Potential Benefits</th><th>Response</th>
                                        <tr>
                                            <td>Benefits from the research project:</td>
                                            <td>
                                                <?php
                                                $fid = "potenben_id";
                                                $myrow = $obj->get_confirmation_joining_two("proposal", "potenbenefits", "potenbenlist", $id, $userid, $fid);
                                                $num = count($myrow);
                                                if ($num > 0) {
                                                    foreach ($myrow as $row) {
                                                        echo "- " . $row['potenben_desc'] . "<br>";
                                                    }
                                                }
                                                ?> 
                                            </td>                                
                                        </tr>
                                        <tr>
                                            <?php
                                            $partid = "4";
                                            $fid = "loa_id";
                                            $myrow = $obj->get_confirmation_joining_two_for_assess("proposal", "assessment_ans", "listofassessement", $id, $fid, $partid);
                                            $num = count($myrow);
                                            if ($num > 0) {
                                                foreach ($myrow as $row) {
                                                    echo "<tr><td>" . $row['loa_desc'] . "</td>";
                                                    if ($row['loa_ans'] == '1')
                                                        echo "<td>Yes</td>";
                                                    else
                                                        echo "<td>No</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            ?>                            
                                        </tr>
                                        <tr>
                                            <td>Conflict of Interest:</td>
                                            <td>                                                                     
                                                <?php
                                                $fid = "intelist_id";
                                                $myrow = $obj->get_confirmation_joining_two("proposal", "coninterest", "coninterlist", $id, $userid, $fid);
                                                $num = count($myrow);
                                                if ($num > 0) {
                                                    foreach ($myrow as $row) {
                                                        echo $row['interlist_desc'] . "<br>";
                                                    }
                                                }
                                                ?> 
                                            </td>                                
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>

                </div>

            </div>
            
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-4"><button type="button" class="btn btn-lg btn-primary btn-block" onclick='goBack()'>Back</button></div>
                <div class="col-lg-4"></div>
                
            </div>
            
            
        </div>
        
        <hr>
        
<!--        <div class="row">
            <input id="fname" name="submid" type="hidden" value="<?php #echo $_GET['id']; ?>">
            <input id="statusaction" name="statususername" type="hidden" value="<?php #echo $mi['username']; ?>">

            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-4"><center>           
                        <button type="submit" name="completefirst" class="btn btn-success">Complete</button>
                        <button type="submit" name="incomplete" class="btn btn-danger">Incomplete</button>
                        <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button></center>
                </div>
                <div class="col-lg-4"></div>              
            </div>                     

        </div>-->
        
        
        
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

<!--DATE PICKER-->
<script>
    $(document).ready(function(){ 
        $('#sandbox-container input').datepicker({
            orientation: "top right",
            autoclose: true,
            todayHighlight: true
        });
    });
    
</script>
<!--DATE PICKER-->