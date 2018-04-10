<?php
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "id5307714_presecure";
    private $username = "root";
    private $password = "";

    /*private $host = "sql12.freesqldatabase.com";
    private $db_name = "sql12231674";
    private $username = "sql12231674";
    private $password = "Mn9qwd3BkL";*/
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>