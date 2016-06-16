<?php

require __DIR__ . '/../vendor/autoload.php';

use CD\Commands\GetLastPR;
use Symfony\Component\Console\Application;

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$application = new Application();
$application->add(new GetLastPR());
$application->run();
