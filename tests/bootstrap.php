<?php

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load the .env.test file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env.test');
$dotenv->load();
