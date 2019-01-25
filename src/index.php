<?php
$value = "World";
$host = $_SERVER['HTTP_HOST'];
?>

<html>
    <body>
        <h1>Hello, <?= $value ?>!</h1>
        <p> You are on: <?= var_dump($host)?></p>
        <h1>Try this API, and explore POIS </h1>

   </body>
</html>