<?php
include "researcher_action.php";

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
      <div class="container-fluid"><?php $subid = (int) $_GET['id'];?>
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
$where = array("username" => $mi['username']);
$getUserUpdate = $obj->getUser("phrepuser", $where);
    if($getUserUpdate){?>
          
          <div class="row"><h2>Request for Amendments</h2><hr>
              <div class="col-lg-2"></div>
              <div class="col-lg-10">
                  
              </div>
          </div>
          
          <div class="row">             
              
              <div class="col-lg-5">
                  <h3>1. Download Form</h3>
                    <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                        <input name="dlfile" type="hidden" value="NEC Form 13 - Amendments_ver1.docx">
                        <div class="panel panel-default">
                          <div class="panel-body">
                            <p class="pull-left">NEC Form 13 - Amendments_ver1.docx</p>
                            <p class="pull-right"><button class="btn btn-primary" type="submit" name="download">Download</button></p>
                          </div>
                        </div>
                    </form>
                  <h3>2. Choose Type of Amendment</h3>
                    <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                        
                        <input name="subid" type="hidden" value="<?php echo $_GET['id']; ?>">
                        
                        <div class="panel panel-default">
                          <div class="panel-body">
                                
                              <?php
                              $getmaxid = $obj->getMaxValueofppa($subid); 
                              $where = array("pid" => $getmaxid, "sreq_id" => "1");
                              $getsubreq = $obj->fetch_record_with_where("sub_request", $where);
                              if($getsubreq){
                                foreach($getsubreq as $req){
                                    echo '<div class="checkbox">
                                        <label><input name="amendmenttype[]" type="checkbox" checked value="1">Protocol</label>
                                    </div>';    
                                }
                              }
                              else{
                                echo '<div class="checkbox">
                                    <label><input name="amendmenttype[]" type="checkbox" value="1">Protocol</label>
                                </div>';
                                  
                              }
                              
                              ?>
                              
                              <?php
                              $getmaxid = $obj->getMaxValueofppa($subid); 
                              $where2 = array("pid" => $getmaxid, "sreq_id" => "2");
                              $getsubreq2 = $obj->fetch_record_with_where("sub_request", $where2);
                              if($getsubreq2){
                                  $st = "display:block";
                                foreach($getsubreq2 as $req){
                                    echo '<div class="checkbox">
                                            <label data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                <input name="amendmenttype[]" type="checkbox" checked value="2" id="myCheck" onclick="myFunction()">Informed Consent
                                            </label>
                                        </div>';    
                                }
                              }
                              else{
                                  $st = "display:none";
                                echo '<div class="checkbox">
                                        <label data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            <input name="amendmenttype[]" type="checkbox" value="2" id="myCheck" onclick="myFunction()">Informed Consent
                                        </label>
                                    </div>';
                                  
                              }
                              
                              ?>
                          </div>
                        </div>
                        
                        <div id="text" style="<?php echo $st;?>"">
                        <hr>
                        <p>Revisions made involve which of the following?</p>
                        <div class="panel panel-default">
                          <div class="panel-body">
                        <?php
                        
                        $getamend = $obj->fetch_record("amendment_icf_type");
                        foreach($getamend as $amend){
                            if(($amend['id']>=1)&&($amend['id']<=7)){
                                
                                $where = array(
                                    "pid" => $getmaxid,
                                    "aicf_id" => $amend['id']
                                );
                                $geticf = $obj->fetch_record_with_where("amendment_icf", $where);
                                if($geticf){
                                    foreach($geticf as $icf){
                                       echo '<div class="checkbox">
                                                <label><input type="checkbox" name="amendicf[]" checked value="'.$amend['id'].' required">'.$amend['aicf_desc'].'</label>
                                            </div>'; 
                                    }                                    
                                }
                                else{
                                    echo '<div class="checkbox">
                                        <label><input type="checkbox" name="amendicf[]" value="'.$amend['id'].' required">'.$amend['aicf_desc'].'</label>
                                    </div>';                                    
                                }
                                
                                
                            }
                        }
                        
                        ?>
                            <div id="collapseTwo" aria-expanded="false" class="collapse row">
                                    <div class="col-lg-12">
                                        <br>                                   
                                        <input type="text" class="form-control" id="othersicf" placeholder="Type here...">                                        
                                    </div>                                
                            </div>
                              
                          </div>
                        </div>
                        </div>
                        
                        
                    
                          <input id="fname" name="submid" type="hidden" value="<?php echo $subid;?>" placeholder="" class="form-control input-md">
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
                          <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].""; ?>
                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>">
                          
                         <?php $getmaxtimes = $obj->getmaxtimes($subid);
                            echo '<input type="hidden" name="maxreqtimes" value="'.$getmaxtimes.'">';
                            
                            $getmaxrevpost = $obj->getmaxpostapproval($subid);
                            $gmr = $getmaxrevpost + 1;
                            echo '<input id="maxrevpost" name="maxrevpost" type="hidden" value="'.$gmr.'">';
                          ?>
                            <input id="doctype" name="doctype" type="hidden" value="17,AmendmentRequestForm">
                          <h3>3. Upload Amendment Request Form</h3>
                              <span class="btn btn-default btn-file">
                                  <input type = "file" name = "uploadamend" required="required"/>                        
                              </span>
                              <input type = "submit" name="amendtype" class="btn btn-success"/>
                      </form> 
                  
                  
                    <?php 
                        $where = array("sub_id" => $subid, "doctype" => "17", "kind" => "ARF", "finaldoc" => "1");
                        $getclerance = $obj->fetch_record_with_where("document_postapproval", $where);
                            if($getclerance){
                                foreach($getclerance as $clear){
                                    echo '<br><a class="btn btn-default btn-xs" href="researcher_action.php?deleteamend=1&subid='.$clear['sub_id'].'&id='.$clear['file_id'].'" role="button"><span data-toggle="tooltip" title="DELETE" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> '.$clear['file_name'];
                                    echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$clear['path'].'">';
                                    $ecpath = $clear['path'];
                                }
                            }
                            else{
                                echo '<br><div class="alert alert-danger" role="alert">No file found.</div>';
                            }
                    ?>
                  <hr>
                    <h3>4. Upload the File to be Amended</h3>
                    <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">  
                  
                        
                        <div class="row">    
                            <div class="col-lg-12">
                                  <?php 
                                  $where = array("username" => $mi['username']);
                                  $getUserID = $obj->getUser("phrepuser", $where);
                                  if($getUserID){
                                      foreach($getUserID as $user){
                                          echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid">';
                                          $userid = $user['id'];
                                      }
                                  }
                                  
                                  $ppaid = $obj->getMaxValueofppa($subid);
                                  echo '<input type="hidden" value="'.$ppaid.'" name="ppaid">';
                                             
                                  ?>
                              <div class="col-lg-12 alert alert-info" role="alert">Step 1: <br>Select its <b>File Type</b>. Please upload the 
                              required documents.</div>
                                <select id="doctype" name="doctype" class="form-control" required="required">
                                    <option value="" disabled selected>(Select file type to upload)</option>
                                        <?php                                                 
                                            $getfilerev = $obj->getamendfile($subid);
                                                if($getfilerev > 0){
                                                    foreach($getfilerev as $revfile){
                                                        echo "<option value='".$revfile['docid'].",".$revfile['forfilename'].",".$revfile['revision'].",".$revfile['amend'].",".$revfile['file_id'].",".$revfile['kind'].",".$revfile['doctypetimes'].",document'>".$revfile['doctype_desc']."_".$revfile['doctypetimes']."</option>";
                                                    }
                                                } 
                                        ?>
                                        <?php //POST DOCUMENTS                                                 
                                            $getfilerevpa = $obj->getamendfilepa($subid);
                                                if($getfilerevpa > 0){
                                                    foreach($getfilerevpa as $revfilepa){
                                                        echo "<option value='".$revfilepa['docid'].",".$revfilepa['forfilename'].",".$revfilepa['revision'].",".$revfilepa['amend'].",".$revfilepa['file_id'].",".$revfilepa['kind'].",".$revfilepa['doctypetimes'].",document_postapproval'>".$revfilepa['doctype_desc']."_".$revfilepa['doctypetimes']."</option>";
                                                    }
                                                } 
                                        ?>


                                </select>
                            
                            
                            
                        </div>    
                  </div><br>
                    <div class="row">    
                      <div class="col-lg-12">  
                          
                            
                                  
                                  <fieldset> 
                                      <?php $rq = $obj->getmaxtimes($subid); echo '<input id="rqtimes" name="rqtimes" type="hidden" value="'.$rq.'">'; ?>
                                      
                                      <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id']; ?>" placeholder="" class="form-control input-md">
                                      <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                        <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].""; ?>
                                      <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link; ?>" placeholder="" class="form-control input-md">
                                        <?php $mi = getMemberInfo(); ?>
                                      <input id="username" name="username" type="hidden" value="<?php echo $mi['username']; ?>" placeholder="" class="form-control input-md">
                                  </fieldset>  
                                  
                                  <div class="alert alert-info" role="alert">Step 2: <br>Only doc, docx, xls, xlsx, ppt, pptx, or pdf 
                                      files are allowed to be uploaded.</div>
                                  <span class="btn btn-default btn-file">
                                      <input type = "file" name = "amendment" required/>                        
                                  </span>
                                  <input type = "submit" class="btn btn-success"/>
                                <p><small>*After clicking "Submit", please check the "Date Amended" in the table if it is current.</small></p>
                          
                          
                          
                                                
                      
                      </div> 
                    </div>
                  <hr>
                    </form>
                    
                
                <h3>5. Upload the Additional Files (Optional)</h3>    
                <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">  
                <div class="row">
                                        
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
                <div class="col-lg-12">
                    <div class="col-lg-12 alert alert-info" role="alert">Step 1: <br><p class="pull-left">Please select for a file type.</p><span class="glyphicon glyphicon-arrow-right pull-right" aria-hidden="true"></span><br></div>
                    
                    <select id="doctype" name="doctype" class="form-control" required="required">
                        <option value="">(Select file type to upload)</option>
                            <?php 
                            $myrow = $obj->fetch_record("document_type");
                            foreach ($myrow as $row) {
                                ?>  <?php 
                                        if(($row['docid'] == '15')||($row['docid'] == '17')||($row['docid'] == '18')||($row['docid'] == '21')){
                                            
                                        }
                                        else{
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
                                            
                                        }
                                        
                                        
                                    ?>
                                <?php
                            }
                            ?>
                            
                    </select>
                </div>
                </div>
                <br>
                <div class="row">    
                <div class="col-lg-12">           
                        <fieldset> 
                           <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                <input id="kind" name="kind" type="hidden" value="SF" placeholder="" class="form-control input-md">
                                <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].""; ?>
                                <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                <?php $mi = getMemberInfo(); ?>
                                <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                        </fieldset>         
                    <div class="alert alert-info" role="alert">Step 2: <br> Accepted files: doc, docx, xls, xlsx, jpg, jpeg, ppt, pptx, or pdf</div>
                    <span class="btn btn-default btn-file">
                    <input type = "file" name = "amendsup" required="required"/>                        
                    </span>
                    <input type = "submit" class="btn btn-success"/>
                </div>
            
            </div>
            </form>      
                <form action = "researcher_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">   
                    <?php 
                    $getreser = $obj->getresearcherInfo($userid);
                    if($getreser>0){
                        foreach($getreser as $researcher){
                            echo '<input name="resemail" type="hidden" value="'.$researcher['email'].'">';
                            echo '<input name="resfullname" type="hidden" value="'.$researcher['fname'].' '.$researcher['mname'].' '.$researcher['lname'].'">';
                        }
                    }
                    ?>
                    
                    <?php 
                    $getsec = $obj->getsecretary($subid);
                    if($getsec>0){
                        foreach($getsec as $sec){
                            $getsecInfo = $obj->getSecInfo($sec['secretary']);
                            if($getsecInfo>0){
                                foreach($getsecInfo as $secretary){
                                    echo '<input name="secemail" type="hidden" value="'.$secretary['email'].'">';
                                    echo '<input name="secfullname" type="hidden" value="'.$secretary['fname'].' '.$secretary['mname'].' '.$secretary['lname'].'">';
                                }                                
                            }
                        }
                    }
                    ?>
                    
                    <?php 
                        $where = array("sub_id" => $subid, "doctype" => "17", "kind" => "ARF", "finaldoc" => "1");
                        $getclerance = $obj->fetch_record_with_where("document", $where);
                            if($getclerance){
                                foreach($getclerance as $clear){
                                    echo ' <input name="path" type="hidden" value="'.$clear['path'].'">';
                                    $ecpath = $clear['path'];
                                }
                            }
                    ?>
                    
                    <input name="subject" type="hidden" value="Request for Amendments">
                    <textarea name="body" style="display:none;">
                     This is to inform your committee that I have filed an amendment of my approved proposal. Please let me know your my current standing
                     on this request. Thank you. Sample only.
                    </textarea>
                    
                    <?php
                        $getmax = $obj->getmaxstatus($subid);
                        if($getmax>0){
                            foreach($getmax as $sa){
                                $getstataction = $obj->getstat($subid, $sa['sa']);
                                if($getstataction>0){
                                    foreach($getstataction as $stat){
                                        if ($stat['status_action'] != '3'){
                                
                                 ?>
                                    
                                    <div class="row"><!--THIS IS THE BUTTON--><hr>
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

                                        <input id="fname" name="submid" type="hidden" value="<?php echo $subid;?>" placeholder="" class="form-control input-md">
                                          <div class="col-lg-1"></div>
                                          <div class="col-lg-10"><center><small>*Once clicked "Done Resubmit", it cannot be undone.</small>           
                                              <button type="submit" name="requestamend" class="btn btn-info">Apply Ammendment</button>
                                              <button type="submit" class="btn btn-danger" onclick='goBack()'>Cancel</button></center>
                                          </div>
                                          <div class="col-lg-1"></div>              
                                    </div>
                                
                                <?php }
                                else{echo '<br><div class="alert alert-danger" role="alert">You have already submitted what was required.</div>';}
                                    }
                                }
                            }
                        }
                    ?>
                    
                    
                    
                </form>
                  
                </div>
              
              <div class="col-lg-7">
                  <!--<h3>Resubmission</h3>-->
