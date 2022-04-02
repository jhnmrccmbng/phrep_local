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
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8"><center><h1>Configuration</h1></center>
                    <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                        <fieldset>

                        <!-- Form Name -->
                        <legend>Type1 and Type 3 May to October</legend>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="schoolid">School ID</label>
                          <div class="col-md-4">
                            <select id="schoolid" name="schoolid" class="form-control">
                              <?php 
                                for($i=1; $i<=13; $i++){
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="fieldname">Field Name</label>
                          <div class="col-md-4">
                            <select id="fieldname" name="fieldname" class="form-control">
                              <option value="curt">curt</option>
                              <option value="fans">fans</option>
                              <option value="pci">pci</option>
                              <option value="reinfest">reinfest</option>
                              <option value="oi">oi</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- Button -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="savetype1"></label>
                          <div class="col-md-4">
                            <button id="savetype1" name="savetype1" class="btn btn-primary">Save</button>
                          </div>
                        </div>

                        </fieldset>
                        </form>
                    
                    <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                        <fieldset>

                        <!-- Form Name -->
                        <legend>Type1 and Type 3 November to December</legend>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="schoolid">School ID</label>
                          <div class="col-md-4">
                            <select id="schoolid" name="schoolid" class="form-control">
                              <?php 
                                for($i=1; $i<=13; $i++){
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="fieldname">Field Name</label>
                          <div class="col-md-4">
                            <select id="fieldname" name="fieldname" class="form-control">
                              <option value="curt">curt</option>
                              <option value="fans">fans</option>
                              <option value="pci">pci</option>
                              <option value="reinfest">reinfest</option>
                              <option value="oi">oi</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- Button -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="savenovdec"></label>
                          <div class="col-md-4">
                            <button id="savenovdec" name="savenovdec" class="btn btn-primary">Save</button>
                          </div>
                        </div>

                        </fieldset>
                        </form>
                    
                    <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                        <fieldset>

                        <!-- Form Name -->
                        <legend>Type1 and Type 3 January to April</legend>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="schoolid">School ID</label>
                          <div class="col-md-4">
                            <select id="schoolid" name="schoolid" class="form-control">
                              <?php 
                                for($i=1; $i<=13; $i++){
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="fieldname">Field Name</label>
                          <div class="col-md-4">
                            <select id="fieldname" name="fieldname" class="form-control">
                              <option value="curt">curt</option>
                              <option value="fans">fans</option>
                              <option value="pci">pci</option>
                              <option value="reinfest">reinfest</option>
                              <option value="oi">oi</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- Button -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="savejanapr"></label>
                          <div class="col-md-4">
                            <button id="savejanapr" name="savejanapr" class="btn btn-primary">Save</button>
                          </div>
                        </div>

                        </fieldset>
                        </form>
                    
                    <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                        <fieldset>

                        <!-- Form Name -->
                        <legend>Type2 January to October</legend>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="schoolid">School ID</label>
                          <div class="col-md-4">
                            <select id="schoolid" name="schoolid" class="form-control">
                              <?php 
                                for($i=14; $i<=16; $i++){
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="fieldname">Field Name</label>
                          <div class="col-md-4">
                            <select id="fieldname" name="fieldname" class="form-control">
                              <option value="curt">curt</option>
                              <option value="fans">fans</option>
                              <option value="pci">pci</option>
                              <option value="reinfest">reinfest</option>
                              <option value="oi">oi</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- Button -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="savejanoct"></label>
                          <div class="col-md-4">
                            <button id="savejanoct" name="savejanoct" class="btn btn-primary">Save</button>
                          </div>
                        </div>

                        </fieldset>
                        </form>
                    
                    <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                        <fieldset>

                        <!-- Form Name -->
                        <legend>Type2 November to December</legend>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="schoolid">School ID</label>
                          <div class="col-md-4">
                            <select id="schoolid" name="schoolid" class="form-control">
                              <?php 
                                for($i=14; $i<=16; $i++){
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="fieldname">Field Name</label>
                          <div class="col-md-4">
                            <select id="fieldname" name="fieldname" class="form-control">
                              <option value="curt">curt</option>
                              <option value="fans">fans</option>
                              <option value="pci">pci</option>
                              <option value="reinfest">reinfest</option>
                              <option value="oi">oi</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- Button -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="savenovdec2"></label>
                          <div class="col-md-4">
                            <button id="savenovdec2" name="savenovdec2" class="btn btn-primary">Save</button>
                          </div>
                        </div>

                        </fieldset>
                        </form>
                    
                    <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                        <fieldset>

                        <!-- Form Name -->
                        <legend>Type4 January to December</legend>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="schoolid">School ID</label>
                          <div class="col-md-4">
                            <select id="schoolid" name="schoolid" class="form-control">
                              <?php 
                                for($i=17; $i<=21; $i++){
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="fieldname">Field Name</label>
                          <div class="col-md-4">
                            <select id="fieldname" name="fieldname" class="form-control">
                              <option value="curt">curt</option>
                              <option value="fans">fans</option>
                              <option value="pci">pci</option>
                              <option value="reinfest">reinfest</option>
                              <option value="oi">oi</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- Button -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="savejandec4"></label>
                          <div class="col-md-4">
                            <button id="savejandec4" name="savejandec4" class="btn btn-primary">Save</button>
                          </div>
                        </div>

                        </fieldset>
                        </form>
                    
                    
                </div>
                <div class="col-lg-2"></div>
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