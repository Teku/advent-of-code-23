<?php

require_once('../daily.php');

$daily = new Daily(1, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

$total = 0;

// EVaulate each line
foreach ($lines as $line) {
    $total += (int) calibrate($line);
}

echo $total . PHP_EOL;

// Part 1
function calibrate($line) : int|string {
    // return only numeric values
    $numbers = preg_replace('/[^0-9]/', '', $line);
    // return only the first and the last digit, combined.
    return substr($numbers, 0, 1) . substr($numbers, -1);
}