<!--                  <div class="row">
                      <div class="panel panel-default">
                         Default panel contents 
                        <div class="panel-heading">Files History</div>

                         Table 
                        <table class="table table-bordered table-condensed table-striped">
                            
                            <tr><center>
                                <th>VER</th>
                                <th>Type</th>
                                <th>Date Amended</th>
                                <th>Date Uploaded</th>
                            </center></tr>
                            
                            <?php 
//                            $where = array("sub_id"=>$subid, "finaldoc"=>'1');
//                            $getfiles = $obj->fetch_record_with_where("document", $where);
//                            if($getfiles > 0){
//                                foreach($getfiles as $files){
//                                    if(($files['kind'] == 'MP') || ($files['kind'] == 'SF')){
//                                        
//                                            echo '<tr>
//                                                <td><center>'.$getnum = $obj->ordinalize($files['revision']).'</td>
//                                                <td><small>'.$files['file_name'].'</small></td>
//                                                <td>'.date("M. j, Y G:i A",strtotime($files['date_modified'])).'</td>
//                                                <td>'.date("M. j, Y",strtotime($files['date_uploaded'])).'</center></td>
//                                            </tr>';  
//                                        
//                                        
//                                    }          
//                                }
//                            }
                            ?>
                            
                        </table>
                      </div>
                  </div>-->
                  
                  <div class="row"><h3>Files to be Amended</h3>
                      <div class="panel panel-success">
                        <!-- Default panel contents -->
                        <div class="panel-heading">List of Files<small> | You can delete file here and reupload again.</small></div>

                        <!-- Table -->
                        <table class="table table-condensed table-striped">
                            
                            <tr><center>
                                <th><center><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></center></th>
                                <th>Document Type</th>
                                <th>Date and Time Amended</th>
                            </center></tr>
                            
                            <?php 
                            $getamended = $obj->getamendedfile($subid);
                            if($getamended>0){
                                foreach($getamended as $amended){
                                    if(($amended['kind'] == 'MP') || ($amended['kind'] == 'SF')){
                                        if(($amended['finalamend']=='0')){
                                            
                                        }
                                        else{
                                            if($amended['tbl'] == '1'){
                                                echo '<tr>
                                                    <td><center><a class="btn btn-default btn-xs" href="researcher_action.php?deleteamendedfile=1&subid='.$amended['sub_id'].'&id='.$amended['file_id'].'&idd='.$amended['documentfile_id'].'&doctype='.$amended['doctype'].'&tb=1" role="button"><span data-toggle="tooltip" title="DELETE" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></center></td>
                                                    <td>'.$amended['doctype_desc'].'_'.$amended['doctypetimes'].'</td>
                                                    <td>'.date("M. j, Y G:i",strtotime($amended['date_modified'])).'</td>
                                                </tr>'; 
                                                
                                            }
                                            elseif($amended['tbl'] == '2'){
                                                echo '<tr>
                                                    <td><center><a class="btn btn-default btn-xs" href="researcher_action.php?deleteamendedfile=1&subid='.$amended['sub_id'].'&id='.$amended['file_id'].'&idd='.$amended['documentfile_id'].'&doctype='.$amended['doctype'].'&tb=2" role="button"><span data-toggle="tooltip" title="DELETE" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></center></td>
                                                    <td>'.$amended['doctype_desc'].'_'.$amended['doctypetimes'].'</td>
                                                    <td>'.date("M. j, Y G:i",strtotime($amended['date_modified'])).'</td>
                                                </tr>';                                                
                                            }                                             
                                        }
                                    } 
                                }
                            }
                            ?>
                            
                        </table>
                      </div>
                  </div>
                  
              </div>
              
              </div>
              
              
          </div>
<?php } else{ echo '<br><br><br><br><br><br><br><div class="row">
              <div class="col-lg-4"></div>
              <div class="col-lg-4"><div class="alert alert-danger" role="alert"><center>Please update your profile to get started.<br><br><a class="btn btn-success" href="update_profile.php" role="button">Update Profile!</a></center></div></div>
              <div class="col-lg-4"></div>
          </div> '; }?>
        
          
          
          
          
          
          
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
jQuery(document).ready(function($) {
        var checkBox = document.getElementById("myCheck");
        var text = document.getElementById("text");
        if (checkBox.checked == true){
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
});

    function myFunction() {
        var checkBox = document.getElementById("myCheck");
        var text = document.getElementById("text");
        if (checkBox.checked == true){
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }
</script>