<?php
    class acc_man{

        // DATABASE CONNECTION IS STORED HERE
        public $conn;

        // ON THE CONSTRUCT WE STORE THE DATABASE INSTANCE
        public function __construct($conn){
            $this->conn = $conn;
        }

        // ADD NEW USER IN THE DATABASE ( ALL THE INFO IS ALREADY VALIDATED WHERE THE FUNCTION IS CALLED )
        function add_acc($code,$username,$password,$name,$prename,$date_created,$rank,$teacher_id,$phone){
            $stmt = $this->conn->prepare('INSERT INTO users (code,username,password,name,prename,date_created,rank,teacher_id,phone) VALUES (:code,:username,:password,:name,:prename,:date_created,:rank,:teacher_id,:phone)');
            $stmt->execute(['code'=>$code,'username'=>$username, 'password' => $password,'name' => $name,'prename' => $prename,'date_created' => $date_created,'rank'=>$rank,'teacher_id'=>$teacher_id,'phone'=>$phone]);
            $stmt = null;
        }

        // DELETE CODE
        function del_code($code){
            $stmt = $this->conn->prepare('DELETE FROM codes WHERE code = :code');
            $stmt->execute(['code' => $code]);
            $stmt = null;
        }

        function update_teacher($id,$tch){
            $stmt = $this->conn->prepare('UPDATE users SET teacher_id = :teacher_id WHERE id = :id');
            $stmt->execute(['teacher_id'=>$tch,'id'=>$id]);
            $stmt = null;
        }

        // CREATE USER_DATA LINE
        function add_acc_ud($id){
            $stmt = $this->conn->prepare('INSERT INTO user_data (id,data) VALUES (:id,:data)');
            $stmt->execute(['id'=>$id,'data'=>json_encode(array())]);
            $stmt = null;
        }

        // VERIFY THE LOGIN CREDITALS AND RETURN THE ACCOUNT DATA
        function ver_acc($username,$password){
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username'=>$username]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($info){
                if(password_verify($password,$info['password'])){
                    unset($info["password"]);
                    return $info;
                }else{
                    return false;
                }
            }
            $stmt = null;
        }

        function get_all_students($teacher_id){
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE teacher_id = :teacher_id');
            $stmt->execute(['teacher_id'=>$teacher_id]);
            $info = $stmt->fetchAll();
            
            if($info) return $info;
            $stmt = null;
        }

        // VERIFY IF AN ACCOUNT EXIST BY USERNAME
        function has_acc($username){
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username'=>$username]);
            $exists = $stmt->fetchColumn();
            if($exists){
                return true;
            }else{
                return false;
            }
            $stmt = null;
        }

        // UPDATE THE CODE TO THE USED STATEMENT
        function get_udata($id){
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id'=>$id]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($info) return $info;
            $stmt = null;
        }

        function get_data($id){
            $stmt = $this->conn->prepare('SELECT * FROM user_data WHERE id = :id');
            $stmt->execute(['id'=>$id]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($info) return $info;
            $stmt = null;
        }


        // VERIFY IF A CODE IS ALREADY USED OR EXIST (THE INTEGRITY ALREADY VERIFIED HERE)
        function code_ok($code){
            $stmt = $this->conn->prepare('SELECT * FROM codes WHERE code = :code');
            $stmt->execute(['code'=>$code]);
            $exists = $stmt->fetch(PDO::FETCH_ASSOC);
            if($exists){
                return $exists;
                // if(!$exists["used"]) return true;
                // return false;
            }else{
                return false;
            }
            $stmt = null;
        }

        // WE GET ALL THE CODES ON THE USER
        function get_all_codes($teacher_id){
            $stmt = $this->conn->prepare('SELECT * FROM codes WHERE created_by = :teacher_id');
            $stmt->execute(['teacher_id'=>$teacher_id]);
            $info = $stmt->fetchAll();
            
            if($info) return $info;
            $stmt = null;
        }

        // UPDATE THE CODE TO THE USED STATEMENT
        function code_used($code){
            $stmt = $this->conn->prepare('UPDATE codes SET used = 1 WHERE code = :code');
            $stmt->execute(['code'=>$code]);
            $stmt = null;
        }

        // UPDATE THE CODE TO THE NEW CATEGORY
        function mod_code($code,$category){
            $stmt = $this->conn->prepare('UPDATE codes SET category = :category WHERE code = :code');
            $stmt->execute(['category'=>$category,'code'=>$code]);
            $stmt = null;
        }

        // CREATE CODE
        function create_code($id,$code,$date,$type){
            $stmt = $this->conn->prepare('INSERT INTO codes (code,date,created_by,type) VALUES (:code,NOW(),:created_by,:type)');
            $stmt->execute(['code'=>$code,'created_by'=>$id,'type'=>$type]);
            $stmt = null;
        }

        // UPDATE THE CODE TO THE USED STATEMENT
        function get_codes($id){
            $stmt = $this->conn->prepare('SELECT * FROM codes WHERE created_by = :id');
            $stmt->execute(['id'=>$id]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($info) return $info;
            $stmt = null;
        }


        // GET HOURS DONE FROM DATABASE
        function get_hours($id){
            $stmt = $this->conn->prepare('SELECT * FROM hours_done WHERE id = :id');
            $stmt->execute(['id'=>$id]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($info) return $info;
            $stmt = null;
        }

        // GET HOURS DONE FROM DATABASE
        function get_hoursa($id){
            $stmt = $this->conn->prepare('SELECT * FROM hours_available WHERE id = :id');
            $stmt->execute(['id'=>$id]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($info) return $info;
            $stmt = null;
        }

        // ADD HOUR DONE
        function add_hourd($id,$date){
            $stmt = $this->conn->prepare('INSERT INTO hours_done (id,date) VALUES (:id,:date)');
            $stmt->execute(['id'=>$id,'date'=>$date]);
            $stmt = null;
        }

        // ADD HOUR AVAILABLE
        function add_houra($id,$date,$time){
            $stmt = $this->conn->prepare('INSERT INTO hours_available (id,date,time) VALUES (:id,:date,:time)');
            $stmt->execute(['id'=>$id,'date'=>$date,'time'=>$time]);
            $stmt = null;
        }

        // MODIFY RANK
        function update_rank($id,$rank){
            $stmt = $conn->prepare('UPDATE users SET rank = :rank WHERE id = :id');
            $stmt->execute(['id'=>$id,'rank' => $rank]);
            $stmt = null;
        }

        // DELETE ACCOUNT
        function delete_acc($id){
            $stmt = $conn->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $stmt = null;
        }

        // ADD QUESTION
        function question_add($question,$a,$b,$c,$ans){
            $stmt = $this->conn->prepare('INSERT INTO questions (question,a,b,c,ans) VALUES (:question,:a,:b,:c,:ans)');
            $stmt->execute(['question'=>$question,'a'=>$a,'b'=>$b,'c'=>$c,'ans'=>json_encode($ans)]);
            $stmt = null;
        }

        // REMOVE QUESTION
        function question_delete($id){
            $stmt = $conn->prepare('DELETE FROM questions WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $stmt = null;
        }


        // GET HOURS DONE FROM DATABASE
        function get_learners($id){
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE teacher_id = :id');
            $stmt->execute(['id'=>$id]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($info) return $info;
            $stmt = null;
        }

        // UPDATE USER DATA
        function update_ud($id,$data){
            $stmt = $this->conn->prepare('UPDATE user_data SET data = :data WHERE id = :id');
            $stmt->execute(['id'=>$id,'data' => json_encode($data)]);
            $stmt = null;
        }
    }
?>