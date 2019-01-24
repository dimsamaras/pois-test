<?php

$value = "World";

try{
    $conn = new PDO('mysql:host=database;dbname=test;charset=utf8mb4', 'user', 'secret');
}catch(PDOException $exception){
    echo "Connection error: " . $exception->getMessage();
}
// $databaseTest = ($db->query('SELECT * FROM poi'))->fetchAll(PDO::FETCH_OBJ);
$query = "SELECT * FROM pois";
$stmt = $conn->query($query);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['name'] . "\n";
}

// $conn->prepare($query);
// $databaseTest = $conn->execute($query);
?>

<html>
    <body>
        <h1>Hello, <?= $value ?>!</h1>
        <h1>Try this API</h1>

<!--        --><?php //foreach($databaseTest as $row): ?>
<!--            <p>Hello, --><?//= $row->name ?><!--</p>-->
<!--        --><?php //endforeach; ?>
    </body>
</html>