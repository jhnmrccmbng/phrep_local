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
            <?php $id = (int) $_GET['id']; $idu = (int) $_GET['idu']; $r = (int) $_GET['r'];?>
            
            <?php //CHECKING IF PROPOSAL IS REQUESTING POST-APPROVAL
            $where = array("subid" => $id);
            $postapproval = $obj->fetch_record_with_where("proposal_post_approval", $where);
            if($postapproval){$ppa = '1';}else{$ppa = '0';}            
            ?>
            
            
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
                                    $where = array("phrepuser_id" => $userid); $getphreuserrec = $obj->fetch_record("rec_groups", $where);
                                    foreach($getphreuserrec as $ercp){
                                        $where = array("id" => $ercp['rec_list_id']); $getlogo = $obj->fetch_record_with_where("rec_list", $where);
                                        foreach($getlogo as $logo){ ?>
                                            <tr>
                                    <td style="height: 50px; width:190px;" rowspan="5"><img src="../<?php echo $logo['logo_path'];?>" alt="logo" style="width:190px;height:190px;"></td>
                                    <td colspan="3">
                                    <center>
                                    <h4>
                                        <?php echo strtoupper($logo['erc_name']);?>
                                    </h4>
                                    <h5>STANDARD OPERATING PROCEDURES</h5></center>
                                    </td>
                                    </tr>
                                        
                                    <?php    }
                                    }
                                    
                                }
                                
                                ?>
                            
                            <?php
                            $evaltype = $obj->getKindofEvalcomm($idu,$id,$r);
                            if($evaltype){
                                foreach($evaltype as $eval){ ?>
                                    
                            
                            <tr>
                            <td style="height: 50px; width:600px;" rowspan="4"><center><h3><?php echo $eval['evaltype_desc'];?></h3></center></td>
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
                                $geteval = $obj->getevaluationanswer($idu,$id);
                                if($geteval){
                                    foreach($geteval as $ev){
                                ?>
                                <tr style="height: 23px;">
                                <td style="width: 140px; height: 23px;"><strong>Title of the Study</strong></td>
                                <td style="width: 216px; height: 23px;" colspan="3"><?php echo $ev['prop_ptitle'];?></td>
                                </tr>
                                <tr style="height: 26px;">
                                <td style="width: 140px; height: 26px;"><strong>NEC Code</strong></td>
                                <td style="width: 216px; height: 26px;"><?php echo $ev['code'];?></td>
                                <td style="width: 124px; height: 26px;"><strong>Type of Review</strong></td>
                                <td style="width: 247px; height: 26px;"><?php echo $ev['rt_name'];?></td>
                                </tr>
                                <tr style="height: 23px;">
                                <td style="width: 140px; height: 23px;"><strong>Principal Investigator</strong></td>
                                <td style="width: 216px; height: 23px;"><?php $getpi = $obj->getprincipalinvestigator($id); if($getpi){foreach($getpi as $pi){echo $pi['title'].' '.$pi['fname'].' '.$pi['mname'].' '.$pi['lname'];?></td>
                                <td style="width: 124px; height: 23px;"><strong>Institution</strong></td>
                                <td style="width: 247px; height: 23px;"><?php echo $pi['institution']; }}?></td>
                                </tr>
                                <tr style="height: 23px;">
                                <td style="width: 140px; height: 23px;"><strong>Reviewer</strong></td>
                                <td style="width: 216px; height: 23px;"><?php echo $ev['fname']." ".$ev['mname']." ".$ev['lname'];?></td>
                                <td style="width: 124px; height: 23px;"><strong>Primary Reviewer</strong></td>
                                <td style="width: 247px; height: 23px;"><?php if($ev['primary_reviewer']=='1') echo "YES"; else echo "NO";?></td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                                
                                <?php
                                $evaltype = $obj->getKindofEvalcomm($idu,$id,$r);
                                if($evaltype){
                                    foreach($evaltype as $eval){ ?>
                                <tr style="height: 31px;">
                                <td bgcolor="#d3d3d3" style="width: 140px; height: 31px;" colspan="4"><b><?php echo $eval['instruction'];?></b></td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                                
                                <?php
                                if($ppa == '1'){
                                    $ppaid = $obj->getmaxpropapp($id);
                                    $ans = $obj->gettingAnswerscommpa($idu,$id, $r, $ppaid);
                                    if($ans){
                                        foreach($ans as $an){
                                            ?>
                                        <tr style="height: 31px;">
                                            <td style="width: 140px; height: 31px;" colspan="4">
                                                <br>
                                                <?php echo $an['comment']; ?>
                                                <br>
                                            </td>
                                        </tr>
                                    <?php 
                                        } 
                                    } 
                                }
                                else{
                                    $ans = $obj->gettingAnswerscomm($idu,$id, $r);
                                    if($ans){
                                        foreach($ans as $an){
                                            ?>
                                        <tr style="height: 31px;">
                                            <td style="width: 140px; height: 31px;" colspan="4">
                                                <br>
                                                <?php echo $an['comment']; ?>
                                                <br>
                                            </td>
                                        </tr>
                                    <?php 
                                        } 
                                    }                                    
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
                                <?php
                                date_default_timezone_set('Asia/Manila');
                                $geteval = $obj->getevaluationanswercomm($idu, $id, $r);
                                if ($geteval) {
                                    foreach ($geteval as $ev) {
                                        ?>
                                        <tr>
                                            <p>&nbsp;</p>
                                            <td style="width: 10px; text-align: center;" colspan="3"><b>
                                                <?php 
                                                if($ev['decision'] == '1'){
                                                    echo "APPROVED";                                                    
                                                }
                                                else if(($ev['decision'] == '2')||($ev['decision'] == '3')){
                                                    echo "REVISE";                                                    
                                                }
                                                else if($ev['decision'] == '4'){
                                                    echo "EXEMPTED";                                                    
                                                }
                                                else if($ev['decision'] == '5'){
                                                    echo "DISAPPROVED";                                                    
                                                }
                                                else echo "NO DECISION";
                                                ?>
                                                </b></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 363px; text-align: center; vertical-align: middle;">
                                                <p>&nbsp;</p>

                                                  <!-- added by jm: Esig of reviewers -->

                                                  <p>&nbsp;</p>

                                                  <?php $username = $ev['username'];?>
                                                  
                                                  <?php 

                                                  echo "<img src='../images/sig/$username.png' style='width:150px; height:100px; z-index: -0; position: absolute; left: 17%; right: auto;'>"; 

                                                  ?>

                                                  <!-- <img src="../images/sig/fhromualdez.png" alt="logo" style="width:120px;height:90px; z-index: -0; position: absolute; left: 20%; right: auto; "> -->

                                                   
                                                <p>&nbsp;</p>

                                                <p>&nbsp;</p>

                                                <p>&nbsp;</p>


                                                
                                                <?php $namen = $ev['fname']." ".$ev['mname']." ".$ev['lname'];?>
                                                <p><?php echo "<u>&nbsp;&nbsp;&nbsp;".strtoupper($namen)."&nbsp;&nbsp;&nbsp;</u>";?></p>
                                                <p><b>Name and Signature of Reviewer</b></p>
                                            </td>
                                            <td style="width: 363px; text-align: center; vertical-align: middle;">
                                                <p>&nbsp;</p>
                                                <p><?php echo "<u>&nbsp;&nbsp;&nbsp;".date("F d, Y", substr($ev['eval_date'], 0, 10))."&nbsp;&nbsp;&nbsp;</u>";?></p>
                                                <p><b>Review Date</b></p>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
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