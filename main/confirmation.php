<?php
include "confirmation_action.php";
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
	if(!in_array($mi['group'], array('Admins', 'Researcher'))){
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
      }</style>
  </head>
  <body>
      

      <?php 
        $id = $_GET['id'];
        $key = $obj->getmagicword(); 
        $id = $obj->decrypt($id,$key);
        include "checker.php";//checks whether the id has been used by the researcher already.
        $where = array("sub_id" => $id);
        $none = $obj->fetch_record_with_where("proposal", $where);
        foreach($none as $avail){
            if (!$avail['date_submitted'] == null){
                echo '<div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12">
                                        <div class="jumbotron well">
                                                <h2>
                                                        Uh-oh! Sorry the page is not available. Please try again or click the button to return home.
                                                </h2>
                                        </div> 
                                        <a class="btn btn-danger btn-block btn-default" href="dashboard.php" role="button">HOME</a>
                                </div>
                        </div>
                </div>'; 
                exit;
            }
        }
      ?>  
      
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
                    
          <?php 
            $currDirr = dirname(__FILE__);
            include("$currDirr/breadcrumbs.php");
          
          ?>
          
        <!--<form class="form-horizontal" role="form" action="submission_action.php" method="POST">-->   
        <h1>Step 5. Confirming the Submission</h1>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <p>To submit your proposal to Philippine Health Research Ethics 
                        Portal click Finish Submission. You will receive an 
                        acknowledgement by email and will be able to view the 
                        submission's progress through the review process by 
                        logging into this web site.</p>
                </div>
            </div>
        <hr>
        <div class="row">
            <div class="col-lg-8">
                <h2>Proposal Details</h2>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="media">
                                <div class="media-body">

                                    <!-- TITLE -->
                                    <h5 class="media-heading"><b>Title</b></h5>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                            <?php                                    
                                                $myrow = $obj->get_confirmation_info("proposal", $id, $userid);
                                                $num = count($myrow);
                                                    if($num>0){ foreach ($myrow as $row) {
                                                            echo "<em><b>\"".strtoupper($row['prop_ptitle'])."\"</b></em><br>";}}
                                                ?>
                                                <?php
                                                $field1 = "memberID";
                                                $field2 = "username";
                                                $myrow = $obj->get_data_joined_two("membership_users", "phrepuser", $field1, $field2, $id, $mi['username']);
                                                $num = count($myrow);
                                                    if($num>0){ 
                                                        foreach($myrow as $row){
                                                        echo $row['fname']." ".$row['mname']." ".$row['lname']." - ";                                                        
                                                        echo "<small>".$row['institution']."</small>";                              
                                                        }                                                    
                                                    }
                                                ?>
                                                <?php
                                                $where = array("sub_id" => $id);
                                                $myrow = $obj->fetch_record_with_where("researcher_additional", $where);
                                                $num = count($myrow);
                                                    if($num>0){ 
                                                        foreach($myrow as $row){
                                                        echo "<br>";
                                                        echo "<b>".$row['res_fname']." ".$row['res_mname']." ".$row['res_lname']."</b>, ";                                                        
                                                        echo "<small>".$row['res_insti']."</small>";                                     
                                                        }
                                                    }     
                                                ?>
                                                <?php    
                                                // $fid = "insti_id";
                                                // $myrow = $obj->get_confirmation_joining_two("proposal","studentresdet", "institution", $id, $userid, $fid);
                                                // $num = count($myrow);
                                                //     if($num>0){ foreach ($myrow as $row) {
                                                //             echo "<small>".$row['desc'].",";}}
                                                ?>
                                                <?php  
                                                // $fid = "acad_id";
                                                // $myrow = $obj->get_confirmation_joining_two("proposal","studentresdet", "academicdeg_list", $id, $userid, $fid);
                                                // $num = count($myrow);
                                                //     if($num>0){ foreach ($myrow as $row) {
                                                //             echo $row['desc_acad']."</small>";}}
                                            ?>
                                            </div>
                                        </div>
                                    <!-- TITLE -->
                                    <?php
                                     $fid = "insti_id";
                                     $myrow = $obj->get_confirmation_info("proposal", $id, $userid);
                                     $num = count($myrow);
                                         if($num>0){ 
                                             foreach ($myrow as $row) {
                                    
                                    ?>
                                    <!-- Background -->
                                    <h5 class="media-heading"><b>Background</b></h5>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <?php echo $row['prop_background'];?>
                                            </div>
                                        </div>
                                    <!-- Background -->
                                    
                                    <!-- Objectives -->
                                    <h5 class="media-heading"><b>Objectives</b></h5>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <?php echo $row['prop_obj'];?>
                                            </div>
                                        </div>
                                    <!-- Objectives -->
                                    
                                    <!-- Expected -->
                                    <h5 class="media-heading"><b>Expected Outcomes ans Use of Result</b></h5>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <?php echo $row['prop_outcomes'];?>
                                            </div>
                                        </div>
                                    <!-- Expected -->

                                    <?php
                                    }}


                                    ?>
                                    
                                    <!-- More Details -->
                                    <h5 class="media-heading"><b>More Details</b></h5>
                                        <div class="panel panel-default">
                                            <div class="panel-body">

                                                <div class="row">
                                                    <div class="col-lg-12">

                                                        <table class="table table-bordered table-condensed">
                                                            <tr class="active"><td width="33.3%">Duration</td><td width="33.3%">Multi-Country Research</td><td width="33.3%">Nationawide Research</td></tr>
                                                            <tr>

                                                            <td>
                                                            <?php
                                                            $myrow = $obj->get_date_duration("proposal", $id, $userid);
                                                            $num = count($myrow);
                                                                if($num>0){ foreach ($myrow as $row) {
                                                                        $date1 = new DateTime($row['propdet_strtdate']);
                                                                        $date2 = new DateTime($row['propdet_enddate']);
                                                                        $interval = $date1->diff($date2);
                                                                        $year = $interval->format('%y');
                                                                        $month = $interval->format('%m');
                                                                        $day = $interval->format('%d');
                                                                        if($year != 0){
                                                                            if($year == 1){echo $year." year ";}
                                                                            else{echo $year." years ";}                                                                            
                                                                        }
                                                                        if($month != 0){
                                                                            if($month == 1){echo $month." month ";}
                                                                            else{echo $month." months ";} 
                                                                        }
                                                                        if($day != 0){
                                                                            if($day == 1){echo $day." day";}
                                                                            else{echo $day." days";} 
                                                                        }
                                                                        // echo $interval->format('%y Years, %m months and %d days');                                                                        
                                                                }}
                                                            ?>
                                                            </td>
                                                            <td>
                                                            <?php    
                                                            $field1 = "sub_id";
                                                            $field2 = "sub_id";
                                                            $myrow = $obj->get_confirmation_joining_one("proposal","country_multi", $field1, $field2, $id, $userid);
                                                            $num = count($myrow);
                                                                if($num>0){ foreach ($myrow as $row) {
                                                                        if (($row['mcountry_stat']) == '1'){
                                                                            echo "Yes <br>";
                                                                            $fid = "country_id";
                                                                            $myrow = $obj->get_confirmation_joining_two("proposal","country_list", "country", $id, $userid, $fid);
                                                                            $num = count($myrow);
                                                                                if($num>0){ foreach ($myrow as $row) {
                                                                                    echo "<small>- ".$row['country_name']."<br></small>";}}                                       
                                                                        }
                                                                        else echo "No";
                                                                        
                                                                }}
                                                            ?> 
                                                            </td>
                                                            <td>
                                                            <?php    
                                                            $field1 = "sub_id";
                                                            $field2 = "sub_id";
                                                            $myrow = $obj->get_confirmation_joining_one("proposal","nationwideres", $field1, $field2, $id, $userid);
                                                            $num = count($myrow);
                                                                if($num>0){ foreach ($myrow as $row) {
                                                                        if (($row['nwideres_stat']) == '3'){
                                                                            echo "Yes, with randomly selected geographical areas:<br>";
                                                                            $fid = "nreg_code";
                                                                            $myrow = $obj->get_confirmation_joining_two("proposal","nationregion", "region", $id, $userid, $fid);
                                                                            $num = count($myrow);
                                                                                if($num>0){ foreach ($myrow as $row) {
                                                                                        echo "<small>- ".$row['desc']."<br></small>";}}                                       
                                                                        }
                                                                        else if (($row['nwideres_stat']) == '2'){
                                                                            echo "No, only in";
                                                                            $fid = "nreg_code";
                                                                            $myrow = $obj->get_confirmation_joining_two("proposal","nationregion", "region", $id, $userid, $fid);
                                                                            $num = count($myrow);
                                                                                if($num>0){ foreach ($myrow as $row) {
                                                                                    echo "<small>- ".$row['desc']."<br></small>";}}                                       
                                                                        }
                                                                        else echo "Yes";                                                
                                                                }}
                                                            ?>  
                                                            </td>
                                                            
                                                            </tr>
                                                        </table>

                                                        <table class="table table-bordered table-condensed">
                                                            <tr class="active"><td width="33.3%">Research Fields</td><td width="33.3%">Involves Human Subject</td><td width="33.3%">Data Collection</td></tr>
                                                            
                                                            <tr>
                                                            <td>
                                                            <?php    
                                                                $fid = "resfield_id";
                                                                $myrow = $obj->get_confirmation_joining_two("proposal","researchfields", "researchfields_list", $id, $userid, $fid);
                                                                $num = count($myrow);
                                                                    if($num>0){ foreach ($myrow as $row) {
                                                                        echo "<small>- ".$row['resfield_desc']."<br></small>";}} 
                                                            ?>
                                                            </td>
                                                            <td>
                                                            <?php    
                                                                $field1 = "sub_id";
                                                                $field2 = "sub_id";
                                                                $myrow = $obj->get_confirmation_joining_one("proposal","humansubject", $field1, $field2, $id, $userid);
                                                                $num = count($myrow);
                                                                    if($num>0){ foreach ($myrow as $row) {
                                                                            if (($row['hmnsubj_code']) == '1'){
                                                                                echo "Yes <br>";
                                                                                $fid = "proptype_id";
                                                                                $myrow = $obj->get_confirmation_joining_two("proposal","hmnsubj", "hmnsubj_list", $id, $userid, $fid);
                                                                                $num = count($myrow);
                                                                                    if($num>0){ foreach ($myrow as $row) {
                                                                                            echo "<small>- ".$row['proptype_name']."<br></small>";}}                                       
                                                                            }
                                                                            else echo "No";
                                                                            
                                                                    }}
                                                            ?>  
                                                            </td>
                                                            <td>
                                                            <?php    
                                                            $fid = "datacol_id";
                                                            $myrow = $obj->get_confirmation_joining_two("proposal","datacol", "datacol_list", $id, $userid, $fid);
                                                            $num = count($myrow);
                                                                if($num>0){ foreach ($myrow as $row) {                                                                    
                                                                    echo $row['datacol_name']."<br>";}
                                                                }
                                                                    
                                                                else{}
                                                            ?>  
                                                            </td>
                                                            </tr>
                                                        </table>

                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    <!-- More Details -->
                                    
                                    <!-- Keywords -->
                                    <h5 class="media-heading"><b>Keyword(s)</b></h5>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                            <?php    
                                                $fid = "kw_id";
                                                $myrow = $obj->get_confirmation_joining_two("proposal","keywords", "keywords_list", $id, $userid, $fid);
                                                $num = count($myrow);
                                                    if($num>0){ foreach ($myrow as $row) {
                                                            echo '<input class="btn btn-default btn-sm" type="button" value="'.$row['kw_desc'].'">&nbsp;';
                                                        }}
                                                            
                                            ?>  
                                            </div>
                                        </div>
                                    <!-- Keywords -->

                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            
            
            <div class="col-lg-4">

                <h2>Documents</h2>

                <!-- Documents -->
                <div class="row">
                    <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">File Summary</h4>
                            </div>
                            <div class="panel-body">
                          
                        <table class="table table-striped">
                        
                        <?php 

