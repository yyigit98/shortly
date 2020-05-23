<?php

try {
    $db = new PDO("mysql:host=localhost;dbname=shortly", "root", "");
} catch ( PDOException $e ){
    print $e->getMessage();
}

?>