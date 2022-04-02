<?php
include "sample_dbtest.php";

class UploadOperation extends Database
{
    function upload_info($table, $fields, $id, $doctype, $useurl) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '".$doctype."' AND doctype = '1'";
        
        $qry = mysqli_query($this->con,$sql);
        $row = mysqli_num_rows($qry);
//        echo $row;
            if($row > 0){ 
                $sql = "UPDATE ".$table." SET ";
                $cnt = count($fields);
                for($i=2; $i<$cnt; $i++){
                    $val = array_values($fields);
                    $key = array_keys($fields);
                    $sql .= $key[$i]. " = '" .$val[$i]. "', ";
                }
                $sql = substr($sql, 0, -2);
                $sql .= " WHERE sub_id = ".$id." AND doctype = '".$doctype."'";
//                echo $sql ; exit; 
                $query = mysqli_query($this->con,$sql);
                if($query){    
                    
                    $key = $this->getmagicword();
                    $cleanid = $this->encrypt($id, $key);
                    header("location:".$useurl."?id=".$cleanid);
                    }
            }
            else{
                $sql = "";
                $sql .= "INSERT INTO ".$table;
                $sql .= " (".implode(",", array_keys($fields)).") VALUES ";
                $sql .= " ('".implode("','", array_values($fields))."')"; 
                $query = mysqli_query($this->con,$sql);
                    if($query){                
                    $key = $this->getmagicword();
                    $cleanid = $this->encrypt($id, $key);
                    header("location:".$useurl."?id=".$cleanid);
                        }
            }
        
    }


    function get_upload_info($table, $id, $kind){
        $sql = "SELECT * FROM ".$table." WHERE sub_id='".$id."' AND kind = '".$kind."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
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
    
    function get_upload_info_sup($table1, $table2, $id, $kind, $username){
//        $sql = "SELECT * FROM ".$table1." a INNER JOIN ".$table2." b ON a.doctype = b.docid WHERE sub_id='".$id."' AND kind='".$kind."'";
        $sql = "SELECT * FROM ".$table1." a INNER JOIN ".$table2." b ON a.doctype = b.docid LEFT JOIN document_control c ON a.doctype = c.doctype WHERE a.sub_id='".$id."' AND a.kind='".$kind."' AND a.username = '".$username."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function get_uploaded_supplementaryfiles($table1, $table2, $table3, $table4, $table5, $table6, $kind, $id, $username){
        
        $sql = "SELECT * FROM ".$table1." a INNER JOIN ".$table2." b ON "
                . "a.doctype = b.doctype INNER JOIN ".$table3." f ON f.docid = b.doctype "
                . "WHERE a.sub_id = '".$id."' AND "
                . "a.kind = '".$kind."' AND a.username = '".$username."' AND b.memberId IN "
                . "(SELECT e.username FROM ".$table4." c INNER JOIN ".$table5." d ON "
                . "c.inst_id = d.id INNER JOIN ".$table6." e ON "
                . "d.secretary = e.id WHERE c.sub_id = '".$id."')";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }    

    function showingUploadedFiles($table1, $table2, $id1, $id2, $id, $username){
        
        $sql = "SELECT * FROM ".$table1." a
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                WHERE a.sub_id = '".$id."' and a.username = '".$username."'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function checkingUploadFiles($table1, $table2, $id1, $id2, $id3, $id){
        
        $sql = "SELECT * FROM ".$table1." a
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                where a.sub_id = '".$id."' AND b.doctype = '".$id3."'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }

    function countSuppUploaded($table1, $table2, $table3, $id1, $id2, $id3, $id4, $id5, $id6, $id, $username){
        
        $sql = "SELECT * FROM ".$table1." a 
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                WHERE a.".$id4." = '".$id."' AND b.".$id3." NOT IN 
                (SELECT c.".$id3." FROM ".$table3." c WHERE c.".$id4." = '".$id."' and c.".$id5." = '".$username."' AND c.".$id6." = 'SF')";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
        function countSuppRequired($table1, $table2, $id1, $id2, $id){
        
        $sql = "SELECT * FROM ".$table1." a
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                where a.sub_id = '".$id."' AND b.doctype = '".$id3."'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    
    public function fetch_record($table){
		$sql = "SELECT * FROM ".$table;
		$array = array();
		$query = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$array[] = $row;
		}
		return $array;
	} 
        
    public function fetch_record_for_doctype($table1, $table2, $table3, $table4, $id, $id1, $id2, $id3, $id4, $id5, $id6, $doc){
//		$sql = "SELECT * FROM ".$table;
                $sql .= "SELECT * FROM ".$table1." a ";
                $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
                $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
                $sql .= "INNER JOIN ".$table4." d ON c.".$id5." = d.".$id6." ";
                $sql .= "WHERE a.sub_id = '".$id."' AND d.doctype = '".$doc."'";
		$array = array();
		$query = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$array[] = $row;
		}
		return $array;
	} 
        
    public function fetch_record_doctype($table1, $table2, $username){
		$sql = "SELECT * FROM ".$table1." a LEFT JOIN ".$table2." b ON a.docid = b.doctype WHERE b.memberId = '".$username."' OR b.memberId IS NULL order by a.docid";
		$array = array();
		$query = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$array[] = $row;
		}
		return $array;
	}
        
    public function fetch_count_req($table1, $table2, $table3, $table4, $id, $id1, $id2, $id3, $id4, $username){
                $sql = "SELECT * FROM ".$table1." a ";
                $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
                $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
                $sql .= "WHERE c.doctype NOT IN (SELECT v.doctype FROM ".$table4." v WHERE v.sub_id = '".$id."' and v.username = '".$username."')";
		$array = array();
		$query = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$array[] = $row;
		}
		return $array;
	}
    
            
    public function delete_record($table,$where){
            $sql = "";
            $condition = "";
            foreach ($where as $key => $value){
                $condition .= $key . "='". $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            $sql = "DELETE FROM ".$table." WHERE ".$condition;
//            echo $sql;
            if(mysqli_query($this->con,$sql)){
                return true;
            }
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
    

    public function human_filesize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
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
    
function getFileifUpload($subid, $finaldoc, $doctype) {
        $sql = "SELECT sub_id  FROM `document` WHERE `sub_id` = '$subid' AND `finaldoc` = '$finaldoc' AND `doctype` = '$doctype'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row["sub_id"];
        // echo $row;exit;
        return $fileid;
    } 
    
function getSupFileifUpload($subid, $finaldoc, $doctype) {
        $sql = "SELECT sub_id  FROM `document` WHERE `sub_id` = '$subid' AND `finaldoc` = '$finaldoc' AND `kind` = '$doctype'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row["sub_id"];
        return $fileid;
    } 

    public function insert_record($table, $fields) {
        $sql = "";
        $sql .= "INSERT INTO " . $table;
        $sql .= " (" . implode(",", array_keys($fields)) . ") VALUES ";
        $sql .= " ('" . implode("','", array_values($fields)) . "')";
        $query = mysqli_query($this->con, $sql);
        if ($query) {
            return true;
        }
    }
    
function getmaxsupfilestimes($subid, $doctype) {
        $sql = "SELECT MAX(doctypetimes) as doctimes, doctype FROM document WHERE sub_id = '$subid' AND doctype = '$doctype' GROUP BY doctype";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $doctimes = $row["doctimes"];
        return $doctimes;
    } 
    
}

$obj = new UploadOperation();

if(isset($_POST["submitmainprop"])){
    $key = $obj->getmagicword();
    $cleanid = $obj->decrypt($_POST["submid"], $key);        
}
   


if(isset($_FILES['image'])){
    
        $key = $obj->getmagicword();
        $cleanid = $obj->decrypt($_POST["submid"], $key); 
      $errors= array();      
      $id = $cleanid;
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      // $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose PDF format only.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST["urllink"];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
//            echo $useurl;
            $id = $cleanid;
            $doctype = $_POST["doctype"];
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-$resdoctype[1].".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
//            move_uploaded_file($file_tmp,"uploads/main/".$file_name);
                $doctypefinal = $resdoctype[0];
    //            print_r($doctypefinal);

                $uploadinfor = array(
                //"file_id" => "",
                "sub_id" => $id,
                "revision" => "1",
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => $_POST["kind"],
                "doctype" => $doctypefinal,
                "doctypetimes" => "1",
                "batchnum" => "1",
                "path"=> $path_dir,
                "username" => $_POST["userid"]
                );  
    //           print_r($uploadinfor);
                
                if($obj->upload_info("document", $uploadinfor, $id, $doctypefinal, $useurl)){
                }
                
            }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
      }else{
         print_r($errors);
      }
   }
   
