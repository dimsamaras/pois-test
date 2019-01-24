<?php
/**
 * Connect to an ODBC database using driver invocation.
 */

/**
 * Class DB
 * Creates and handles the connections to the database.
 */
class DB{

    /**
     * db host
     * @var string
     */
    private $host = "database";
    /**
     * db name
     * @var string
     */
    private $db_name = "test";
    /**
     * db user
     * @var string
     */
    private $username = "user";
    /**
     * db user passwd
     * @var string
     */
    private $password = "secret";
    /**
     * the db object
     * @var PDO
     */
    public $conn;

    /**
     * Get the database connection.
     * @return PDO|null
     */
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