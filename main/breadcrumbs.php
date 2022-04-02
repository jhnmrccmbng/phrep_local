
<div class="row"><!--THIS IS THE BREADCRUMBS OF THE PROCESS-->
                <ol class="breadcrumb">
                    <?php 
                    
                    $key = $obj->getmagicword();
                    $where = array("sub_id" => $id);
                    $get1 = $obj->fetch_record_with_where("submission", $where);
                    $cget1 = count($get1);
                    if($cget1>0){
                            echo '<li><a href="submission-s1.php?id='.$obj->encrypt($id, $key).'" class="btn" >1 START</a></li>';
                        
                    }
                    else{
                         echo '<li><a href="submission-s1.php?id='.$obj->encrypt($id, $key).'" class="btn disabled">1 START</a></li>';
                    }
                    ?>
                    
                    <?php 
                    $where = array("sub_id" => $id);
                    $get2 = $obj->fetch_record_with_where("proposal", $where);
                    $cget2 = count($get2);
                    if($cget2>0){
                            echo '<li><a href="basic_information.php?id='.$obj->encrypt($id, $key).'" class="btn" >2 ENTER PROPOSAL BASIC INFORMATION</a></li>';
                        
                    }
                    else{
                         echo '<li><a href="basic_information.php?id='.$obj->encrypt($id, $key).'" class="btn disabled">2 ENTER PROPOSAL BASIC INFORMATION</a></li>';
                    }
                    ?>
                    
                    
                    <?php 
                    $where = array("sub_id" => $id, "kind" => 'MP');
                    $get3 = $obj->fetch_record_with_where("document", $where);
                    $cget3 = count($get3);
                    if($cget3>0){
                            echo '<li><a href="uploadmainprop.php?id='.$obj->encrypt($id, $key).'" class="btn" >3 UPLOAD MAIN PROPOSAL</a></li>';
                        
                    }
                    else{
                         echo '<li><a href="uploadmainprop.php?id='.$obj->encrypt($id, $key).'" class="btn disabled">3 UPLOAD MAIN PROPOSAL</a></li>';
                    }
                    ?>
                    
                                        
                    
                    <?php 
                    $where = array("sub_id" => $id, "kind" => 'SF');
                    $get4 = $obj->fetch_record_with_where("document", $where);
                    $cget4 = count($get4);
                    if($cget4>0){
                            echo '<li><a href="uploadsuppfiles.php?id='.$obj->encrypt($id, $key).'" class="btn" >4 UPLOAD SUPPLEMENTARY FILES</a></li>';
                        
                    }
                    else{
                         echo '<li><a href="uploadsuppfiles.php?id='.$obj->encrypt($id, $key).'" class="btn disabled">4 UPLOAD SUPPLEMENTARY FILES</a></li>';
                    }
                    ?>
                    
                    
                    <?php 
                    $where = array("sub_id" => $id, "kind" => 'SF');
                    $get4 = $obj->fetch_record_with_where("document", $where);
                    $cget4 = count($get4);
                    if($cget4>0){
                            echo '<li><a href="confirmation.php?id='.$obj->encrypt($id, $key).'" class="btn" >5 CONFIRMATION</a></li>';
                        
                    }
                    else{
                         echo '<li><a href="confirmation.php?id='.$obj->encrypt($id, $key).'" class="btn disabled">5 CONFIRMATION</a></li>';
                    }
                    ?>
                                        
                    
                </ol>
</div><!--THIS IS THE BREADCRUMBS OF THE PROCESS-->