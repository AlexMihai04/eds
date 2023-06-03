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

    if($sec->code_integrity($_POST["code"])){
        if($sec->integrity($_POST["username"],4,16)){
            if($sec->integrity($_POST["password"],8,20)){
                if($sec->integrity($_POST["name"],4,16)){
                    if($sec->integrity($_POST["prename"],4,16)){
                        if($sec->integrity($_POST["phone_number"],9,10)){
                            if(!($acc_man->has_acc($_POST["username"]))){
                                $code_data = $acc_man->code_ok($_POST["code"]);
                                if(!$code_data["used"]){
                                    //ACCOUNT CREATED ON DATE :  
                                    $created = date('Y-m-d', time());

                                    // WE CREATE THE ACCOUNT
                                    if($code_data["type"] == 0) $acc_man->add_acc($_POST["code"],$_POST["username"],$sec->encrypt_pass($_POST["password"]),$_POST["name"],$_POST["prename"],$created,"elev",$code_data["created_by"],$_POST["phone_number"]);
                                    else if($code_data["type"] == 1) $acc_man->add_acc($_POST["code"],$_POST["username"],$sec->encrypt_pass($_POST["password"]),$_POST["name"],$_POST["prename"],$created,"instructor",$code_data["created_by"],$_POST["phone_number"]);
                                    else if($code_data["type"] == 2) $acc_man->add_acc($_POST["code"],$_POST["username"],$sec->encrypt_pass($_POST["password"]),$_POST["name"],$_POST["prename"],$created,"detinator_firma",$code_data["created_by"],$_POST["phone_number"]);
                                    // else if($code_data["type"] == 2) $acc_man->add_acc($_POST["code"],$_POST["username"],$sec->encrypt_pass($_POST["password"]),$_POST["name"],$_POST["prename"],$created,"detinator_firma",$code_data["created_by"],$_POST["phone_number"]);

                                    // WE GET THE DATA OF THE USER THAT WE JUST REGISTERED
                                    $user_data = $acc_man->ver_acc($_POST["username"],$_POST["password"]);

                                    // WE SET THE CODE TO THE USED STATEMENT
                                    $acc_man->code_used($_POST["code"]);
                                    $acc_man->add_acc_ud($user_data["id"]);


                                    if($user_data["rank"] == 'detinator_firma') $acc_man->update_teacher($user_data["id"],$user_data["id"]);
                                    if($user_data) {

                                        $response["message"] = "Te-ai inregistrat cu succes ! Logheaza-te !";
                                        $response["res"] = 1;
                                    }
                                }else{
                                    $response["message"] = "Acest cod este deja utilizat.";
                                    $response["res"] = 9;
                                }
                            }else{
                                $response["message"] = "Ai deja un cont la noi.";
                                $response["res"] = 8;
                            } 
                        }else{
                            $response["message"] = "Numarul de telefon nu este corect !";
                            $response["res"] = 10;
                        }      
                    }else{
                        $response["message"] = "Prenumele tau nu respecta criteriile.";
                        $response["res"] = 7;
                    }
                }else{
                    $response["message"] = "Numele tau nu respecta criteriile.";
                    $response["res"] = 6;
                }
            }else{
                $response["message"] = "Parola ta contine caractere interzise.";
                $response["res"] = 4;
            }
        }else{
            $response["message"] = "Username-ul tau contine caractere interzise.";
            $response["res"] = 3;
        }
    }else{
        $response["message"] = "Cod-ul tau nu exista in baza de date";
        $response["res"] = 5;
    }



    // WE SEND BACK THE RESPONSE
    echo json_encode($response);



    $db->close();
?>