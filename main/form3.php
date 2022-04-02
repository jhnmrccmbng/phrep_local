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
	if(!in_array($mi['group'], array('Admins', 'Secretary', 'Reviewer'))){
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
    </style>
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
            <?php $id = (int) $_GET['id']; $idu = (int) $_GET['idu']; $evt = (int) $_GET['et'];?>
            <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
            
            <div class="row">
                <div class="col-lg-12">
                    
                    <div class="row">
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <?php $where = array("secretary" => $userid); $getERCname = $obj->fetch_record("rec_list", $where);?>
                                <?php if($getERCname){foreach($getERCname as $erc){ ?>
                            <tr>
                            <td style="height: 50px; width:190px;" rowspan="5"><img src="../<?php echo $erc['logo_path'];?>" alt="logo" style="width:190px;height:190px;"></td>
                            <td colspan="3">
                            <center>
                            <h4>
                                <?php echo strtoupper($erc['erc_name']);?>
                            </h4>
                            <h5>STANDARD OPERATING PROCEDURES</h5></center>
                            </td>
                            </tr>
                                <?php  }
                                }
                                
                                else{
                                    $table = array("rec_groups", "rec_list");
                                    $join_on = array("rec_list_id", "id");
                                    $where = array("rec_groups.phrepuser_id" => $userid);
                                    $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
                                    if($getdata){
                                        foreach($getdata as $erc){
                                            ?>
                                            <tr>
                                            <td style="height: 50px; width:190px;" rowspan="5"><img src="../<?php echo $erc['logo_path'];?>" alt="logo" style="width:190px;height:190px;"></td>
                                            <td colspan="3">
                                            <center>
                                            <h4>
                                                <?php echo strtoupper($erc['erc_name']);?>
                                            </h4>
                                            <h5>STANDARD OPERATING PROCEDURES</h5></center>
                                            </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            
                            <?php
                            $wheree = array("evaltype_id" => $evt);
                            $evalu = $obj->fetch_record_with_where("rev_evaltype", $wheree);
                            if($evalu){
                                foreach($evalu as $eval){ ?>
                                    
                            
                            <tr>
                                
                            <td style="height: 50px; width:300px;" rowspan="4"><center><h4><?php echo 'Preliminary Evaluation';?></h4></center></td>
                            <td>NEC Form No.</td>
                            
                            <td><?php echo $eval['form_no'];?></td>
                            </tr>
                            <tr>
                            <td>SOP No.</td>
                            <td><?php echo $eval['sop'];?></td>
                            </tr>
                            <tr>
                            <td>Version No.</td>
                            <td><?php echo $eval['version_no'];?></td>
                            </tr>
                            <tr>
                            <td>Version Date</td>
                            <td><?php echo $eval['version_date'];?></td>
                            </tr>
                            
                            <?php
                                }
                            }
                            ?>
                            </tbody>
                            </table>
                            
                            <table class="table table-condensed table-bordered">
                                <tbody>
                                    
                                <?php
                                // $geteval = $obj->getevaluationanswer($idu,$id);
                                // if($geteval){
                                //     foreach($geteval as $ev){


                                $where = array("sub_id" => $id);
                                $geteval = $obj->fetch_record_with_where("rev_exemption_reason", $where);
                                foreach($geteval as $ev){
                                    $revdate = $ev['date'];
                                    $whererev = array("id" => $ev['user_id']);
                                    $reviewer = $obj->fetch_record_with_where("phrepuser", $whererev);
                                    foreach($reviewer as $rev){
                                        $reviewee = $rev['title'].' '.$rev['fname'].' '.$rev['mname'].' '.$rev['lname'];
                                    }

                                    $where = array("sub_id" => $id);
                                    $proposals = $obj->fetch_record_with_where("proposal", $where);
                                    foreach($proposals as $p){
                                        $ptitle = $p['prop_ptitle'];
                                        $code = $p['code'];

                                        $whereres = array("id" => $p['username']);
                                        $researcher = $obj->fetch_record_with_where("phrepuser", $whereres);
                                        foreach($researcher as $res){
                                            $investigator = $res['title'].' '.$res['fname'].' '.$res['mname'].' '.$res['lname'];
                                            $institution = $res['institution'];
                                        }

                                    }
                                }
                                ?>
                                <tr style="height: 23px;">
                                <td style="width: 140px; height: 23px;"><strong>Title of the Study</strong></td>
                                <td style="width: 216px; height: 23px;" colspan="3"><?php echo $ptitle;?></td>
                                </tr>
                                <tr style="height: 26px;">
                                <td style="width: 140px; height: 26px;"><strong>NEC Code</strong></td>
                                <td style="width: 216px; height: 26px;"><?php echo $code;?></td>
                                <td style="width: 124px; height: 26px;"><strong>Type of Review</strong></td>
                                <td style="width: 247px; height: 26px;"><?php echo 'Exempted for Review';?></td>
                                </tr>
                                <tr style="height: 23px;">
                                <td style="width: 140px; height: 23px;"><strong>Principal Investigator</strong></td>
                                <td style="width: 216px; height: 23px;"><?php echo $investigator;?></td>
                                <td style="width: 124px; height: 23px;"><strong>Institution</strong></td>
                                <td style="width: 247px; height: 23px;"><?php echo $institution;?></td>
                                </tr>
                                <tr style="height: 23px;">
                                <td style="width: 140px; height: 23px;"><strong>Reviewer</strong></td>
                                <td style="width: 216px; height: 23px;"><?php echo $reviewee;?></td>
                                <td style="width: 124px; height: 23px;"><strong>Primary Reviewer</strong></td>
                                <td style="width: 247px; height: 23px;"><?php echo 'YES';?></td>
                                </tr>
                                <?php
                                //     }
                                // }
                                ?>
                                

                                <?php
                                    $where = array("sub_id" => $id);
                                    $reasons = $obj->fetch_record_with_where("rev_exemption_reason", $where);
                                    foreach($reasons as $reason){
                                        echo'
                                        <tr style="height: 31px;">
                                            <td style="width: 140px; height: 31px;" colspan="4"><br>';
                                                echo $reason['reason'];
                                            echo '</td>
                                        </tr>';

                                        
                                    }
                                ?>
                                </tbody>
                            </table>
                        
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td style="width: 363px;">&nbsp;<h4><b>Recommendation:</b></h4></td>
                                    <td style="width: 363px;">&nbsp;</td>
                                </tr>

                                <tr>
                                <p>&nbsp;</p>
                                <td style="width: 10px; text-align: center;" colspan="3"><b>
                                    EXEMPTED
                                </tr>
                                       
                                <tr>
                                    <td style="width: 363px; text-align: center; vertical-align: middle;">
                                                <p>&nbsp;</p>
                                                <p><?php echo "<u>&nbsp;&nbsp;&nbsp;".strtoupper($reviewee)."&nbsp;&nbsp;&nbsp;</u>";?></p>
                                                <p><b>Name and Signature of Reviewer</b></p>
                                            </td>
                                            <td style="width: 363px; text-align: center; vertical-align: middle;">
                                                <p>&nbsp;</p>
                                                <p><?php echo "<u>&nbsp;&nbsp;&nbsp;".date("F j, Y",strtotime($revdate))."&nbsp;&nbsp;&nbsp;</u>";?></p>                                                
                                                <p><b>Review Date</b></p>
                                            </td>
                                        
                                        </tr>
                            </tbody>
                        </table>

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