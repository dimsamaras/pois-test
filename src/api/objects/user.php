<?php

/**
 * User object class
 */
class User{
    /**
     * User id.
     * @var int
     */
    public $id;
    /**
     * User name.
     * @var string
     */
    public $firstname;
    /**
     * User lastname
     * @var double
     */
    public $lastname;
    /**
     * User email.
     * @var double
     */
    public $email;
    /**
     * User password
     * @var int
     */
    public $password;
    /**
     * Modified timestamp.
     * @var int
     */
    public $modified;
    /**
     * Created timestamp.
     * @var int
     */
    public $created;
    /**
     * DB connection.
     * @var PDO
     */
    private $conn;
    /**
     * Users db table.
     * @var string
     */
    private $table_name = 'users';

    /**
     * User constructor.
     * @param $db
     */
    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * Create user.
     * @return bool
     */
    function create(){
        $query = "INSERT INTO " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password";

        $stmt = $this->conn->prepare($query);

        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }

        return false;
    }
    /**
     * Delete a user by id.
     * @return bool
     */
    function delete()
    {
        if (!$this->id || !$this->exists()) {
            return false;
        }

        $query = "DELETE FROM {$this->table_name} WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function exists($email = null){
        if ($email) {
            $query = "SELECT u.id
                    FROM " . $this->table_name . " u
                    WHERE u.email = '" . $email . "'
                    LIMIT 0,1";
        }else {
            $query = "SELECT u.id
                    FROM " . $this->table_name . " u
                    WHERE u.id = {$this->id}
                    LIMIT 0,1";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['id']){
            return true;
        }

        return false;
    }
}