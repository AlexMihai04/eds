<?php
    return array(
        // BAZA DE DATE
        "servername" => "localhost",
        "username" => "root",
        "password" => "",
        "database_name" => "eds_school",
        "ranks" => array(
            "elev" => array(
                "details" => array(
                    "rank_name" => "Elev"
                ),
                "permissions" => array(
                    "quizz" => true //HAVE ACCESS TO MAKE QUIZZ
                )
            ),
            "instructor" => array(
                "details" => array(
                    "rank_name" => "Instructor"
                ),
                "permissions" => array(
                    "add_question" => true, //ADD A QUIZZ QUESTION
                    "update_question" => true, //UPDATE A QUIZZ QUESTION
                    "delete_question" => true, //DELETE A QUIZZ QUESTION FROM THE MENU
                    "qmanager" => true, //MENU FOR CREATING/DELETING/MODIFYING THE QUESTIONS
                    "inspect_self_codes"=>true, //SEE ALL THE CODES CREATED BY HIMSELF
                    "create_code" => true, //CREATE A CODE IN ORDER TO A USER TO REGISTER
                    "set_prf_img" => true, //ALLOW THE USER TO SET THE PROFILE PICTURE
                    "see_st_data" => true, //ALLOW AN INSTRUCTOR TO SEE DATA ABOUT THE STUDENT
                    "ban" => true, //ALLOWS TO BAN USERS,
                    "del_code" => true, //ALLOW USER TO DELETE CODES
                    "create_code" => true //ALLOW USER TO CREATE CODES
                )
            ),
            "detinator_firma" => array(
                "details" => array(
                    "rank_name" => "Administrator scoala"
                ),
                "permissions" => array(
                    "del_code" => true, //ALLOW USER TO DELETE CODES
                    "create_code" => true, //ALLOW USER TO CREATE CODES
                    "ban" => true, //ALLOWS TO BAN USERS,
                )
            ),
            "admin" => array(
                "details" => array(
                    "rank_name" => "Administrator website"
                ),
                "permissions" => "*"
            )
        )
    );
?>