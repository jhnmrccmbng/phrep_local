<?php
include "submission_action.php";

?>
<?php
	$currDir = dirname(__FILE__);
        $currDir = substr($currDir, 0, -5);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	
	/* grant access to the groups 'Admins' and 'Data entry' */
	$mi = getMemberInfo();
	if(!in_array($mi['group'], array('Admins', 'Researcher'))){
            header("location: ../index.php?signIn=1");
//		exit;
	}
?>

<?php       
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
        include "checker.php";//checks whether the id has been used by the researcher already.
        
        $key = $obj->getmagicword(); 
        $id = $obj->decrypt($_GET['id'],$key);
        
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
      
      <div class="container-fluid">
          <?php 
//          $id = (int) $_GET['id'];
          
            $currDirr = dirname(__FILE__);
            include("$currDirr/breadcrumbs.php");
          
          ?>

          
          <form class="form-horizontal" role="FORM" action="submission_action.php" method="POST">    
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
            
                <!--            HIDDEN          -->
                <div class="form-group">
                  <div class="col-md-4">
                      <input id="fname" name="resid[]" type="hidden" value="1" placeholder="" class="form-control input-md">
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-md-4">
                      <input id="fname" name="subid[]" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                  </div>
                </div>
                <!--            HIDDEN          -->
                
                
          <?php 
