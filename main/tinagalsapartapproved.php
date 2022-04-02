 <?php
                                  $getmaxdoc = $obj->getMaxDocumentStat($userid);
                                    if($getmaxdoc == ''){
                                        echo '<tr><td colspan="4"><center>No proposal has been reviewed yet.</center></td></tr>';
                                    }
                                    else{
                                        foreach($getmaxdoc as $prop){
//                                            if(($subm['pa_status'] == "onreview")||$subm['pa_status'] == null){
                                                if(($prop['status_action'] == '6')){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    echo '<td>';
                                                    echo '<a class="btn btn-default btn-xs" href="sec_sendemail.php?id='.$prop['sub_id'].'" role="button" data-toggle="tooltip" title="Request for Report">';
                                                    echo '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Email';
                                                    echo '</td></a>';   
                                                    echo '<td>'.$cc['coding'].'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    
                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                    echo '</tr>';
                                                }
                                                
                                                else if(($prop['status_action'] == '23')){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    echo '<td><button class="btn btn-default btn-xs" type="button">DONE</button></center></td></td>';   
                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    
                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a>';
                                                    echo '</tr>';
                                                }
                                                
                                                
                                                
                                                else if($prop['status_action'] == '12'){
                                                    //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    echo '<td><span class="glyphicon glyphicon-ok" aria-hidden="true" data-toggle="tooltip" title="EXEMPTED"></span></td>';   
                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                    echo '</tr>';
                                                }
                                                
//                                            }
                                        }       
                                    }
                                  ?>