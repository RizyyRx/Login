<?php

class UserSession{

//Sets token and fingerprint in $_Session array if all conditions are satisfied
    public static function authenticate($user,$pass){
        //Rename login function
        $username=User::login($user,$pass);//retrieves username
        $user=new User($username);//creates new user object(id will be acquired from __construct function)
        if($username){
            $conn=Database::getConnection();
            $ip=$_SERVER['REMOTE_ADDR'];
            $agent=$_SERVER['HTTP_USER_AGENT'];
            $token=md5(rand(0,999999).$ip.$agent.time());
            $sql="INSERT INTO `user_session` (`uid`, `token`, `login_time`, `ip`, `user_agent`)
            VALUES (?, ?, now(), ?, ?)";
            try {
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $conn->error);
                }
                $stmt->bind_param("isss", $user->id, $token, $ip, $agent);
                if ($stmt->execute()) {
                    Session::set('session_token', $token);
                    $stmt->close();
                    $conn->close();
                    return $token;
                } else {
                    $stmt->close();
                    $conn->close();
                    return false;
                }
            }
            catch (Exception $e) {
                if ($stmt && $stmt instanceof mysqli_stmt) {
                    $stmt->close();
                }
                if ($conn && $conn instanceof mysqli) {
                    $conn->close();
                }
                error_log("Exception: " . $e->getMessage());
                return false;
            }
        }
        else{
            return false;
        }
    }

    /*
    * Authorize function have has 4 level of checks 
        1.Check that the IP and User agent field is filled.
        2.Check if the session is correct and active.
        3.Check that the current IP is the same as the previous IP
        4.Check that the current user agent is the same as the previous user agent

        @return true else false;
    */
    public static function authorize($token)
    {
        try {
            $session = new UserSession($token);//since new UserSession object is created, the construct func returns uid of an user in DB
            if (isset($_SERVER['REMOTE_ADDR']) and isset($_SERVER["HTTP_USER_AGENT"])) {
                if ($session->isValid()) {
                    if ($_SERVER['REMOTE_ADDR'] == $session->getIP()) {
                        if ($_SERVER['HTTP_USER_AGENT'] == $session->getUserAgent()) {
                            return $session;
                        } else throw new Exception("User agent does't match");
                    } else throw new Exception("IP does't match");
                } else {
                    $session->removeSession();
                    throw new Exception("Invalid session");
                }
            } else throw new Exception("IP and User_agent is null");
        } catch (Exception $e) {
            throw new Exception("Something is wrong");
        }
    }

//saves the data of a row and the uid of an user in DB where token matches the token in DB
    public function __construct($token){
        $this->conn=Database::getConnection();
        $this->token=$token;
        $this->data=null;
        $sql="SELECT * FROM `user_session` WHERE `token` = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $this->data = $row;
                $this->uid = $row['uid'];
            } else {
                throw new Exception("Session is invalid");
            }
            $stmt->close();
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
            error_log("Exception: " . $e->getMessage());
        }
    }
    //retrieves a User object in User.class.php representing the user associated with the session.
    public function getUser(){
        return new User($this->uid);
    }

    public function isValid(){
        try{
            if(isset($this->data['login_time'])){
                $logintime=DateTime::createFromFormat('Y-m-d H:i:s',$this->data['login_time']);
                if(time()-$logintime->getTimestamp()<3600){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                throw new Exception("login time is null");
            }
        }
        catch (Exception $e) {
            // Log the error for debugging purposes
            error_log("Exception: " . $e->getMessage());
            // Re-throw the exception to propagate it up the call stack
            throw $e;
        }    
    }

    public function getIP(){
        if(isset($this->data['ip'])){
            return $this->data['ip'];
        }
        else{
            return false;
        }
    }

    public function getUserAgent(){
        if(isset($this->data['user_agent'])){
            return $this->data['user_agent'];
        }
        else{
            return false;
        }
    }

    public function deactivate(){
        if(!$this->conn){
            $this->conn=Database::getConnection();
        }
        $sql = "UPDATE `user_session` SET `active` = 0 WHERE `uid` = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
            $stmt->bind_param("i", $this->uid);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            return false;
        }
        
    }

    public function isActive()
    {
        if (isset($this->data['active'])) {
            return $this->data['active'] ? true : false;
        }
    }

    public function getFingerprint(){
        if(isset($this->data['fingerprint'])){
            if($this->data['fingerprint']){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function removeSession(){
        if(isset($this->data['id'])){
            $id=$this->data['id'];
            if(!$this->conn){
                $this->conn=Database::getConnection();
            }
            $sql="DELETE FROM `user_session` WHERE `id`=$id";
            $sql = "DELETE FROM `user_session` WHERE `id` = ?";
            try {
                $stmt = $this->conn->prepare($sql);
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $this->conn->error);
                }
                $stmt->bind_param("i", $this->data['id']);
                $result = $stmt->execute();
                $stmt->close();
                return $result;
            } catch (Exception $e) {
                error_log("Exception: " . $e->getMessage());
                return false;
            }
        }
    }
}