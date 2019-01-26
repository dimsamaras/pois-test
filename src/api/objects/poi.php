<?php

/**
 * POI object class
 */
class Poi{

    /**
     * POI id.
     * @var int
     */
    public $id;
    /**
     * POI name.
     * @var string
     */
    public $name;
    /**
     * POI lat.
     * @var double
     */
    public $latitude;
    /**
     * POI lng.
     * @var double
     */
    public $longitude;
    /**
     * POI category id.
     * @var int
     */
    public $category;
    /**
     * POI categiry name.
     * @var string
     */
    public $category_name;
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
     * Pois db table.
     * @var string
     */
    private $table_name = 'pois';

    /**
     * Poi constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Create a POI.
     * @return boolean
     */
    function create()
    {
        $query = "INSERT INTO {$this->table_name}
                  SET
                  name=:name, latitude=:latitude, longitude=:longitude, category=:category, created=:created";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->latitude=htmlspecialchars(strip_tags($this->latitude));
        $this->longitude=htmlspecialchars(strip_tags($this->longitude));
        $this->category=htmlspecialchars(strip_tags($this->category));
        $this->created=htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":latitude", $this->latitude);
        $stmt->bindParam(":longitude", $this->longitude);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":created", $this->created);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

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
     * Delete a poi by id.
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
     * Retrieve available pois in an array.
     * @return array
     */
    function retrieve()
    {
        $query = "SELECT p.id, p.name, p.latitude, p.longitude, p.created, p.modified, c.name as category_name
                    FROM {$this->table_name} p
                    LEFT JOIN categories c
                      ON c.id = p.category
                    ORDER BY p.created ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        // check if pois exist in the db.
        $num = $stmt->rowCount();
        $pois = array();
        if ($num > 0) {
            $pois["pois"] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $single_poi = array(
                    "id"            => $row['id'],
                    "name"          => $row['name'],
                    "latitude"      => $row['latitude'],
                    "longitude"     => $row['longitude'],
                    "category_name" => $row['category_name']
                );

                array_push($pois["pois"], $single_poi);
            }
        }
        return $pois;
    }

    /**
     * Retun array of the POI object
     */
    function retrieve_one()
    {
        if (!$this->id) {
            return [];
        }

        $query = "SELECT p.id, p.name, p.latitude, p.longitude, p.created, p.modified, c.name as category_name
                    FROM {$this->table_name} p
                    LEFT JOIN categories c
                      ON c.id = p.category
                    WHERE p.id = {$this->id}
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->category = $row['category'];
        $this->latitude = $row['latitude'];
        $this->longitude = $row['longitude'];
        $this->category_name = $row['category_name'];
        $this->created = $row["created"];
        $this->modified = $row["modified"];

        return ['pois'=>$row];
    }

    /**
     * Retrieve a list of POIs that are near a given location (lat/lng) for a given distance.
     * @param $closeDistance
     * @return array
     */
    function retrieve_by_location($closeDistance)
    {
        if (!$this->longitude || !$this->longitude) {
            return [];
        }
        // https://www.movable-type.co.uk/scripts/latlong.html
        // https://gis.stackexchange.com/questions/31628/find-points-within-a-distance-using-mysql
        $query = "SELECT p.id, p.name, p.latitude, p.longitude, p.created, p.modified, c.name as category_name, (
                        6371 * acos (
                          cos ( radians({$this->latitude}) )
                          * cos( radians(p.latitude) )
                          * cos( radians(p.longitude) - radians({$this->longitude}) )
                          + sin ( radians({$this->latitude}) )
                          * sin( radians(p.latitude) )
                        )
                      ) AS distance
                    FROM {$this->table_name} p
                    LEFT JOIN categories c
                      ON c.id = p.category
                    HAVING distance < {$closeDistance}
                    ORDER BY distance";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        // check if pois exist in the db.
        $num = $stmt->rowCount();
        $pois = array();
        if ($num > 0) {
            $pois["pois"] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $single_poi = array(
                    "id"                    => $row['id'],
                    "name"                  => $row['name'],
                    "latitude"              => $row['latitude'],
                    "longitude"             => $row['longitude'],
                    "category_name"         => $row['category_name'],
                    "distance_from_target"  => $row['distance']
                );

                array_push($pois["pois"], $single_poi);
            }
        }
        return $pois;
    }

    function retrieve_paging($offset, $limit, $url, $page)
    {
        $query = "SELECT p.id, p.name, p.latitude, p.longitude, p.created, p.modified, c.name as category_name
                    FROM {$this->table_name} p
                    LEFT JOIN categories c
                      ON c.id = p.category
                    ORDER BY p.created ASC
                    LIMIT {$offset}, {$limit}";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        // check if pois exist in the db.
        $num = $stmt->rowCount();
        $pois = array();
        if ($num > 0) {
            $pois["pois"] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $single_poi = array(
                    "id"            => $row['id'],
                    "name"          => $row['name'],
                    "latitude"      => $row['latitude'],
                    "longitude"     => $row['longitude'],
                    "category_name" => $row['category_name']
                );

                array_push($pois["pois"], $single_poi);
            }
        }

        // include paging
        $total_rows     = $this->count();
        $page_url       = "{$url}poi/retrieve_all?";
        $paging         = $this->getPaging($page, $total_rows, $limit, $page_url);
        $pois["paging"] =$paging;

        return $pois;
    }

    private function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name;

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    private function getPaging($page, $total_rows, $records_per_page, $page_url){

        // paging array
        $paging_arr=array();

        //total results
        $paging_arr["total_results"] = $total_rows;

        //first page
        $paging_arr["first"] = "{$page_url}page=1";

        // count all pois in the database to calculate total pages
        $total_pages = ceil($total_rows / $records_per_page);

        // range of links to show
        $range = 1;

        // display links to 'range of pages' around 'current page'
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;

        $paging_arr['pages']=array();
        $page_count=0;

        for($x=$initial_num; $x<$condition_limit_num; $x++){
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if(($x > 0) && ($x <= $total_pages)){
                $paging_arr['pages'][$page_count]["page"]=$x;
                $paging_arr['pages'][$page_count]["url"]="{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";

                $page_count++;
            }
        }

        // button for last page
        $paging_arr["last"] = "{$page_url}page={$total_pages}";

        // json format
        return $paging_arr;
    }
}