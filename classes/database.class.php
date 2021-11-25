<?php

class Database {
    /** Connection follows singleton design pattern */
    /** One connection is maintained throughout and referenced as needed */
    /** To use within a class: */
    /** $this->connect = Database::getInstance()->getConnection(); */

    protected static $_instance;
    protected $pdo;
    protected $host;
    protected $dbname;
    protected $user;
    protected $pass;
    
    protected function __construct(){
        $config = parse_ini_file('config.ini');

        $host = $config['host'];
        $dbname = $config['db'];
        $user = $config['username'];
        $pass = $config['password'];

        $dsn = "mysql:host=". $host. ";dbname=" . $dbname;
        //PDO::ATTR_PERSISTENT => true seems to cause more connected threads than without

        try {
            $this->pdo = new PDO($dsn, $user, $pass);
            //$this->showThreads();
            return $this->pdo;
        } catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public function getConnection(){
        return $this->pdo;
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

    public function showThreads(){
        //Prints number of connected threads. Useful for resource monitoring
        $sql = "show status where variable_name = 'Threads_connected'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        Echo"<pre>";
        var_dump($row);
        Echo"</pre>";
    }
}

?>