//          $subidd = $_GET['id']; 
          ?>  
          <div class="row"><!--THIS IS THE FORM AREA-->
            <h1>Step 2. Proposal's Basic Information</h1>
            <hr>
            <h3>A. Researcher</h3>
                <div class="row">
                    <?php
                    $field1 = "memberID";
                    $field2 = "username";
                    $myrow = $obj->get_researcher_info("membership_users", "phrepuser", $field1, $field2, $mi['username']);
                    $num = count($myrow);
                        if($num>0){ 
                            foreach ($myrow as $row){
                                echo ' <div class="col-lg-6">
                                            <div class="panel panel-info">
                                                <!-- Default panel contents -->
                                                <div class="panel-heading">Primary Researcher</div>

                                                <!-- Table -->
                                                <table class="table table-condensed">
                                                    <tr>
                                                        <td width="35%"><p class="text-right">First Name:</p></td>
                                                        <td><p class="text-left">'.$row['fname'].'</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td><p class="text-right">Middle Name:</td>
                                                        <td>'.$row['mname'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td><p class="text-right">Last Name:</td>
                                                        <td>'.$row['lname'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td><p class="text-right">Email:</td>
                                                        <td>'.$row['email'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td><p class="text-right">Phone Number:</td>
                                                        <td>'.$row['pnum'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td><p class="text-right">Institutional Affiliation:</td>
                                                        <td>'.$row['institution'].'</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <hr>
                                        </div>';
                            }
                        }
                    ?>
                   
                    
                    
                </div>
            
            
            <div class="row input_fields_wrap1">      
                <button class="add_field_button1 btn btn-info">Add More Researcher?</button>
            <br>
                            <?php 
                                $where = array("sub_id" => $id);
                                $myrow = $obj->fetch_record_with_where("researcher_additional", $where);
                                $num = count($myrow);
                                    if($num>0){ 
                                        foreach ($myrow as $row){
                                            echo '<div>
                                                <input id="fname" name="resid[]" type="hidden" value="1" placeholder="" class="form-control input-md">
                                                <input id="subid" value="" name="subid[]" type="hidden" placeholder="" class="form-control input-md" required="">
                                                <br>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="fname">First Name</label>
                                                    <div class="col-md-4">
                                                        <input id="fname" value="'.$row['res_fname'].'" name="fname[]" type="text" placeholder="" class="form-control input-md" required="">
                                                    </div>
                                                </div><!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="mname">Middle Name</label>
                                                    <div class="col-md-4">
                                                        <input id="mname" value="'.$row['res_mname'].'" name="mname[]" type="text" placeholder="" class="form-control input-md" required="">
                                                    </div>
                                                </div><!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="lname">Last Name</label>
                                                    <div class="col-md-4">
                                                        <input id="lname" value="'.$row['res_lname'].'" name="lname[]" type="text" placeholder="" class="form-control input-md" required="">
                                                    </div>
                                                </div><!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="email">Email</label>
                                                    <div class="col-md-4">
                                                        <input id="email" value="'.$row['res_email'].'" name="email[]" type="text" placeholder="" class="form-control input-md" required="">
                                                    </div>
                                                </div><!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="pnum">Phone Number</label>
                                                    <div class="col-md-4">
                                                        <input id="pnum[]" value="'.$row['res_pnum'].'" name="pnum[]" type="text" placeholder="" class="form-control input-md" required="">
                                                    </div>
                                                </div><!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label" for="affil">Institutional Affiliation</label>
                                                    <div class="col-md-4">
                                                        <input id="affil[]" value="'.$row['res_insti'].'" name="affil[]" type="text" placeholder="" class="form-control input-md" required="">
                                                    </div>
                                                </div>
                                                <a href="#" class="remove_field btn btn-danger">Remove</a>
                                            </div>';
                                        }
                                    }
                            ?> 
            </div>
                
            <hr>
                
                <h3>B. Title and Summary of Proposal</h3>
                <div class="row">
                    <?php
                    $field = array("sub_id"=>$id, "username"=>$userid);
                    $myrow = $obj->fetch_record_with_where("proposal", $field);
                    $num = count($myrow);
                        if($num>0){ foreach ($myrow as $row) {?>
                        <div class="form-group">
                          <div class="col-md-4">
                              <input id="fname" name="subid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                          </div>
                        </div>
                    
                        <div class="form-group">
                          <div class="col-md-4">
                              <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                          </div>
                        </div>
                    
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="scititle">Title</label>  
                          <div class="col-md-8">
                          <input id="ptitle" name="ptitle" type="text" value="<?php echo $row['prop_ptitle']; ?>" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
<!--                        <div class="form-group">
                          <label class="col-md-2 control-label" for="sci-title">Scientific Title</label>  
                          <div class="col-md-8">
                          <input id="stitle" name="stitle" type="text" value="<?php // echo $row['prop_stitle']; ?>" placeholder="" class="form-control input-md">

                          </div>
                        </div>-->

                        <!-- Textarea -->
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="background">Background</label>
                          <div class="col-md-8">                     
                            <textarea class="form-control background" id="background" name="background"><?php echo strip_tags($row['prop_background']); ?></textarea>
                          </div>
                        </div>

                        <!-- Textarea -->
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="objective">Objectives</label>
                          <div class="col-md-8">                     
                            <textarea class="form-control" id="objective" name="objective"><?php echo strip_tags($row['prop_obj']); ?></textarea>
                          </div>
                        </div>

                        <!-- Textarea -->
<!--                        <div class="form-group">
                          <label class="col-md-2 control-label" for="sciobj">Scientific Objectives</label>
                          <div class="col-md-8">                     
                            <textarea class="form-control" id="sciobj" name="sciobj"><?php // echo strip_tags($row['prop_specobj']); ?></textarea>
                          </div>
                        </div>-->

                        <!-- Textarea -->
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="expected">Expected Outcomes and Use of Results</label>
                          <div class="col-md-8">                     
                            <textarea class="form-control" id="expected" name="expected"><?php echo strip_tags($row['prop_outcomes']); ?></textarea>
                          </div>
                        </div>

                    <?php
                            
                        }
                        }
                        else {?>
                        
                        <div class="form-group">
                          <div class="col-md-4">
                              <input id="fname" name="subid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                          </div>
                        </div>
                    
                        <div class="form-group">
                          <div class="col-md-4">
                              <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                          </div>
                        </div>
                    
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="scititle">Title</label>  
                          <div class="col-md-8">
                          <input id="ptitle" name="ptitle" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
<!--                        <div class="form-group">
                          <label class="col-md-2 control-label" for="sci-title">Scientific Title</label>  
                          <div class="col-md-8">
                          <input id="stitle" name="stitle" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>-->

                        <!-- Textarea -->
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="background">Background</label>
                          <div class="col-md-8">                     
                            <textarea class="form-control background" id="background" name="background"></textarea>
                          </div>
                        </div>

                        <!-- Textarea -->
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="objective">Objectives</label>
                          <div class="col-md-8">                     
                            <textarea class="form-control" id="objective" name="objective"></textarea>
                          </div>
                        </div>

                        <!-- Textarea -->
<!--                        <div class="form-group">
                          <label class="col-md-2 control-label" for="sciobj">Scientific Objectives</label>
                          <div class="col-md-8">                     
                            <textarea class="form-control" id="sciobj" name="sciobj"></textarea>
                          </div>
                        </div>-->

                        <!-- Textarea -->
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="expected">Expected Outcomes and Use of Results</label>
                          <div class="col-md-8">                     
                            <textarea class="form-control" id="expected" name="expected"></textarea>
                          </div>
                        </div>

                            <?php   
                        }
                    ?>

                     <?php    
                        $fid = "kw_id";
                        $myrow = $obj->get_confirmation_joining_two("proposal","keywords", "keywords_list", $id, $userid, $fid);
                        $num = count($myrow);
                            if($num>0){ ?>
                                <div class="form-group">
                                <label class="col-md-2 control-label" for="keyword">Keywords <span data-toggle="tooltip" title="Click ENTER after a word you type" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></label>  
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="keyword[]" id="keyword" value = "<?php
                                foreach ($myrow as $row) {
                                 $key = $row['kw_desc'].", ";
                                 $keys .= $key;
                                }
                                $keyss = substr($keys, 0, -2);
                                echo $keyss;?>
                                "/>
                                </div>
                              </div> <?php
                            }
                            else {
                        echo "<div class='form-group'>
                                <label class='col-md-2 control-label' for='keyword'>Keywords</label>  
                                    <div class='col-md-8'>
                                        <input type='text' class='form-control' name='keyword[]' id='keyword' value = ''/>
                                     </div>
                            </div> ";
                                
                        }?>
                </div>
                
                <hr>
                
                <h3>C. Proposal Details</h3>
                <div class="row">
                    <!-- Multiple Radios (inline) -->
                    <?php
                    
                    $field1 = "sub_id";
                    $field2 = "sub_id";
                    $myrow = $obj->get_confirmation_joining_one("proposal", "studentres", $field1, $field2, $id, $userid);
                    $num = count($myrow);
                    if ($num > 0) {
                        foreach ($myrow as $row) {
                            if ($row['stures_stat'] == '1'){
                                echo '<div class = "form-group">
                                <label class = "col-md-2 control-label" for = "studres">Student Research</label>
                                <div class = "col-md-4">
                                <label class = "radio-inline" for = "studres-0">
                                <input type = "radio" onclick = "javascript:yesnoCheck();" name = "studres" id = "yesCheck" value = "1" checked="checked">
                                Yes
                                </label>
                                <label class = "radio-inline" for = "studres-1">
                                <input type = "radio" onclick = "javascript:yesnoCheck();" name = "studres" id = "noCheck" value = "2">
                                No
                                </label>
                                </div>
                                </div>';
                                
                                //sub select ng YES
                                echo '<div id="ifYes" style="display:block">
                                    <div class="form-group">
                                      <label class="col-md-2 control-label" for="insti">Institution</label>
                                      <div class="col-md-4">
                                        <select id="insti" name="insti_school" onchange="show_Insti();" class="form-control">';?>
                                    
                                    <?php 
                                    $fid = "insti_id";
                                    $myrow = $obj->get_confirmation_joining_two("proposal", "studentresdet", "institution_school", $id, $userid, $fid);
                                    $num = count($myrow);
                                    if ($num > 0) {
                                        foreach ($myrow as $row) {
                                            ?> <option value="<?php echo $row['id']; ?>"><?php echo $row['school_name']; ?></option><?php
                                        }
                                    }?>
                                            
                                          <?php 
                                            $myrow = $obj->fetch_record("institution_school");
                                                foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['school_name']; ?></option>
                                            <?php 
                                                }
                                            ?><?php
                                        echo '</select>
                                      </div>
                                    </div>';
                                        
                                    echo '<div id="insti_other" style="display:none">
                                            <div class="form-group">
                                              <label class="col-md-2 control-label" for="addinsti">Enter School Name</label>  
                                              <div class="col-md-4">
                                              <input id="addinsti" name="addinsti" type="text" placeholder="Enter Complete School Name" class="form-control input-md">

                                              </div>
                                            </div>
                                            </div>';
                                    
                                    
                                    
                                    echo'<!-- Select Basic -->
                                    <div class="form-group">
                                      <label class="col-md-2 control-label" for="acad">Academic Degree</label>
                                      <div class="col-md-4">
                                        <select id="acad" name="acad" class="form-control">';?>
                                    
                                    <?php 
                                    $fid = "acad_id";
                                    $myrow = $obj->get_confirmation_joining_two("proposal", "studentresdet", "academicdeg_list", $id, $userid, $fid);
                                    $num = count($myrow);
                                    if ($num > 0) {
                                        foreach ($myrow as $row) {
                                            ?> <option value="<?php echo $row['id']; ?>"><?php echo $row['desc_acad']; ?></option><?php
                                        }
                                    }?>
                                            <?php 
                                            $myrow = $obj->fetch_record("academicdeg_list");
                                                foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc_acad']; ?></option>
                                            <?php 
                                                }
                                            ?>
                                        <?php echo '</select>
                                      </div>
                                    </div>
                                </div>';
                            }
                            else{
                                
                        echo '<div class = "form-group">
                                <label class = "col-md-2 control-label" for = "studres">Student Research</label>
                                <div class = "col-md-4">
                                <label class = "radio-inline" for = "studres-0">
                                <input type = "radio" onclick = "javascript:yesnoCheck();" name = "studres" id = "yesCheck" value = "1">
                                Yes
                                </label>
                                <label class = "radio-inline" for = "studres-1">
                                <input type = "radio" onclick = "javascript:yesnoCheck();" name = "studres" id = "noCheck" value = "2" checked="checked">
                                No
                                </label>
                                </div>
                                </div>';
                        echo '<div id="ifYes" style="display:none">
                                    <div class="form-group">
                                      <label class="col-md-2 control-label" for="insti">Institution</label>
                                      <div class="col-md-4">
                                        <select id="insti" name="insti_school" class="form-control">
                                            <option value="0">(Select Institution)</option>';?>
                                          <?php 
                                            $myrow = $obj->fetch_record("institution_school");
                                                foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['school_name']; ?></option>
                                            <?php 
                                                }
                                            ?>
                                        <?php echo '</select>
                                      </div>
                                    </div>

                                    <!-- Select Basic -->
                                    <div class="form-group">
                                      <label class="col-md-2 control-label" for="acad">Academic Degree</label>
                                      <div class="col-md-4">
                                        <select id="acad" name="acad" class="form-control">
                                            <option value="0">(Select Degree)</option>';?>
                                          <?php 
                                            $myrow = $obj->fetch_record("academicdeg_list");
                                                foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc_acad']; ?></option>
                                            <?php 
                                                }
                                            ?>
                                        <?php echo '</select>
                                      </div>
                                    </div>
                                </div>';
                            }
                        }
                    }
                    else{
                        echo '<div class = "form-group">
                                <label class = "col-md-2 control-label" for = "studres">Student Research</label>
                                <div class = "col-md-4">
                                <label class = "radio-inline" for = "studres-0">
                                <input type = "radio" onclick = "javascript:yesnoCheck();" name = "studres" id = "yesCheck" value = "1">
                                Yes
                                </label>
                                <label class = "radio-inline" for = "studres-1">
                                <input type = "radio" onclick = "javascript:yesnoCheck();" name = "studres" id = "noCheck" value = "2">
                                No
                                </label>
                                </div>
                                </div>';
                        echo '<div id="ifYes" style="display:none">
                                    <div class="form-group">
                                      <label class="col-md-2 control-label" for="insti">Institution</label>
                                      <div class="col-md-4">
                                        <select id="insti" name="insti_school" onchange="show_Insti();" class="form-control">
                                            <option value="">(Select Institution)</option>';?>
                                          <?php 
                                            $myrow = $obj->fetch_record("institution_school");
                                                foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['school_name']; ?></option>
                                            <?php 
                                                }
                                            ?>
                                        <?php echo '</select>
                                      </div>
                                    </div>';
                                        
                        echo '<div id="insti_other" style="display:none">
                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="addinsti">Enter School Name</label>  
                                  <div class="col-md-4">
                                  <input id="addinsti" name="addinsti" type="text" placeholder="Enter Complete School Name" class="form-control input-md">

                                  </div>
                                </div>
                                </div>';

                        
                        echo'       <div class="form-group">
                                      <label class="col-md-2 control-label" for="acad">Academic Degree</label>
                                      <div class="col-md-4">
                                        <select id="acad" name="acad" class="form-control">
                                            <option value="0">(Select Degree)</option>';?>
                                          <?php 
                                            $myrow = $obj->fetch_record("academicdeg_list");
                                                foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc_acad']; ?></option>
                                            <?php 
                                                }
                                            ?>
                                        <?php echo'</select>
                                      </div>
                                    </div>
                                </div>';
                    }                    
                    ?>
                    <!--END OF STUDENT RESEARCH-->   
                    
                    <!--START OF TYPE OF STUDY-->
                    <?php 
                    $getstudytype = $obj->getStudyType($id, $userid);
                    if($getstudytype){
                        foreach($getstudytype as $st){
                            
                        echo '<div class="form-group">
                                <label class="col-md-2 control-label" for="acad">Study Type</label>
                                    <div class="col-md-4">
                                            <select id="studytype" name="studytype" style="width:370px" class="js-example-basic-single">
                                                <option value="'.$st['idst'].'">'.$st['stdesc'].'</option>';?>
                                              <?php 
                                                $myrow = $obj->fetch_record("studytypelist");
                                                    foreach ($myrow as $row) {
                                                ?>  <option value="<?php echo $row['idst']; ?>"><?php echo $row['stdesc']; ?></option>
                                                <?php 
                                                    }
                                                ?>
                                            <?php echo'</select>
                                    </div>
                              </div>';
                        }
                    }
                    else{
                        echo '<div class="form-group">
                                <label class="col-md-2 control-label" for="acad">Study Type</label>
                                    <div class="col-md-4">
                                            <select id="studytype" name="studytype" style="width:370px" class="js-example-basic-single">
                                                <option value="0">(Select Study Type)</option>';?>
                                              <?php 
                                                $myrow = $obj->fetch_record("studytypelist");
                                                    foreach ($myrow as $row) {
                                                ?>  <option value="<?php echo $row['idst']; ?>"><?php echo $row['stdesc']; ?></option>
                                                <?php 
                                                    }
                                                ?>
                                            <?php echo'</select>
                                    </div>
                              </div>';
                        
                    }
                    
                    
                    ?>
                    <!--END OF TYPE OF STUDY-->
                    
                    <!--START OF PROPOSAL DATE--> 
                    <?php    
                        $myrow = $obj->get_date_duration("proposal", $id, $userid);
                        $num = count($myrow);
                            if($num>0){ foreach ($myrow as $row) {
                                $date1 = new DateTime($row['propdet_strtdate']);
                                $date2 = new DateTime($row['propdet_enddate']);
                                echo'<div class="form-group">
                                    <label class="col-md-2 control-label" for="startdate">Start Date</label>  
                                    <div class="col-md-4">

                                      <div id="sandbox-container">
                                          <input type="text" class="form-control" name="srtdt" value="'.$date1->format('m/d/Y').'" readonly>
                                      </div>

                                    </div>
                                  </div>';
                                echo '<div class="form-group">
                                    <label class="col-md-2 control-label" for="enddate">End Date</label>  
                                    <div class="col-md-4">

                                          <div id="sandbox-container">
                                              <input type="text" class="form-control" name="enddt" value="'.$date2->format('m/d/Y').'" readonly>
                                          </div>
                                    </div>
                                  </div>';
                                }}
                            else{
                                                               
                                echo'<div class="form-group">
                                    <label class="col-md-2 control-label" for="startdate">Start Date</label>  
                                    <div class="col-md-4">

                                      <div id="sandbox-container">
                                          <input type="text" class="form-control" name="srtdt" id="datefrom" value="" readonly>
                                      </div>

                                    </div>
                                  </div>';
                                echo '<div class="form-group">
                                    <label class="col-md-2 control-label" for="enddate">End Date</label>  
                                    <div class="col-md-4">

                                          <div id="sandbox-container">
                                              <input type="text" class="form-control" name="enddt" readonly>
                                          </div>
                                    </div>
                                  </div>';
                            }
                    ?>
                    <!--START OF PRIMARY SPONSOR--> 
                    <?php    
                        $field1 = "propdet_primspon";
                        $field2 = "id";
                        $myrow = $obj->get_confirmation_joining_one("proposal","sponsorlist", $field1, $field2, $id, $userid);
                        $num = count($myrow);
                            if($num>0){ foreach ($myrow as $row) {
                                echo '<div class="form-group">
                                        <label class="col-md-2 control-label" for="primsponsor">Primary Sponsor</label>  
                                        <div class="col-md-8">
                                            <select id="sponsor1" style="width:370px" class="js-example-basic-single" name="sponsor1">
                                              <option value="'.$row['id'].'">'.$row['spon_desc'].'</option>';?>
                                              <?php 
                                              $myrow = $obj->fetch_record("sponsorlist");
                                                  foreach ($myrow as $row) {
                                              ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['spon_desc']; ?></option>
                                              <?php 
                                                  }
                                              ?>
                                          <?php echo'</select> 
                                        </div>
                                      </div>';
                                          
                                echo '<div class="form-group" id="psponsor" style="display:none;">
                                            <label class="col-md-2 control-label" for="addpsponsor">*Add Primary Sponsor</label>  
                                                <div class="col-md-4">
                                                <input id="addpsponsor" name="addpsponsor" type="text" value="" placeholder="" class="form-control input-md" required>
                                                </div>
                                        </div> ';
                                                  }}
                            else{
                                echo '<div class="form-group">
                                    <label class="col-md-2 control-label" for="primsponsor">Primary Sponsor</label>  
                                    <div class="col-md-8">
                                        <select id="sponsor1" style="width:370px" class="js-example-basic-single" name="sponsor1">
                                          <option value="0">(Select Sponsor)</option>';?>
                                          <?php 
                                          $myrow = $obj->fetch_record("sponsorlist");
                                              foreach ($myrow as $row) {
                                          ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['spon_desc']; ?></option>
                                          <?php 
                                              }
                                          ?><?php
                                    echo'  </select> 
                                    </div>
                                  </div>';
                                    
                                echo '<div class="form-group" id="psponsor" style="display:none;">
                                            <label class="col-md-2 control-label" for="addpsponsor">*Add Primary Sponsor</label>  
                                                <div class="col-md-4">
                                                <input id="addpsponsor" name="addpsponsor" type="text" value="" placeholder="" class="form-control input-md" required>
                                                </div>
                                        </div> ';
                            }
                    ?> 
                    <!--END OF PRIMARY SPONSOR-->                     
                    
                    <?php    
                        $fid = "spon_id";
                        $myrow = $obj->get_confirmation_joining_two("proposal","sponsor", "sponsorlist", $id, $userid, $fid);
                            if($myrow){ 
                                echo'<div class="form-group">
                                    <label class="col-md-2 control-label" for="secspon">Secondary Sponsor</label>  
                                         <div class="col-md-8">                               
                                         <select style="width:370px" class="sponsor2" name="secsponsor[]" multiple="multiple" data-tags="true" data-placeholder="Select an Secondary Sponsor">';
                            foreach ($myrow as $row) {?>
                                <option value="<?php echo $row['id']; ?>" selected><?php echo $row['spon_desc']; ?></option>
                            <?php                               
                            }
                            $myrow = $obj->fetch_record("sponsorlist");
                                foreach ($myrow as $row) {
                                        ?><option value="<?php echo $row['id']; ?>"><?php echo $row['spon_desc']; ?></option>
                                    <?php } 
                                    echo'</select>                               
                                        </div>
                                    </div>';
                                    } 
                                    
                                    else{
                                        echo '<div class="form-group">
                                                <label class="col-md-2 control-label" for="secspon">Secondarys Sponsor</label>  
                                                <div class="col-md-8">                               
                                                      <select style="width:370px" class="sponsor2" name="secsponsor[]" multiple="multiple" data-tags="true" data-placeholder="Select an Secondary Sponsor">';?>
                                                      <?php 
                                                      $myrow = $obj->fetch_record("sponsorlist");
                                                          foreach ($myrow as $row) {
                                                              ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['spon_desc']; ?></option>
                                                      <?php 
                                                          }
                                                      ?>
                                                <?php echo'</select>                               
                                                </div>
                                              </div>';
                                    } ?>                         
                                                    
                        <?php    
                            $field1 = "sub_id";
                            $field2 = "sub_id";
                            $myrow = $obj->get_confirmation_joining_one("proposal","country_multi", $field1, $field2, $id, $userid);
                            $num = count($myrow);
                                if($num>0){ foreach ($myrow as $row) {
                                    if ($row['mcountry_stat'] == '1'){
                                        echo '<div class="form-group">
                                                <label class="col-md-2 control-label" for="multires">Multi-country Research</label>
                                                <div class="col-md-4"> 
                                                  <label class="radio-inline" for="multires-0">
                                                    <input type="radio" name="multires" id="countryyesCheck" onclick="javascript:countryyesnoCheck();" value="1" checked="checked">
                                                    Yes
                                                  </label> 
                                                  <label class="radio-inline" for="multires-1">
                                                    <input type="radio" name="multires" id="countrynoCheck" onclick="javascript:countryyesnoCheck();" value="2">
                                                    No
                                                  </label>
                                                </div>
                                              </div>';
                                        
                                        echo '<div class="form-group" id="countryifYes" style="display:block">
                                                <label class="col-md-2 control-label" for="country">Select a Country</label>
                                                <div class="col-md-4">
                                                  <select name="country[]" class="country" style="width:370px" multiple="multiple" data-tags="true" data-placeholder="Select a country">';?>
                                                    <?php
                                                    $fid = "country_id";
                                                    $myrow = $obj->get_confirmation_joining_two("proposal","country_list", "country", $id, $userid, $fid);
                                                    $num = count($myrow);
                                                        if($num>0){ foreach ($myrow as $row) {
                                                                echo '<option value="'.$row['id'].'" selected>'.$row['country_name'].'</option>';}}
                                                    ?>
                                                      
                                                      
                                                      <?php 
                                                      $myrow = $obj->fetch_record("country");
                                                          foreach ($myrow as $row) {
                                                      ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['country_name']; ?></option>
                                                      <?php 
                                                          }
                                                      ?>
                                                <?php  echo '</select>
                                                </div>
                                              </div>';
                                    }
                                    else{
                                        echo '<div class="form-group">
                                                <label class="col-md-2 control-label" for="multires">Multi-country Research</label>
                                                <div class="col-md-4"> 
                                                  <label class="radio-inline" for="multires-0">
                                                    <input type="radio" name="multires" id="countryyesCheck" onclick="javascript:countryyesnoCheck();" value="1">
                                                    Yes
                                                  </label> 
                                                  <label class="radio-inline" for="multires-1">
                                                    <input type="radio" name="multires" id="countrynoCheck" onclick="javascript:countryyesnoCheck();" value="2" checked="checked">
                                                    No
                                                  </label>
                                                </div>
                                              </div>';
                                        
                                        echo '<div class="form-group" id="countryifYes" style="display:none">
                                                <label class="col-md-2 control-label" for="country">Select a Country</label>
                                                <div class="col-md-4">
                                                  <select name="country[]" class="country" style="width:370px" multiple="multiple" data-tags="true" data-placeholder="Select a country">';?>
                                                    <?php 
                                                      $myrow = $obj->fetch_record("country");
                                                          foreach ($myrow as $row) {
                                                      ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['country_name']; ?></option>
                                                      <?php 
                                                          }
                                                      ?>
                                                <?php  echo '</select>
                                                </div>
                                              </div>';
                                    }
                                } 
                                }
                                else{
                                    echo '<div class="form-group">
                                        <label class="col-md-2 control-label" for="multires">Multi-country Research</label>
                                        <div class="col-md-4"> 
                                          <label class="radio-inline" for="multires-0">
                                            <input type="radio" name="multires" id="countryyesCheck" onclick="javascript:countryyesnoCheck();" value="1">
                                            Yes
                                          </label> 
                                          <label class="radio-inline" for="multires-1">
                                            <input type="radio" name="multires" id="countrynoCheck" onclick="javascript:countryyesnoCheck();" value="2">
                                            No
                                          </label>
                                        </div>
                                      </div>

                                      <!-- Country -->
                                      <div class="form-group" id="countryifYes" style="display:none">
                                        <label class="col-md-2 control-label" for="country">Select a Country</label>
                                        <div class="col-md-4">
                                          <select name="country[]" class="country" style="width:370px" multiple="multiple" data-tags="true" data-placeholder="Select a country">';?>
                                            <?php 
                                              $myrow = $obj->fetch_record("country");
                                                  foreach ($myrow as $row) {
                                              ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['country_name']; ?></option>
                                              <?php 
                                                  }
                                              ?>
                                    <?php    echo '</select>
                                        </div>
                                      </div>';
                                }
                        ?>
                                              
                        <!-- START OF NATIONWIDE RESEARCH CHOICE -->  
                        <?php    
                            $field1 = "sub_id";
                            $field2 = "sub_id";
                            $myrow = $obj->get_confirmation_joining_one("proposal","nationwideres", $field1, $field2, $id, $userid);
                            $num = count($myrow);
                                if($num>0){ 
                                    foreach ($myrow as $row) {
                                    
                                    if (($row['nwideres_stat']) == '3'){
                                        echo '<div class="form-group">
                                                <label class="col-md-2 control-label" for="nationres">Multi-site Research</label>
                                                <div class="col-md-8"> 
                                                  <label class="radio-inline" for="nationres-0">
                                                    <input type="radio" name="nationres" id="nationyesCheck" value="1" onclick="javascript:nationyesnoCheck();">
                                                    Yes
                                                  </label> 
                                                  <label class="radio-inline" for="nationres-1">
                                                    <input type="radio" name="nationres" id="nationyeswCheck" value="3" onclick="javascript:nationyesnoCheck();" checked="checked">
                                                    Yes, with randomly selected geographical areas
                                                  </label> 
                                                  <label class="radio-inline" for="nationres-2">
                                                    <input type="radio" name="nationres" id="nationnoCheck" value="2" onclick="javascript:nationyesnoCheck();">
                                                    No
                                                  </label>
                                                </div>
                                              </div>';
                                        
                                        echo'<div class="form-group" id="nationifYesw" style="display:block">
                                            <label class="col-md-2 control-label" for="regionmulti">Region(s)</label>
                                            <div class="col-md-4">
                                              <select id="regionmulti" name="regionmulti[]" class="regionmulti" style="width:370px" multiple="multiple" data-tags="true" data-placeholder="Select Region">';?>
                                                
                                                
                                        <?php    
                                        $fid = "nreg_code";
                                        $myrow = $obj->get_confirmation_joining_two("proposal","nationregion", "region", $id, $userid, $fid);
                                        $num = count($myrow);
                                                if($num>0){ foreach ($myrow as $row) {
                                                    echo'<option value="'.$row['id'].'" selected>'.$row['desc'].'</option>';}} 
                                                    
                                                  $myrow = $obj->fetch_record("region");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc']; ?></option>
                                                  <?php 
                                                      }
                                                  ?>
                                              <?php echo '</select>
                                                <span class="help-block">You can select multiple.</span>
                                            </div>
                                          </div>';
                                        
                                        echo '<div class="form-group" id="nationifNo" style="display:none">
                                            <label class="col-md-2 control-label" for="natregion">Region</label>
                                            <div class="col-md-4">
                                              <select id="natregion" style="width:370px" name="natregion" class="form-control js-example-basic-single">
                                                <option value="0">(Select Region)</option>';?>
                                                  <?php 
                                                  $myrow = $obj->fetch_record("region");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc']; ?></option>
                                                  <?php 
                                                      }
                                                  ?>                                 
                                              <?php echo '</select>
                                            </div>
                                          </div>';
                                                    
                                                }
                                                
                                    else if (($row['nwideres_stat']) == '2'){
                                        echo '<div class="form-group">
                                                <label class="col-md-2 control-label" for="nationres">Multi-site Research</label>
                                                <div class="col-md-8"> 
                                                  <label class="radio-inline" for="nationres-0">
                                                    <input type="radio" name="nationres" id="nationyesCheck" value="1" onclick="javascript:nationyesnoCheck();">
                                                    Yes
                                                  </label> 
                                                  <label class="radio-inline" for="nationres-1">
                                                    <input type="radio" name="nationres" id="nationyeswCheck" value="3" onclick="javascript:nationyesnoCheck();">
                                                    Yes, with randomly selected geographical areas
                                                  </label> 
                                                  <label class="radio-inline" for="nationres-2">
                                                    <input type="radio" name="nationres" id="nationnoCheck" value="2" onclick="javascript:nationyesnoCheck();" checked="checked">
                                                    No
                                                  </label>
                                                </div>
                                              </div>';
                                        
                                        echo '<!-- Select Basic -->
                                          <div class="form-group" id="nationifNo" style="display:block">
                                            <label class="col-md-2 control-label" for="natregion">Region</label>
                                            <div class="col-md-4">


                                              <select id="natregion" style="width:370px" name="natregion" class="form-control js-example-basic-single">';?>
                                                  
                                                  <?php 
                                                  $fid = "nreg_code";
                                                    $myrow = $obj->get_confirmation_joining_two("proposal","nationregion", "region", $id, $userid, $fid);
                                                    $num = count($myrow);
                                                            if($num >= 0){ foreach ($myrow as $row) {
                                                                echo '<option value="'.$row['id'].'" selected>'.$row['desc'].'</option>';}}
                                                  ?>
                                                  
                                                  <?php 
                                                  $myrow = $obj->fetch_record("region");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc']; ?></option>
                                                  <?php 
                                                      }
                                                  ?>                                 
                                              <?php echo '</select>


                                              
                                            </div>
                                          </div>';
                                              
                                        echo '<div class="form-group" id="nationifYesw" style="display:none">
                                            <label class="col-md-2 control-label" for="regionmulti">Region(s)</label>
                                            <div class="col-md-4">
                                              <select id="regionmulti" name="regionmulti[]" class="regionmulti" style="width:370px" multiple="multiple" data-tags="true" data-placeholder="Select a country">';?>
                                                <?php 
                                                  $myrow = $obj->fetch_record("region");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc']; ?></option>
                                                  <?php 
                                                      }
                                                  ?>
                                              <?php echo '</select>
                                                <span class="help-block">You can select multiple.</span>
                                            </div>
                                          </div>';
                                        
                                                                               
                                    }
                                    
                                    else{
                                        echo '<div class="form-group">
                                                <label class="col-md-2 control-label" for="nationres">Multi-site Research</label>
                                                <div class="col-md-8"> 
                                                  <label class="radio-inline" for="nationres-0">
                                                    <input type="radio" name="nationres" id="nationyesCheck" value="1" onclick="javascript:nationyesnoCheck();" checked="checked">
                                                    Yes
                                                  </label> 
                                                  <label class="radio-inline" for="nationres-1">
                                                    <input type="radio" name="nationres" id="nationyeswCheck" value="3" onclick="javascript:nationyesnoCheck();">
                                                    Yes, with randomly selected geographical areas
                                                  </label> 
                                                  <label class="radio-inline" for="nationres-2">
                                                    <input type="radio" name="nationres" id="nationnoCheck" value="2" onclick="javascript:nationyesnoCheck();">
                                                    No
                                                  </label>
                                                </div>
                                              </div>';
                                        
                                        echo '<div class="form-group" id="nationifYesw" style="display:none">
                                            <label class="col-md-2 control-label" for="regionmulti">Region(s)</label>
                                            <div class="col-md-4">
                                              <select id="regionmulti" name="regionmulti[]" class="regionmulti" style="width:370px" multiple="multiple" data-tags="true" data-placeholder="Select a country">';?>
                                                <?php 
                                                  $myrow = $obj->fetch_record("region");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc']; ?></option>
                                                  <?php 
                                                      }
                                                  ?>
                                              <?php echo '</select>
                                                <span class="help-block">You can select multiple.</span>
                                            </div>
                                          </div>

                                          <!-- Select Basic -->
                                          <div class="form-group" id="nationifNo" style="display:none">
                                            <label class="col-md-2 control-label" for="natregion">Region</label>
                                            <div class="col-md-4">
                                              <select id="natregion" style="width:370px" name="natregion" class="form-control js-example-basic-single">
                                                <option value="0">(Select Region)</option>';?>
                                                  <?php 
                                                  $myrow = $obj->fetch_record("region");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc']; ?></option>
                                                  <?php 
                                                      }
                                                  ?>                                 
                                              <?php echo '</select>
                                            </div>
                                          </div>';
                                            }                                               
                                        }
                                    }
                                else{
                                    echo ' <div class="form-group">
                                        <label class="col-md-2 control-label" for="nationres">Multi-site Research</label>
                                        <div class="col-md-8"> 
                                          <label class="radio-inline" for="nationres-0">
                                            <input type="radio" name="nationres" id="nationyesCheck" value="1" onclick="javascript:nationyesnoCheck();">
                                            Yes
                                          </label> 
                                          <label class="radio-inline" for="nationres-1">
                                            <input type="radio" name="nationres" id="nationyeswCheck" value="3" onclick="javascript:nationyesnoCheck();">
                                            Yes, with randomly selected geographical areas
                                          </label> 
                                          <label class="radio-inline" for="nationres-2">
                                            <input type="radio" name="nationres" id="nationnoCheck" value="2" onclick="javascript:nationyesnoCheck();">
                                            No
                                          </label>
                                        </div>
                                      </div>


                                      <!-- Select Basic -->
                                          <div class="form-group" id="nationifYesw" style="display:none">
                                            <label class="col-md-2 control-label" for="regionmulti">Region(s)</label>
                                            <div class="col-md-4">
                                              <select id="regionmulti" name="regionmulti[]" class="regionmulti" style="width:370px" multiple="multiple" data-tags="true" data-placeholder="Select a country">';?>
                                                <?php 
                                                  $myrow = $obj->fetch_record("region");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc']; ?></option>
                                                  <?php 
                                                      }
                                                  ?>
                                              <?php echo '</select>
                                                <span class="help-block">You can select multiple.</span>
                                            </div>
                                          </div>

                                          <!-- Select Basic -->
                                          <div class="form-group" id="nationifNo" style="display:none">
                                            <label class="col-md-2 control-label" for="natregion">Region</label>
                                            <div class="col-md-4">
                                              <select id="natregion" style="width:370px" name="natregion" class="form-control js-example-basic-single">
                                                <option value="0">(Select Region)</option>';?>
                                                  <?php 
                                                  $myrow = $obj->fetch_record("region");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['desc']; ?></option>
                                                  <?php 
                                                      }
                                                  ?>                                 
                                              <?php echo '</select>
                                            </div>
                                          </div>';
                                }        
                        ?>
                        
                       <!-- END OF NATIONWIDE RESEARCH CHOICE -->                       
                       
                        <!-- START OF RESEARCH FIELDS CHOICE -->
                       <?php
                       
                       echo '<div class="form-group">
                          <label class="col-md-2 control-label" for="resfield">Research Fields</label>
                          <div class="col-md-4">
                            
                              <select style="width:370px" class="resfield" name="resfield[]" multiple="multiple" data-tags="true" data-placeholder="Select Research Fields">';
                       
                       $fid = "resfield_id";
                       $myrow = $obj->get_confirmation_joining_two("proposal", "researchfields", "researchfields_list", $id, $userid, $fid);
                       $num = count($myrow);
                       if ($num > 0) {
                           foreach ($myrow as $row) {
                               echo '<option value="'.$row['id'].'" selected>'.$row['resfield_desc'].'</option>';
                           }
                       }
                       ?> 
                                                
                        <?php
                        $myrow = $obj->fetch_record("researchfields_list");
                        foreach ($myrow as $row) {
                            ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['resfield_desc']; ?></option>
                            <?php
                        }

                        echo '</select>
                          </div>
                        </div>'; ?>
                        <!-- END OF RESEARCH FIELDS CHOICE -->
                        
                        <!-- START OF INVOLVES HUMAN SUBJECT CHOICE -->
                        <?php
                        $field1 = "sub_id";
                        $field2 = "sub_id";
                        $myrow = $obj->get_confirmation_joining_one("proposal", "humansubject", $field1, $field2, $id, $userid);
                        $num = count($myrow);
                        if ($num > 0) {
                            foreach ($myrow as $row) {
                                if (($row['hmnsubj_code']) == '1') {
                                    
                                    echo '<div class="form-group">
                                            <label class="col-md-2 control-label" for="humansubj">Involves Human Subjects</label>
                                            <div class="col-md-4"> 
                                              <label class="radio-inline" for="humansubj-0">
                                                <input type="radio" onclick="javascript:humsubyesnoCheck();" name="humansubj" id="humsubyesCheck" value="1" checked="checked">
                                                Yes
                                              </label> 
                                              <label class="radio-inline" for="humansubj-1">
                                                <input type="radio" onclick="javascript:humsubyesnoCheck();" name="humansubj" id="humsubnoCheck" value="2">
                                                No
                                              </label>
                                            </div>
                                          </div>';
                                    
                                    echo '<div id="humsubifYes" style="display:block">
                                            <!-- Select Basic -->
                                            <div class="form-group">
                                              <label class="col-md-2 control-label" for="proptype">Proposal Type</label>
                                              <div class="col-md-4">
                                                <select style="width:370px" id="proptype" name="proptype[]" class="proptype" multiple="multiple" data-tags="true" data-placeholder="Select proposal type">';?>
                                                    
                                    <?php                
                                                    $fid = "proptype_id";
                                                    $myrow = $obj->get_confirmation_joining_two("proposal", "hmnsubj", "hmnsubj_list", $id, $userid, $fid);
                                                    $num = count($myrow);
                                                    if ($num > 0) {
                                                        foreach ($myrow as $row) {
                                                            echo '<option value="'.$row['id'].'" selected>'.$row['proptype_name'].'</option>';
                                                        }
                                                    }
                                    ?>
                                    <?php 
                                                    $myrow = $obj->fetch_record("hmnsubj_list");
                                                        foreach ($myrow as $row) {
                                                    ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['proptype_name']; ?></option>
                                                    <?php 
                                                        }
                                                    ?>
                                                    
                                    <?php echo '</select>
                                              </div>
                                            </div>
                                            <!-- Select Basic -->
                                            <div class="form-group">
                                              <label class="col-md-2 control-label" for="datacol">Data Collection </label>
                                              <div class="col-md-4">
                                                <select id="datacol" name="datacol" class="form-control">'; ?>
                                                  
                                                    <?php    
                                                        $fid = "datacol_id";
                                                        $myrow = $obj->get_confirmation_joining_two("proposal","datacol", "datacol_list", $id, $userid, $fid);
                                                        $num = count($myrow);
                                                            if($num>0){ foreach ($myrow as $row) {
                                                                echo '<option value="'.$row['id'].'" selected>'.$row['datacol_name'].'</option>';}} 
                                                    ?> 
                                                    
                                                    <?php 
                                                    $myrow = $obj->fetch_record("datacol_list");
                                                        foreach ($myrow as $row) {
                                                    ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['datacol_name']; ?></option>
                                                    <?php 
                                                        }
                                                    ?>
                                    <?php echo '</select>
                                              </div>
                                            </div>
                                            </div>';
                                    
                                } 
                                
                                else {
                                    echo '<div class="form-group">
                                            <label class="col-md-2 control-label" for="humansubj">Involves Human Subjects</label>
                                            <div class="col-md-4"> 
                                              <label class="radio-inline" for="humansubj-0">
                                                <input type="radio" onclick="javascript:humsubyesnoCheck();" name="humansubj" id="humsubyesCheck" value="1">
                                                Yes
                                              </label> 
                                              <label class="radio-inline" for="humansubj-1">
                                                <input type="radio" onclick="javascript:humsubyesnoCheck();" name="humansubj" id="humsubnoCheck" value="2" checked="checked">
                                                No
                                              </label>
                                            </div>
                                          </div>';
                                    
                                    echo '<div id="humsubifYes" style="display:none">
                                            <!-- Select Basic -->
                                            <div class="form-group">
                                              <label class="col-md-2 control-label" for="proptype">Proposal Type</label>
                                              <div class="col-md-4">
                                                <select style="width:370px" id="proptype" name="proptype[]" class="proptype" multiple="multiple" data-tags="true" data-placeholder="Select proposal type">'; ?>
                                                    <?php 
                                                    $myrow = $obj->fetch_record("hmnsubj_list");
                                                        foreach ($myrow as $row) {
                                                    ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['proptype_name']; ?></option>
                                                    <?php 
                                                        }
                                                    ?><?php
                                    echo '</select>
                                              </div>
                                            </div>
                                            <!-- Select Basic -->
                                            <div class="form-group">
                                              <label class="col-md-2 control-label" for="datacol">Data Collection </label>
                                              <div class="col-md-4">
                                                <select id="datacol" name="datacol" class="form-control">'; ?>
                                                  <?php 
                                                    $myrow = $obj->fetch_record("datacol_list");
                                                        foreach ($myrow as $row) {
                                                    ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['datacol_name']; ?></option>
                                                    <?php 
                                                        }
                                                    ?> <?php
                                    echo '</select>
                                              </div>
                                            </div>
                                            </div>';
                                }
                            }
                        }
                        else {
                            echo '<div class="form-group">
                                            <label class="col-md-2 control-label" for="humansubj">Involves Human Subjects</label>
                                            <div class="col-md-4"> 
                                              <label class="radio-inline" for="humansubj-0">
                                                <input type="radio" onclick="javascript:humsubyesnoCheck();" name="humansubj" id="humsubyesCheck" value="1">
                                                Yes
                                              </label> 
                                              <label class="radio-inline" for="humansubj-1">
                                                <input type="radio" onclick="javascript:humsubyesnoCheck();" name="humansubj" id="humsubnoCheck" value="2">
                                                No
                                              </label>
                                            </div>
                                          </div>';
                                    
                                    echo '<div id="humsubifYes" style="display:none">
                                            <!-- Select Basic -->
                                            <div class="form-group">
                                              <label class="col-md-2 control-label" for="proptype">Proposal Type</label>
                                              <div class="col-md-4">
                                                <select style="width:370px" id="proptype" name="proptype[]" class="proptype" multiple="multiple" data-tags="true" data-placeholder="Select proposal type">';?>
                                                    <?php 
                                                    $myrow = $obj->fetch_record("hmnsubj_list");
                                                        foreach ($myrow as $row) {
                                                    ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['proptype_name']; ?></option>
                                                    <?php 
                                                        }
                                                    ?><?php
                                    echo '</select>
                                              </div>
                                            </div>
                                            <!-- Select Basic -->
                                            <div class="form-group">
                                              <label class="col-md-2 control-label" for="datacol">Data Collection </label>
                                              <div class="col-md-4">
                                                <select id="datacol" name="datacol" class="form-control">
                                                <option value="0">(Select Data Collection)</option>';?>
                                                  <?php 
                                                    $myrow = $obj->fetch_record("datacol_list");
                                                        foreach ($myrow as $row) {
                                                    ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['datacol_name']; ?></option>
                                                    <?php 
                                                        }
                                                    ?><?php
                                    echo '</select>
                                              </div>
                                            </div>
                                            </div>';
                        }
                        ?>
                        <!-- END OF INVOLVES HUMAN SUBJECT CHOICE -->
                        
                        <!-- START OF PROPOSAL REVIEW BY OTHER COMMITTEE CHOICE -->
                        <?php
                        $field1 = "sub_id";
                        $field2 = "sub_id";
                        $myrow = $obj->get_confirmation_joining_one("proposal", "reviewcomdata", $field1, $field2, $id, $userid);
                        $num = count($myrow);
                        if ($num > 0) {
                            foreach ($myrow as $row) {
                                if (($row['rcom_stat']) == '1') {
                                    echo '<div class="form-group">
                                            <label class="col-md-2 control-label" for="propcom">Proposal Reviewed by other Committee</label>
                                            <div class="col-md-4"> 
                                              <label class="radio-inline" for="propcom-0">
                                                <input type="radio" onclick="javascript:revcomyesnoCheck();" name="propcom" id="revcomyesCheck" value="1" checked="checked">
                                                Yes
                                              </label> 
                                              <label class="radio-inline" for="propcom-1">
                                                <input type="radio" onclick="javascript:revcomyesnoCheck();" name="propcom" id="revcomnoCheck" value="2">
                                                No
                                              </label>
                                            </div>
                                          </div>';
                                    echo '<div class="form-group" id="revcomifYes" style="display:block">
                                            <label class="col-md-2 control-label" for="revcom">Proposal reviewed by other Review Committee</label>
                                            <div class="col-md-4">
                                              <select id="revcom" name="revcom" class="form-control">';?>
                                                
                                                  <?php    
                                                        $fid = "rcom_stat";
                                                        $myrow = $obj->get_confirmation_joining_two("proposal","reviewcomdata", "reviewcom_list", $id, $userid, $fid);
                                                        $num = count($myrow);
                                                            if($num>0){ foreach ($myrow as $row) {
                                                                echo '<option value="'.$row['id'].'" selected>'.$row['reviewcom_name'].'</option>';}} 
                                                    ?> 
                                                  
                                                  <?php 
                                                  $myrow = $obj->fetch_record("reviewcom_list");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['reviewcom_name']; ?></option>
                                                  <?php 
                                                      }
                                                  ?><?php
                                    echo '</select>
                                            </div>
                                          </div>';
                                }
                                else{ //IF NOT 1
                                    echo '<div class="form-group">
                                            <label class="col-md-2 control-label" for="propcom">Proposal Reviewed by other Committee</label>
                                            <div class="col-md-4"> 
                                              <label class="radio-inline" for="propcom-0">
                                                <input type="radio" onclick="javascript:revcomyesnoCheck();" name="propcom" id="revcomyesCheck" value="1">
                                                Yes
                                              </label> 
                                              <label class="radio-inline" for="propcom-1">
                                                <input type="radio" onclick="javascript:revcomyesnoCheck();" name="propcom" id="revcomnoCheck" value="2" checked="checked">
                                                No
                                              </label>
                                            </div>
                                          </div>';
                                    echo '<div class="form-group" id="revcomifYes" style="display:none">
                                            <label class="col-md-2 control-label" for="revcom">Proposal reviewed by other Review Committee</label>
                                            <div class="col-md-4">
                                              <select id="revcom" name="revcom" class="form-control">';?>
                                                
                                                  <?php    
                                                        $fid = "rcom_stat";
                                                        $myrow = $obj->get_confirmation_joining_two("proposal","reviewcomdata", "reviewcom_list", $id, $userid, $fid);
                                                        $num = count($myrow);
                                                            if($num>0){ foreach ($myrow as $row) {
                                                                echo '<option value="'.$row['id'].'" selected>'.$row['reviewcom_name'].'</option>';}} 
                                                    ?> 
                                                  
                                                  <?php 
                                                  $myrow = $obj->fetch_record("reviewcom_list");
                                                      foreach ($myrow as $row) {
                                                  ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['reviewcom_name']; ?></option>
                                                  <?php 
                                                      }
                                                  ?><?php
                                    echo '</select>
                                            </div>
                                          </div>';
                                }
                            }
                        }
                        else{//KUNG WALA DATA SA DB
                            echo '<div class="form-group">
                                    <label class="col-md-2 control-label" for="propcom">Proposal Reviewed by other Committee</label>
                                    <div class="col-md-4"> 
                                      <label class="radio-inline" for="propcom-0">
                                        <input type="radio" onclick="javascript:revcomyesnoCheck();" name="propcom" id="revcomyesCheck" value="1">
                                        Yes
                                      </label> 
                                      <label class="radio-inline" for="propcom-1">
                                        <input type="radio" onclick="javascript:revcomyesnoCheck();" name="propcom" id="revcomnoCheck" value="2">
                                        No
                                      </label>
                                    </div>
                                  </div>

                                  <!-- Select Basic -->
                                  <div class="form-group" id="revcomifYes" style="display:none">
                                    <label class="col-md-2 control-label" for="revcom">Proposal reviewed by other Review Committee</label>
                                    <div class="col-md-4">
                                      <select id="revcom" name="revcom" class="form-control">
                                      <option value="0">(Select)</option>';?>
                                        <?php 
                                          $myrow = $obj->fetch_record("reviewcom_list");
                                              foreach ($myrow as $row) {
                                          ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['reviewcom_name']; ?></option>
                                          <?php 
                                              }
                                          ?><?php
                            echo '</select>
                                    </div>
                                  </div>';
                        }                        
                        ?>
                       <!-- START OF PROPOSAL REVIEW BY OTHER COMMITTEE CHOICE --> 
                   
                </div><!--ROW SA PROPOSAL DETAILS-->
                
                <hr>
                
                <h3>D. Source(s) of Monetary or Material Support</h3>
                
                <?php
                $myrow = $obj->monetarySource($id, $userid);
                $num = count($myrow);
                if ($num > 0) {
                    
                    echo '<div class="row input_fields_wrap2">'; 
                    foreach ($myrow as $row1) { 
                        echo '<div>';   
                            echo ' <a href="#" class="remove_field2 btn btn-danger">Remove</a>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="amount">Monetary Source</label>  
                                        <div class="col-md-4">
                                              <input type="text" class="form-control" name="monsrc[]" value="'.$row1['monsrc_id'].'">     
                                        </div>
                                    </div>
                                        
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="appendedtext">Amount in Philippine Peso (PhP)';?> <span data-toggle="tooltip" title="Enter the full amount without comma" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><?php echo '</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input id="appendedtext" name="amount[]" class="form-control number" placeholder="" type="text" value="'.number_format($row1['amount'],2).'" >
                                                 <span class="input-group-addon">Ph&#x20B1;</span>
                                            </div>
                                        </div>
                                </div>
                                
                            </div>';//parent div that is deleted                                         
                    }
                    echo '<div class="row">
                            <div class="col-lg-4">          
                                <button class="add_field_button2 btn btn-info">Add More Source</button>  
                            </div>
                        </div>
                        <br>';
                    
                echo '</div>';//THIS IS class row input_fields_wrap2
                }
                
                else{
                    echo '<div class="row input_fields_wrap2">                       
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="amount">Monetary Source</label>  
                          <div class="col-md-4">
                                <input type="text" class="form-control" name="monsrc[]">     
                          </div>
                        </div>  
                    
                        <!-- Appended Input-->
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="appendedtext">Amount in Philippine Peso (PhP) <span data-toggle="tooltip" title="Enter the full amount without comma" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></label>
                          <div class="col-md-4">
                            <div class="input-group">
                              <input id="appendedtext" name="amount[]" class="form-control number" placeholder="" type="text" >
                              <span class="input-group-addon">PhP</span>
                            </div>

                          </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">          
                                <button class="add_field_button2 btn btn-info">Add More Source</button>  
                            </div>
                        </div><br>
                        </div>';
                    
                }
                
                ?>
                
                
                
                <hr>
                <!--ASSESSMENT QUESTIONNAIRE-->
                <h3>E. Assessment Questionnaire</h3>
                
                <div class="row">
                    <h4>Does the proposed research include research subjects:</h4>
                    <!-- Multiple Radios (inline) -->
                    
                    <?php
                    $partid = "1";
                    $fid = "loa_id";
                    $myrow = $obj->get_confirmation_joining_two_for_assess("proposal", "assessment_ans", "listofassessement", $id, $userid, $fid, $partid);
                    $num = count($myrow);
                    if ($num > 0) {
                        foreach ($myrow as $row) {
                            echo '<div class="col-lg-8"><input type="hidden" name="q[]" value="'.$row['id'].'" >'.$row['loa_desc'].'</div>
                                        <div class="col-lg-4">';
                            if ($row['loa_ans'] == '1') {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1" checked="checked">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>';
                            } 
                            else if ($row['loa_ans'] == '2') {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2" checked="checked">
                                              No
                                            </label>';                                
                            }
                            else {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>'; 
                            }
                        echo '</div>'; 
                        }   
                    }
                    else{
                        
                        $where = array(
                                "part_id" => "1",);
                            $myrow = $obj->fetch_record_with_where("listofassessement", $where);
                            foreach ($myrow as $row) {
                                echo'<div class="col-lg-8"><input type="hidden" name="q[]" value="'.$row['id'].'" >'.$row['loa_desc'].'</div>
                                        <div class="col-lg-4">
                                        
                                            <label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>
                                            
                                        </div> ';
                            }
                        
                    }
                    ?> 
                         
                </div>
                
                <div class="row">
                    <h4>Does the research include:</h4>
                    <?php
                    $partid = "2";
                    $fid = "loa_id";
                    $myrow = $obj->get_confirmation_joining_two_for_assess("proposal", "assessment_ans", "listofassessement", $id, $userid, $fid, $partid);
                    $num = count($myrow);
                    if ($num > 0) {
                        foreach ($myrow as $row) {
                            echo '<div class="col-lg-8"><input type="hidden" name="q[]" value="'.$row['id'].'" >'.$row['loa_desc'].'</div>
                                        <div class="col-lg-4">';
                            if ($row['loa_ans'] == '1') {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1" checked="checked">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>';
                            } 
                            else if ($row['loa_ans'] == '2') {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2" checked="checked">
                                              No
                                            </label>';                                
                            }
                            else {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>'; 
                            }
                        echo '</div>'; 
                        }   
                    }
                    else{
                        
                        $where = array(
                                "part_id" => "2",);
                            $myrow = $obj->fetch_record_with_where("listofassessement", $where);
                            foreach ($myrow as $row) {
                                echo'<div class="col-lg-8"><input type="hidden" name="q[]" value="'.$row['id'].'" >'.$row['loa_desc'].'</div>
                                        <div class="col-lg-4">
                                        
                                            <label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>
                                            
                                        </div> ';
                            }
                        
                    }
                    ?>                    
                </div>
                
                
                
                <div class="row">
                    <h4>Potential risks:</h4>
                    <?php
                    $fid = "risklevel_id";
                    $myrow = $obj->get_confirmation_joining_two("proposal", "risklevel", "risklevellist", $id, $userid, $fid);
                    $num = count($myrow);
                    if ($num > 0) {
                        

                        foreach ($myrow as $row) {
                            echo '<div class="col-lg-8">Level of the risk involved in Research:</div>
                                <div class="col-lg-4">
                                    <select id="select2" style="width:300px" class="js-example-basic-single" name="risklevel" required>
                                        <option value="' . $row['id'] . '">' . $row['risklevel_desc'] . '</option>';
                            
                            $myrow = $obj->fetch_record("risklevellist");
                            foreach ($myrow as $row1) {
                                ?>  <option value="<?php echo $row1['id']; ?>"><?php echo $row1['risklevel_desc']; ?></option>
                                <?php
                            }
                        echo'</select>                        
                                </div>';
                            
                        }
                    } 
                    else{
                        echo '<div class="col-lg-8">Level of the risk involved in Research:</div>
                                <div class="col-lg-4">
                                    <select id="select2" style="width:300px" class="js-example-basic-single" name="risklevel" required>
                                        <option value=""></option>';?>
                                        <?php 
                                        $myrow = $obj->fetch_record("risklevellist");
                                            foreach ($myrow as $row) {
                                        ?>  <option value="<?php echo $row['id']; ?>"><?php echo $row['risklevel_desc']; ?></option>
                                        <?php 
                                            }
                                        ?><?php
                                echo'</select>                        
                                </div>';
                    }
                    ?> 
                    
                    
                </div>
                  
                <div class="row">                    
                    <div class="col-lg-8">Risks apply to:</div>
                    
                    <div class="col-lg-4">
                            <?php
                                $myrow = $obj->fetch_record("riskapplist");
                                        foreach ($myrow as $row) {

                                            echo '<div class="checkbox">
                                              <label for="checkboxes-'.$row['id'].'">
                                                <input type="checkbox" name="riskapply[]" id="'.$row['id'].'" value="'.$row['id'].'"';?> 
                                                    <?php 
                                                    $myrow1 = $obj->get_data_from("riskapply", $row['id'], "riskapp_id", $id);
                                                    $count1 = count($myrow1);
                                                    if ($count1 == 1){echo "checked";}
                                                    else {echo "";}
                                                    ?>
                                                <?php echo '>
                                                '.$row['riskapp_desc'].'
                                              </label>
                                            </div>';
                                        } 
                            ?>                        
                    </div>
                </div>
                
                               
                
                
                    <div class="row">
                    <h4>Potential Benefits:</h4>
                        <div class="col-lg-8">Benefits from the research project:</div>
                        <div class="col-lg-4">    
                            
                            <?php
                                $myrow = $obj->fetch_record("potenbenlist");
                                        foreach ($myrow as $row) {

                                            echo '<div class="checkbox">
                                              <label for="checkboxes-'.$row['id'].'">
                                                <input type="checkbox" name="potenben[]" id="'.$row['id'].'" value="'.$row['id'].'"';?> 
                                                    <?php 
                                                    $myrow1 = $obj->get_data_from("potenbenefits", $row['id'], "potenben_id", $id);
                                                    $count1 = count($myrow1);
                                                    if ($count1 == 1){echo "checked";}
                                                    else {echo "";}
                                                    ?>
                                                <?php echo '>
                                                '.$row['potenben_desc'].'
                                              </label>
                                            </div>';
                                        } 
                            ?>
                        </div>
                    </div>
                
                
                
                
                    <div class="row">
                    <?php
                    $partid = "4";
                    $fid = "loa_id";
                    $myrow = $obj->get_confirmation_joining_two_for_assess("proposal", "assessment_ans", "listofassessement", $id, $userid, $fid, $partid);
                    $num = count($myrow);
                    if ($num > 0) {
                        foreach ($myrow as $row) {
                            echo '<div class="col-lg-8"><input type="hidden" name="q[]" value="'.$row['id'].'" >'.$row['loa_desc'].'</div>
                                        <div class="col-lg-4">';
                            if ($row['loa_ans'] == '1') {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1" checked="checked">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>';
                            } 
                            else if ($row['loa_ans'] == '2') {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2" checked="checked">
                                              No
                                            </label>';                                
                            }
                            else {
                                echo '<label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>'; 
                            }
                        echo '</div>'; 
                        }   
                    }
                    else{
                        
                        $where = array(
                                "part_id" => "4",);
                            $myrow = $obj->fetch_record_with_where("listofassessement", $where);
                            foreach ($myrow as $row) {
                                echo'<div class="col-lg-8"><input type="hidden" name="q[]" value="'.$row['id'].'" >'.$row['loa_desc'].'</div>
                                        <div class="col-lg-4">
                                        
                                            <label class="radio-inline" for="radios-0">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-0" value="1">
                                              Yes
                                            </label> 
                                            
                                            <label class="radio-inline" for="radios-1">
                                              <input type="radio" name="radios-'.$row['id'].'" id="radios-1" value="2">
                                              No
                                            </label>
                                            
                                        </div> ';
                            }
                        
                    }
                    ?>   
                    </div>
                
                    <div class="row">
                         <?php
                                echo '<div class="col-lg-8">Conflict of Interest:</div>
                                    <div class="col-lg-4">'; 
                                
                                $myrow = $obj->fetch_record("coninterlist");
                                        foreach ($myrow as $row) {

                                            echo '<label class="radio-inline" for="radios-'.$row['id'].'">
                                                <input type="radio" name="conflict" id="'.$row['id'].'" value="'.$row['id'].'"';?> 
                                                    <?php 
                                                    $myrow1 = $obj->get_data_from("coninterest", $row['id'], "intelist_id", $id);
                                                    $count1 = count($myrow1);
                                                    if ($count1 == 1){echo "checked";}
                                                    else {echo "";}
                                                    ?>
                                                <?php echo '>
                                                '.$row['interlist_desc'].'
                                              </label>';
                                        } 
                                        
                                echo '</div> ';
                            ?>
                        
                           
                    </div>
            
          </div><!--THIS IS THE FORM AREA-->
          
          <div class="row"><!--THIS IS THE BUTTON-->
                <div class="col-lg-4"></div>
                <div class="col-lg-4"><center>           
                    <button type="submit" name="submitmainprop" class="btn btn-info">Save and Continue</button>
                    <!-- <button class="btn btn-default" onclick='goBack()'>Back</button> -->
                    <a class="btn btn-default" href="submission-s1.php?id=<?php echo $_GET['id'];?>" role="button">Back</a></center>
                </div>
                <div class="col-lg-4"></div>              
          </div><!--THIS IS THE BUTTON-->
        </form> 
      </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
  </body>
  
