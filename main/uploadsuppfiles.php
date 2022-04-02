<?php
include "upload_file.php";
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
        $id = $obj->decrypt($_GET['id'],$key);
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
      <div class="container-fluid">

          <?php 
            $currDirr = dirname(__FILE__);
            include("$currDirr/breadcrumbs.php");
          
          ?>
          
        <!--<form class="form-horizontal" role="form" action="submission_action.php" method="POST">-->          
          <div class="row"><!--THIS IS THE FORM AREA-->
            <h1>Step 4. Uploading Supplementary Files</h1>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <p>This optional step allows Supplementary Files to be added 
                        to a submission. The files, which should be in document 
                        file format, might include (a) Project Proposal (following 
                        the PCHRD Detailed Proposal Form), (b) Workplan Schedule 
                        (Gantt Chart of Activities), (c) Counterpart Funding of 
                        Implementing Agency, (d) Biosafety Clearance, if applicable, 
                        (e) Institutional Animal Care and Use Clearance, (f) 
                        Ethical Clearance, if applicable, (g) Informed Consent 
                        Form, if involving human subjects, (h) Case Report Form, 
                        if applicable, (i) Duties and Responsibilities of each 
                        Project Personnel, or other relevant documents.</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                               
                </div>
            </div>
            
            
            <div class="row">
              <form action = "upload_file.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">                             
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
                <div class="col-lg-6">
                    <div class="col-lg-12 alert alert-info" role="alert">Step 1: <br><p class="pull-left">Please select for a file type.</p><span class="glyphicon glyphicon-arrow-right pull-right" aria-hidden="true"></span><br></div>
                    
                    <select id="doctype" name="doctype" class="form-control" required="required">
                        <option value="">(Select file type to upload)</option>
                            <?php 
                            $myrow = $obj->fetch_record("document_type");
                            foreach ($myrow as $row) {
                                ?>  <?php 
                                        $doc = $row['docid'];
                                        if ($doc == 1){
                                            //NONE
                                        }
                                        else{
                                        $id1 = "inst_id"; $id2 = "id"; $id3 = "secretary"; $id4 = "id"; $id5 = "id"; $id6 = "memberId";
                                        $myrow2 = $obj->fetch_record_for_doctype("combased", "rec_list", "phrepuser", "document_control", $id, $id1, $id2, $id3, $id4, $id5, $id6, $doc);
                                        $count = count($myrow2);
                                        if ($count > 0){?>
                                            <option value="<?php echo $row['docid'].",".$row['forfilename']; ?>"><?php echo $row['doctype_desc']; ?>*</option><?php
                                        }
                                        else{?>
                                            <option value="<?php echo $row['docid'].",".$row['forfilename']; ?>"><?php echo $row['doctype_desc']; ?></option><?php
                                        }
                                        }
                                        
                                        
                                    ?>
                                <?php
                            }
                            ?>
                            
                    </select>
                </div>
                <div class="col-lg-6">           
                        <fieldset> 
                           <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                <?php $actual_link = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'].""; ?>
                                <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                <?php $mi = getMemberInfo(); ?>
                                <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                        </fieldset>         
                    <div class="alert alert-info" role="alert">Step 2: <br> Accepted files: pdf</div>
                    <span class="btn btn-default btn-file">
                    <input type = "file" name = "imagesuppfiles" required="required" accept="application/pdf"/>                        
                    </span>
                    <input type = "submit" class="btn btn-success"/>
                </div>
            </form>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <tr>
                            <th>TITLE</th>
                            <th>ORIGINAL FILE NAME</th>
                            <th>DATE UPLOADED</th>
                            <th>ACTION</th>
                        </tr>
                        
                        <?php 
                        $myrow = $obj->showingUploadedFiles("document", "document_type", "doctype", "docid", $id, $userid);
                        $num = count($myrow);
                            if($num>0){
                            foreach ($myrow as $row) {
                                $idd = $row['doctype'];
                                if ($idd == '1'){
                                    
                                }
                                else{
                                $myrow3 = $obj->checkingUploadFiles("combased", "document_control", "inst_id", "erc_id", $idd, $id);
                                $naa = count($myrow3);
                                    if ($naa > 0){
                                        echo '<tr class="danger">
                                            <td>
                                                '.$row['doctype_desc'].'<span class="badge">Required</span>
                                            </td>
                                            <td>
                                                '.$row['orig_filename'].'
                                            </td>
                                            <td>';?>
                                                <?php $d = strtotime($row['date_uploaded']);   echo date("F j, Y",$d); ?><?php
                                                
                                        $fi = $row['file_id'];
                                        $key = $obj->getmagicword(); 
                                        $fi = $obj->encrypt($fi,$key);
                                        echo'</td>
                                            <td>
                                                <a href="upload_file.php?delete=1&id='.$fi.'&subid=';?><?php echo $_GET['id']; ?><?php echo'" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>';
                                    }
                                    else{
                                        
                                        echo '<tr>
                                            <td>
                                                '.$row['doctype_desc'].'
                                            </td>
                                            <td>
                                                '.$row['orig_filename'].'
                                            </td>
                                            <td>';?>
                                                <?php $d = strtotime($row['date_uploaded']);   echo date("F j, Y",$d); ?><?php
                                            
                                        $fi = $row['file_id'];
                                        $key = $obj->getmagicword(); 
                                        $fi = $obj->encrypt($fi,$key);    
                                                
                                        echo'</td>
                                            <td>
                                                <a href="upload_file.php?delete=1&id='.$fi.'&subid=';?><?php echo $_GET['id']; ?><?php echo'" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>';
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
            
            <hr>
          </div><!--THIS IS THE FORM AREA-->
          <?php 
          $getSupFile = $obj->getSupFileifUpload($id, "1", "SF");
        //   $count = count($getSupFile);
          if($getSupFile){ ?>
          <form action = "upload_file.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">   
          <div class="row"><!--THIS IS THE BUTTON-->
              <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                <div class="col-lg-4"></div>
                <div class="col-lg-4"><center>           
                    <button type="submit" name="submitsupfiles" class="btn btn-info">Save and Continue</button>
                    <button type="button" class="btn btn-default" onclick='goBack()'>Back</button></center>
                </div>
                <div class="col-lg-4"></div>              
          </div><!--THIS IS THE BUTTON-->
          </form>
              
          <?php }
          else{ ?>
          <form action = "upload_file.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">   
          <div class="row"><!--THIS IS THE BUTTON-->
              <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                <div class="col-lg-4"></div>
                <div class="col-lg-4"><center>           
                    <button type="submit" name="submitsupfiles" class="btn btn-info" disabled="disabled">Save and Continue</button>
                    <button type="button" class="btn btn-danger">Cancel</button></center>
                </div>
                <div class="col-lg-4"></div>              
          </div><!--THIS IS THE BUTTON-->
          </form>
              
          <?php }
          ?>          
          
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
function goBack() {
    window.history.back();
}
</script>