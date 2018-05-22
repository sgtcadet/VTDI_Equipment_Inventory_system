<?php
//database_connection.php
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "appterz";
try {
    $connect = new PDO("mysql:host=$servername;dbname=$databasename;port=8080;", $username, $password);
    // set the PDO error mode to exception
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
    session_start();
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
//$connect = new PDO('mysql:host=localhost;dbname=appterz', 'root', '');
//session_start();

?>
