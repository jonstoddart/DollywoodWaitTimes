<?php

require_once '../config.php';
require_once '../src/Db.php';
require_once '../src/DbRideWait.php';

$DbRideWait = new DbRideWait($db);

if (!empty($_GET['ride'])) {
    $ride_name = $_GET['ride'];
}
else {
    header('Location: /');
    exit;
}

if (!empty($_GET['day'])) {
    $day = $_GET['day'];
}
else {
    $day = date('Y-m-d', strtotime($DbRideWait->getAvailableDates()[0]['formatted_created_at']));
}

$dates = $DbRideWait->getAvailableDates();
$waits = $DbRideWait->getDailyWaitsForRide(new \DateTime($day), $ride_name);

echo $twig->render('pages/ride.html.twig', ['ride_name' => $ride_name, 'waits' => $waits, 'day' => $day, 'dates' => $dates]);