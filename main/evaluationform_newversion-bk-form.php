            
                    <form class="form-horizontal" role="form" action="rev_dashboard_action.php" method="POST">
                    <?php
                    //KATAPUSAN SA REVIEWER NA NAKA REVIEW NA
                    
                    $where = array("sub_id" => $id, "revid" => $userid, "revform_id" => "1");
                    $getanswers = $obj->fetch_record_with_where("rev_answers", $where);
                    if($getanswers){ ?>
                        <h4><b>Guide questions for reviewing the proposal/protocol:</b></h4>
                        <?php
                           $getquestions = $obj->getQuestions(3, $id, $userid);
                           $num = 1;
//                         
                           ?>  

                           <?php
                           

                           foreach($getquestions as $quest){
                               if($quest['revid'] == $userid){

                                    echo "<p class='questions'>".$quest['aidq'].".)".$quest['qdesc']."<br>";

                                    if($quest['idat'] == '1'){
                                        if($quest['ansdesc'] == "Unable to assess"){
                                            echo '<label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio1'.$quest['aidq'].'" class="forremove" value="'.$quest['aidq'].',Unable to assess" required checked="checked"> Unable to assess
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio2'.$quest['aidq'].'" value="'.$quest['aidq'].',Yes"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio3'.$quest['aidq'].'" value="'.$quest['aidq'].',No"> No
                                                </label>';
                                            if($quest['withtext'] == 1){
                                                    $where = array("idq" =>$quest['aidq'], "sub_id" => $id, "revid" => $userid );
                                                    $getsub = $obj->fetch_record_with_where("rev_subanswers", $where);
                                                    if($getsub){
                                                        foreach($getsub as $suba){
                                                            echo '<div id="q'.$quest['aidq'].'" style="display:none">
                                                                <textarea class="form-control forremove" rows="3" id="t'.$quest['aidq'].'" name="text'.$quest['aidq'].'" required>'.$suba['subansdesc'].'</textarea>
                                                            </div>';                                                        
                                                        }                                                        
                                                    }
                                                    else{
                                                            echo '<div id="q'.$quest['aidq'].'" style="display:none">
                                                                <textarea class="form-control forremove" rows="3" id="t'.$quest['aidq'].'" name="text'.$quest['aidq'].'" required></textarea>
                                                            </div>';
                                                        
                                                    }
                                                }
                                            
                                        }

                                        else if($quest['ansdesc'] == "Yes" OR $quest['ansdesc'] == "No"){
                                            echo '<label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio1'.$quest['aidq'].'" class="forremove" value="'.$quest['aidq'].',Unable to assess" required> Unable to assess
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio2'.$quest['aidq'].'" value="'.$quest['aidq'].',Yes" checked="checked"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio3'.$quest['aidq'].'" value="'.$quest['aidq'].',No"> No
                                                </label>';
                                            if($quest['withtext'] == 1){
                                                    $where = array("idq" =>$quest['aidq'], "sub_id" => $id, "revid" => $userid );
                                                    $getsub = $obj->fetch_record_with_where("rev_subanswers", $where);
                                                    if($getsub){
                                                        foreach($getsub as $suba){
                                                            echo '<div id="q'.$quest['aidq'].'" style="display:none">
                                                                <textarea class="form-control forremove" rows="3" id="t'.$quest['aidq'].'" name="text'.$quest['aidq'].'" required>'.$suba['subansdesc'].'</textarea>
                                                            </div>';                                                        
                                                        }                                                        
                                                    }
                                                    else{
                                                            echo '<div id="q'.$quest['aidq'].'" style="display:none">
                                                                <textarea class="form-control forremove" rows="3" id="t'.$quest['aidq'].'" name="text'.$quest['aidq'].'" required></textarea>
                                                            </div>';
                                                        
                                                    }
                                                }
                                            
                                        }
                                        else if($quest['ansdesc'] == "YES" OR $quest['ansdesc'] == "No"){
                                            echo '<label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio1'.$quest['aidq'].'" class="forremove" value="'.$quest['aidq'].',Unable to assess" required> Unable to assess
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio2'.$quest['aidq'].'" value="'.$quest['aidq'].',Yes"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio3'.$quest['aidq'].'" value="'.$quest['aidq'].',No" checked="checked"> No
                                                </label>';
                                            if($quest['withtext'] == 1){
                                                    $where = array("idq" =>$quest['aidq'], "sub_id" => $id, "revid" => $userid );
                                                    $getsub = $obj->fetch_record_with_where("rev_subanswers", $where);
                                                    if($getsub){
                                                        foreach($getsub as $suba){
                                                            echo '<div id="q'.$quest['aidq'].'" style="display:none">
                                                                <textarea class="form-control forremove" rows="3" id="t'.$quest['aidq'].'" name="text'.$quest['aidq'].'" required>'.$suba['subansdesc'].'</textarea>
                                                            </div>';                                                        
                                                        }                                                        
                                                    }
                                                    else{
                                                            echo '<div id="q'.$quest['aidq'].'" style="display:none">
                                                                <textarea class="form-control forremove" rows="3" id="t'.$quest['aidq'].'" name="text'.$quest['aidq'].'" required></textarea>
                                                            </div>';
                                                        
                                                    }
                                                }
                                            
                                        }
                                    }
                                    else if($quest['idat'] == '2'){
                                        if($quest['ansdesc'] == "Not applicable"){
                                            echo '<label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio1'.$quest['aidq'].'" value="'.$quest['aidq'].',Not applicable" class="forremove" required checked="checked"> Not applicable
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio2'.$quest['aidq'].'" value="'.$quest['aidq'].',Yes"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio3'.$quest['aidq'].'" value="'.$quest['aidq'].',No"> No
                                                </label>';
                                            if($quest['withtext'] == 1){
                                                    echo str_replace('%id%',$quest['aidq'],$obj->fetchAnswerType("3"));
                                                }
                                            
                                        }
                                        else if($quest['ansdesc'] == "Yes"){
                                            echo '<label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio1'.$quest['aidq'].'" value="'.$quest['aidq'].',Not applicable" class="forremove" required> Not applicable
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio2'.$quest['aidq'].'" value="'.$quest['aidq'].',Yes" checked="checked"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio3'.$quest['aidq'].'" value="'.$quest['aidq'].',No"> No
                                                </label>';
                                            if($quest['withtext'] == 1){
                                                    echo str_replace('%id%',$quest['aidq'],$obj->fetchAnswerType("3"));
                                                }
                                            
                                        }
                                        else if($quest['ansdesc'] == "No"){
                                            echo '<label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio1'.$quest['aidq'].'" value="'.$quest['aidq'].',Not applicable" class="forremove" required> Not applicable
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio2'.$quest['aidq'].'" value="'.$quest['aidq'].',Yes"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                        <input type="radio" onclick="javascript:choices'.$quest['aidq'].'();" name="inlineRadioOptions'.$quest['aidq'].'" id="inlineRadio3'.$quest['aidq'].'" value="'.$quest['aidq'].',No" checked="checked"> No
                                                </label>';
                                            if($quest['withtext'] == 1){
                                                    echo str_replace('%id%',$quest['aidq'],$obj->fetchAnswerType("3"));
                                                }
                                            
                                        }
                                    }
                                    else if($quest['idat'] == '3'){
                                            echo '<div id="q'.$quest['aidq'].'" style="display:none">
                                                  <textarea class="form-control forremove" rows="3" id="t'.$quest['aidq'].'" name="text'.$quest['aidq'].'" required>'.$quest['ansdesc'].'</textarea>
                                                  </div>';
                                    }
                                    
                                    echo "</p><br>";
                                    
                                    
                                    
                               }
                               else if($quest['revid'] == null){
                                   
                                    echo "<p class='questions'>".$quest['aidq'].".)".$quest['qdesc']."<br>"; 
                                    
                                    echo str_replace('%id%',$quest['aidq'],$obj->fetchAnswerType($quest['idat']));
                                    if($quest['withtext'] == 1){
                                            echo str_replace('%id%',$quest['aidq'],$obj->fetchAnswerType("3"));
                                        }
                                        
                                    echo "</p><br>";
                                    
//                                    if($quest['idat'] == '3'){ 
//                                            echo '<div id="q'.$quest['aidq'].'" style="display:none">
//                                                  <textarea class="form-control forremove" rows="3" id="t'.$quest['aidq'].'" name="text'.$quest['aidq'].'" required></textarea>
//                                                  </div>';
//                                        
//                                    }
                                    
                               }
                               
//                                $except = "AND idat != '4'";
//                                $getq = $obj->fetchQuestionsReviewspecific('1', $quest['idq'], $except);
//                                if($getq){
//                                    foreach($getq as $q){
//                                        echo "<p class='questions' data-id='".$q['idq']."'>".$num.".) ".$q['qdesc']."<br>";
//                                        
//                                        echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType($q['idat']));
//                                        
//                                        if($q['withtext'] == 1){
//                                            echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType("3"));
//                                        }
//                                        echo "</p><br>";
//                                    } 
//                                } 
                           }
                                
                        
                        
                    }
                    //KATAPUSAN SA REVIEWER NA NAKA REVIEW NA
                    
                    //DIRI SUGOD KUNG WALA PA KA SUBMIT ANG REVIEWER  SA ILANG EVALUATION
                    
                    else{
                        ?>
                        <h4><b>Guide questions for reviewing the proposal/protocol:</b></h4>
                        <?php
                           $except = "AND idat != '4'";
                           $getq = $obj->fetchQuestionsReview('3', $except);
                           if($getq){
                               $num = 1;
                               foreach($getq as $q){
                                   echo "<p class='questions'>".$num.".) ".$q['qdesc']."<br>";
                                   echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType($q['idat']));
                                   if($q['withtext'] == 1){
                                       echo str_replace('%id%',$q['idq'],$obj->fetchAnswerType("3"));
                                   }
                                   echo "</p><br>";$num++;
                               }
                           }
                    }
                    
                    ?>
                                        
