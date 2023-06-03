<?php
    $questions = file_get_contents('../../cfg/new_contact.json');
    $data = json_decode($questions, true);
    $data[array_key_last($data)+1] = $_POST["data"];
    $questions = json_encode($data);
    file_put_contents('../../cfg/new_contact.json',$questions);
    echo true;
?>