</html>
<!--ADD RESEARCHER--><?php $sam = '123';?>


<?php 
include_once("$currDir/footer.php");
?>


<script>
$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap1"); //Fields wrapper
    var add_button      = $(".add_field_button1"); //Add button 
    
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        var myvar = <?php echo json_encode($id); ?>;
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div><input id="fname" name="resid[]" type="hidden" value="1" placeholder="" class="form-control input-md"><input id="subid" value="'+myvar+'" name="subid[]" type="hidden" placeholder="" class="form-control input-md" required=""><div class="form-group"><label class="col-md-2 control-label" for="fname">First Name</label><div class="col-md-4"><input id="fname" name="fname[]" type="text" placeholder="" class="form-control input-md" required=""></div></div><!-- Text input--><div class="form-group"><label class="col-md-2 control-label" for="mname">Middle Name</label><div class="col-md-4"><input id="mname" name="mname[]" type="text" placeholder="" class="form-control input-md" required=""></div></div><!-- Text input--><div class="form-group"><label class="col-md-2 control-label" for="lname">Last Name</label><div class="col-md-4"><input id="lname" name="lname[]" type="text" placeholder="" class="form-control input-md" required=""></div></div><!-- Text input--><div class="form-group"><label class="col-md-2 control-label" for="email">Email</label><div class="col-md-4"><input id="email" name="email[]" type="text" placeholder="" class="form-control input-md" required=""></div></div><!-- Text input--><div class="form-group"><label class="col-md-2 control-label" for="pnum">Phone Number</label><div class="col-md-4"><input id="pnum[]" name="pnum[]" type="text" placeholder="" class="form-control input-md" required=""></div></div><div class="form-group"><label class="col-md-2 control-label" for="pnum">Institutional Affiliation</label><div class="col-md-4"><input id="affil[]" name="affil[]" type="text" placeholder="" class="form-control input-md" required=""></div></div><a href="#" class="remove_field btn btn-danger">Remove</a>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
</script>
<!--ADD SOURCE OF MONEY-->
<script>
$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap2"); //Fields wrapper
    var add_button      = $(".add_field_button2"); //Add button ID
    
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div><a href="#" class="remove_field2 btn btn-danger">Remove</a><div class="form-group"><label class="col-md-2 control-label" for="amount">Monetary Source</label><div class="col-md-4"><input type="text" class="form-control" name="monsrc[]"></div></div><div class="form-group"><label class="col-md-2 control-label" for="appendedtext">Amount in Philippine Peso (PhP) <span data-toggle="tooltip" title="Enter the full amount without comma" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></label><div class="col-md-4"><div class="input-group"><input id="appendedtext" name="amount[]" class="form-control number" placeholder="" type="text" ><span class="input-group-addon">PhP</span></div></div></div></div>'); //add input box
//                alert('sample');
                    $("input.number").keyup(function(e) {
                        
                    // skip for arrow keys
                    if(event.which >= 37 && event.which <= 40){
                     event.preventDefault();
                    }

                    $(this).val(function(index, value) {
                        value = value.replace(/,/g,'');
                        return numberWithCommas(value);
                    });
                  });
        
        }
    });
    
    $(wrapper).on("click",".remove_field2", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
</script>

<script>
$(document).ready(function() {
    var wrapper         = $(".input_fields_wrap2"); //Fields wrapper
    
    $(wrapper).on("click",".remove_field2", function(e){ //user click on remove text
        $(this).parent('div').remove();
    });
});

</script>


<!--DATE PICKER-->
<script>
    $(document).ready(function(){ 
            
            $('#sandbox-container input').datepicker({
                orientation: "top left",
                autoclose: true,
                todayHighlight: true
            });

        // $('.input-daterange input').datepicker({
        //     orientation: "top left",
        //     autoclose: true,
        //     todayHighlight: true
        // });
        // $('.input-daterange input').each(function() {
        //     $(this).datepicker('clearDates');
        // });

    });
    
</script>
<!--DATE PICKER-->


<!--WYSIWYG editor-->
<script>
    CKEDITOR.replace( 'background' );
    CKEDITOR.replace( 'objective' );
//    CKEDITOR.replace( 'sciobj' );
    CKEDITOR.replace( 'expected' );
</script>
<!--WYSIWYG editor-->

<script>
    $('#keyword').tokenfield();
</script>

<script type="text/javascript">

function yesnoCheck() {
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('ifYes').style.display = 'block';
    }
    else document.getElementById('ifYes').style.display = 'none';

}

