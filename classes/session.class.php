<?php

class Session {
    /** Session follows singleton design pattern */
    /** One connection is maintained throughout and referenced as needed */
    /** To use within a class: */
    /** $this->session = Session::getInstance()->getSession(); */

    protected static $_instance;
    
    protected function __construct(){
        $this->connect = Database::getInstance()->getConnection();
        
        //Session confirmation variables
        $this->sessionId = session_id();
        $this->establishedTime = $_SESSION['establishedTime'] ?? NULL;
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];
        
        //Generate user to access permissions and additional details
        $this->userId = $_SESSION['userId'] ?? NULL;
        $this->loggedInUser = new User($this->userId) ?? "Error - Not Found";
        
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
        //Store userId in the server session, used during pageload validation
        $_SESSION['userId'] = $this->userId;

        //Upon login, add session to database
        $sql = "INSERT INTO session 
        (sessionid,userid,establishedtime,ipaddress) VALUES 
        (?, ?, ?, ?)";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->sessionId, $this->userId, $this->establishedTime, $this->ipAddress]);
    }

}

?>