//                        $id = $_GET['id']; 
                        $myrow = $obj->showingUploadedFiles("document", "document_type", "doctype", "docid", $id, $userid);
                        $num = count($myrow);
                            if($num>0){
                            foreach ($myrow as $row) {
                                $d = strtotime($row['date_uploaded']);
                                $idd = $row['doctype'];
                                if ($idd == '1'){
                                    echo '<tr class="success">
                                            <td>
                                                <b>'.$row['orig_filename'].'</b><br>
                                                <small>'.$row['doctype_desc'].'<br>
                                                '.$obj->human_filesize($row['file_size']).'<br>
                                                '.date("F j, Y",$d).'</small>
                                            </td>';
                                    echo'</tr>';
                                }
                                else{
                                $myrow3 = $obj->checkingUploadFiles("submission", "document_control", "reclist_id", "erc_id", $idd, $id);
                                $naa = count($myrow3);
                                    if ($naa > 0){
                                        echo '<tr class="success">
                                                <td>
                                                    <b>'.$row['orig_filename'].'</b><br>
                                                    <small>'.$row['doctype_desc'].'<br>
                                                    '.$obj->human_filesize($row['file_size']).'<br>
                                                    '.date("F j, Y",$d).'</small>
                                                </td>';
                                        echo'</tr>';
                                    }
                                    else{
                                        
                                        echo '<tr>
                                        <td>
                                            <b>'.$row['orig_filename'].'</b><br>
                                            <small>'.$row['doctype_desc'].'<br>
                                            '.$obj->human_filesize($row['file_size']).'<br>
                                            '.date("F j, Y",$d).'</small>
                                        </td>';
                                        echo'</tr>';
                                    }
                                }
                                
                            }
                            }
                            else{
                                echo '<tr><td colspan="4"><i><center>No supplementary files have been added to this submission.</center></i></td></tr>';
                            }
                        ?>
                    </table>
                                
                                
                                
                            </div>
                          </div>


                          
                    </div>               
                </div>
                <!-- Documents --> 
                
                <!-- Documents -->
                <div class="row">
                    <div class="col-lg-12">
                    
                    <h2>Source of Fund</h2>
                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                            <!-- Table -->
                            <table class="table table-bordered table-condensed">

                                <tr class="active">
                                    <td>Primary Sponsor</td>
                                </tr>
                                <tr>
                                    <td>   
                                        <?php    
                                            $field1 = "propdet_primspon";
                                            $field2 = "id";
                                            $myrow = $obj->get_confirmation_joining_one("proposal","sponsorlist", $field1, $field2, $id, $userid);
                                            $num = count($myrow);
                                                if($num>0){ foreach ($myrow as $row) {
                                                        echo "<small>".$row['spon_desc']."</small>";}}
                                        ?> 
                                    </td>
                                </tr>

                                <tr class="active">
                                    <td>Secondary Sponsor</td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                        <?php    
                                        $fid = "spon_id";
                                        $myrow = $obj->get_confirmation_joining_two("proposal","sponsor", "sponsorlist", $id, $userid, $fid);
                                        $num = count($myrow);
                                            if($num>0){ foreach ($myrow as $row) {
                                                    echo "<small>".$row['spon_desc']."<br></small>";}}
                                        ?>  

                                    </td>
                                </tr>

                                <tr class="active">
                                    <td>Monetary Support</td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                        <?php    
                                        $myrow = $obj->gettingMonetarylist($id, $userid);
                                        $num = count($myrow);
                                            if($num>0){ foreach ($myrow as $row) {
                                                $amount = $row['amount'];  
                                                echo "<small>&#x20B1; ".number_format($amount, 2, '.', ',')."<br>";
                                                echo $row['monsrc_id']."</small>";
                                            }} 
                                        ?> 

                                    </td>
                                </tr>

                            </table>
                    </div>
                    

                    </div>               
                </div>
                <!-- Documents --> 
                
                <!-- Documents -->
                <div class="row">
                    <div class="col-lg-12">
                    <h2>Risk Assessment</h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#risk">
                    Open to view
                    </button>
                    </div>               
                </div>
                <!-- Documents -->                                       
            
            </div>
        
        </div>

          
          <hr>


          
          <h4>Comments for the Secretariat</h4>
          
          </div><!--THIS IS THE FORM AREA-->
          <form action = "confirmation_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
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
              
              <?php
              $field = array("sub_id"=>$id, "username"=>$userid);
              $myrow = $obj->fetch_record_with_where("proposal", $field);
              $num = count($myrow);
                    if($num>0){ foreach ($myrow as $row) {
                        if (empty($row['commentforsec'])){
                            echo "<div class='form-group'>
                                    <div class='col-md-6'>                     
                                      <textarea class='form-control' id='textarea' name='commentsforsec' data-toggle='tooltip' data-placement='bottom' title='Say something to the Secretariat!'></textarea>
                                    </div>
                                  </div>
                                  <div class='form-group'>
                                      <div class='col-md-4'>
                                          <input id='username' name='username' type='hidden' value='".$userid."' placeholder='' class='form-control input-md'>
                                      </div>
                                  </div>";
                        }
                        else {
                            echo "<div class='form-group'>
                                    <div class='col-md-6'>                     
                                      <textarea class='form-control' id='textarea' name='commentsforsec'>".$row['commentforsec']."</textarea>
                                    </div>
                                  </div>
                                  <div class='form-group'>
                                      <div class='col-md-4'>
                                          <input id='username' name='username' type='hidden' value='".$userid."' placeholder='' class='form-control input-md'>
                                      </div>
                                  </div>";
                        }
                    
                    }} 
              ?>
              
            <hr>
            
            <h4>Please read REMINDER and IMPORTANT then mark it checked before clicking "Submit Proposal".</h4>
            
            <div class="alert alert-warning" role="alert">
                <div class="checkbox">
                    <label>
                    <input type="checkbox" id="reminder" onclick="check();"> <strong>REMINDER!</strong> Please make sure information entered 
                    here are all correct. You can't change information once it is submitted. Please go 
                    back and review if needed.
                    </label>
                </div>            
            </div>
          
            <div class="alert alert-danger" role="alert">
                <div class="checkbox">
                    <label>
                    <input type="checkbox" id="important" onclick="check();"> <strong>IMPORTANT!</strong> Confirmation email will be sent after you 
                    click "Submit Proposal". Please check your SPAM folder you are not recieving email confirmation
                    after 3 minutes.
                    </label>
                </div>            
            </div>

            
          <div class="row"><!--THIS IS THE BUTTON-->
              <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                
                <div class="col-lg-4">           
                    <button type="submit" name="confirmsubmission" id="confirmsubmission" class="btn btn-info btn-lg" disabled="disabled">Submit Proposal</button>
                    <button type="button" class="btn btn-default btn-lg" onclick='goBack()'>Back</button>
                </div>
                <div class="col-lg-4"></div>              
          </div><!--THIS IS THE BUTTON-->
          
          <!--THIS IS FOR THE CODE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!-->
          <?php 
          $id1 = "sub_id";
          $id2 = "sub_id";
          $id3 = "reclist_id";
          $id4 = "id";

          $myrow = $obj->generatingCode("proposal", "submission", "rec_list", $id1, $id2, $id3, $id4, $userid, $id);
          $num = count($myrow);
          if ($num > 0) {
            foreach ($myrow as $row) {
                date_default_timezone_set('Asia/Manila');
                $dat = strtotime("now");
                echo'<input id="fname" name="code" type="hidden" value="'.date("Ymd",$dat).'-'.$row['sub_id'].'-'.$row['erc_initials'].'" placeholder="" class="form-control input-md">';
                
              }
          }
          ?>
          <!--THIS IS FOR THE CODE-->
          
          
          
          </form>
          
      </div>    

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




