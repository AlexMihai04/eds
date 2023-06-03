<?php
    session_start();

    // ALL THE CUSTOM EXTENSIONS WE NEED
    include "../lib/database.php";
    include "../lib/security.php";
    include "../lib/account_management.php";
    include "../lib/user.php";
    include "../lib/ranks.php";

    if(!$_POST["crsf"] || $_SESSION["crsf"] != $_POST["crsf"]){
        session_destroy();
        header("location:login.php");
    }

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
    
    if($_POST["action"] == 'set_data'){
        // echo json_encode($_SESSION["user"]["data"]);
        $ok = true;
        if($_POST["field"] == "banned" && !$ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"ban")) $ok = false;
        if($ok){
        //     echo "test";
            $id = $_SESSION["user"]["user_data"]["id"];
            $data = $_SESSION["user"]["data"];
            if($_POST["id"] > 0){
                $id = $_POST["id"];
                $data = $acc_man->get_data($id);
                $data = json_decode($data["data"],1);
            }
            if(gettype($data)=="array" || gettype($data)=="object") $data[$_POST["field"]] = $_POST["data"];
            else{
                $data = array();
                $data[$_POST["field"]] = $_POST["data"];
            }
            $acc_man->update_ud($id,$data);
        }
    }else if($_POST["action"] == "create_code"){
        if($ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"create_code")){
            $first = generateRandomString(6);
            
            $first = strtolower($first);

            $second = strval(rand(1000,9999));

            $third = generateRandomString(3);
            $third = strtoupper($third);

            $code = $first."@".$second."@".$third;

            $t = 0;
            if($_SESSION["user"]["user_data"]["rank"] == "detinator_firma") $t = 1;
            $acc_man->create_code($_SESSION["user"]["user_data"]["id"],$code,date("d/m/Y"),$t);

            echo $code;
        }
    }else if($_POST["action"] == "modify_code"){
        if($sec->code_integrity($_POST["code"]) && $sec->integrity($_POST["cat"],1,2)){
            $acc_man->mod_code($_POST["code"],$_POST["cat"]);
        }
    }else if($_POST["action"] == "update_question" && $ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"update_question")){
        $questions = file_get_contents('../../cfg/questions.json');
        $data = json_decode($questions, true);
        $data[$_POST["q_id"]] = $_POST["data"];
        $questions = json_encode($data);
        file_put_contents('../../cfg/questions.json',$questions);
        echo true;
    }else if($_POST["action"] == "delete_question" && $ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"delete_question")){
        $questions = file_get_contents('../../cfg/questions.json');
        $data = json_decode($questions, true);
        unset($data[$_POST["q_id"]]);
        $questions = json_encode($data);
        file_put_contents('../../cfg/questions.json',$questions);
        echo true;
    }else if($_POST["action"] == "add_question" && $ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"add_question")){
        $questions = file_get_contents('../../cfg/questions.json');
        $data = json_decode($questions, true);
        
        $data[array_key_last($data)+1] = $_POST["data"];
        $questions = json_encode($data);
        file_put_contents('../../cfg/questions.json',$questions);
        echo true;
    }else if($_POST["action"] == 'del_code'){
        if($ranks->has_permission($_SESSION["user"]["user_data"]["rank"],"del_code") && $sec->code_integrity($_POST["code"])){
            $acc_man->del_code($_POST["code"]);
        }
    }else{
        echo "insufficient permission";
    }

    function generateRandomString($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // echo "test";

    $db->close();

?>