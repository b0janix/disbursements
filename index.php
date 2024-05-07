<?php

use App\Service\Calculation;
use App\Service\FileParser;

require_once 'vendor/autoload.php';

if (!isset($argv[1])) {
    exit('Please provide a valid Y-m date string' . PHP_EOL);
}

try {
    $dateObj = new DateTime($argv[1]);
} catch (Exception) {
    exit('Please provide a valid Y-m date string' . PHP_EOL);
}

$data = [
  'income' => 0
];

$fileParser = new FileParser(new Calculation());

$fileParser->parseFile(__DIR__ . '/files', $dateObj->format('Y-m'), $data);

$data['income'] = number_format($data['income'], 2, '.', '');

var_dump($data);