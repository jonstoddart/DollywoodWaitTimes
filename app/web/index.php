<?php

require_once '../config.php';
require_once '../src/Db.php';
require_once '../src/DbRideWait.php';

$day = $_GET['day'];

if (empty($day)) {
    $day = date('Y-m-d');
}

//$waits = $entityManager->getRepository('RideWait')->getLatestWaits();

$DbRideWait = new DbRideWait($db);

$dates = $DbRideWait->getAvailableDates();
$waits = $DbRideWait->getLatestWaits();
$all_waits = $DbRideWait->getDailyWaitsByRide(new \DateTime($day));
$colors = [
    'rgba(229, 44, 44, 1)',
    'rgba(44, 132, 44, 1)',
    'rgba(44, 44, 229, 1)',
    'rgba(229, 229, 44, 1)',
    'rgba(44, 229, 229, 1)',
    'rgba(229, 132, 44, 1)',
    'rgba(132, 132, 44, 1)',
    'rgba(132, 44, 132, 1)',
    'rgba(132, 44, 44, 1)',
    'rgba(44, 229, 44, 1)',
    'rgba(44, 44, 132, 1)',
    'rgba(229, 229, 132, 1)',
    'rgba(44, 132, 132, 1)',
    'rgba(132, 44, 229, 1)',
    'rgba(229, 132, 132, 1)',
    'rgba(132, 229, 132, 1)',
    'rgba(132, 132, 229, 1)',
    'rgba(132, 229, 229, 1)',
    'rgba(132, 229, 44, 1)',
    'rgba(44, 132, 229, 1)',
    'rgba(229, 132, 229, 1)',
    'rgba(44, 229, 132, 1)'
];

$i = 0;
foreach ($all_waits['rides'] as $name => $ride_waits) {
    $all_waits['rides'][$name]['color'] = $colors[$i++];
}

echo $twig->render('pages/home.html.twig', ['waits' => $waits, 'all_waits' => $all_waits, 'day' => $day, 'dates' => $dates]);