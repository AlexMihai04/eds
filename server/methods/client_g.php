<?php
    session_start();

    // ALL THE CUSTOM EXTENSIONS WE NEED
    include "../lib/database.php";
    include "../lib/security.php";
    include "../lib/account_management.php";
    include "../lib/user.php";
    include "../lib/ranks.php";


    //CONFIG FILE
    $cfg = include "../../cfg/config.php";
    

    // DATABASE MODULE
    $db = new db();
    $db->parse_config($cfg);
    $db->connect();

    // RANKS MODULE
    $ranks = new ranks();
    $ranks->parse_config($cfg);

    // SECURITY MODULE
    $sec = new sec();

    // ACCOUNT MANAGEMENT MODULE
    $acc_man = new acc_man($db->get_instance());



    if($_POST["action"] == 'get_udata'){
        // $_SESSION["times"] = $_SESSION["times"] + 1;
        $user_data = $acc_man->get_udata($_SESSION["user"]["user_data"]["id"]); //users table
        $u_data= $acc_man->get_data($_SESSION["user"]["user_data"]["id"]); //user_data table
        $students = array();
        $teacher_info = array();
        if($user_data["rank"] == "instructor"){ //WE GET ALL HIS STUDENTS INFORMATION
            // $students = array();
            $s_user_data = $acc_man->get_all_students($user_data["id"]);
            foreach ($s_user_data as $key => $value) {
                $us_data = $acc_man->get_data($value["id"]);
                unset($value["password"]);
                unset($value["3"]);
                array_push($students,array("user_data" => $value,"data" => $us_data));
            }
            // echo json_encode($students);
        }else if($user_data["rank"] == 'elev'){
            $t_user_data = $acc_man->get_udata($_SESSION["user"]["user_data"]["teacher_id"]); //users table
            unset($t_user_data["password"]);
            $t_data = $acc_man->get_data($_SESSION["user"]["user_data"]["teacher_id"]); //user_data table
            $teacher_info = array("user_data" => $t_user_data,"data" => json_decode($t_data["data"],true));
        }else if($user_data["rank"] == "detinator_firma"){
            $s_user_data = $acc_man->get_all_students($user_data["id"]);
            foreach ($s_user_data as $key => $value) {
                $k = $acc_man->get_all_students($value["id"]);
                $w = array();
                if(gettype($k) == "array"){
                    foreach ($k as $k2 => $v2) {
                        $u2 = $acc_man->get_data($v2["id"]);
                        unset($v2["password"]);
                        unset($v2["3"]);
                        array_push($w,array("user_data" => $v2,"data"=>json_decode($u2["data"],true)));
                    }
                }
                $us_data = $acc_man->get_data($value["id"]);
                unset($value["password"]);
                unset($value["3"]);
                array_push($students,array("user_data" => $value,"data" => json_decode($us_data["data"],true),"ins_students"=>$w));
            }
        }

        unset($user_data["password"]);
        $user = array('user_data' => $user_data,'data' => json_decode($u_data["data"],true),'students'=>$students,'teacher'=>$teacher_info);
        $_SESSION["user"] = $user;
        echo json_encode($_SESSION["user"]);
    }else if($_POST["action"] == "get_chestionare"){
        $questions = file_get_contents('../../cfg/questions.json');
        echo $questions;
    }else if($_POST["action"] == "get_codes"){
        $ids = $_SESSION["user"]["user_data"]["id"];
        if(isset($_POST["id"])) $ids = $_POST["id"];
        $codes = $acc_man->get_all_codes($ids);
        echo json_encode($codes);
    }


    $db->close();

?>