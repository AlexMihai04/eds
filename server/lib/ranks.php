<?php
    class ranks{
        public $ranks;


        // GET THE PERMISSIONS FROM THE CFG
        public function parse_config($cfg){
            $this->ranks = $cfg["ranks"];
        }

        // VERIFY IF USER HAS PERMISSION
        public function has_permission($rank,$permission){
            if((isset($this->ranks[$rank]["permissions"][$permission]) and $this->ranks[$rank]["permissions"][$permission]) or $this->ranks[$rank]["permissions"] == "*") return true;
            return false;
        }

        // GET THE RANK NAME BASED ON THE RANK CODE
        public function rank_name($rank){
            if(isset($this->ranks[$rank])) return $this->ranks[$rank]["details"]["rank_name"];
            return false;
        }


    } 

?>