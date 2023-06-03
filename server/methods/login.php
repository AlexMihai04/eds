<?php
    // ALL THE CUSTOM EXTENSIONS WE NEED
    include "../lib/database.php";
    include "../lib/security.php";
    include "../lib/account_management.php";
    include "../lib/user.php";

    //CONFIG FILE
    $cfg = include "../../cfg/config.php";

    
    session_start();



    // WE PREPARE THE ERROR RESPONSE IF NEEDED
    $response = array('res' => 0,'message' => 'A aparut o eroare. Cod : #0');

    // WE VERIFY IF THE CRSF IS THE SAME WITH THE ONE RECEIVED
    if($_POST["crsf"] != $_SESSION["crsf"]) echo json_encode($response);



    // DATABASE EXTENSION INSTANCE
    $db = new db();
    $db->parse_config($cfg);
    $db->connect();



    // SECURITY INSTANCE
    $sec = new sec();

    
    $acc_man = new acc_man($db->get_instance());

    if($sec->integrity($_POST["username"],4,16)){
        if($sec->integrity($_POST["password"],8,20)){
            $data = $acc_man->ver_acc($_POST["username"],$_POST["password"]);
            if($data){
                
                // WE STORE THE USER DATA
                $user_data = $acc_man->get_udata($data["id"]); //users table
                $u_data= $acc_man->get_data($data["id"]); //user_data table
                $user = array('user_data' => $data,'data' => json_decode($u_data["data"],true));
                if(!isset($user["data"]["banned"]) || ($user["data"]["banned"] == false || $user["data"]["banned"] == "false")){
                    $_SESSION["user"] = $user;

                    $_SESSION["is_auth"] = true;

                    $response["message"] = "Te-ai logat cu succes!";
                    $response["res"] = 1;
                }else{
                    $response["message"] = "Acest cont este dezactivat .";
                    $response["res"] = 2;
                }
            }else{
                $response["message"] = "Acest cont nu exista.";
                $response["res"] = 2;
            }
        }else{
            $response["message"] = "Parola ta contine caractere interzise.";
            $response["res"] = 4;
        }
    }else{
        $response["message"] = "Username-ul tau contine caractere interzise.";
        $response["res"] = 3;
    }
    
    // WE SEND BACK THE RESPONSE
    echo json_encode($response);



    $db->close();
?>