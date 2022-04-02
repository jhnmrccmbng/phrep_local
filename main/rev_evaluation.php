<?php
include "rev_dashboard_action.php";
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
	if(!in_array($mi['group'], array('Admins', 'Reviewer'))){
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
      }</style>
  </head>
  <body>
      <form action = "rev_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          <div class="row">
              <h2><center>Evaluation</center></h2>
              <div class="col-lg-1"></div>
              <div class="col-lg-10">
                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Reviewer's Worksheet</div>
                        <div class="panel-body">
                          <p>Title of Study</p>
                        </div>
                        
                        <table class="table table-bordered">
                                <?php 
                                        $eval = $obj->gettingEvaluationQuestion($mi['username']);
                                        if($eval){
                                            foreach($eval as $question){
                                                echo "<tr>";
                                                echo "<td>";
                                                echo $question[question_desc];
                                                    $getSubquestion = $obj->gettingSubQuestions($question[qid]);
                                                    if($getSubquestion){
                                                        foreach($getSubquestion as $ans1){
                                                            if($ans1[answerableBy] == 1){
                                                                echo '<div class="form-group">';
                                                                echo '<label class="col-md-1 control-label" for="radios"></label>';
                                                                echo '<div class="col-md-11">';
                                                                echo '<label class="radio-inline" for="radios-'.$ans1[question_id].'">';
                                                                echo '<input type="radio" name="radios-'.$ans1[question_id].'" id="radios-'.$ans1[question_id].'" value="'.$ans1[ansid].'">';
                                                                echo $ans1[answer_desc];
                                                                echo '</label> ';
                                                                echo '</div>';
                                                                echo '</div>';
                                                            }
                                                            else if($ans1[answerableBy] == 2){
                                                                echo '<br>'.$ans1[question_desc];
                                                                echo '<textarea class="form-control" id="textarea" name="textarea"></textarea>';
                                                            }                                                            
                                                        }
                                                    }
                                                
                                                
                                                echo"</td>";
                                                echo '<td>';?>
                                                        <?php
                                                            $anslist = $obj->gettingEvaluationAnswers($question[qid]);
                                                            if($anslist){
                                                                foreach($anslist as $ans){
                                                                    if($ans[answerableBy] == 1){
                                                                        echo '<div class="form-group">';
                                                                        echo '<label class="col-md-1 control-label" for="radios"></label>';
                                                                        echo '<div class="col-md-11">';
                                                                        echo '<label class="radio-inline" for="radios-'.$ans[question_id].'">';
                                                                        echo '<input type="radio" name="radios-'.$ans[question_id].'" id="radios-'.$ans[question_id].'" value="'.$ans[ansid].'">';
                                                                        echo $ans[answer_desc];
                                                                        echo '</label> ';
                                                                        echo '</div>';
                                                                        echo '</div>';
                                                                        
                                                                    }
                                                                    else if($ans[answerableBy] == 2){
                                                                        
                                                                    }
                                                                }
                                                            }
                                                        ?>                                                          
                                                        
                                                <?php
                                                echo '</td>';
                                                echo '</tr>';
//                                                $where = array("question_dependent_to" => $question[qid]);
//                                                $getSubquestion = $obj->fetch_record_with_where("rec_question_eval",$where);
//                                                if($getSubquestion){
//                                                    foreach($getSubquestion as $subq){
//                                                        echo '<tr>';
//                                                        echo '<td>';
//                                                        echo $question[question_desc];
//                                                        echo '</td>';
//                                                        echo '<td>';
//                                                            
//                                                        echo '</td>';
//                                                        echo '</tr>';
//                                                        
//                                                    }
//                                                }
                                            }
                                        }
                                    ?>
                        </table>
                    </div>
                  
              </div>
              <div class="col-lg-1"></div>
              
             
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
