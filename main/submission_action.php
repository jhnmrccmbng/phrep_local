<?php
error_reporting(E_ALL);
include "sample_dbtest.php";

class DataOperation extends Database {

    public function insert_record($table, $fields) {
        $sql = "";
        $sql .= "INSERT INTO " . $table;
        $sql .= " (" . implode(",", array_keys($fields)) . ") VALUES ";
        $sql .= " ('" . implode("','", array_values($fields)) . "')";
//        echo $sql; 
        $query = mysqli_query($this->con, $sql);
        if ($query) {
            return true;
        }
    }

    public function insert_record_with_update($table, $fields, $id) {

        $sql = "SELECT * FROM " . $table . " WHERE sub_id = '" . $id . "'";
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);
//        echo $row;
        if ($row > 0) {
            $sql = "UPDATE " . $table . " SET ";
            $cnt = count($fields);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields);
                $key = array_keys($fields);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                header("location:uploadmainprop.php?id=" . $id);//////////////////////////////////
            }
        } else {
            $sql = "";
            $sql .= "INSERT INTO " . $table;
            $sql .= " (" . implode(",", array_keys($fields)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($fields)) . "')";
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                return true;
            }
        }
    }

    public function fetch_record($table) {
        $sql = "SELECT * FROM " . $table;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }

    public function fetch_record_with_where($table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }

    public function select_record($table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition;
        $query = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_array($query);
        return $row;
    }

    public function update_record($table, $where, $fields) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        foreach ($fields as $key => $value) {
            $sql .= $key . "='" . $value . "', ";
        }
        $sql = substr($sql, 0, -2);
        $sql = "UPDATE " . $table . " SET " . $sql . " WHERE " . $condition;
        if (mysqli_query($this->con, $sql)) {
            return true;
        }
    }

    public function delete_record($table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql = "DELETE FROM " . $table . " WHERE " . $condition;
        if (mysqli_query($this->con, $sql)) {
            return true;
        }

        return false;
    }
    function getmagicword(){
        $sql = "SELECT theword FROM magicword where id = '1'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $theword = $row["theword"];
        return $theword;
    }
    
    function encrypt($string, $key){
        $string = openssl_encrypt($string, "AES-128-ECB", $key);
        // $string = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
        $string = $this->base64_url_encode($string);
        return $string;
    }
    function decrypt($string, $key){
        $string = $this->base64_url_decode($string);
        $string = openssl_decrypt($string, "AES-128-ECB", $key);
        // $string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));
        return $string;
    }
    function base64_url_encode($input) {
        $dirty = array("+", "/", "=");
        $clean = array(".", "_", "-");
        $cleanurl = str_replace($dirty, $clean, $input);
        
        return $cleanurl;
    }

    function base64_url_decode($input) {        
        $dirty = array("+", "/", "=");
        $clean = array(".", "_", "-");
        $cleanurl = str_replace($clean, $dirty, $input);
        
        return $cleanurl;
    }
    
    public function submissions($table, $field, $subid, $username){
        if($subid=='0'){
            $this->insert_record($table, $field);
            $sql2 = "SELECT MAX(sub_id) as id FROM submission WHERE username = '".$username."' LIMIT 1";
            $qry = mysqli_query($this->con, $sql2);
                if ($qry) {
                    $num = mysqli_fetch_array($qry);
                    $id = $this->encrypt($num["id"], $this->getmagicword());
                    header("location:basic_information.php?id=" . $id);
                }
        }
        else{            
            $id = $this->decrypt($subid, $this->getmagicword());
            $where = array("sub_id" => $id);
            $this->update_record("submission", $where, $field);
            $id = $this->encrypt($id, $this->getmagicword());
            header("location:basic_information.php?id=" . $id);
        }
    }
    
    
    function getStudyType($id, $userid){
        
        $sql = "SELECT * FROM proposal a 
                INNER JOIN studytypelist b ON a.prop_studytype = b.idst
                WHERE a.sub_id = '$id' AND a.username = '$userid'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    

    public function submission($table1, $fields1, $table2, $fields2, $table3, $fields3, $username) {
        $sql = "";
        $sql .= "INSERT INTO " . $table1;
        $sql .= " (" . implode(",", array_keys($fields1)) . ") VALUES ";
        $sql .= " ('" . implode("','", array_values($fields1)) . "'); ";
        $sql .= "INSERT INTO " . $table2;
        $sql .= " (sub_id," . implode(",", array_keys($fields2)) . ") VALUES ";
        $sql .= " (LAST_INSERT_ID(),'" . implode("','", array_values($fields2)) . "'); ";
        $sql .= "INSERT INTO " . $table3;
        $sql .= " (sub_id," . implode(",", array_keys($fields3)) . ") VALUES ";
        $sql .= " " . implode(",", array_values($fields3)) . "; ";

        ///THIS IS TO QUERY AND CLOSE THE SESSION EVERY SUCCESSFUL QUERY
        if (mysqli_multi_query($this->con, $sql)) {
            do {
                if ($result = mysqli_store_result($this->con)) {
                    mysqli_free_result($result);
                } else {
                    
                }
            } while (mysqli_more_results($this->con) && mysqli_next_result($this->con));
        }
        ///THIS IS TO QUERY AND CLOSE THE SESSION EVERY SUCCESSFUL QUERY
        ///THIS IS TO SELECT TO ID THAT HAS BEEN ADDED
        $sql2 = "SELECT MAX(sub_id) as id FROM submission WHERE username = '".$username."' LIMIT 1";
        $qry = mysqli_query($this->con, $sql2);
        if ($qry) {
            $num = mysqli_fetch_array($qry);
            $nums[] = $num;
//            echo $num["id"];
            header("location:basic_information.php?id=" . $num["id"]);///////////////////////////////////////////
        }///THIS IS TO SELECT TO ID THAT HAS BEEN ADDED
    }

    public function saving_dynamicform($table, $fields, $keys) {
        $sql = "";
        $sql .= "INSERT INTO " . $table;
        $sql .= " (" . implode(", ", array_values($keys)) . ") VALUES ";
        $sql .= " " . implode(",", array_values($fields)) . "; ";
//            echo $sql;
        $query = mysqli_query($this->con, $sql);
        if ($query) {
            return true;
        }

        return false;
    }

    public function saving_monetary($table, $fields, $keys, $subid, $arrayh) {
        $num = count($fields);
        for ($i = 0; $i < $num; $i++) {
            $sql = "SELECT * FROM " . $table . " WHERE " . $keys[0] . " = '" . $arrayh[$i] . "' AND " . $keys[1] . " = '" . $subid . "'";
//                    echo $sql;
            $qry = mysqli_query($this->con, $sql);
            $row = mysqli_num_rows($qry);
//                    echo $row;
            if ($row > 0) {
//                        echo "There is already monetary source!";
            } else {
                $sql = "";
                $sql .= "INSERT INTO " . $table;
                $sql .= " (" . implode(", ", array_values($keys)) . ") VALUES ";
                $sql .= " " . $fields[$i] . "; ";
//                        echo $sql;
                $query = mysqli_query($this->con, $sql);
                if ($query) {
                    return true;
                }

                return false;
            }
        }
    }

    public function save_keyword($table1, $table2, $field1, $count, $id) {

        for ($i = 0; $i < $count; $i++) {
            $fieldlower = strtolower($field1[$i]);
            $sql = "SELECT * FROM " . $table1 . " WHERE `kw_desc` = '" . $fieldlower . "'";
            $query = mysqli_query($this->con, $sql);
            $rows = mysqli_num_rows($query);
            if ($rows > 0) {
                $sql = "SELECT id FROM " . $table1 . " WHERE kw_desc = '" . $fieldlower . "'";
                $query = mysqli_query($this->con, $sql);
                $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
                $sql = "SELECT * FROM " . $table2 . " WHERE kw_id = '" . $row["id"] . "' AND sub_id = '" . $id . "'";
                $query = mysqli_query($this->con, $sql);
                $rows = mysqli_num_rows($query);
                if ($rows > 0) {
//                                    echo "Its already in keyword table<br>"; 
                } else {
                    $sql = "INSERT INTO " . $table2 . " (sub_id, kw_id) VALUES ('" . $id . "', '" . $row["id"] . "')";
                    $query = mysqli_query($this->con, $sql);
                    if ($query) {
//                        return true;
//                                        echo "<br>And stored it in keywords table<br>";
//                                        header("location:basic_information.php?msg=Record Inserted");//commented na
                    }
//                                    else 
//                                        echo "<br>AND not saved in the keywords table<br>";
//                                    mysqli_free_result($query);
                }
            } else {
                $sql = "INSERT INTO " . $table1 . " (kw_desc) VALUES ('" . $fieldlower . "')";
                $query = mysqli_query($this->con, $sql);
                if ($query) {
                    $sql = "SELECT id FROM " . $table1 . " WHERE kw_desc = '" . $fieldlower . "'";
                    $query = mysqli_query($this->con, $sql);
                    $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
                    $rows = mysqli_num_rows($query);
                    if ($rows > 0) {
                        $sql1 = "INSERT INTO " . $table2 . " (sub_id, kw_id) VALUES ('" . $id . "', '" . $row["id"] . "')";
                        $query1 = mysqli_query($this->con, $sql1);
                        //                        echo "<br>But it is now saved in the DB<br>";
                        if ($query1) {
//                                header("location:basic_information.php?msg=Record Inserted");commented na
//                                return TRUE;
                        }
                    }
                } else{
                    
                }
            }
        }
        return TRUE;
    }

    public function multiple_insert($table, $fields, $data, $subid, $arrayh) {
        $num = count($data);
        for ($i = 0; $i < $num; $i++) {
            $sql = "SELECT * FROM " . $table . " WHERE " . $fields[0] . " = '" . $arrayh[$i] . "' AND " . $fields[1] . " = '" . $subid . "'";
//                    echo $sql;
            $qry = mysqli_query($this->con, $sql);
            $row = mysqli_num_rows($qry);
//                    echo $row;
            if ($row > 0) {
//                        echo "&nbsp; It is on the table already!<br>";                                                
            } else {
                $sql = "";
                $sql .= "INSERT INTO " . $table;
                $sql .= " (" . $fields[0] . "," . $fields[1] . ") VALUES ";
                $sql .= "('" . $arrayh[$i] . "','" . $subid . "')";
//                        echo $sql;
                $query = mysqli_query($this->con, $sql);
                if ($query) {
//                    echo "&nbsp; added new in the table!<br>";
                }
            }
        }
        //TO UPLOADMAINPROP
//        header("location:uploadmainprop.php?id=" . $subid);//////////////////////////////////
    }

    public function saving_assessment($table, $data, $field, $subid, $arrayh, $arraym) {
        $num = count($data);
//                echo $num;
        for ($i = 0; $i < $num; $i++) {
            $sql = "SELECT * FROM " . $table . " WHERE " . $field[0] . " = '" . $arrayh[$i] . "' AND " . $field[1] . " = '" . $subid . "' AND " . $field[2] . " = '" . $arraym[$i] . "' ";
//                    echo $sql;
            $qry = mysqli_query($this->con, $sql);
            $row = mysqli_num_rows($qry);
//                    echo $row;
            if ($row > 0) {
//                        echo "&nbsp; It is on the assessment table already!<br>";                                                
            } else {
                $sql = "";
                $sql .= "INSERT INTO " . $table;
                $sql .= " (" . $field[0] . "," . $field[1] . "," . $field[2] . ") VALUES ";
                $sql .= "('" . $arrayh[$i] . "','" . $subid . "','" . $arraym[$i] . "')";
//                        echo $sql;
                $query = mysqli_query($this->con, $sql);
                if ($query) {
//                    echo "&nbsp; added new in the assessment table!<br>";
                }
            }
        }
    }

    public function insert_one_select($table, $fields, $count, $fld, $arrayh, $subid) {
        $num = count($count);
//                echo $num;
        for ($i = 0; $i < $num; $i++) {
            $sql = "SELECT * FROM " . $table . " WHERE " . $fld[0] . " = '" . $arrayh[$i] . "' AND " . $fld[1] . " = '" . $subid . "'";
//                    echo $sql;
            $qry = mysqli_query($this->con, $sql);
            $row = mysqli_num_rows($qry);
//                    echo $row;
            if ($row > 0) {
//                        echo "&nbsp; It is on the apply risk table already!<br>";                                                
            } else {
                $sql = "";
                $sql .= "INSERT INTO " . $table;
                $sql .= " (" . implode(",", array_keys($fields)) . ") VALUES ";
                $sql .= " ('" . implode("','", array_values($fields)) . "')";
                $query = mysqli_query($this->con, $sql);
                if ($query) {
//                    echo "&nbsp; added new in the risk apply table!<br>";
                }
            }
        }
    }

    public function fetch_max_record($table) {
        $sql = "SELECT MAX(sub_id) as id FROM " . $table . " LIMIT 1";
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
//                    $array[] = $row;
            echo $row["id"];
        }
        return $array;
    }

    public function saving_studentresearch($table1, $fields1, $id) {
        $sql = "SELECT * FROM " . $table1 . " WHERE sub_id = '" . $id . "'";
        
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);
//          echo $row;

        if ($row > 0) {
            $sql = "UPDATE " . $table1 . " SET ";
            $cnt = count($fields1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields1);
                $key = array_keys($fields1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//            echo $sql; echo "I UPDATE KAY NAA"; //icomment later
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                header("location:uploadmainprop.php?id=" . $id);///////////////////////////////////
            }
        } else {
            $sql = "";
            $sql .= "INSERT INTO " . $table1;
            $sql .= " (" . implode(",", array_keys($fields1)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($fields1)) . "')";
//            echo $sql; echo "INSERT LANG KAY WALA PA"; //icomment later
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                return true;
            }
        }
    }

    public function saving_studentresearchdet($table1, $fields1, $id, $studres) {
        $sql = "SELECT * FROM " . $table1 . " WHERE sub_id = '" . $id . "'";
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);
//          echo $row;

        if (($row > 0) && ($studres) == '1') {
            $sql = "UPDATE " . $table1 . " SET ";
            $cnt = count($fields1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields1);
                $key = array_keys($fields1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                header("location:uploadmainprop.php?id=" . $id);////////////////////////////////////
            }
        } else if (($row > 0) && ($studres) == '2') {
            $sql = "DELETE FROM " . $table1 . " WHERE sub_id = '" . $id . "'";
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                header("location:uploadmainprop.php?id=" . $id);///////////////////////////////////////
            }
        } else {
            $sql = "";
            $sql .= "INSERT INTO " . $table1;
            $sql .= " (" . implode(",", array_keys($fields1)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($fields1)) . "')";
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                echo "STUDENT RESEARCH DETAIL INSERTED";//icomment later
                return true;
            }
        }
    }
    
    public function saving_others_institution($table, $where, $id, $studres, $acad){
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition. " LIMIT 1";
        
        $query = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_array($query);
        if ($row > 1){
            foreach($query as $found){
                $studentresdets = array(
                    "sub_id" => $id,
                    "insti_id" => $found[id],
                    "acad_id" => $acad
                );
                $this->saving_studentresearchdet("studentresdet", $studentresdets, $id, $studres);
            }
        }
        else{
            $sql = "";
            $sql .= "INSERT INTO " . $table;
            $sql .= " (" . implode(",", array_keys($where)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($where)) . "')";
            
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $last_id = mysqli_insert_id($this->con);
                $studentresdets = array(
                    "sub_id" => $id,
                    "insti_id" => $last_id,
                    "acad_id" => $acad
                );
                $this->saving_studentresearchdet("studentresdet", $studentresdets, $id, $studres);
//                echo "STUDENT RESEARCH INSERTED"; //icomment later
            }
        }
    }

    public function insert_record_w_validation($table, $fields, $checkingid) {
        $sql = "";
        $condition = "";
        foreach ($checkingid as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql = "SELECT * FROM " . $table . " WHERE " . $condition;
        $query = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($query);
        if ($row > 0) {
//            echo "There is data in the Nationwideres!<br>";
        } else {
            $sql = "";
            $sql .= "INSERT INTO " . $table;
            $sql .= " (" . implode(",", array_keys($fields)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($fields)) . "')";
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                return true;
            }
        }
    }

    public function inserting_tables_w_subprocess($table1, $fields1, $table2, $fields2, $count, $checkingid) {

        $sql = "";
        $condition = "";
        foreach ($checkingid as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql = "SELECT * FROM " . $table1 . " WHERE " . $condition;

        $query = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($query);

        if ($row > 0) {
//            echo "&nbsp; It is on the country_multi table already!<br>";
        } else {
            $sql = "";
            $sql .= "INSERT INTO " . $table1;
            $sql .= " (" . implode(",", array_keys($fields1)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($fields1)) . "')";
//                        echo $sql;
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                        echo $count;
                for ($i = 0; $i < $count; $i++) {
                    $sql = "";
                    $condition = "";
                    foreach ($fields2[$i] as $key => $value) {
                        $condition .= $key . "='" . $value . "' AND ";
                    }
                    $condition = substr($condition, 0, -5);
                    $sql = " SELECT * FROM " . $table2 . " WHERE " . $condition;
//                            echo $sql;

                    $query = mysqli_query($this->con, $sql);
                    $row = mysqli_num_rows($query);

                    if ($row > 0) {
//                        echo "&nbsp; It is on the country_list table already!<br>";
                    } else {
                        $sql = "";
                        $sql .= "INSERT INTO " . $table2;
                        $sql .= " (" . implode(",", array_keys($fields2[$i])) . ") VALUES ";
                        $sql .= " ('" . implode("','", array_values($fields2[$i])) . "')";
//                                echo $sql;
                        $query = mysqli_query($this->con, $sql);
                        if ($query) {
//                            echo "&nbsp; added new in the country_list table!<br>";
                        }
                    }
                }
            } else
                return FALSE;
        }
    }

    public function inserting_tables_w_subprocesss($table1, $fields1, $table2, $field2, $id, $determine, $count, $country) {
        $sql = "SELECT * FROM " . $table1 . " WHERE sub_id = '" . $id . "'";
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);
//          echo $row;

        if (($row > 0) && ($determine) == '1') {
            $sql = "UPDATE " . $table1 . " SET ";
            $cnt = count($fields1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields1);
                $key = array_keys($fields1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $this->deleting_one_by_one($table2, $country, $count, $id, "country_id"); //DELETE USAH NIA DAYUN/////////////////////////////
                $this->inserting_multiple_data($table2, $field2, $count); //MAG-ADD SIA UG PANIBAG-O////////////////////////////
//                header("location:uploadmainprop.php?id=" . $id);////////////////////////////////////////////////////////
            }
        } else if (($row > 0) && ($determine) == '2') {

            $sql = "UPDATE " . $table1 . " SET ";
            $cnt = count($fields1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields1);
                $key = array_keys($fields1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $sql = "DELETE FROM " . $table2 . " WHERE sub_id = '" . $id . "'";
                $query = mysqli_query($this->con, $sql);
                if ($query) {
//                    header("location:uploadmainprop.php?id=" . $id);//////////////////////////////////////////////
                }
            }
        } else {
            $sql = "";
            $sql .= "INSERT INTO " . $table1;
            $sql .= " (" . implode(",", array_keys($fields1)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($fields1)) . "')";
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//			return true;
                $this->inserting_multiple_data($table2, $field2, $count);///////////////////////////////////////////
            }
        }
    }

    public function inserting_multiple_data($table2, $field2, $count) {
        for ($i = 0; $i < $count; $i++) {
            $sql = "";
            $condition = "";
            foreach ($field2[$i] as $key => $value) {
                $condition .= $key . "='" . $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            $sql = " SELECT * FROM " . $table2 . " WHERE " . $condition;
            
            $query = mysqli_query($this->con, $sql);
            $row = mysqli_num_rows($query);

            if ($row > 0) {
//                echo "INSERTED<br>";
            } else {
                $sql = "";
                $sql .= "INSERT INTO " . $table2;
                $sql .= " (" . implode(",", array_keys($field2[$i])) . ") VALUES ";
                $sql .= " ('" . implode("','", array_values($field2[$i])) . "')";
//                echo $sql; exit;
                $query = mysqli_query($this->con, $sql);
                if ($query) {
//                    return true;
//                    echo "INSERTED DATA";//icomment later
//                    echo "&nbsp; added new in the country_list table!<br>";
                }
            }
        }
    }


    public function deleting_one_by_one($table2, $field2, $count, $id, $field) {

        $sqlll = "";
        $sqll = "DELETE FROM " . $table2 . " WHERE sub_id = " . $id . " AND " . $field . " NOT IN (";
        for ($i = 0; $i < $count; $i++) {
            $sql = $field2[$i][$field] = preg_replace('/[^\d]/', '', $field2[$i][$field]) . ",";

            $sqll .= $sql;
        }
        $sqlll .= substr($sqll, 0, -1);
        $sqlll .= ")";
//        echo $sqlll;
        $query = mysqli_query($this->con, $sqlll);
        if ($query) {
            return true;
        }
    }
    
        public function deleting_one_by_one_in_three_columns($table2, $field2, $count, $id, $field) {

        $sqlll = "";
        $sqll = "DELETE FROM " . $table2 . " WHERE sub_id = " . $id . " AND " . $field . " IN (";
        for ($i = 0; $i < $count; $i++) {
            $sql = $field2[$i][$field] = preg_replace('/[^\d]/', '', $field2[$i][$field]) . ",";

            $sqll .= $sql;
        }
        $sqlll .= substr($sqll, 0, -1);
        $sqlll .= ")";
//        echo $sqlll;
        $query = mysqli_query($this->con, $sqlll);
                if ($query) {
                    return true;
                }
    }
    public function inserting_multicountry_research($table1, $table2, $field2, $table3, $field3, $id, $size, $determine) {

        $sql = "SELECT * FROM " . $table2 . " WHERE sub_id = '" . $id . "'";
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);


        if (($row > 0) && ($determine) == '1') {

            $sql = "UPDATE " . $table2 . " SET ";
            $cnt = count($field2);
//            echo $cnt;
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($field2);
                $key = array_keys($field2);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//            echo $sql;

            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $sql = "DELETE FROM " . $table3 . " WHERE sub_id = '" . $id . "'";
                $query2 = mysqli_query($this->con, $sql);
                
                    if($query2){
                        $this->inserting_multiple_data($table3, $field3, $size); //MAG-ADD SIA UG PANIBAG-O
//                        header("location:uploadmainprop.php?id=" . $id);                        
                    }
            }
        }
        else if (($row > 0) && ($determine) == '2') {
            $sql = "UPDATE " . $table2 . " SET ";
            $cnt = count($field2);
//            echo $cnt;
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($field2);
                $key = array_keys($field2);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//            echo $sql;

            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $sql = "DELETE FROM " . $table3 . " WHERE sub_id = '" . $id . "'";
                $query2 = mysqli_query($this->con, $sql);
                
                    if($query2){
//                        header("location:uploadmainprop.php?id=" . $id);                        
                    }
            }
        }    
        else {
            $sql = "";
            $sql .= "INSERT INTO " . $table2;
            $sql .= " (" . implode(",", array_keys($field2)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($field2)) . "')";
//            echo $sql; exit;
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                echo "MULTI COUNTRY INSERTED"; //icomment later
//			return true;
                if ($determine == 1){$this->inserting_multiple_data($table3, $field3, $size); }
            }
        }
    }
    
    public function inserting_nationwide_research($table1, $table2, $field2, $table3, $field3, $id, $determine, $size, $size1, $nationres1) {

        $sql = "SELECT * FROM " . $table2 . " WHERE sub_id = '" . $id . "'";
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);


        if (($row > 0) && ($determine) == '3') {

            $sql = "UPDATE " . $table2 . " SET ";
            $cnt = count($nationres1);
//            echo $cnt;
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($nationres1);
                $key = array_keys($nationres1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//            echo $sql;

            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $this->deleting_one_by_one($table3, $field2, $size, $id, "nreg_code"); //DELETE USAH NIA DAYUN///////////////////////
                $this->inserting_multiple_data($table3, $field2, $size); //MAG-ADD SIA UG PANIBAG-O
//                header("location:uploadmainprop.php?id=" . $id);
            }
        } else if (($row > 0) && ($determine) == '2') {
            $sql = "UPDATE " . $table2 . " SET ";
            $cnt = count($nationres1);
//            echo $cnt;
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($nationres1);
                $key = array_keys($nationres1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//            echo $sql;

            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $this->deleting_one_by_one($table3, $field3, $size1, $id, "nreg_code"); //DELETE USAH NIA DAYUN///////////////////////////
                $this->inserting_multiple_data($table3, $field3, $size1); //MAG-ADD SIA UG PANIBAG-O
//                header("location:uploadmainprop.php?id=" . $id);
            }
        } else if (($row > 0) && ($determine) == '1') {

            $sql = "UPDATE " . $table2 . " SET ";
            $cnt = count($nationres1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($nationres1);
                $key = array_keys($nationres1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $sql = "DELETE FROM " . $table3 . " WHERE sub_id = '" . $id . "'";
                $query = mysqli_query($this->con, $sql);
                if ($query) {
//                    header("location:uploadmainprop.php?id=" . $id);/////////////////////////////////////////
                }
            }
        } else {
            $sql = "";
            $sql .= "INSERT INTO " . $table2;
            $sql .= " (" . implode(",", array_keys($nationres1)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($nationres1)) . "')";
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//			return true;
                if ($determine == 2){$this->inserting_multiple_data($table3, $field3, $size1); }/////////////////////////////////
                else if ($determine == 3){$this->inserting_multiple_data($table3, $field2, $size);}
                else{}                    
            }
        }
    }

    public function inserting_involves_human($table1, $fields1, $table2, $field2, $table3, $field3, $id, $determine, $countprop, $countdatacol) {
        $sql = "SELECT * FROM " . $table1 . " WHERE sub_id = '" . $id . "'";
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);
//          echo $sql;

        if (($row > 0) && ($determine) == '1') {
            $sql = "UPDATE " . $table1 . " SET ";
            $cnt = count($fields1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields1);
                $key = array_keys($fields1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                if($this->deleting_one_by_one($table2, $field2, $countprop, $id, "proptype_id")){ //DELETE USAH NIA DAYUN/////////////////////////////
                $this->inserting_multiple_data($table2, $field2, $countprop);} //MAG-ADD SIA UG PANIBAG-O////////////////////////////

                if($this->deleting_one_by_one($table3, $field3, $countdatacol, $id, "datacol_id")){ //DELETE USAH NIA DAYUN/////////////////////////////
                $this->inserting_multiple_data($table3, $field3, $countdatacol);} //MAG-ADD SIA UG PANIBAG-O////////////////////////////
//                header("location:uploadmainprop.php?id=" . $id);////////////////////////////////////////////////////////
            }
        } else if (($row > 0) && ($determine) == '2') {

            $sql = "UPDATE " . $table1 . " SET ";
            $cnt = count($fields1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields1);
                $key = array_keys($fields1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
                $sql = "DELETE FROM " . $table2 . " WHERE sub_id = '" . $id . "'";
                $query = mysqli_query($this->con, $sql);
                if ($query) {
//                    header("location:uploadmainprop.php?id=" . $id);//////////////////////////////////////////////
                }
            }
            if ($query) {
                $sql = "DELETE FROM " . $table3 . " WHERE sub_id = '" . $id . "'";
                $query = mysqli_query($this->con, $sql);
                if ($query) {
//                    header("location:uploadmainprop.php?id=" . $id);//////////////////////////////////////////////
                }
            }
        } else {
            $sql = "";
            $sql .= "INSERT INTO " . $table1;
            $sql .= " (" . implode(",", array_keys($fields1)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($fields1)) . "')";
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//			return true;
                if ($determine == 1) {
                    $this->inserting_multiple_data($table2, $field2, $countprop); ///////////////////////////////////////////
                    $this->inserting_multiple_data($table3, $field3, $countdatacol);
                } else {
                    
                }
            }
        }
    }

    public function inserting_proposal_review($table1, $fields1, $table2, $field2, $id, $determine, $countrev) {
        $sql = "SELECT * FROM " . $table1 . " WHERE sub_id = '" . $id . "'";
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);
//          echo $sql;
//        echo $determine;

        if (($row > 0) && ($determine) == '1') {
            $sql = "UPDATE " . $table1 . " SET ";
            $cnt = count($fields1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields1);
                $key = array_keys($fields1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                header("location:uploadmainprop.php?id=" . $id);////////////////////////////////////////////////////////commented na
                if($this->deleting_one_by_one($table2, $field2, $countrev, $id, "reviewcom_id")){ //DELETE sia sa reviewcom/////////////////////////////
                    $this->inserting_multiple_data($table2, $field2, $countrev);} //MAG-ADD SIA UG PANIBAG-O sa reviewcom////////////////////////////
            }
        }
        else if (($row > 0) && ($determine) == '2') {
            $sql = "UPDATE " . $table1 . " SET ";
            $cnt = count($fields1);
            for ($i = 0; $i < $cnt; $i++) {
                $val = array_values($fields1);
                $key = array_keys($fields1);
                $sql .= $key[$i] . " = '" . $val[$i] . "', ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE sub_id = " . $id . "";
//                echo $sql ; 
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//                header("location:uploadmainprop.php?id=" . $id);////////////////////////////////////////////////////////commented na
                $this->deleting_one_by_one($table2, $field2, $countrev, $id, "reviewcom_id"); //DELETE sia sa reviewcom/////////////////////////////
            }
        }
        
        else {
            $sql = "";
            $sql .= "INSERT INTO " . $table1;
            $sql .= " (" . implode(",", array_keys($fields1)) . ") VALUES ";
            $sql .= " ('" . implode("','", array_values($fields1)) . "')";
            $query = mysqli_query($this->con, $sql);
            if ($query) {
//			return true;
                if ($determine == 1) {
                    $this->inserting_multiple_data($table2, $field2, $countrev); ///////////////////////////////////////////
                } else {
                    
                }
            }
        }
    }

    public function inserting_monetary_source($table1, $fields1, $id, $countmonetary) {

        $this->inserting_multiple_data($table1, $fields1, $countmonetary); //MAG-ADD SIA UG PANIBAG-O////////////////////////////
    }
    
    public function inserting_yesno_riskassessment($table1, $fields1, $id, $countassess) {

//        header("location:uploadmainprop.php?id=" . $id);////////////////////////////////////////////////////////commented na
        if($this->deleting_one_by_one_in_three_columns($table1, $fields1, $countassess, $id, "loa_id")){ //DELETE USAH NIA DAYUN (IN ni siya)/////////////////////////////
            $this->inserting_multiple_data($table1, $fields1, $countassess);} //MAG-ADD SIA UG PANIBAG-O////////////////////////////
    }
    
    public function inserting_single_entry($table1, $fields1, $id, $count, $commonid) {

//        header("location:uploadmainprop.php?id=" . $id);////////////////////////////////////////////////////////commented na
        if($this->deleting_one_by_one($table1, $fields1, $count, $id, $commonid)){ //DELETE USAH NIA DAYUN/////////////////////////////
            $this->inserting_multiple_data($table1, $fields1, $count);} //MAG-ADD SIA UG PANIBAG-O////////////////////////////
    }
    public function get_confirmation_joining_two($table1, $table2, $table3, $id, $username, $fid){
//        $sql = "SELECT * FROM ".$table." WHERE sub_id='".$id."' AND username = '".$username."'";
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.sub_id = b.sub_id ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$fid." = c.id ";
        $sql .= "WHERE a.sub_id = ".$id." and a.username = '".$username."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
        
    function get_researcher_info($table1, $table2, $field1, $field2, $username){
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$field1." = b.".$field2." ";
        $sql .= "WHERE a.".$field1." = '".$username."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function get_confirmation_joining_one($table1, $table2, $field1, $field2, $id, $username){
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$field1." = b.".$field2." ";
        $sql .= "WHERE a.sub_id = ".$id." and a.username = '".$username."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function get_date_duration($table, $id, $username){
        $sql= "SELECT * FROM ".$table." WHERE sub_id = ".$id." AND username = '".$username."'";
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
        function get_confirmation_joining_two_for_assess($table1, $table2, $table3, $id, $username, $fid, $partid){
//        $sql = "SELECT * FROM ".$table." WHERE sub_id='".$id."' AND username = '".$username."'";
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.sub_id = b.sub_id ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$fid." = c.id ";
        $sql .= "WHERE a.sub_id = ".$id." and a.username = '".$username."' ";
        $sql .= "AND c.part_id = '".$partid."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
        public function get_data_from($table, $fieldvalue, $fieldname, $id){
        $sql = "SELECT * FROM ".$table." WHERE $fieldname = '".$fieldvalue."' and sub_id = '".$id."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
    public function additional_researcher($table, $field, $id, $count){
        
        $sql = "SELECT * FROM " . $table . " WHERE sub_id = '" . $id . "'";
//        echo $sql;
        $qry = mysqli_query($this->con, $sql);
        $row = mysqli_num_rows($qry);
        
        if ($row > 0){
            $where = array("sub_id" => $id);
            $this->delete_record($table, $where);
            $this->inserting_multiple_data($table, $field, $count);
        }
        else{
            $this->inserting_multiple_data($table, $field, $count);
        }
        
        
    }
    
    public function getUser($table, $where){
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    public function getInsti($subid){
        $sql = "SELECT *, b.id AS rl FROM submission a 
                INNER JOIN rec_list b on a.reclist_id = b.id
                INNER JOIN institution c on b.insti_id = c.id
                WHERE a.sub_id = '$subid'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
    return $array;
    }
    
    public function monetarySource($subid, $user){
        $sql = "SELECT * FROM proposal a 
                INNER JOIN monetary_source b ON a.sub_id = b.sub_id
                WHERE a.username = '$user' AND a.sub_id = '$subid'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
    return $array;
    }
    
    public function getCommu($subid){
        $sql = "SELECT *, b.id AS rl FROM submission a 
                INNER JOIN rec_list b on a.reclist_id = b.id
                INNER JOIN region c on b.region_id = c.id
                WHERE a.sub_id = '$subid'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
    return $array;
    }    
    
    function generatingCode($table1, $table2, $table3, $id1, $id2, $id3, $id4, $username, $id){
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
        $sql .= "WHERE a.username = '".$username."' AND a.sub_id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    public function insert_record_sponsor($table, $fields) {
        $sql = "";
        $sql .= "INSERT INTO " . $table;
        $sql .= " (" . implode(",", array_keys($fields)) . ") VALUES ";
        $sql .= " ('" . implode("','", array_values($fields)) . "')";
        $query = mysqli_query($this->con, $sql);
        if ($query) {
            return mysqli_insert_id($this->con);
        }
    }    
    public function addpsponsor($table, $field, $id){
        if($this->select_record($table, $field)){
            $a = $this->select_record($table, $field); //this is the id
//            echo $a['id'];
            $adspon = array(
                "propdet_primspon" => $a['id']
            ); 
            $where = array(
                "sub_id" => $id
            );
            if($this->update_record("proposal", $where, $adspon)){
//                echo "OK"; 
            }
//            exit;
        }
        else{
            $adspon = array(
                "propdet_primspon" => $this->insert_record_sponsor($table, $field));
            $where = array(
                "sub_id" => $id
            );
            if($this->update_record("proposal", $where, $adspon)){
//                echo "OK"; exit;
            }
            }
    }

}

$obj = new DataOperation;



if (isset($_POST["submit"])) {
    
//    echo "<pre>".var_dump($_POST)."</pre>";exit;
    $username = $_POST["userid"];
    $subid = $_POST["subid"];
    
    date_default_timezone_set('Asia/Manila');
    $datetime = date("Y-m-d H:i:s",strtotime("now"));
    
    $classifi = $_POST["classifi"];
    
    //SAVING INSTITUTION BASED RESEARCH
    if ($classifi == '1'){
        $subfields = array(
            "reclist_id" => $_POST["rec"],
            "rc_id" => $classifi,//pareho lang
            "date_created" => $datetime,
            "username" => $username
        );
        
        if ($obj->submissions("submission", $subfields, $subid, $username)) {
            
        }
    }
    //SAVING COMMUNITY BASED RESEARCH
    else if ($classifi == '2'){
        $subfields = array(
            "reclist_id" => $_POST["rec2"],
            "rc_id" => $classifi,//pareho lang
            "date_created" => $datetime,
            "username" => $username
        );
        
        if ($obj->submissions("submission", $subfields, $subid, $username)) {
           
        }
    }
    
    
}
if(isset($_POST["submitmainprop"])){
    
    $key = $obj->getmagicword();
    $cleanid = $obj->decrypt($_POST["subid"], $key);
}


if(isset($_POST["submitmainprop"])){ //jan22commented
//    echo "<pre>"; var_dump($_POST); echo "</pre>"; exit;
    
    if(isset($_POST['fname']) == null){
        
    }
    else{
        $id = $cleanid;
        $where = array("sub_id" => $id);
        if($obj->delete_record("researcher_additional", $where)){
            $fname = $_POST["fname"];
            $mname = $_POST["mname"];
            $lname = $_POST["lname"];
            $email = $_POST["email"];
            $pnum = $_POST["pnum"];
            $affil = $_POST["affil"];

            $count = count($_POST["fname"]);

            for ($i = 0; $i < $count; $i++) {
                $researcheraddl[$i] = array(
                    "sub_id" => $id,
                    "res_fname" => $fname[$i],
                    "res_mname" => $mname[$i],
                    "res_lname" => $lname[$i],
                    "res_email" => $email[$i],
                    "res_pnum" => $pnum[$i],
                    "res_insti" => $affil[$i]
                );
                if($obj->insert_record("researcher_additional", $researcheraddl[$i])){

                }
            }
            
        }
//        exit;

//        if ($obj->additional_researcher("researcher_additional", $researcheraddl, $id, $count)) {
////            header("location:uploadmainprop.php?id=".$id);
//        }
    }    
}


if (isset($_POST["submitmainprop"])) { //B. Title and Summary of Proposal and C. Proposal Details //jan22commented
    $background = addslashes($_POST["background"]);
    $objective = addslashes($_POST["objective"]);
    $studytype = addslashes($_POST["studytype"]);
    $expected = addslashes($_POST["expected"]);
    $nwstrtdt = date("Y-m-d", strtotime($_POST["srtdt"]));
    $nwenddt = date("Y-m-d", strtotime($_POST["enddt"]));
    $id = $cleanid;


    $myArray = array(
        "username" => $_POST["userid"],
        "sub_id" => $cleanid,
        "prop_ptitle" => $_POST["ptitle"],
//        "prop_stitle" => $_POST["stitle"],
        "prop_background" => $background,
        "prop_obj" => $objective,
        "prop_studytype" => $studytype,
        "prop_outcomes" => $expected,
        "propdet_strtdate" => $nwstrtdt,
        "propdet_enddate" => $nwenddt
//        "propdet_primspon" => $_POST["sponsor1"]
    );
//    print_r($myArray); exit;
    if ($obj->insert_record_with_update("proposal", $myArray, $id)) {
//		header("location:submission-s1.php?msg=Record Inserted");
    }
}

if (isset($_POST["submitmainprop"])) { //Keyword //jan22commented
    $subid = $cleanid;
    $keyword = $_POST["keyword"];
    
    $count = count($keyword);
    for ($i = 0; $i < $count; $i++) {
        $kwlist[$i] = array(
            "kw_desc" => $keyword[$i]
        );
    }
    $values = array();
    foreach ($kwlist as $item) {
        $values[] = "{$item['kw_desc']}";
    }
    $values = implode(", ", $values);
    $values = explode(", ", $values);
    
    $num = count($values);
//    print_r($values);
    
       

    if ($obj->save_keyword("keywords_list", "keywords", $values, $num, $subid)) {
//            header("location:basic_information.php?msg=Record Inserted");
    }
}

if (isset($_POST["submitmainprop"])) { //C. Saving Student Research NEW //jan22commented
    $id = $cleanid;
    $studres = $_POST["studres"];

    $studentres = array(
        "sub_id" => $id,
        "stures_stat" => $studres
    );

    if ($obj->saving_studentresearch("studentres", $studentres, $id)) {
//		header("location:submission-s1.php?msg=Record Inserted");
    }
}

if (isset($_POST["submitmainprop"])) { //C. Saving Student Research Detail NEW //jan22commented
    $id = $cleanid;
    $insti = $_POST["insti_school"];
    $acad = $_POST["acad"];
    $studres = $_POST["studres"];
    $addedschool = $_POST["addinsti"];
       

    if ($insti == 1){
        
        $addinsti = array(
            "school_name" => $addedschool
        );
        
        if ($obj->saving_others_institution("institution_school", $addinsti, $id, $studres, $acad)) {
    //		header("location:submission-s1.php?msg=Record Inserted");
        }
    
    }
    else{
       $studentresdet = array(
        "sub_id" => $id,
        "insti_id" => $insti,
        "acad_id" => $acad
    );

        if ($obj->saving_studentresearchdet("studentresdet", $studentresdet, $id, $studres)) {
    //		header("location:submission-s1.php?msg=Record Inserted");
        }
    
    }
    
    
}

if (isset($_POST["submitmainprop"])) { //B. Saving Primary Sponsor //jan22commented
    $primesponsor = $_POST["sponsor1"];
    $id = $cleanid;
    
    if($primesponsor == "10"){
        $addpsponsor = array(
            "spon_desc" => $_POST["addpsponsor"]
        );

//        print_r($addpsponsor); 
//        echo "10 sya";
//        exit;

        if($obj->addpsponsor("sponsorlist", $addpsponsor, $id)){

        }        
    }
    else{ 
        $myArray = array(
            "propdet_primspon" => $primesponsor
        );
        $where = array(
            "sub_id" => $cleanid          
        );
        
        if($obj->update_record("proposal", $where, $myArray)){
//                echo "OK"; exit;
            }
        
    }
}


if (isset($_POST["submitmainprop"])) { //C. Saving secondary Sponsors //jan22commented
    $id = $cleanid;
    $secsponsor = $_POST["secsponsor"];
//    print_r($secsponsor);
//    $multi = array();
    $size = count($secsponsor);
//    echo $size;

    $where = array("sub_id" => $id);
    if($obj->delete_record("sponsor", $where)){
        for ($i = 0; $i < $size; $i++) {
            $multi[$i] = array(
                "spon_id" => $secsponsor[$i],
                "sub_id" => $id
            );
            if($obj->fetch_record_with_where("sponsor", $multi[$i])){
            
            }
            else{
                $obj->insert_record("sponsor", $multi[$i]);
            }
        }        
    }
}
if (isset($_POST["submitmainprop"])) { //C. Saving Multi-Country Research //jan22commented
    
    $id = $cleanid;
    $mcresearch = $_POST["multires"];
    $country = $_POST["country"];
    $count = count($country);
    
    $multicountryres = array(
        "sub_id" => $id,
        "mcountry_stat" => $mcresearch
    );
    
    for ($i = 0; $i < $count; $i++) {//IF YES
        $countries[$i] = array(
            "sub_id" => $id,
            "country_id" => $country[$i]
        );
    }
    
    if ($obj->inserting_multicountry_research("proposal", "country_multi", $multicountryres, "country_list", $countries, $id, $count, $mcresearch)) {
//		header("location:submission-s1.php?msg=Record Inserted");
    }
}
if (isset($_POST["submitmainprop"])) { //C. Saving Nationwide ResearchNEW //jan22commented
        
    $id = $cleanid;
    $nationres = $_POST["nationres"];
    $natregion = $_POST['natregion'];
    
    
    $check = array("sub_id" => "$id");
    $checknationreg = $obj->fetch_record_with_where("nationwideres", $check);
    if($checknationreg){
        foreach ($checknationreg as $reg){
            
            if($reg["nwideres_stat"] == "1"){
                $regwhere = array("sub_id" => $id); $regfields = array("nwideres_stat" => $nationres);
                if($obj->update_record("nationwideres", $regwhere, $regfields)){
                    if($nationres == "2"){
                        $fields = array("sub_id" => $id, "nreg_code" => $nationres);
                        if($obj->insert_record("nationregion", $fields)){
//                            echo "Updated and Inserred". $nationres;
                        }
                    }
                    else if($nationres == "3"){
                        $regmulti = $_POST["regionmulti"];
                        $count = count($regmulti);
                        for ($i = 0; $i < $count; $i++){
                            $rfields = array("sub_id" => $id, "nreg_code" => $regmulti[$i]);
                            if($obj->insert_record("nationregion", $rfields)){ 
//                                echo "Updated and Inserted". $nationres;
                            }                        
                        }                        
                    }
                    else{}
                }
            }
            
            else if($reg["nwideres_stat"] == "2"){
                $regwhere = array("sub_id" => $id); $regfields = array("nwideres_stat" => $nationres);
                if($obj->update_record("nationwideres", $regwhere, $regfields)){
                    if($nationres == "1"){
                        $fields = array("sub_id" => $id);
                        if($obj->delete_record("nationregion", $fields)){
//                            echo "Deleted". $nationres;
                        }
                    }
                    else if($nationres == "3"){
                        $fields = array("sub_id" => $id);
                        if($obj->delete_record("nationregion", $fields)){
//                            echo "Deleted". $nationres;
                            $regmulti = $_POST["regionmulti"];
                            $count = count($regmulti);
                            for ($i = 0; $i < $count; $i++){
                                $rfields = array("sub_id" => $id, "nreg_code" => $regmulti[$i]);
                                if($obj->insert_record("nationregion", $rfields)){ 
//                                    echo "Updated and Inserted". $nationres;
                                }
                            }    
                        }                        
                    }
                    else if($nationres == "2"){
                        $fields = array("sub_id" => $id);
                        if($obj->delete_record("nationregion", $fields)){
//                            echo "Deleted". $nationres;
                            
                            $fields = array("sub_id" => $id, "nreg_code" => $natregion);
                            if($obj->insert_record("nationregion", $fields)){
//                                echo "Updated and Inserred". $nationres;
                            }    
                        }    
                    }
                }                
            }
            else if($reg["nwideres_stat"] == "3"){
                $regwhere = array("sub_id" => $id); $regfields = array("nwideres_stat" => $nationres);

                    
                if($obj->update_record("nationwideres", $regwhere, $regfields)){
                    if($nationres == "1"){
                        $fields = array("sub_id" => $id);
                        if($obj->delete_record("nationregion", $fields)){
//                            echo "Deleted". $nationres;
                        }
                    }
                    else if($nationres == "2"){
                        $fields = array("sub_id" => $id);
                        if($obj->delete_record("nationregion", $fields)){
//                            echo "Deleted". $nationres;
                            
                            $fields = array("sub_id" => $id, "nreg_code" => $natregion);
                            if($obj->insert_record("nationregion", $fields)){
//                                echo "Updated and Inserred". $nationres;
                            }    
                        }    
                    }


                    else if($nationres == "3"){
                        $fields = array("sub_id" => $id);
                        if($obj->delete_record("nationregion", $fields)){
//                            echo "Deleted". $nationres;
                            
                            // JM ADDED THIS PARA KAPAG NASA NATIONALRES 3, MAUUPDATE ito
                            $regmulti = $_POST["regionmulti"];
                            $count = count($regmulti);
                            for ($i = 0; $i < $count; $i++){
                                $rfields = array("sub_id" => $id, "nreg_code" => $regmulti[$i]);
                                if($obj->insert_record("nationregion", $rfields)){ 
//                                echo "Updated and Inserted". $nationres;
                                }                        
                            }     
                        }    
                    }


                    else{}
                }                 
            }
            else{}
        }
    }
//     else{
//         if ($nationres == "1") {
//             $fields = array("sub_id" => $id, "nwideres_stat" => $nationres);
//             if ($obj->insert_record("nationwideres", $fields)) {
// //                echo "Inserred" . $nationres;
//             }
//         } 
//         else if($nationres == "2"){
//             $fields = array("sub_id" => $id, "nwideres_stat" => $nationres);
//             if ($obj->insert_record("nationwideres", $fields)) {
// //                echo "Updated and Inserred" . $nationres;
//                 $nfields = array("sub_id" => $id, "nreg_code" => $nationres);
//                 if($obj->insert_record("nationregion", $nfields)){
                    
//                 }
//             }
            
//         }
//         else if($nationres == "3"){
//             $fields = array("sub_id" => $id, "nwideres_stat" => $nationres);
//             if ($obj->insert_record("nationwideres", $fields)) {
// //                echo "Updated and Inserred" . $nationres;
//                 $regmulti = $_POST["regionmulti"];
//                 $count = count($regmulti);
//                 for ($i = 0; $i < $count; $i++) {
//                     $rfields = array("sub_id" => $id, "nreg_code" => $regmulti[$i]);
//                     if ($obj->insert_record("nationregion", $rfields)) {
// //                        echo "Updated and Inserted" . $nationres;
//                     }
//                 }
//             }
//         }
//         else{}
//     }
}

if (isset($_POST["submitmainprop"])) { //C. Saving Research Fields //jan22commented
    
    $id = $cleanid;
    $myArray = $_POST["resfield"];
    $multi = array();
    $size = count($myArray);

    for ($i = 0; $i < $size; $i++) {
        $multi[$i] = array(
            "resfield_id" => $myArray[$i],
            "sub_id" => $id
        );
    }
//        print_r($multi);

    $keys = array_keys($multi[0]);
//        echo implode(',', $keys);

    $arrayf = array_column($multi, 'resfield_id');


    if ($obj->deleting_one_by_one("researchfields", $multi, $size, $id, "resfield_id")) {
            $obj->inserting_multiple_data("researchfields", $multi, $size);
    }
}

if (isset($_POST["submitmainprop"])) { //C. Saving INVOLEVES HUMAN SUBJECT NEW //jan22commented
    //SETTING THE ID INTO ARRAYS FOR CHECKING
    $id = $cleanid;
    $determine = $_POST["humansubj"];
    $proptype = $_POST['proptype'];
    $datacol = $_POST["datacol"];

    $hmnsbj = array(
        "sub_id" => $id,
        "hmnsubj_code" => $determine
    );
    $sizeprop = count($proptype);

    for ($i = 0; $i < $sizeprop; $i++) {
        $proptype[$i] = array(
            "sub_id" => $id,
            "proptype_id" => $proptype[$i]
        );
    }
    $sizedatacol = count($datacol);
    for ($i = 0; $i < $sizedatacol; $i++) {
        $dtcol[$i] = array(
            "sub_id" => $id,
            "datacol_id" => $datacol[$i]
        );
    }
    if ($obj->inserting_involves_human("humansubject", $hmnsbj, "hmnsubj", $proptype, "datacol", $dtcol, $id, $determine, $sizeprop, $sizedatacol)) {
        //		header("location:submission-s1.php?msg=Record Inserted");
    }
}

if (isset($_POST["submitmainprop"])) { //C. Saving PROPOSAL REVIEW BY OTHER REVIEW COMMITTEE NEW //jan22commented
    $id = $cleanid;
    $propcom = $_POST["propcom"]; //determine pud
    $revcom = $_POST["revcom"];
    $countrevcom = count($revcom);

    $revcomdata = array(
        "sub_id" => $id,
        "rcom_stat" => $propcom
    );

    for ($i = 0; $i < $countrevcom; $i++) {
        $revcoma[$i] = array(
            "sub_id" => $id,
            "reviewcom_id" => $revcom[$i]
        );
    }

    if ($obj->inserting_proposal_review("reviewcomdata", $revcomdata, "reviewcom", $revcoma, $id, $propcom, $countrevcom)) {
//		header("location:submission-s1.php?msg=Record Inserted");
    }
}

if (isset($_POST["submitmainprop"])) {//D. Monetary Support NEW //jan22commented
    $monsrc_id = $_POST['monsrc'];
    $id = $cleanid;
    $amount = str_replace(",", "", $_POST["amount"]);

    $where = array("sub_id" => $id);
    if($obj->delete_record("monetary_source", $where)){}

    $size = count($monsrc_id);    
        for ($i = 0; $i < $size; $i++) {
            $res[$i] = array(
                "monsrc_id" => $monsrc_id[$i],
                "sub_id" => $id,
                "amount" => $amount[$i]
            );
//            print_r($res[$i]); exit;
            $obj->insert_record("monetary_source", $res[$i]);  
        }     

}

if (isset($_POST["submitmainprop"])) {//E. Assessment Questionnaire //jan22commented
    $id = $cleanid;
    $loa = $_POST['q'];
//        print_r($loa);
    $cnt = count($loa);
    $cn = $cnt + 1;

    for ($i = 1; $i < $cn; $i++) {////mao ni ang field1
        $res1[$i] = array(
            "loa_ans" => $_POST['radios-' . $i . '']
        );
    }
    $assesval = array_column($res1, 'loa_ans');
    
    for ($i = 0; $i < $cnt; $i++) {////mao ni ang field1
        $res[$i] = array(
            "loa_id" => $loa[$i],
            "sub_id" => $id,
            "loa_ans" => $assesval[$i]
        );
    }  
//print_r($res);    
    if ($obj->inserting_yesno_riskassessment("assessment_ans", $res, $id, $cnt)) {
        
    }
}

if (isset($_POST["submitmainprop"])) {//E. Assessment Questionnaire RISK LEVEL//jan22commented
    $risklevel = $_POST["risklevel"];
    $id = $cleanid;
    $count = count($risklevel);

    for ($i = 0; $i < $count; $i++) {
        $rlevel[$i] = array(
            "risklevel_id" => $risklevel,
            "sub_id" => $id,
        );
    }

    if ($obj->inserting_single_entry("risklevel", $rlevel, $id, $count, "risklevel_id")) {
//		header("location:submission-s1.php?msg=Record Inserted");
    }
}

if (isset($_POST["submitmainprop"])) {//E. Assessment Questionnaire CONFLICT OF INTEREST //jan22commented
    $conflict = $_POST["conflict"];
    $id = $cleanid;
    $count = count($conflict);

    for ($i = 0; $i < $count; $i++) {
        $conf[$i] = array(
            "intelist_id" => $conflict[$i],
            "sub_id" => $id,
        );
    }

    if ($obj->inserting_single_entry("coninterest", $conf, $id, $count, "intelist_id")) {
//		header("location:submission-s1.php?msg=Record Inserted");
    }
}

if (isset($_POST["submitmainprop"])) { //E. Assessment Questionnaire RISK APPLY TO //jan22commented
    $id = $cleanid;
    $riskapply = $_POST["riskapply"];
    $count = count($riskapply);

    for ($i = 0; $i < $count; $i++) {
        $rapply[$i] = array(
            "riskapp_id" => $riskapply[$i],
            "sub_id" => $id
        );
    }

    if ($obj->inserting_single_entry("riskapply", $rapply, $id, $count, "riskapp_id")) {
//		header("location:submission-s1.php?msg=Record Inserted");
    }
}

if (isset($_POST["submitmainprop"])) { //E. Assessment Questionnaire BENEFITS RESEARCH PROJECT //jan22commented
    $id = $cleanid;
    $benefit = $_POST["potenben"];
    $count = count($benefit);

    for ($i = 0; $i < $count; $i++) {
        $benef[$i] = array(
            "potenben_id" => $benefit[$i],
            "sub_id" => $id
        );
    }

    if ($obj->inserting_single_entry("potenbenefits", $benef, $id, $count, "potenben_id")) {
//		header("location:submission-s1.php?msg=Record Inserted");
    }
}


if(isset($_POST["submitmainprop"])){ //jan22commented
    $id = $_POST["subid"];
    header("location:uploadmainprop.php?id=".$id);
    
}


if (isset($_POST["edit"])) {
    $id = $_POST["id"];
    $where = array("id" => $id);
    $myArray = array(
        "m_name" => $_POST["name"],
        "qty" => $_POST["qty"]
    );
    if ($obj->update_record("stock", $where, $myArray)) {
        header("location:index.php?msg=Record Updated Successfully");
    }
}

if (isset($_GET["delete"])) {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $where = array("id" => $id);
        if ($obj->delete_record("stock", $where)) {
            header("location:index.php?msg=Record Deleted Sucessfully");
        }
    }
}




if (isset($_POST["updaterec"])) {
    $username = $_POST["userid"];
    $subid = $cleanid;
    
    date_default_timezone_set('Asia/Manila');
    $datetime = date("Y-m-d H:i:s",strtotime("now"));
    
    $classifi = $_POST["classifi"];
    
    //SAVING INSTITUTION BASED RESEARCH
    if ($classifi == '1'){
        $where = array("sub_id" => $subid);
        $subfields = array(
            "reclist_id" => $_POST["rec"],
            "rc_id" => $classifi,//pareho lang
            "date_created" => $datetime,
            "username" => $username
        );
        
        if ($obj->update_record("submission", $where, $subfields)) {
            
        }
    }
    //SAVING COMMUNITY BASED RESEARCH
    else if ($classifi == '2'){
        $where = array("sub_id" => $subid);
        $subfields = array(
            "reclist_id" => $_POST["rec2"],
            "rc_id" => $classifi,//pareho lang
            "date_created" => $datetime,
            "username" => $username
        );
        
        if ($obj->update_record("submission", $where, $subfields)) {
           
        }
    }
    
    
}

if (isset($_POST["updaterec"])) {
    $subid = $cleanid;
    
    $updaterec = array(
        "sub_id" => $subid
    );
    if($obj->delete_record("proposal_status", $updaterec)){
        header("location:basic_information.php?id=".$subid);
    }
    
}

?>