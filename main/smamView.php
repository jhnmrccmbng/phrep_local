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
                    <table class="table table-bordered">
                        <tr>
                            <th>school ID</th>
                            <th>year</th>
                            <th>month</th>
                            <th>curt</th>
                            <th>fans</th>
                            <th>mean_rh</th>
                            <th>mean_temp</th>
                            <th>pci</th>
                            <th>reinfest</th>
                            <th>oi</th>
                        </tr>
                    <?php
                    $getSmam = $obj->select_all("smam");
                    if($getSmam){
                        foreach($getSmam as $smam){
                        echo "<tr>";
                        
                        echo "<td>";
                        echo $smam["school_id"];
                        echo "</td>";
                        
                        echo "<td>";
                        echo $smam["year"];
                        echo "</td>";
                        
                        echo "<td>";
                        echo $smam["month"];
                        echo "</td>";
                        
                        echo "<td>";
                        echo sprintf('%0.2f', round($smam["curt"], 2));
                        echo "</td>";
                        
                        echo "<td>";
                        echo sprintf('%0.2f', round($smam["fans"], 2));
                        echo "</td>";
                        
                        echo "<td>";
                        echo sprintf('%0.2f', round($smam["mean_rh"], 2));
                        echo "</td>";
                        
                        echo "<td>";
                        echo sprintf('%0.2f', round($smam["mean_temp"], 2));
                        echo "</td>";
                        
                        echo "<td>";
                        echo sprintf('%0.2f', round($smam["pci"], 2));
                        echo "</td>";
                        
                        echo "<td>";
                        echo sprintf('%0.2f', round($smam["reinfest"], 2));
                        echo "</td>";
                        
                        echo "<td>";
                        echo sprintf('%0.2f', round($smam["oi"], 2));
                        echo "</td>";
                        
                        echo "</tr>";
                    }
                    }
                    
                    ?>
                    </table>
                    
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