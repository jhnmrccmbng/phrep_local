<?php
include "rev_dashboard_action.php";
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
      .filefont{
          font-size: 14px;
      }
      .proponent{
          font-size: 18px;
      }
    </style>
  </head>
  <body>
      
                            <?php 
                            $where = array("username" => $mi['username']);
                            $getUserID = $obj->getUser("phrepuser", $where);
                            if($getUserID){
                                foreach($getUserID as $user){
                                    echo '<input type="hidden" class="form-control" value="'.$user[id].'" name="userid">';
                                    $userid = $user[id];
                                }
                            }
                            ?>
      <div class="container-fluid">
        <?php $id = (int) $_GET['id'];?>
        <!--<form class="form-horizontal" role="form" action="submission_action.php" method="POST">-->   
            <h1>
                <?php
                $myrow = $obj->get_confirmation_info("proposal", $id, $userid);
                $num = count($myrow);
                if ($num > 0) {
                    foreach ($myrow as $row) {
                        echo $row["prop_ptitle"] . "<br>";
                    }
                }
                ?>
                <?php
                $fid = "insti_id";
                $myrow = $obj->get_confirmation_joining_two("proposal", "studentresdet", "institution", $id, $userid, $fid);
                $num = count($myrow);
                if ($num > 0) {
                    foreach ($myrow as $row) {
                        echo "Institution: " . $row["desc"];
                    }
                }
                ?>
                <?php
                $fid = "acad_id";
                $myrow = $obj->get_confirmation_joining_two("proposal", "studentresdet", "academicdeg_list", $id, $userid, $fid);
                $num = count($myrow);
                if ($num > 0) {
                    foreach ($myrow as $row) {
                        echo "Academic Degree: " . $row["desc_acad"];
                    }
                }
                ?>
            </h1>
            <p class="proponent">
                <?php
                $myrow = $obj->get_data_joined_two("$userid");
                $num = count($myrow);
                if ($num > 0) {
                    foreach ($myrow as $row) {
                        echo "" . $row[fname] . " " . $row[mname] . " " . $row[lname] . "";
                    }
                }
                ?>
                <?php
                $where = array("sub_id" => $id);
                $myrow = $obj->fetch_record_with_where("researcher_additional", $where);
                $num = count($myrow);
                if ($num > 0) {
                    foreach ($myrow as $row) {
                        echo ", " . $row[res_fname] . " " . $row[res_mname] . " " . $row[res_lname] . ", ";
                    }
                }
                ?>
            </p>
            <hr>
            <div class="row">
                <div class="col-lg-6">
                    <dl>
<!--                        <dt><h3>Proponent(s)</h3></dt>
                        <dd>-->
                            <?php
//                            $myrow = $obj->get_data_joined_two("$userid");
//                            $num = count($myrow);
//                            if ($num > 0) {
//                                foreach ($myrow as $row) {
//                                    echo "<b>" . $row[fname] . " " . $row[mname] . " " . $row[lname] . "</b><br>";
//                                    echo "<i>" . $row[email] . "<br>";
//                                    echo "" . $row[institution] . "<br>";
//                                    echo $row[pnum] . "</i>";
//                                }
//                            }
                            ?>
                            <?php
