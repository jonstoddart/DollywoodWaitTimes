<?php

require_once '../config.php';

use RideWait;

$waits = $entityManager->getRepository('RideWait')->getLatestWaits();

echo $twig->render('pages/home.html.twig', ['waits' => $waits]);