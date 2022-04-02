<?php
include "submission_action.php";

                    $res = $obj->fetch_max_record("submission");
                    foreach ($res as $row) {
                        echo $row["id"];
                    }
?>