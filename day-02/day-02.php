<?php

require_once('../daily.php');

$daily = new Daily(2, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

$max_values = array(
    'red' => 12,
    'green' => 13,
    'blue' => 14
);

$game_sum = 0;

// Part 1
foreach ($lines as $line) {
    // Remove spaces
    $nospace = str_replace(' ', '', $line);

    if ($line === "") continue; 

    // Get game number from Game info
    $split = explode(':', $nospace);
    preg_match('/Game(\d+)/', $split[0], $game_info);
    $game_number = $game_info[1] . PHP_EOL;

    $cubes_count = array(
        'red' => 0,
        'green' => 0,
        'blue' => 0
    );

    $draws = explode(';', $split[1]);

    foreach ($draws as $draw) {

        $cubes = explode(',', $draw);

        // Many...
        foreach ($cubes as $cube) {
            preg_match('/(\d+)(\w+)/', $cube, $cube_info);

            if ($cube_info[1] > $cubes_count[$cube_info[2]]) {
                $cubes_count[$cube_info[2]] = $cube_info[1];
            }
        }

    }

    $valid = true;

    // Check max values
    foreach ($max_values as $color => $count) {
        if($cubes_count[$color] > $count) {
            // Invalid...
            $valid = false;
        }
    }

    if($valid) {
        $game_sum += $game_number;
    }
}

echo $game_sum. PHP_EOL;