//                            $where = array("sub_id" => $id);
//                            $myrow = $obj->fetch_record_with_where("researcher_additional", $where);
//                            $num = count($myrow);
//                            if ($num > 0) {
//                                foreach ($myrow as $row) {
//                                    echo "<br><br>";
//                                    echo "<b>" . $row[res_fname] . " " . $row[res_mname] . " " . $row[res_lname] . "</b><br>";
//                                    echo "<i>" . $row[res_email] . "<br>";
//                                    echo "Standford University<br>";
//                                    echo $row[res_pnum] . "</i>";
//                                }
//                            }
                            ?>
                        <!--</dd><br>-->
                        
                        <?php
                        $fid = "insti_id";
                        $myrow = $obj->get_confirmation_info("proposal", $id, $userid);
                        $num = count($myrow);
                        if ($num > 0) {
                            foreach ($myrow as $row) {
                                ?>
                        <dt><h3>Background</h3></dt>
                        <dd><?php echo $row["prop_background"];?></dd>
                        
                        <dt><h3>Objectives</h3></dt>
                        <dd><?php echo $row["prop_obj"];?></dd>
                        
                        <dt><h3>Expected Outcomes and Use of Results</h3></dt>
                        <dd><?php echo $row["prop_outcomes"];?></dd>
                        
                                <?php
                            }
                        }
                        ?> 
                        <dt></dt>
                        <dd></dd>
                        
                        <dt></dt>
                        <dd></dd>
                    </dl>
                    <hr>
                    <h3>Additional Information</h3>
                    <dl class="dl-horizontal">
                        <dt>Keywords</dt>
                        <dd>
                            <?php
                            $fid = "kw_id";
                            $myrow = $obj->get_confirmation_joining_two("proposal", "keywords", "keywords_list", $id, $userid, $fid);
                            $num = count($myrow);
                            if ($num > 0) {
                                foreach ($myrow as $row) {
                                    echo "<span class='label label-primary'>" . $row["kw_desc"] . "</span>&nbsp;";
                                }
                            }
                            ?>   
                        </dd>
                        
                        <dt>Duration</dt>
                        <dd>
                            <?php
                            $myrow = $obj->get_date_duration("proposal", $id, $userid);
                            $num = count($myrow);
                            if ($num > 0) {
                                foreach ($myrow as $row) {
                                    $date1 = new DateTime($row['propdet_strtdate']);
                                    $date2 = new DateTime($row['propdet_enddate']);
                                    $interval = $date1->diff($date2);
                                    echo $interval->format('%y Years, %m months and %d days');
                                }
                            }
                            ?>
                        </dd>
                        
                        <dt>Primary Sponsor</dt>
                        <dd>
                            <?php
                            $field1 = "propdet_primspon";
                            $field2 = "id";
                            $myrow = $obj->get_confirmation_joining_one("proposal", "sponsorlist", $field1, $field2, $id, $userid);
                            $num = count($myrow);
                            if ($num > 0) {
                                foreach ($myrow as $row) {
                                    echo $row["spon_desc"];
                                }
                            }
                            ?>
                        </dd>
                        
                        <dt>Secondary Sponsors</dt>
                        <dd>
                            <?php
                            $fid = "spon_id";
                            $myrow = $obj->get_confirmation_joining_two("proposal", "sponsor", "sponsorlist", $id, $userid, $fid);
                            $num = count($myrow);
                            if ($num > 0) {
                                foreach ($myrow as $row) {
                                    echo "" . $row["spon_desc"] . "<br>";
                                }
                            }
                            ?>
                        </dd>
                        
                        <dt>Multi-country Research</dt>
                        <dd>
                            <?php
                            $field1 = "sub_id";
                            $field2 = "sub_id";
                            $myrow = $obj->get_confirmation_joining_one("proposal", "country_multi", $field1, $field2, $id, $userid);
                            $num = count($myrow);
                            if ($num > 0) {
                                foreach ($myrow as $row) {
                                    if (($row["mcountry_stat"]) == '1') {
                                        echo "Yes <br>";
                                        $fid = "country_id";
                                        $myrow = $obj->get_confirmation_joining_two("proposal", "country_list", "country", $id, $userid, $fid);
                                        $num = count($myrow);
                                        if ($num > 0) {
                                            foreach ($myrow as $row) {
                                                echo "- " . $row["country_name"] . "<br>";
                                            }
                                        }
                                    } else
                                        echo "No";
                                }
                            }
                            ?> 
                        </dd>
                        
                        <dt>Nationwide Research</dt>
                        <dd>
                            <?php
                            $field1 = "sub_id";
                            $field2 = "sub_id";
                            $myrow = $obj->get_confirmation_joining_one("proposal", "nationwideres", $field1, $field2, $id, $userid);
                            $num = count($myrow);
                            if ($num > 0) {
                                foreach ($myrow as $row) {
                                    if (($row["nwideres_stat"]) == '3') {
                                        echo "Yes, with randomly selected geographical areas";
                                        $fid = "nreg_code";
                                        $myrow = $obj->get_confirmation_joining_two("proposal", "nationregion", "region", $id, $userid, $fid);
                                        $num = count($myrow);
                                        if ($num > 0) {
                                            foreach ($myrow as $row) {
                                                echo "<li>" . $row["desc"] . "</li>";
                                            }
                                        }
                                    } else if (($row["nwideres_stat"]) == '2') {
                                        echo "No, only in";
                                        $fid = "nreg_code";
                                        $myrow = $obj->get_confirmation_joining_two("proposal", "nationregion", "region", $id, $userid, $fid);
                                        $num = count($myrow);
                                        if ($num > 0) {
                                            foreach ($myrow as $row) {
                                                echo " " . $row["desc"] . "";
                                            }
                                        }
                                    } else
                                        echo "Yes";
                                }
                            }
                            ?> 
                        </dd>
                        
                        <dt>Research Field(s)</dt>
                        <dd>
                            <?php
                            $fid = "resfield_id";
                            $myrow = $obj->get_confirmation_joining_two("proposal", "researchfields", "researchfields_list", $id, $userid, $fid);
                            $num = count($myrow);
                            if ($num > 0) {
                                foreach ($myrow as $row) {
                                    echo "" . $row["resfield_desc"] . "<br>";
                                }
                            }
                            ?> 
                        </dd>
                        
                        <dt>Involves Human Subject</dt>
                        <dd>
                            <?php
                            $field1 = "sub_id";
                            $field2 = "sub_id";
                            $myrow = $obj->get_confirmation_joining_one("proposal", "humansubject", $field1, $field2, $id, $userid);
                            $num = count($myrow);
                            if ($num > 0) {
                                foreach ($myrow as $row) {
                                    if (($row["hmnsubj_code"]) == '1') {
                                        echo "Yes <br>";
                                        $fid = "proptype_id";
                                        $myrow = $obj->get_confirmation_joining_two("proposal", "hmnsubj", "hmnsubj_list", $id, $userid, $fid);
                                        $num = count($myrow);
                                        if ($num > 0) {
                                            foreach ($myrow as $row) {
                                                echo "- " . $row["proptype_name"] . "<br>";
                                            }
                                        }
                                    } else
                                        echo "No";
                                }
                            }
                            ?>
                        </dd>
                        
                        <dt></dt>
                        <dd></dd>
                        
                        <dt></dt>
                        <dd></dd>
                        
                        <dt></dt>
                        <dd></dd>
                    </dl>
                    <hr>
                    <h3>Monetary Support</h3>
                    <dl class="dl-horizontal">
                        <?php
                        $myrow = $obj->gettingMonetarylist($id, $userid);
                        $num = count($myrow);
                        if ($num > 0) {
                            foreach ($myrow as $row) {
                                echo "<dt>" . $row[monsrc_id] . "</dt>";
                                $amount = $row[amount];
                                echo "<dd><p class='pull-right'>&#x20B1; " . number_format($amount, 2, '.', ',') . "</p></dd>";
                            }
                        }
                        ?>                      
                    </dl>
                    
                </div>
                
                <div class="col-lg-6">
                    <h3>Attached Documents</h3>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 class="panel-title">File Summary</h4>
                                </div>
                                <div class="panel-body">

                                    <table class="table table-condensed filefont">
                                        <tr>
                                            <th>TYPE</th><center>
                                            <th>DOWNLOAD</th>
                                            <th>SIZE</th></center>
                                        </tr>

                                        <?php
                                        $id = $_GET['id'];
                                        $myrow = $obj->showingUploadedFiles("document", "document_type", "doctype", "docid", $id, $userid);
                                        $num = count($myrow);
                                        if ($num > 0) {
                                            foreach ($myrow as $row) {
                                                $idd = $row['doctype'];
                                                if ($idd == '1') {
                                                    echo '<tr class="danger">
                                                <td>' . $row[doctype_desc] . '</td><center>
                                                <td><a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-default" href="'.$row[path].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a> </td>'; ?>
                                                <?php echo '<td>'. $obj->human_filesize($row["file_size"]); ?><?php echo'</td></center>'; ?>
                                                <?php echo'</tr>';
                                                } 
                                                
                                                else {
                                                    $myrow3 = $obj->checkingUploadFiles("submission", "document_control", "reclist_id", "erc_id", $idd, $id);
                                                    $naa = count($myrow3);
                                                    if ($naa > 0) {
                                                        echo '<tr><center>
                                                    <td>' . $row[doctype_desc] . '</td>
                                                    <td><a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-default" href="'.$row[path].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a></td>';?>
                                                    
                                                    <?php echo '<td>'. $obj->human_filesize($row["file_size"]); ?><?php echo'</td>'; ?>
                                                    <?php echo'</center></tr>';
                                                    } else {
                                                        echo '<tr><center>
                                                    <td>' . $row[doctype_desc] . '</td>
                                                    <td><a data-toggle="tooltip" title="DOWNLOAD" class="btn btn-default" href="'.$row[path].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a></td>
                                                    <td>';
                                                        ?>
                                                    <?php echo $obj->human_filesize($row["file_size"]); ?><?php echo'</td>'; ?>
                                                    <?php echo'</center></tr>';
                                                    }
                                                }
                                            }
                                        } else {
                                            echo '<tr><td colspan="2"><i><center>No supplementary files have been added to this submission.</center></i></td></tr>';
                                        }
                                        ?>
                                    </table>



                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                    <h3>Risk Assessment</h3>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-primary">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        About Research Subjects
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
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
                                                echo "<tr><td>" . $row[loa_desc] . "</td>";
                                                if ($row[loa_ans] == '1')
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
                                        <th>Does the research include:</th><th>Response</th>
                                        <?php
                                        $partid = "2";
                                        $fid = "loa_id";
                                        $myrow = $obj->get_confirmation_joining_two_for_assess("proposal", "assessment_ans", "listofassessement", $id, $fid, $partid);
                                        $num = count($myrow);
                                        if ($num > 0) {
                                            foreach ($myrow as $row) {
                                                echo "<tr><td>" . $row[loa_desc] . "</td>";
                                                if ($row[loa_ans] == '1')
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
                                                        echo $row["risklevel_desc"];
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
                                                        echo "- " . $row["riskapp_desc"] . "<br>";
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
                                                        echo "- " . $row["potenben_desc"] . "<br>";
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
                                                    echo "<tr><td>" . $row[loa_desc] . "</td>";
                                                    if ($row[loa_ans] == '1')
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
                                                        echo $row["interlist_desc"] . "<br>";
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
          
          <hr>
          
          <div class="row">
              <div class="col-lg-4">
              </div>
              <div class="col-lg-4">
                  <center><button class="btn btn-primary btn-lg" type="submit" onclick='goBack()'>Back</button></center>
              </div>
              <div class="col-lg-4">
              </div>
          </div>
                    
          </div><!--THIS IS THE FORM AREA-->
          
             

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     

    
<div class='modal fade' id='myModal' role='dialog'>
  <div class='modal-dialog'>
    <!-- Modal content-->
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>Oh wait!</h4>
      </div>
        <div class='modal-body'>
            <p>You still need to completely upload the files required.</p>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        </div>
    </div>
  </div>
</div>
    
  </body>
  
</html>
<?php 
include_once("$currDir/footer.php");
?>