<?php

require_once '../config.php';

$db = new PDO("mysql:host={$config['database']['host']};port={$config['database']['port']};dbname={$config['database']['dbname']}", $config['database']['user'], $config['database']['password']);

$ride_name = $_GET['ride'];

$sql = 'SELECT ride_name, ride_status, wait_time, DATE_FORMAT(DATE_SUB(created_at, INTERVAL 5 HOUR), "%a %b %e, %l:%i%p") AS formatted_created_at FROM ride_waits WHERE ride_name = :ride_name ORDER BY created_at DESC';
$statement = $db->prepare($sql);
$statement->execute([':ride_name' => $ride_name]);
$waits = $statement->fetchAll(PDO::FETCH_ASSOC);

$graph_waits = array_reverse($waits);

echo $twig->render('pages/ride.html.twig', ['ride_name' => $ride_name, 'waits' => $waits, 'graph_waits' => $graph_waits]);