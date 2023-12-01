<?php

require_once('../daily.php');

$daily = new Daily(1, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

// Part 1
$count = 0;
foreach ($lines as $line) {
    $count += count(preg_split('/\s+/', $line));
}

echo $count. PHP_EOL;