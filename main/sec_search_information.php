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
	if(!in_array($mi['group'], array('Admins', 'Secretary'))){
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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

        <style>
          body {
            font-family: 'Libre Franklin', sans-serif;
          } .tr_link{cursor:pointer}
          .font-rev {font-size: 8pt;}
          .prime-submitted{font-size: 8pt; color: green; font-weight: bolder;}
          .underline {text-decoration: underline;}
          #loading { display: none; }
      
        @media screen and (max-width: 1020px) {
                .nav {
                    padding-left:2px;
                    padding-right:2px;
                }
                .nav li {
                    display:block !important;
                    width:100%;
                    margin:0px;
                }
                .nav li.active {
                    border-bottom:1px solid #ddd!important;
                    margin: 0px;
                }
            }
    
        </style>
    </head>

    <body>
        <!--<form action = "sec_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">-->
            <?php
            $where = array("username" => $mi['username']);
            $getUserID = $obj->getUser("phrepuser", $where);
            if ($getUserID) {
                foreach ($getUserID as $user) {
                    echo '<input type="hidden" class="form-control" value="' . $user['id'] . '" name="userid" id="userid">';
                    $userid = $user['id'];
                }
            }
            ?>

            <div class="container-fluid">
                <?php $id = (int) $_GET['id']; ?>
                <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">

                <div class="row">
                    <div class="col-lg-12">
                        <h2>
                            <?php
                            $where = array("secretary" => $userid);
                            $getRECname = $obj->fetch_record("rec_list", $where);
                            foreach ($getRECname as $rec) {
                                echo $rec['erc_name'];
                            }
                            ?>
                        </h2>
                    </div>
                </div>
                <hr>          
                <div class="row">
                    <div class="col-lg-2">
                        <h2>Dashboard</h2>
                        <?php include("$currDir/main/sec_dashboard_pane.php"); ?>                  
                    </div>

                    <div class="col-lg-10">     
                        <h2>Search</h2>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon3">Study Title</span>
                            <input type="text" name="search_study" id="search_study" class="form-control" aria-describedby="basic-addon3">
                        </div>
                        
                        <br>
                        <div id="loading">Loading...</div>
                        <div id="result"></div>
                    </div>
                </div>
            </div>    

            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
             <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
             
        <!--</form>-->


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

$(document).ajaxStart(function() {
  $("#loading").show();
}).ajaxStop(function() {
  $("#loading").hide();
});

$(document).ready(function(){
   $('#search_study').keyup(function(){
       var txt = $(this).val().replace(/\'/g, "&#039;");
       
       
       
       if(txt === 0){
           
       }
       else{
           
           $('#result').html('');
           $.ajax({
               url: 'fetch_study.php',
               method: 'post',
               dataType:'text',
               data:{search:txt},
               success:function(data)
               {
                   $('#result').html(data);
               }
           });
       }
   }); 
});

</script>