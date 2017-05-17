<?php

require_once '../config.php';

$db = new PDO("mysql:host={$config['database']['host']};port={$config['database']['port']};dbname={$config['database']['dbname']}", $config['database']['user'], $config['database']['password']);

$ride_name = $_GET['ride'];

$sql = 'SELECT * FROM ride_waits WHERE ride_name = :ride_name ORDER BY updated_at DESC';
$statement = $db->prepare($sql);
$statement->execute(['ride_name' => $ride_name]);
$waits = $statement->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('pages/ride.html.twig', ['ride_name' => $ride_name, 'waits' => $waits]);