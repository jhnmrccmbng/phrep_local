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
                    <div class="col-lg-5">
                        <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                            <fieldset>
                        <?php
                            if (isset($_GET["update"])) {
                                if (isset($_GET["id"])) {
                                    $id = $_GET["id"];

                                    //$id = $_GET["id"] ?? null;
                                    $where = array(
                                        "id" => $id,);
                                    $row = $obj->select_record("institution", $where);
                        ?>
                            <!-- Form Name -->
                            <legend>Edit Institution's Information</legend>
                           
                            <input id="id" name="id" type="hidden" value="<?php echo $id; ?>">

                            <!-- Text input-->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="institution">Institution</label>  
                              <div class="col-md-8">
                              <input id="institution" name="institution" type="text" placeholder="" class="form-control input-md" required="" value="<?php echo $row[desc]; ?>">

                              </div>
                            </div>


                            
                            <!-- Button (Double) -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="updateinsti"></label>
                              <div class="col-md-8">
                                <button id="updateinsti" name="updateinsti" class="btn btn-success">Update</button>
                                <button id="button2id" name="button2id" class="btn btn-danger">Cancel</button>
                              </div>
                            </div>
                            <?php
                                }
                            } else{
                                ?>
                            <!-- Form Name -->
                            <legend>Add Institution</legend>

                            <!-- Text input-->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="institution">Institution</label>  
                              <div class="col-md-8">
                              <input id="institution" name="institution" type="text" placeholder="" class="form-control input-md" required="">

                              </div>
                            </div>
                            
                            <!-- Button (Double) -->
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="saveinsti"></label>
                              <div class="col-md-8">
                                <button id="saveinsti" name="saveinsti" class="btn btn-success">Save</button>
                                <button id="button2id" name="button2id" class="btn btn-danger">Cancel</button>
                              </div>
                            </div>
                            
                            <?php
                                
                            }
                            ?>
                            
                            
                            

                            </fieldset>
                            </form>

                    </div>
                    <div class="col-lg-7">
                        <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Lists of Institution</div>

                        <!-- Table -->
                        <table class="table table-bordered table-condensed table-striped">
                            <tr>
                                <th>#</th>
                                <th>Institution Name</th>
                                <th><center><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></center></th>
                            </tr>
                            
                            
                            <?php 
                            $getrec = $obj->getInsitution();
                            if($getrec>0){
                                foreach($getrec as $rec){
                                    echo '<tr>';
                                    echo '<td>'.$rec[id].'</td>';
                                    echo '<td>'.$rec[desc].'</td>';
                                   
                                    echo '<td><center>';
                                    echo '<a class="btn btn-warning" href="co_add_institution.php?update=1&id='.$rec[id].'" role="button">Edit</a>';
                                    echo '</center></td>';
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