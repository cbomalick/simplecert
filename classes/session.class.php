<?php

class Session {
    /** Session follows singleton design pattern */
    /** One connection is maintained throughout and referenced as needed */
    /** To use within a class: */
    /** $this->session = Session::getInstance()->getSession(); */

    protected static $_instance;
    
    protected function __construct(){
        $this->connect = Database::getInstance()->getConnection();
        
        $this->sessionId = session_id();
        $this->userId = $_SESSION['userId'] ?? NULL;
        $this->establishedTime = $_SESSION['establishedTime'] ?? NULL;
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];
        $this->loggedInUser = new User($this->userId) ?? "Error - Not Found";
        $this->loggedInUser->personId = $this->userId;
        
        return $this;
    }

    public function getSession(){
        return $this;
    }

    public static function getInstance(){
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __clone(){
        //** Disabled */
    }

    public function validateSession(){
        //Check to make sure user session is valid each time page loads
        $sql = "SELECT COUNT(seqno) FROM session WHERE sessionid = ? AND userid = ? AND ipaddress = ? AND establishedtime > NOW() - INTERVAL 2 HOUR";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->sessionId, $this->userId, $this->ipAddress]);
        $row = $stmt->fetch();

        if($row['COUNT(seqno)'] > 0){
            return TRUE;
        } else
            return FALSE;
    }

    public function createSession(){
        //Upon login, add session to database
        $sql = "INSERT INTO session 
        (sessionid,userid,establishedtime,ipaddress) VALUES 
        (?, ?, ?, ?)";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->sessionId, $this->userId, $this->establishedTime, $this->ipAddress]);
    }

}

?>