<!--                    <h3><b>Recommendation:</b></h3>
                    <p><small><i>Your choice of recommendation will not be stored when you click "Save" button. It will be saved once you click "Submit".</i></small></p>
                        <p class="questions">
                            <?php
//                            $getdecs = $obj->fetch_record("rev_decision");
//                            if($getdecs){
//                                foreach($getdecs as $ds){
//                                    echo '<label class="radio-inline">
//                                            <input type="radio" name="recommendation" class="forremove" id="inlineRadio'.$ds['id'].'" value="'.$ds['id'].'" required> '.$ds['dec_desc'].'
//                                            </label>';
//                                }
//                            }
                            ?>
                        </p>-->
                        
                        <input id="subid" name="subid" type="hidden" value="<?php echo $id;?>">
                        <input id="revid" name="revid" type="hidden" value="<?php echo $userid;?>">
                        <input id="evaltype" name="evaltype" type="hidden" value="1">
                        <input id="evaldate" name="evaldate" type="hidden" value="<?php $date = date_create('now'); echo date_format($date, 'U');?>">
                        
                        <?php 
                            $maxEF = $obj->getmaxEFdoc($id);
                            echo '<input name="maxef" type="hidden" value="'.$maxEF.'">';
                        ?>
                    <div class="row"><br>
                        <div class="col-lg-12">
                            <button class="btn btn-primary btn-lg" type="submit" name="savereview" id="savereview" data-toggle="tooltip" title="You can return later to submit">Save</button>
                            <button class="btn btn-success btn-lg" type="submit" name="submitreview" id="submitreview" data-toggle="tooltip" title="This will finalized your evaluation">Submit</button>
                            
                            <a class="btn btn-default btn-lg" href="<?php echo "reviewproposal.php?id=".$id."#evaluation";?>" role="button">Cancel</a>
                        </div>
                    </div> 
                    </form>