if(isset($_FILES['imagesuppfiles'])){
    
        $key = $obj->getmagicword();
        $cleanid = $obj->decrypt($_POST["submid"], $key); 
      $errors= array();      
      $id = $cleanid;
      $file_name = $_FILES['imagesuppfiles']['name'];
      $file_size = $_FILES['imagesuppfiles']['size'];
      $file_tmp = $_FILES['imagesuppfiles']['tmp_name'];
      $file_type = $_FILES['imagesuppfiles']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['imagesuppfiles']['name'])));
      
      // $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose PDF format only.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST["urllink"];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
//            echo $useurl;
            $id = $cleanid;
            $doctype = $_POST["doctype"];
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-$resdoctype[1].".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            
            
            
            //
            $doctypefinal = $resdoctype[0];
            $where = array("sub_id" => $id,"doctype" => $doctypefinal);  
            $getdocfile = $obj->fetch_record_with_where("document", $where);
            if($obj->fetch_record_with_where("document", $where)){
                if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                    $maxsuppfile = $obj->getmaxsupfilestimes($id, $doctypefinal);

        //            print_r($doctypefinal);

                    $uploadinfor = array(
                    //"file_id" => "",
                    "sub_id" => $id,
                    "revision" => "1",
                    "finaldoc" => "1",
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" =>$datetime,
                    "kind" => $_POST["kind"],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => $maxsuppfile+1,
                    "batchnum" => "1",
                    "path"=> $path_dir,
                    "username" => $_POST["userid"]
                    );  
                    if($obj->insert_record("document", $uploadinfor)){
                        header("location:".$useurl."?id=".$_POST["submid"]);
                    }

//                    echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
                    
                }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
                
            }
            else{
                if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                    $maxsuppfile = $obj->getmaxsupfilestimes($id, $doctypefinal);

        //            print_r($doctypefinal);

                    $uploadinfor = array(
                    //"file_id" => "",
                    "sub_id" => $id,
                    "revision" => "1",
                    "finaldoc" => "1",
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" =>$datetime,
                    "kind" => $_POST["kind"],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => "1",
                    "batchnum" => "1",
                    "path"=> $path_dir,
                    "username" => $_POST["userid"]
                    ); 
                    if($obj->insert_record("document", $uploadinfor)){
                        header("location:".$useurl."?id=".$_POST["submid"]);
                    }

//                    echo "<pre>"; print_r($uploadinfor); echo "</pre>";exit;
                    
                }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
                
            }     
      }else{
         print_r($errors);
      }
   }   

   
if(isset($_POST["submitsupfiles"])){
    $id = $_POST["submid"];
    header("location:confirmation.php?id=".$id);   
    
}

if(isset($_POST["submitmainprop"])){
    $id = $_POST["submid"];
    header("location:uploadsuppfiles.php?id=".$id);   
    
}

if (isset($_GET["delete"])) {
    if (isset($_GET["id"])) {
        if (isset($_GET["subid"])){
            
            $fi = $_GET["id"];
            $key = $obj->getmagicword();
            $fi = $obj->decrypt($fi, $key);
            
            $subid = $_GET["subid"];
            $where = array("file_id"=>$fi);
//            print_r($where);
            if($obj->delete_record("document",$where)){
                header("location:uploadsuppfiles.php?id=".$subid);
            }
        }
        
    } 
}


?>

