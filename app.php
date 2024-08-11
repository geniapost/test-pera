<?php

require 'vendor/autoload.php';

use App\BinChecker;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$converter = new \App\CurrencyConverter();
$binChecker = new BinChecker();
$processor = new \App\TransactionProcessor($converter, $binChecker);
$processor->getCommissions($argv[1]);
