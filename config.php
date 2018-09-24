<?php
ini_set('post_max_size', '256M');
ini_set('upload_max_filesize', '256M');
ini_set('max_input_vars', '2000');

header('Content-Type: text/html; charset=utf-8');

define( 'MYSQL_HOST', 		'db4free.net' );
define( 'MYSQL_USER', 		'kyberhmg' );
define( 'MYSQL_PASSWORD', 	'kyberhmg1' );
define( 'MYSQL_DB_NAME', 	'kyberhmg' );

/*
define( 'MYSQL_HOST', 		'localhost' );
define( 'MYSQL_USER', 		'root' );
define( 'MYSQL_PASSWORD', 	'root' );
define( 'MYSQL_DB_NAME', 	'swgoh' );
*/

try {
    $PDO = new PDO( 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );
} catch ( PDOException $e ) {
    echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();
}

$PDO->exec("SET NAMES 'utf8'");
$PDO->exec("SET character_set_connection = utf8");
$PDO->exec("SET character_set_client = utf8");
$PDO->exec("SET character_set_results = utf8");

//$url =  "//{$_SERVER['HTTP_HOST']}/swgoh";
$url =  "//{$_SERVER['HTTP_HOST']}";
$site = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
?>