<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="risk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Risk Assessment Summary</h4>
      </div>
      <div class="modal-body">
      <table class="table table-condensed table-bordered">
                            <th>Does the proposed research include research subjects:</th><th>Response</th>
                            
                            <?php    
                                $partid= "1";
                                $fid = "loa_id";
                                $myrow = $obj->get_confirmation_joining_two_for_assess("proposal","assessment_ans", "listofassessement", $id, $userid, $fid, $partid);
                                $num = count($myrow);
                                    if($num>0){ foreach ($myrow as $row) {
                                        echo "<tr><td>".$row['loa_desc']."</td>"; 
                                        if($row['loa_ans'] == '1')
                                            echo "<td>Yes</td>"; 
                                        else
                                            echo "<td>No</td>";
                                        echo "</tr>";}} 
                            ?> 
                            
                            <th>Does the research include:</th><th></th>
                            <?php    
                                $partid= "2";
                                $fid = "loa_id";
                                $myrow = $obj->get_confirmation_joining_two_for_assess("proposal","assessment_ans", "listofassessement", $id, $userid, $fid, $partid);
                                $num = count($myrow);
                                    if($num>0){ foreach ($myrow as $row) {
                                        echo "<tr><td>".$row['loa_desc']."</td>"; 
                                        if($row['loa_ans'] == '1')
                                            echo "<td>Yes</td>"; 
                                        else
                                            echo "<td>No</td>";
                                        echo "</tr>";}} 
                            ?>
                            
                            <th>Potential Risks</th><th></th>
                            <tr>
                                <td>Level of the risk involved in Research:</td>
                                <td>
                                    <?php    
                                    $fid = "risklevel_id";
                                    $myrow = $obj->get_confirmation_joining_two("proposal","risklevel", "risklevellist", $id, $userid, $fid);
                                    $num = count($myrow);
                                        if($num>0){ foreach ($myrow as $row) {
                                            echo $row['risklevel_desc'];}} 
                                    ?> 
                                </td>                                
                            </tr>
                            
                            <tr>
                                <td>Risk apply to:</td>
                                <td>
                                    <?php    
                                    $fid = "riskapp_id";
                                    $myrow = $obj->get_confirmation_joining_two("proposal","riskapply", "riskapplist", $id, $userid, $fid);
                                    $num = count($myrow);
                                        if($num>0){ foreach ($myrow as $row) {
                                            echo "- ".$row['riskapp_desc']."<br>";}} 
                                    ?> 
                                </td>                                
                            </tr>
                            
                            <th>Potential Benefits</th><th></th>
                            <tr>
                                <td>Benefits from the research project:</td>
                                <td>
                                    <?php    
                                    $fid = "potenben_id";
                                    $myrow = $obj->get_confirmation_joining_two("proposal","potenbenefits", "potenbenlist", $id, $userid, $fid);
                                    $num = count($myrow);
                                        if($num>0){ foreach ($myrow as $row) {
                                            echo "- ".$row['potenben_desc']."<br>";}} 
                                    ?> 
                                </td>                                
                            </tr>
                            <tr>
                                    <?php    
                                        $partid= "4";
                                        $fid = "loa_id";
                                        $myrow = $obj->get_confirmation_joining_two_for_assess("proposal","assessment_ans", "listofassessement", $id, $userid, $fid, $partid);
                                        $num = count($myrow);
                                            if($num>0){ foreach ($myrow as $row) {
                                                echo "<tr><td>".$row['loa_desc']."</td>"; 
                                                if($row['loa_ans'] == '1')
                                                    echo "<td>Yes</td>"; 
                                                else
                                                    echo "<td>No</td>";
                                                echo "</tr>";}} 
                                    ?>                            
                            </tr>
                            <tr>
                                <td>Conflict of Interest:</td>
                                <td>                                                                     
                                    <?php    
                                    $fid = "intelist_id";
                                    $myrow = $obj->get_confirmation_joining_two("proposal","coninterest", "coninterlist", $id, $userid, $fid);
                                    $num = count($myrow);
                                        if($num>0){ foreach ($myrow as $row) {
                                            echo $row['interlist_desc']."<br>";}} 
                                    ?> 
                                </td>                                
                            </tr>
                    </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function check(element) {
var cb1 = document.getElementById("reminder");
console.log(cb1);
var cb2 = document.getElementById("important");
var sub = document.getElementById("confirmsubmission");
if (cb1.checked == true  &&  cb2.checked == true)
    sub.disabled = false;
else
    sub.disabled = true;
}
</script>


<script>
function goBack() {
    window.history.back();
}
</script>