</script>

<script type="text/javascript">

function countryyesnoCheck() {
    if (document.getElementById('countryyesCheck').checked) {
        document.getElementById('countryifYes').style.display = 'block';
    }
    else document.getElementById('countryifYes').style.display = 'none';

}

</script>

<script type="text/javascript">

function nationyesnoCheck() {
    if (document.getElementById('nationyesCheck').checked) {
        document.getElementById('nationifYesw').style.display = 'none';
        document.getElementById('nationifNo').style.display = 'none';
    }
    else if (document.getElementById('nationyeswCheck').checked) {
        document.getElementById('nationifYesw').style.display = 'block';
        document.getElementById('nationifNo').style.display = 'none';
    }
    else {
        document.getElementById('nationifYesw').style.display = 'none';
        document.getElementById('nationifNo').style.display = 'block';
    }

}

</script>

<script type="text/javascript">

function humsubyesnoCheck() {
    if (document.getElementById('humsubyesCheck').checked) {
        document.getElementById('humsubifYes').style.display = 'block';
    }
    else document.getElementById('humsubifYes').style.display = 'none';

}

</script>

<script type="text/javascript">

function revcomyesnoCheck() {
    if (document.getElementById('revcomyesCheck').checked) {
        document.getElementById('revcomifYes').style.display = 'block';
    }
    else document.getElementById('revcomifYes').style.display = 'none';

}

</script>

<script type="text/javascript">

function show_Insti() {
    if (document.getElementById('insti').value == '1') {
        document.getElementById('insti_other').style.display = 'block';
    }
    else{
        document.getElementById('insti_other').style.display = 'none';
        document.getElementById('addinsti').value = "";
    } 

}
</script>

<script type="text/javascript">
$(document).ready(function () {
    var sponsor = $('#sponsor1').val();
    
    if(sponsor === 10){
    }
    else{
        $("#psponsor").hide()
        $('#addpsponsor').removeAttr('required')
    }
    
    $('#sponsor1').on('change',function(){
        if( $(this).val()==="10"){
        $("#psponsor").show()
        }
        else{
        $("#psponsor").hide()
        $('#addpsponsor').removeAttr('required')
        }
    });    
});

$('input.number').keyup(function(event) {
  // skip for arrow keys
  if(event.which >= 37 && event.which <= 40){
   event.preventDefault();
  }

  $(this).val(function(index, value) {
      value = value.replace(/,/g,'');
      return numberWithCommas(value);
  });
});

function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}
</script>

<script>
function goBack() {
    window.history.back();
}
</script>