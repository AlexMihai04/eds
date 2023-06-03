<?php
    class sec{

        // GENERATE CRSF TOKEN
        public function generate_token(){
            $token = openssl_random_pseudo_bytes(16);
            $token = bin2hex($token);
            return $token;
        }

        // ENCRYPT USER PASSWORD
        public function encrypt_pass($pass){
            return password_hash($pass,PASSWORD_DEFAULT);
        }


        //Min length of text : int
        //Max length of text : int
        //Alphabetical : ok
        //Numeric : ok
        //Upper : ok
        //Lower : ok
        //Special characters : not ok
        public function integrity($input,$min,$max){
            if(isset($input) and isset($min) and isset($max)) return preg_match("^[a-zA-Z0-9]{".$min.",".$max."}$^",$input);
            return false;
        }

        // VERIFY IF THE EMAIL IS OK
        public function email_integrity($input){
            return preg_match("^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$",$input);
        }

        // VERIFY IF THE CODE IS OK
        public function code_integrity($input){
            return preg_match("^(\w[a-z]{5,6})@(\w[0-9]{3,4})@(\w[A-Z]{2,3})$^",$input);
        }
    }
?>