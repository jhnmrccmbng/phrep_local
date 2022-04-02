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
if (!in_array($mi['group'], array('Admins', 'Secretary'))) {
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

        .filename {
            font-size: 11px;
        }

        .history {
            font-size: 15px;
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
            if ($getUserID) {
                foreach ($getUserID as $user) {
                    echo '<input type="hidden" class="form-control" value="' . $user['id'] . '" name="userid">';
                    $userid = $user['id'];
                }
            }
            ?>

            <div class="row">
                <div class="col-lg-12">
                    <?php $id = (int)$_GET['id']; ?>

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
                    if ($getprop) {
                        foreach ($getprop as $prp) {
                            $where2 = array("id" => $prp['username']);
                            $getprp = $obj->fetch_record_with_where("phrepuser", $where2);
                            if ($getprp) {
                                foreach ($getprp as $phrp) {
                                    $fullname = $phrp['title'] . ' ' . $phrp['fname'] . ' ' . $phrp['mname'] . ' ' . $phrp['lname'];
                                    $insti = $phrp['institution'];

                                    $where3 = array("memberID" => $phrp['username']);
                                    $getmm = $obj->fetch_record_with_where("membership_users", $where3);
                                    if ($getmm) {
                                        foreach ($getmm as $mm) {
                                            $email = $mm['email'];
                                        }
                                    }
                                }
                            }
                        }
                    }

                    ?>

                    <center>
                        <h1><?php echo $ptitle; ?><br><small><?php echo $fullname . ' | ' . $insti . ' | ' . $email; ?></small></h1>
                    </center>
                </div>
            </div>


            <div class="row">
                <!--THIS IS THE FORM AREA-->
                <div class="row">
                    <div class="col-lg-6">
                        <h1>Proposal's Files</h1>
                        <hr>
                        <?php $id = (int)$_GET['id']; ?>
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
                                            <th>ACTION</th>
                                        </tr>

                                        <?php
                                        $getmaxbatch = $obj->getmaxbatch($id);
                                        for ($i = 1; $i <= $getmaxbatch; $i++) {
                                            echo '<tr><td class="warning" colspan="2"><strong>' . $obj->ordinalize($i) . ' SUBMISSION</strong></td></tr>';
                                            $getdocbybatch = $obj->getdocbybatch($id, $i);
                                            if ($getdocbybatch) {
                                                foreach ($getdocbybatch as $b) {
                                                    if ($b['doctype'] == '1') {
                                                        if ($b['newsubmit'] == '1') {
                                                            $n = "<span class='badge'>New</span>";
                                                        } else {
                                                            $n = "";
                                                        }
                                                        if ($b['finaldoc'] == '1') {
                                                            $f = "<span class='badge' data-toggle='tooltip' title='FINALDOC'><span class='glyphicon glyphicon-pushpin' aria-hidden='true'></span></span>";
                                                        } else {
                                                            $f = "";
                                                        }
                                                        echo '<tr>
                                                        <td>
                                                            ' . $b['doctype_desc'] . ' <span class="badge"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span></span> (' . $b['doctypetimes'] . ') ' . $n . $f . '
                                                                <br><small class="filename">' . $b['file_name'] . ' | ' . $obj->ordinalize($b['revision']) . ' version <br>'; ?>
                                        <?php $d = strtotime($b['date_uploaded']);
                                        echo date("M j, Y", $d); ?><?php
                                                                                                                echo '</small>
                                                        </td>'; ?>
                                        <?php
                                        echo '<td><center>
                                                        <input name="dlfile" type="hidden" value="' . $b['file_name'] . '">
                                                        <a class="btn btn-success" href="' . $b['path'] . '" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/' . $b['file_name'] . '&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                    } else {
                                        if ($b['newsubmit'] == '1') {
                                            $n = "<span class='badge'>New</span>";
                                        } else {
                                            $n = "";
                                        }
                                        if ($b['finaldoc'] == '1') {
                                            $f = "<span class='badge' data-toggle='tooltip' title='FINALDOC'><span class='glyphicon glyphicon-pushpin' aria-hidden='true'></span></span>";
                                        } else {
                                            $f = "";
                                        }
                                        echo '<tr>
                                                        <td>
                                                            ' . $b['doctype_desc'] . ' (' . $b['doctypetimes'] . ') ' . $n . $f . '
                                                                <br><small class="filename">' . $b['file_name'] . ' | ' . $obj->ordinalize($b['revision']) . ' version <br>'; ?>
                                        <?php $d = strtotime($b['date_uploaded']);
                                        echo date("M j, Y", $d); ?><?php
                                                                                                                echo '</small>
                                                        </td>'; ?>
                                        <?php
                                        echo '<td><center>
                                                        <input name="dlfile" type="hidden" value="' . $b['file_name'] . '">
                                                        <a class="btn btn-success" href="' . $b['path'] . '" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/' . $b['file_name'] . '&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
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
                            <hr>

                            <?php
                            $where = array("sub_id" => $id);
                            $getrevt = $obj->fetch_record_with_where("review_type", $where);

                            if ($getrevt) {
                                ?>

                            <h1>Reviewers</h1>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php
                                    $where = array("sub_id" => $id);
                                    $getrevt = $obj->fetch_record_with_where("review_type", $where);
                                    foreach ($getrevt as $rv) {
                                        $where = array("id" => $rv['rt_id']);
                                        $getrtype = $obj->fetch_record_with_where("review_type_list", $where);
                                        if ($getrtype) {
                                            foreach ($getrtype as $rtp) {
                                                $reviewtype = $rtp['rt_name'];
                                            }
                                        } else {
                                            $reviewtype = 'Not yet assigned.';
                                        }

                                        $where1 = array("sub_id" => $id, "review" => '1');
                                        $getreviewer = $obj->fetch_record_with_where("rev_groups", $where1);
                                    }
                                    ?>

                                    <p>Type of Review: <b><?php echo $reviewtype; ?></b></p>
                                    <ol>
                                        <?php
                                        if ($getreviewer) {
                                            foreach ($getreviewer as $gr) {
                                                $where = array("id" => $gr['phrepuser_id']);
                                                $getname = $obj->fetch_record_with_where("phrepuser", $where);
                                                foreach ($getname as $n) {
                                                    $wheres = array("sub_id" => $id, "review" => '1', "phrepuser_id" => $n['id']);
                                                    $getprev = $obj->fetch_record_with_where("rev_groups", $wheres);
                                                    foreach ($getprev as $rtype) {
                                                        $previw = $rtype['primary_reviewer'];
                                                        if ($previw == '1') {
                                                            $pp = '- Primary Reviewer';
                                                        } else {
                                                            $pp = '';
                                                        }
                                                    }
                                                    echo '<li>' . $n['title'] . ' ' . $n['fname'] . ' ' . $n['mname'] . ' ' . $n['lname'] . '' . $pp . '</li>';
                                                }
                                            }
                                        } else {
                                            echo '<i>Not yet assigned to any reviewers yet.</i>';
                                        }
                                        ?>
                                    </ol>
                                </div>
                            </div>

                            <hr>

                            <?php 
                        }
                        ?>


                            <?php
                            $where = array("sub_id" => $id);
                            $gethistory = $obj->fetch_record_with_where("proposal_status", $where);
                            if ($gethistory) {
                                ?>
                            <h1>History</h1>
                            <div class="panel-group" id="accordions" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-success">
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
                                            if ($gethistory) {
                                                foreach ($gethistory as $gh) {
                                                    $hid = array("id" => $gh['status_action']);
                                                    $gethname = $obj->fetch_record_with_where("proposal_status_action", $hid);

                                                    if ($gethname) {
                                                        foreach ($gethname as $ghn) {
                                                            echo '<span class="history">' . $ghn['action_name'] . '</span>';
                                                            echo '<small class="pull-right">' . date("M j, Y @ g:ia", strtotime($gh['status_date'])) . '</small><hr>';
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo '<span class="history"><i>No information yet.</i></span>';
                                            }
                                            //                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!--KUNG NAAY HISTORY-->
                            <?php 
                        }
                        ?>
                        </div>
                    </div>

                    <!--Right pane-->
                    <div class="col-lg-6">
                        <h1>Proposal's Information</h1>
                        <hr>

                        <?php
                        $where = array("sub_id" => $id);
                        $getpropinfo = $obj->fetch_record_with_where("proposal", $where);
                        foreach ($getpropinfo as $p) {
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

                        <dl>
                            <dt>Code</dt>
                            <dd><?php echo $pcode; ?></dd>
                            <dt>Background</dt>
                            <dd><?php echo $pbg; ?></dd>
                            <dt>Objective</dt>
                            <dd><?php echo $pobj; ?></dd>
                            <dt>Outcomes</dt>
                            <dd><?php echo $pout; ?></dd>
                            <dt>Date of Submission</dt>
                            <dd><?php $d = strtotime($pdsub);
                                echo date("F j, Y", $d); ?></dd>
                            <dt>Primary Sponsor</dt>
                            <dd><?php echo $psp; ?></dd>
                        </dl>

                        <hr>
                        <?php 

                        //                //getting its file history
                        //                $gethistory = $obj->fetch_record_with_where("proposal_status", $where);
                        //                if($gethistory){
                        //                    echo '<h1>History</h1>';
                        //
                        //                    foreach($gethistory as $gh){
                        //                        $hid = array("id" => $gh['status_action']);
                        //                        $gethname = $obj->fetch_record_with_where("proposal_status_action", $hid);
                        //
                        //                        if($gethname){
                        //                            foreach($gethname as $ghn){
                        //                                echo '<br><span class="history">'.$ghn['action_name'].'</span><br>';
                        //                                echo '<small>'.date("M j, Y @ g:ia",strtotime($gh['status_date'])).'</small>';
                        //                            }
                        //                        }
                        //                    }                    
                        //                }
                        ?>
                        <h2>Risk Assessment</h2>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-primary">
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
                                            <th>Does the proposed research include research subjects:</th>
                                            <th>Response</th>

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
                            <div class="panel panel-primary">
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
                                            <th>Does the research include:</th>
                                            <th>Response</th>
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
                            <div class="panel panel-primary">
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
                                            <th>Potential Risks</th>
                                            <th>Response</th>
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
                            <div class="panel panel-primary">
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
                                            <th>Potential Benefits</th>
                                            <th>Response</th>
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

                        <?php
                        $where = array("sub_id" => $id, "kind" => 'RL', "doctype" => '16');

                        //getting its file history
                        $getdecletter = $obj->fetch_record_with_where("document", $where);
                        if ($getdecletter) {
                            ?>
                        <hr>
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
                                            $where = array("sub_id" => $id, "doctype" => '16');

                                            //getting its file history
                                            $getdecletter = $obj->fetch_record_with_where("document", $where);
                                            if ($getdecletter) {
                                                foreach ($getdecletter as $b) {
                                                    echo '<tr>';
                                                    echo '<td>' . $b['orig_filename'] . '<br><small><small>'; ?>
                                            <?php $d = strtotime($b['date_uploaded']);
                                            echo date("M j, Y", $d); ?><?php echo '</small></small></td>';
                                                                                                                    echo '<td><div class="pull-right">
                                                  <a class="btn btn-success" href="' . $b['path'] . '" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                  <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/' . $b['orig_filename'] . '&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                                                                                                                    echo '</div></td></tr>';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo '<tr><td colspan="2"><i>No letters yet.</i></td></tr>';
                                                                                                            }
                                                                                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php

                    }
                    ?>

                        <?php
                        $where = array("sub_id" => $id, "kind" => 'DAL', "doctype" => '37');

                        //getting its file history
                        $getdecletter = $obj->fetch_record_with_where("document", $where);
                        if ($getdecletter) {
                            ?>
                        <hr>
                        <h1>Request For Appeal</h1>
                        <table class="table table-striped">
                            <?php 
                            $where = array("sub_id" => $id, "doctype" => '37');

                            //getting its file history
                            $getdecletter = $obj->fetch_record_with_where("document", $where);
                            if ($getdecletter) {
                                foreach ($getdecletter as $b) {
                                    echo '<tr>';
                                    echo '<td>' . $b['orig_filename'] . '<br><small><small>'; ?>
                            <?php $d = strtotime($b['date_uploaded']);
                            echo date("M j, Y", $d); ?><?php echo '</small></small></td>';
                                                                                                    echo '<td><div class="pull-right">
                                            <a class="btn btn-success" href="' . $b['path'] . '" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                            <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/' . $b['orig_filename'] . '&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                                                                                                    echo '</div></td></tr>';
                                                                                                }
                                                                                            }
                                                                                            ?>
                        </table>
                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
            <hr>
            <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>">
            <input id="statusaction" name="statususername" type="hidden" value="<?php echo $mi['username']; ?>">

            <?php 

            $getifassigned = $obj->getifassgined($id);
            if ($getifassigned == '0') {
                echo '<div class="row">
                                <div class="col-lg-4"></div>
                                    <div class="col-lg-4"><center>           
                                        <button type="submit" name="completefirst" class="btn btn-success" id="complete" onclick="return ddatecheck();">Complete</button>
                                        <button type="button"  class="btn btn-danger" onclick="incompleted()">Incomplete</button>
                                        <button type="button" class="btn btn-default" onclick="goBack()" id="cancel">Cancel</button></center>
                                    </div>
                                <div class="col-lg-4"></div>              
                            </div> ';
            } else {
                echo '<div class="row">
                            <div class="col-lg-12">
                            
                            <h4>Assign New Due Date of Review:</h4>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div id="sandbox-container">
                                        <input type="text" class="form-control" id="newddate" name="newddate" readonly required>
                                    </div><br>
                                </div>
                            </div>                             
                            <hr>
                            
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4"></div>
                                    <div class="col-lg-4"><center>           
                                        <button type="submit" name="completeresubmitted" class="btn btn-success" id="complete" onclick="return ddatecheck();">Complete</button>
                                        <button type="button"  class="btn btn-danger" onclick="incompleted()">Incomplete</button>
                                        <button type="button" class="btn btn-default" onclick="goBack()" id="cancel">Cancel</button></center>
                                    </div>
                                <div class="col-lg-4"></div>              
                            </div> ';
            }
            ?>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8" id="incompletediv" style="display:none;">
                    <h3>Write your reasons why incomplete? </h3>
                    <textarea class="form-control incompletemsg" id="incompletemsg" name="incompletemsg"></textarea><br>
                    <button type="submit" name="incomplete" class="btn btn-success">Done!</button>
                    <button type="button" class="btn btn-default" onclick="cancelincom()">Cancel</button>
                </div>
                <div class="col-lg-2"></div>
            </div>

        </form>
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
    function goBack() {
        window.history.back();
    }
</script>

<!--DATE PICKER-->
<script>
    $(document).ready(function() {
        $('#sandbox-container input').datepicker({
            orientation: "top right",
            autoclose: true,
            todayHighlight: true
        });
    });


    function incompleted() {
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

    function cancelincom() {
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

    CKEDITOR.replace('incompletemsg');

    //function ddatecheck(){
    //    var v = document.getElementById("complete").value;
    //    if(document.getElementById("complete").value === ""){
    //        alert(v);
    //        return false;
    //    }
    //    else{
    //        return true;
    //    }
    //}
</script>
<!--DATE PICKER--> 