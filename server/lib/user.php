<?php
    // ALL THE USER INFO FOR THE SESSION
    class user{
        // ALL USER DATA EXCLUDING THE PASSWORD IS SAVED HERE ( NO SENSITIVE INFO )
        private $data;

        // ON THE CONSTRUCT WE STORE THE DATA
        public function __construct($data){
            $this->data = $data;
        }

        // GET THE USER DATA
        function get_data(){
            return $this->data;
        }
    }
?>