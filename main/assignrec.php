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
      
      
<div class="panel-body" id="submission_dv_container">      
<div class="row">  
    <div class="col-md-6 col-lg-12" id="submission_dv_form">
        
        
          <?php 
            $id = (int) $_GET['id'];
            $currDirr = dirname(__FILE__);
            include("$currDirr/breadcrumbs.php");
          
          ?>
        
        
    <h1>Step 1. Starting the Submission</h1>
    <p>Encountering difficulties? Contact REC Secretariat for assistance.</p>
    <hr>
    <h4>Ethics Committees</h4>
    <p class="text-fluid">The assignment of an ethics committee is based on the classification of 
        the proposal. Institution based research proposals are automatically 
        assigned to RECs affiliated to the Researcher's institution. In the 
        absence of an affiliated REC, the research proposal will be assigned to 
        one of the accredited REC within the region of the researcher's institution. 
        Community based research proposals are assigned to accredited RECs 
        within the region where the research will be conducted. If the 
        researcher's institution has an affiliated REC, the proposal 
        will automatically be assigned there for review, otherwise the 
        proposal will be assigned orderly to one of the accredited RECs 
        within the indicated region.</p>
    
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4"><center><h5>Research Classification</h5></center></div>
    </div>
    <form class="form-horizontal" role="form" action="submission_action.php" method="POST">
        <?php 
        $where = array("username" => $mi['username']);
        $getUserID = $obj->getUser("phrepuser", $where);
        if($getUserID){
            foreach($getUserID as $user){
                echo '<input type="hidden" class="form-control" value="'.$user[id].'" name="userid">';
            }
        }
        ?>
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8"><center>
            <?php $id = (int) $_GET['id'];?>
            
            <?php 
            $where = array("sub_id" => $id);
            $getclassres = $obj->fetch_record_with_where("submission", $where);
            $getcount = count($getclassres);
            if($getcount>0){
            foreach($getclassres as $classres){
                if($classres[rc_id]=='1'){
                    echo '<div class="form-group">
                                <label class="radio-inline"> 
                                    <input onclick="javascript:researchClassification();" type="radio" id="researchClass1" required name="classifi" value="1" checked="checked"> Institution Based
                                </label>
                                <label class="radio-inline"> 
                                    <input onclick="javascript:researchClassification();" type="radio" id="researchClass2" required name="classifi" value="2"> Community Based
                                </label>                
                        </div>';                    
                }
                else if($classres[rc_id]=='2'){
                    echo '<div class="form-group">
                                <label class="radio-inline"> 
                                    <input onclick="javascript:researchClassification();" type="radio" id="researchClass1" required name="classifi" value="1"> Institution Based
                                </label>
                                <label class="radio-inline"> 
                                    <input onclick="javascript:researchClassification();" type="radio" id="researchClass2" required name="classifi" value="2" checked="checked"> Community Based
                                </label>                
                        </div>';  
                    
                }
                
                else{
                        echo '<div class="form-group">
                                    <label class="radio-inline"> 
                                        <input onclick="javascript:researchClassification();" type="radio" id="researchClass1" required name="classifi" value="1"> Institution Based
                                    </label>
                                    <label class="radio-inline"> 
                                        <input onclick="javascript:researchClassification();" type="radio" id="researchClass2" required name="classifi" value="2"> Community Based
                                    </label>                
                            </div>';                  
                }
            }
                
            }
            else{
                    echo '<div class="form-group">
                                <label class="radio-inline"> 
                                    <input onclick="javascript:researchClassification();" type="radio" id="researchClass1" required name="classifi" value="1"> Institution Based
                                </label>
                                <label class="radio-inline"> 
                                    <input onclick="javascript:researchClassification();" type="radio" id="researchClass2" required name="classifi" value="2"> Community Based
                                </label>                
                        </div>';                  
            }
            
            ?>  
            </center>
            
            <?php 
            $where = array("sub_id" => $id);
            $getclassres = $obj->fetch_record_with_where("submission", $where);
            $getcount = count($getclassres);
            if($getcount>0){
                foreach($getclassres as $classres){
                    if($classres[rc_id]=='1'){ ?>
                        <div class="form-group" id="ifInsti" style="display:block">                
                            <div class="panel panel-success">
                                <div class="panel-heading">Institution Based</div>
                                <div class="panel-body">
                                  <div class="form-group">
                                    <label class="col-md-4 control-label" for="region">Institution</label>
                                    <div class="col-md-8">
                                      <select id="institution" name="institution" class="form-control institution">
                                        <?php 
                                        $getinsti = $obj->getInsti($id);
                                            foreach($getinsti as $insti){
                                                echo '<option value="'.$insti[id].'">'.$insti[desc].'</option>';
                                            }
                                        
                                        ?>
                                          <option value="0" disabled>(Select Institution)</option>
                                          <?php 
                                          $myrow = $obj->fetch_record("institution");
                                          foreach ($myrow as $row) {
                                          ?>  <option value="<?php echo $row["id"]; ?>"><?php echo $row["desc"]; ?></option>
                                          <?php 
                                            }
                                          ?>
                                      </select>
                                        
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-md-4 control-label" for="rec">Review Ethics Committee (REC)</label>
                                    <div class="col-md-8">
                                      <select id="rec" name="rec" class="form-control erci">
                                          <?php 
                                        $getinsti = $obj->getInsti($id);
                                            foreach($getinsti as $insti){
                                                echo '<option value="'.$insti["rl"].'">'.$insti["erc_name"].'</option>';
                                            }
                                        
                                        ?>
                                        <option value="0" disabled>(Select REC)</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="form-group" id="ifComBased" style="display:none">   
                            <div class="panel panel-primary">
                                <div class="panel-heading">Community Based</div>
                                <div class="panel-body">
                                  <!-- Select Basic -->
                                    <div class="form-group">
                                      <label class="col-md-4 control-label" for="region">Region</label>
                                      <div class="col-md-8">
                                        <select id="region" name="region2" class="form-control region">
                                          <option value="0">(Select Region)</option>
                                            <?php 
                                            $myrow = $obj->fetch_record("region");
                                            foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row["id"]; ?>"><?php echo $row["desc"]; ?></option>
                                            <?php 
                                              }
                                            ?>
                                        </select>
                                      </div>
                                    </div>
                                    <!-- Select Basic -->
                                    <div class="form-group">
                                      <label class="col-md-4 control-label" for="rec">Review Ethics Committee (REC)</label>
                                      <div class="col-md-8">
                                        <select id="rec" name="rec2" class="form-control erc">
                                          <option value="0">(Select REC)</option>
                                        </select>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    
                    <?php }
                    else if($classres[rc_id]=='2'){ ?>
                         <div class="form-group" id="ifInsti" style="display:none">                
                            <div class="panel panel-success">
                                <div class="panel-heading">Institution Based</div>
                                <div class="panel-body">
                                  <div class="form-group">
                                    <label class="col-md-4 control-label" for="region">Institution</label>
                                    <div class="col-md-8">
                                      <select id="institution" name="institution" class="form-control institution">
                                        <option value="0">(Select Institution)</option>
                                          <?php 
                                          $myrow = $obj->fetch_record("institution");
                                          foreach ($myrow as $row) {
                                          ?>  <option value="<?php echo $row["id"]; ?>"><?php echo $row["desc"]; ?></option>
                                          <?php 
                                            }
                                          ?>
                                      </select>
                                    </div>
                                  </div>
                                  <!-- Select Basic -->
                                  <div class="form-group">
                                    <label class="col-md-4 control-label" for="rec">Review Ethics Committee (REC)</label>
                                    <div class="col-md-8">
                                      <select id="rec" name="rec" class="form-control erci">
                                        <option value="0">(Select REC)</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="form-group" id="ifComBased" style="display:block">   
                            <div class="panel panel-primary">
                                <div class="panel-heading">Community Based</div>
                                <div class="panel-body">
                                  <!-- Select Basic -->
                                    <div class="form-group">
                                      <label class="col-md-4 control-label" for="region">Region</label>
                                      <div class="col-md-8">
                                        <select id="region" name="region2" class="form-control region">
                                            <?php 
                                            $getcomu = $obj->getCommu($id);
                                                foreach($getcomu as $comu){
                                                    echo '<option value="'.$comu[id].'">'.$comu[desc].'</option>';
                                                }

                                            ?>
                                            
                                          <option value="0">(Select Region)</option>
                                            <?php 
                                            $myrow = $obj->fetch_record("region");
                                            foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row["id"]; ?>"><?php echo $row["desc"]; ?></option>
                                            <?php 
                                              }
                                            ?>
                                        </select>
                                      </div>
                                    </div>
                                    <!-- Select Basic -->
                                    <div class="form-group">
                                      <label class="col-md-4 control-label" for="rec">Review Ethics Committee (REC)</label>
                                      <div class="col-md-8">
                                        <select id="rec" name="rec2" class="form-control erc">
                                            <?php 
                                            $getcomu = $obj->getCommu($id);
                                                foreach($getcomu as $comu){
                                                    echo '<option value="'.$comu[rl].'">'.$comu[erc_name].'</option>';
                                                }

                                            ?>
                                          <option value="0">(Select REC)</option>
                                        </select>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php }
                    else { ?>
                        <div class="form-group" id="ifInsti" style="display:none">                
                            <div class="panel panel-success">
                                <div class="panel-heading">Institution Based</div>
                                <div class="panel-body">
                                  <div class="form-group">
                                    <label class="col-md-4 control-label" for="region">Institution</label>
                                    <div class="col-md-8">
                                      <select id="institution" name="institution" class="form-control institution">
                                        <option value="0">(Select Institution)</option>
                                          <?php 
                                          $myrow = $obj->fetch_record("institution");
                                          foreach ($myrow as $row) {
                                          ?>  <option value="<?php echo $row["id"]; ?>"><?php echo $row["desc"]; ?></option>
                                          <?php 
                                            }
                                          ?>
                                      </select>
                                    </div>
                                  </div>
                                  <!-- Select Basic -->
                                  <div class="form-group">
                                    <label class="col-md-4 control-label" for="rec">Review Ethics Committee (REC)</label>
                                    <div class="col-md-8">
                                      <select id="rec" name="rec" class="form-control erci">
                                        <option value="0">(Select REC)</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>           



                        <div class="form-group" id="ifComBased" style="display:none">   
                            <div class="panel panel-primary">
                                <div class="panel-heading">Community Based</div>
                                <div class="panel-body">
                                  <!-- Select Basic -->
                                    <div class="form-group">
                                      <label class="col-md-4 control-label" for="region">Region</label>
                                      <div class="col-md-8">
                                        <select id="region" name="region2" class="form-control region">
                                          <option value="0">(Select Region)</option>
                                            <?php 
                                            $myrow = $obj->fetch_record("region");
                                            foreach ($myrow as $row) {
                                            ?>  <option value="<?php echo $row["id"]; ?>"><?php echo $row["desc"]; ?></option>
                                            <?php 
                                              }
                                            ?>
                                        </select>
                                      </div>
                                    </div>
                                    <!-- Select Basic -->
                                    <div class="form-group">
                                      <label class="col-md-4 control-label" for="rec">Review Ethics Committee (REC)</label>
                                      <div class="col-md-8">
                                        <select id="rec" name="rec2" class="form-control erc">
                                          <option value="0">(Select REC)</option>
                                        </select>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    <?php } 
                    
                    
                }            
            }
            else{ ?>
                <div class="form-group" id="ifInsti" style="display:none">                
                <div class="panel panel-success">
                    <div class="panel-heading">Institution Based</div>
                    <div class="panel-body">
                      <div class="form-group">
                        <label class="col-md-4 control-label" for="region">Institution</label>
                        <div class="col-md-8">
                          <select id="institution" name="institution" class="form-control institution">
                            <option value="0">(Select Institution)</option>
                              <?php 
                              $myrow = $obj->fetch_record("institution");
                              foreach ($myrow as $row) {
                              ?>  <option value="<?php echo $row["id"]; ?>"><?php echo $row["desc"]; ?></option>
                              <?php 
                                }
                              ?>
                          </select>
                        </div>
                      </div>
                      <!-- Select Basic -->
                      <div class="form-group">
                        <label class="col-md-4 control-label" for="rec">Review Ethics Committee (REC)</label>
                        <div class="col-md-8">
                          <select id="rec" name="rec" class="form-control erci">
                            <option value="0">(Select REC)</option>
                          </select>
                        </div>
                      </div>
                    </div>
                </div>
            </div>           
                           
            
            
            <div class="form-group" id="ifComBased" style="display:none">   
                <div class="panel panel-primary">
                    <div class="panel-heading">Community Based</div>
                    <div class="panel-body">
                      <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="region">Region</label>
                          <div class="col-md-8">
                            <select id="region" name="region2" class="form-control region">
                              <option value="0">(Select Region)</option>
                                <?php 
                                $myrow = $obj->fetch_record("region");
                                foreach ($myrow as $row) {
                                ?>  <option value="<?php echo $row["id"]; ?>"><?php echo $row["desc"]; ?></option>
                                <?php 
                                  }
                                ?>
                            </select>
                          </div>
                        </div>
                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="rec">Review Ethics Committee (REC)</label>
                          <div class="col-md-8">
                            <select id="rec" name="rec2" class="form-control erc">
                              <option value="0">(Select REC)</option>
                            </select>
                          </div>
                        </div>
                    </div>
                </div>
            </div>             
            
            <?php }
            
            ?>
            
            
        </div>  
        <div class="col-lg-2"></div>
    </div>
    
    <hr>
    <h4>Proposal Review Fee</h4>
    <p>Investigators are required to pay a Proposal Review Fee as part of the 
        submission process to contribute to review and training costs. The 
        review fee will be discussed with the secretary of the REC upon the 
        receipt of the proposal.</p>
    <hr>
    
    <h4>Submission Checklist</h4>
    <p>Indicate that this proposal is ready to be considered by the Review 
        Committee by checking off the following (comments to the Secretary can 
        be added at Step 5).</p>
    <div class="checkbox">
        <label><input type="checkbox" value="1" name="checks[]" required>[?] I agree to provide soft copies of the protocol and supplementary files of my research for paperless review of an Review Ethics Committee (REC), and submit a final report upon research completion.</label>
    </div>
    <div class="checkbox">
        <label><input type="checkbox" value="2" name="checks[]" required>[?] I understand that this submission and all it contains will be forwarded to an REC for paperless ethics review and that communications regarding this research will be strictly between me and the assigned REC.</label>
    </div>
    <div class="checkbox">
        <label><input type="checkbox" value="3" name="checks[]" required>[?] I understand that my research will be indexed in PHREP, and will be treated with confidentiality at all times.</label>
    </div>
    <div class="checkbox">
        <label><input type="checkbox" value="4" name="checks[]" required>[?] I agree for PHREP to make available the general information and brief description/abstract of my research to the general public once the research is completed.</label>
    </div>
    
    <div class="form-group">
        <div class="col-md-4">
            <input name="username" type="hidden" value="<?php echo $mi['username']; ?>" placeholder="" class="form-control input-md">
            <input name="subid" type="hidden" value="<?php echo $id; ?>">
        </div>
    </div>    
    
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="col-lg-4"></div>
            <div class="col-lg-4"><center>           
                <button type="submit" name="updaterec" class="btn btn-info">Update REC</button>
                <button type="button" class="btn btn-danger">Cancel</button></center>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    </form>
    
    </div>
    <!-- DV action buttons -->
		<div class="col-md-4 col-lg-3" id="submission_dv_action_buttons">
		</div>
</div>
</div>
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
//$(document).ready(function(){ 
//    $(".desc").hide();
//    $("input[name=classifi]").change(function() {
//        var test = $(this).val();
//        $(".desc").hide();
//        $("#"+test).show();
//    }); 
//});


function researchClassification() {
    if (document.getElementById('researchClass1').checked) {
        document.getElementById('ifInsti').style.display = 'block';
        document.getElementById('ifComBased').style.display = 'none';
    }
    else if(document.getElementById('researchClass2').checked) {
        document.getElementById('ifComBased').style.display = 'block';
        document.getElementById('ifInsti').style.display = 'none';
    }

}



$(document).ready(function(){
   $('.region').change(function(){
       var region_id = $(this).val();
       $.ajax({
           url:"fetch_erc.php",
           method:"POST",
           data:{regionId:region_id},
           dataType:"text",
           success:function(data)
           {
               $('.erc').html(data);
           }
       });
   });
});


$(document).ready(function(){
   $('.institution').change(function(){
       var institution_id = $(this).val();
       $.ajax({
           url:"fetch_ercinsti.php",
           method:"POST",
           data:{instiId:institution_id},
           dataType:"text",
           success:function(data)
           {
               $('.erci').html(data);
           }
       });
   });
});

</script>
