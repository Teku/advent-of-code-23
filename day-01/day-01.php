<?php

require_once('../daily.php');

$daily = new Daily(1, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

$total = 0;

// Evaulate each line
foreach ($lines as $line) {

    // parse number strings to numeric values
    $line = alphaToNumeric($line);

    $total += (int) calibrate($line);
}

echo $total . PHP_EOL;

// Part 1
function calibrate($line) : int {
    // return only numeric values
    $numbers = preg_replace('/[^0-9]/', '', $line);

    // return only the first and the last digit, combined.
    $result = substr($numbers, 0, 1) . substr($numbers, -1);

    return (int) $result;
}

// Part 2
function alphaToNumeric($line) : string {

    $numbers = ['-kludge-', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

    $array = [];

    // Get each number string, store position
    foreach ($numbers as $key => $value) {
        $_pos = 0;

        while (($_pos = strpos($line, $value, $_pos))!== false) {
            $array[$_pos] = $key;
            $_pos = $_pos + strlen($value);
        }
    }

    // Find each number and position...
    for($i=0; $i<strlen($line); $i++) {
        if(is_numeric($line[$i])) {
            $array[$i] = (int) $line[$i];
        }
    }

    // Sort the array by position
    ksort($array);

    return (int) implode($array);
}