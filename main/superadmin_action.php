<?php

include "sample_dbtest.php";

class DataOperation extends Database {
        
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
    public function truncate($table){
        $count = count($table);
        $sql = "";
        for ($i=0; $i<$count; $i++){
            $sql = "TRUNCATE TABLE ". $table[$i]. ";";
            $query = mysqli_query($this->con, $sql);
        }
        return true;
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
    public function academic(){
		$sql = "SELECT DISTINCT(a.desc_acad), b.acad_id FROM academicdeg_list a 
                        LEFT JOIN studentresdet b ON a.id = b.acad_id order by a.id";
		$array = array();
		$query = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$array[] = $row;
		}
		return $array;
	}
        
    public function getallreviewanswer(){
		$sql = "SELECT * FROM `rev_answers` 
                        GROUP BY sub_id, revid";
		$array = array();
		$query = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($query)){
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
        
        
}

$obj = new DataOperation;

if (isset($_POST["truncate"])) {
    
    $table = array(
        "assessment_ans",
        "combased",
        "coninterest",
        "country_list",
        "country_multi",
        "document",
        "datacol",
        "ethical_clearance",
        "hmnsubj",
        "humansubject",
        "keywords",
        "keywords_list",
        "monetary_source",
        "nationregion",
        "nationwideres",
        "note",
        "potenbenefits",
        "proposal",
        "proposal_status",
        "proposal_post_approval",
        "researchfields",
        "researcher_additional",
        "reviewcom",
        "reviewcomdata",
        "review_suggest",
        "review_type",
        "review_type_duedate",
        "rev_answers",
        "rev_groups",
        "rev_comment",
        "riskapply",
        "risklevel",
        "sponsor",
        "studentres",
        "studentresdet",
        "submission",
        "indigenous");

    
    if($obj->truncate($table)){
        header("location:truncate.php?success=yes");
    }
}

if (isset($_GET["deletemember"])) {
    if (isset($_GET["p"])) {
        $id = $_GET["p"];
        $where = array("memberID" => $id);
        if ($obj->delete_record("membership_users", $where)) {
            header("location:settings.php");
        }
    }
}
if (isset($_GET["deletepuser"])) {
    if (isset($_GET["u"])) {
        $id = $_GET["u"];
        $where = array("id" => $id);
        if ($obj->delete_record("phrepuser", $where)) {
            header("location:settings.php");
        }
    }
}

if (isset($_GET["delrevgroup"])) {
    if (isset($_GET["p"])) {
        $id = $_GET["p"];
        $where = array("id" => $id);
        if ($obj->delete_record("rev_groups", $where)) {
            header("location:settings.php");
        }
    }
}

if (isset($_GET["delpropstatus"])) {
    if (isset($_GET["p"])) {
        $id = $_GET["p"];
        $where = array("id" => $id);
        if ($obj->delete_record("proposal_status", $where)) {
            header("location:settings.php");
        }
    }
} 

if (isset($_GET["delrtduedate"])) {
    if (isset($_GET["p"])) {
        $id = $_GET["p"];
        $where = array("id" => $id);
        if ($obj->delete_record("review_type_duedate", $where)) {
            header("location:settings.php");
        }
    }
}


if (isset($_GET["delnote"])) {
    if (isset($_GET["p"])) {
        $id = $_GET["p"];
        $where = array("id" => $id);
        if ($obj->delete_record("note", $where)) {
            header("location:settings.php");
        }
    }
}

if (isset($_GET["delrecgroup"])) {
    if (isset($_GET["p"])) {
        $id = $_GET["p"];
        $where = array("id" => $id);
        if ($obj->delete_record("rec_groups", $where)) {
            header("location:settings.php");
        }
    }
}


if (isset($_POST["updatetemplate"])) {
//   echo $_POST['tempid']; exit;     
   
   $where = array("id" => $_POST['tempid']);
   $fields = array("body" => $_POST['template']);
   
//   print_r($fields);
   
   if($obj->update_record("email_templates", $where, $fields)){
       header("location:settings.php");
   }
   
   
}


if (isset($_POST["evalsubmit"])) {
//   echo $_POST['tempid']; exit;     
   
   $where = array("id" => $_POST['evalid']);
   $fields = array("evaluation_submitted" => $_POST['evalsub'], "primary_reviewer" => $_POST['primaryrev']);
   
//   print_r($fields);
   
   if($obj->update_record("rev_groups", $where, $fields)){
       header("location:settings.php");
   }
   
   
}

if (isset($_POST["codeedit"])) {
//   var_dump($_POST); exit;     
   
   $where = array("sub_id" => $_POST['subid']);
   $fields = array("code" => $_POST['code']);
   
//   print_r($fields);
   
   if($obj->update_record("proposal", $where, $fields)){
       header("location:settings.php");
   }  
   
}

if (isset($_POST["submissionedit"])) {
//   echo $_POST['tempid']; exit;     
   
   $where = array("sub_id" => $_POST['subid']);
   $fields = array("reclist_id" => $_POST['reclistid']);
   
//   print_r($fields);
   
   if($obj->update_record("submission", $where, $fields)){
       header("location:settings.php");
   }  
   
}


if (isset($_GET["delrevans"])) {
    if (isset($_GET["i"])) {
        $subid = $_GET["i"];
        $phrepuser = $_GET["r"];
        $where = array("sub_id" => $subid, "revid" => $phrepuser);
        if ($obj->delete_record("rev_answers", $where)) {
            header("location:settings.php");
        }
    }
}


if (isset($_POST["orderedit"])) {
//   echo $_POST['tempid']; exit;     
   
   $where = array("sub_id" => $_POST['subid']);
   $fields = array("ordering" => $_POST['ordering'], "coding" => $_POST['coding'], "year" => $_POST['year']);
   
//   print_r($fields);
   
   if($obj->update_record("submission", $where, $fields)){
       header("location:settings.php");
   }  
   
}

if (isset($_POST["adddoctype"])) {
//   var_dump($_POST); exit;     
   
    $doctype = array(
        "doctype_desc" => $_POST["doctypedesc"],
        "forfilename" => $_POST["forfilename"]
    );
   
//   print_r($fields);
   
   if($obj->insert_record("document_type", $doctype)){
       header("location:settings.php");
   }  
   
}

if (isset($_POST["updateddate"])) {
//   var_dump($_POST); exit;     
   
    $ddate = array(
        "rt_duedate" => $_POST["ddate"]
    );
    $where = array(
        "id" => $_POST["ddateid"]
    );
//   print_r($fields);
   
   if($obj->update_record("review_type_duedate", $where, $ddate)){
       header("location:settings.php");
   }  
   
}

?>