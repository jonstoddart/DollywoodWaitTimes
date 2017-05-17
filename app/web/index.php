<?php

require_once '../config.php';

$db = new PDO("mysql:host={$config['database']['host']};port={$config['database']['port']};dbname={$config['database']['dbname']}", $config['database']['user'], $config['database']['password']);

$sql = '
        SELECT RW.*
        FROM ride_waits RW
        INNER JOIN (
            SELECT ride_name,
            MAX(ride_wait_id) AS max_id
            FROM ride_waits
            GROUP BY ride_name
        ) RW_ID ON RW.ride_wait_id = RW_ID.max_id
    ';
$statement = $db->prepare($sql);
$statement->execute();
$waits = $statement->fetchAll(PDO::FETCH_ASSOC);
//$waits = $entityManager->getRepository('RideWait')->getLatestWaits();

foreach ($waits as $key=>$wait) {
    $waits[$key]['created_at'] = date('D M j, g:ia', strtotime('-5 hours', strtotime($wait['created_at'])));
}

echo $twig->render('pages/home.html.twig', ['waits' => $waits]);