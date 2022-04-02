<?php
include "coordinator_action.php";

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
	if(!in_array($mi['group'], array('Admins', 'Coordinator'))){
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
      }.tr_link{cursor:pointer}</style>
  </head>
  <body>
        <div class="container-fluid">
            <?php $id = (int) $_GET['id']; ?>
            <?php
            $where = array("username" => $mi['username']);
            $getUserID = $obj->getUser("phrepuser", $where);
            if ($getUserID) {
                foreach ($getUserID as $user) {
                    echo '<input type="hidden" class="form-control" value="' . $user[id] . '" name="userid">';
                    $userid = $user[id];
                }
            }
            ?>
            <div class="row"><h1>Review Ethics Committee</h1>
                <div class="col-lg-3">
                    
                </div>
                    <div class="col-lg-6">
                        <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                            <fieldset>
                        <?php
                            if (isset($_GET["update"])) {
                                if (isset($_GET["id"])) {
                                    $id = $_GET["id"];

                                    //$id = $_GET["id"] ?? null;
                                    $where = array(
                                        "id" => $id,);
                                    $row = $obj->select_record("rec_list", $where);
                        ?>
                            <!-- Form Name -->
                            <legend>Edit REC Information</legend>
                           
                            <input id="id" name="id" type="hidden" value="<?php echo $id; ?>">


                            <!-- Text input-->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="ercname">Complete Name</label>  
                              <div class="col-md-8">
                              <input id="ercname" value="<?php echo $row["erc_name"]; ?>" name="ercname" type="text" placeholder="" class="form-control input-md" required="">

                              </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="ercinit">Initials</label>  
                              <div class="col-md-8">
                              <input id="ercinit" value="<?php echo $row["erc_initials"]; ?>" name="ercinit" type="text" placeholder="" class="form-control input-md" required="">

                              </div>
                            </div>

                            <!-- Select Basic -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="erclevel">Level</label>
                              <div class="col-md-8">
                                <select id="erclevel" name="erclevel" class="form-control">
                                    
                                  <option value="<?php echo $row["erc_level"]; ?>" selected><?php echo $row["erc_level"]; ?></option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                </select>
                              </div>
                            </div>

                            <!-- Select Basic -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="insti">Institution</label>
                              <div class="col-md-8"><small>*If Institution is not applicable just leave it as is.</small>
                                <select id="insti" name="insti" class="form-control">
                                  <?php 
                                    $where = array("id" => $row["insti_id"],);
                                    $insti = $obj->select_record("institution", $where);
                                    if($insti[id] == 0){
                                        echo '<option value="0" selected>(SELECT INSTITUTION)</option>  ';
                                    }
                                    else{
                                        echo '<option value="'.$insti[id].'" selected>'.$insti[desc].'</option>  ';
                                    }
                                  ?>                                 
                                  <?php
                                  $getinsti = $obj->getInstitutions();
                                  if($getinsti>0){
                                      foreach($getinsti as $insti){
                                          echo '<option value="'.$insti[id].'">'.$insti[desc].'</option>';
                                      }
                                  }
                                  ?>
                                </select>
                              </div>
                            </div>

                            <!-- Select Basic -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="region">Region</label>
                              <div class="col-md-8">
                                <select id="region" name="region" class="form-control">
                                    <?php 
                                    $where = array("id" => $row["region_id"],);
                                    $region = $obj->select_record("region", $where);
                                    if($region[id] == 0){
                                        echo '<option value="0" disabled="disabled" selected>(SELECT REGION)</option>  ';
                                    }
                                    else{
                                        echo '<option value="'.$region[id].'" selected>'.$region[desc].'</option>  ';
                                    }
                                  ?> 
                                    <?php 
                                    $getregion = $obj->getRegions();
                                    if($getregion>0){
                                        foreach($getregion as $region){
                                            echo '<option value="'.$region[id].'">'.$region[desc].'</option>';
                                        }
                                    }
                                    
                                    ?>
                                </select>
                              </div>
                            </div>
                            
                            <!-- Button (Double) -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="saverec"></label>
                              <div class="col-md-8">
                                <button id="saverec" name="updaterec" class="btn btn-success">Update</button>
                                <button id="button2id" name="button2id" class="btn btn-danger">Cancel</button>
                              </div>
                            </div>
                            <?php
                                }
                            } else{
                                ?>
                            <!-- Form Name -->
                            <legend>Add REC</legend>

                            <!-- Text input-->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="ercname">Complete Name</label>  
                              <div class="col-md-8">
                              <input id="ercname" name="ercname" type="text" placeholder="" class="form-control input-md" required="">

                              </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="ercinit">Initials</label>  
                              <div class="col-md-8">
                              <input id="ercinit" name="ercinit" type="text" placeholder="" class="form-control input-md" required="">

                              </div>
                            </div>

                            <!-- Select Basic -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="erclevel">Level</label>
                              <div class="col-md-8">
                                <select id="erclevel" name="erclevel" class="form-control">
                                  <option value="0" disabled="disabled" selected>(SELECT REC LEVEL)</option>
                                  <option value="1">I</option>
                                  <option value="2">II</option>
                                  <option value="3">III</option>
                                </select>
                              </div>
                            </div>

                            <!-- Select Basic -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="insti">Institution</label>
                              <div class="col-md-8"><small>*If Institution is not applicable just leave it as is.</small>
                                <select id="insti" name="insti" class="form-control">
                                  <option value="0" disabled="disabled" selected>(SELECT INSTITUTION)</option>                                    
                                  <?php
                                  $getinsti = $obj->getInstitutions();
                                  if($getinsti>0){
                                      foreach($getinsti as $insti){
                                          echo '<option value="'.$insti[id].'">'.$insti[desc].'</option>';
                                      }
                                  }
                                  ?>
                                </select>
                              </div>
                            </div>

                            <!-- Select Basic -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="region">Region</label>
                              <div class="col-md-8">
                                <select id="region" name="region" class="form-control">
                                  <option value="0" disabled="disabled" selected>(SELECT REGION)</option>
                                    <?php 
                                    $getregion = $obj->getRegions();
                                    if($getregion>0){
                                        foreach($getregion as $region){
                                            echo '<option value="'.$region[id].'">'.$region[desc].'</option>';
                                        }
                                    }
                                    
                                    ?>
                                </select>
                              </div>
                            </div>
                            
<!--                             Select Basic 
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="region">Secretary</label>
                              <div class="col-md-8">
                                  <select id="secretary" name="secretary" class="form-control" required="required">
                                  <option value="0" disabled="disabled" selected>(SELECT SECRETARY)</option>
                                    <?php 
//                                    $getunsec = $obj->getUnAssignedSec();
//                                    if($getunsec>0){
//                                        foreach($getunsec as $unsec){
//                                            echo '<option value="'.$unsec[pid].'">'.$unsec[lname].', '.$unsec[fname].' '.$unsec[mname].'</option>';
//                                        }
//                                    }
//                                    
                                    ?>
                                </select>
                              </div>
                            </div>-->
                            
                            <!-- Button (Double) -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="saverec"></label>
                              <div class="col-md-8">
                                <button id="saverec" name="saverec" class="btn btn-success">Save</button>
                                <button id="button2id" name="button2id" class="btn btn-danger">Cancel</button>
                              </div>
                            </div>
                            
                            <?php
                                
                            }
                            ?>
                            
                            
                            

                            </fieldset>
                            </form>

                    </div>
                
                    <div class="col-lg-3">     
                    </div>
                
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Lists of REC's</div>

                        <!-- Table -->
                        <table class="table table-bordered table-condensed table-striped">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Initals</th>
                                <th>Level</th>
                                <th>Institution</th>
                                <th>Region</th>
                                <th>Secretariat?</th>
                                <th><center><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></center></th>
                            </tr>
                            
                            
                            <?php 
                            $getrec = $obj->getREC();
                            if($getrec>0){
                                foreach($getrec as $rec){
                                    echo '<tr>';
                                    echo '<td>'.$rec[recid].'</td>';
                                    echo '<td>'.$rec[erc_name].'</td>';
                                    echo '<td>'.$rec[erc_initials].'</td>';
                                    echo '<td>'.$rec[erc_level].'</td>';
                                    if($rec[insti_id] == '0'){
                                        echo '<td><center><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></center></td>';
                                    }
                                    else{
                                        $getinsti = $obj->getInsti($rec[insti_id]);
                                        if($getinsti>0){
                                            foreach($getinsti as $insti){
                                                if($insti[insti_id] == 0){            
                                                    echo '<td>'.$insti[desc].'</td>';                                   
                                                }
                                            }
                                        }
                                    }
                                    
                                    echo '<td>'.$rec[desc].'</td>';  
                                    
                                    if($rec[secretary] == 0){
                                        echo '<td class="danger"><center><a class="btn btn-success" href="co_assignsec.php?update=1&id='.$rec[recid].'" role="button">Add</a></center></td>';
                                    }
                                    else{
                                        echo '<td class="success"><center><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></center></td>';
                                    }
                                    echo '<td style="width:150px"><center><a class="btn btn-primary" href="co_addrec.php?update=1&id='.$rec[recid].'" role="button">Edit</a></center></td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                            
                        </table>
                      </div>
                    </div>
                </div>
            
            
        </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
  </body>
  
</html>
<?php include_once("$currDir/footer.php"); ?>
<script>
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});


$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>