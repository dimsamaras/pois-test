<?php

class Category{
    /**
     * Category id.
     * @var int
     */
    public $id;
    /**
     * Category name.
     * @var string
     */
    public $name;
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
     * Categories db table.
     * @var string
     */
    private $table_name = 'categories';

    /**
     * Category constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Create a Category.
     * @return boolean
     */
    function create()
    {
        $query = "INSERT INTO {$this->table_name}
                  SET
                  name=:name, created=:created";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->created=htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":created", $this->created);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Find whether a Category exists.
     * @return bool
     */
    function exists()
    {
        if (!$this->id) {
            return [];
        }
        $query = "SELECT p.id
                    FROM {$this->table_name} p
                    WHERE p.id = {$this->id}
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['id']){
            return true;
        }

        return false;
    }

    /**
     * Delete a Category by id.
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

    /**
     * Retrieve available categories along with the number of their POIs
     * @return array
     */
    function retrieve()
    {
        $query = "SELECT c.id, c.name, COUNT(p.id) as p_count
                    FROM {$this->table_name} c
                    LEFT JOIN pois p
                      ON p.category = c.id
                    GROUP BY c.id
                    ORDER BY c.name ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();
        $categories = array();
        if ($num > 0) {
            $categories["categories"] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $single_category = array(
                    "id"            => $row['id'],
                    "name"          => $row['name'],
                    "poi_count"     => $row['p_count']
                );

                array_push($categories["categories"], $single_category);
            }
        }
        return $categories;
    }

}