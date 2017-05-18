<?php

require_once '../config.php';
require_once '../src/Db.php';
require_once '../src/DbRideWait.php';

//$waits = $entityManager->getRepository('RideWait')->getLatestWaits();

$DbRideWait = new DbRideWait($db);

$waits = $DbRideWait->getLatestWaits();
$all_waits = $DbRideWait->getDailyWaitsByRide(new \DateTime());
$colors = [
    'rgba(255, 0, 0, 1)',
    'rgba(0, 0, 255, 1)',
    'rgba(0, 255, 255, 1)',
    'rgba(0, 0, 0, 1)',
    'rgba(0, 255, 0, 1)',
    'rgba(244, 164, 96, 1)',
    'rgba(255, 255, 0, 1)',
    'rgba(255, 0, 255, 1)',
    'rgba(150, 0, 0, 1)',
    'rgba(80, 66, 43, 1)',
    'rgba(0, 150, 0, 1)',
    'rgba(0, 0, 150, 1)',
    'rgba(150, 150, 150, 1)',
    'rgba(33, 101, 101, 1)'
];

$i = 0;
foreach ($all_waits['rides'] as $name => $ride_waits) {
    $all_waits['rides'][$name]['color'] = $colors[$i++];
}

echo $twig->render('pages/home.html.twig', ['waits' => $waits, 'all_waits' => $all_waits]);