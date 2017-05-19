<?php

require_once '../config.php';
require_once '../src/Db.php';
require_once '../src/DbRideWait.php';

$ride_name = $_GET['ride'];
$day = $_GET['day'];

if (empty($day)) {
    $day = date('Y-m-d');
}

$DbRideWait = new DbRideWait($db);

$dates = $DbRideWait->getAvailableDates();
$waits = $DbRideWait->getDailyWaitsForRide(new \DateTime($day), $ride_name);

echo $twig->render('pages/ride.html.twig', ['ride_name' => $ride_name, 'waits' => $waits, 'day' => $day, 'dates' => $dates]);