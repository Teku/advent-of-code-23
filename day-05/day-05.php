<?php

require_once('../daily.php');

$daily = new Daily(5, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

$seeds = [];
$map_count = 0;
$maps = [];

// Part 1 - get seeds and maps
foreach ($lines as $line => $data) {

    // ignore empty...
    if($data == "") continue;

    // seed, first line
    if($line == 0) {
        // strip out data: and get seed array
        $seeds = explode(' ', str_replace('seeds: ', '', $data));
    }

    // maps, find regex to empty line...
    if ( strpos($data, 'map:') ) {
        ++$map_count;
    } elseif($map_count > 0) {
        $split_data = explode(' ', $data);
        
        $destination_range_start = $split_data[0];
        $source_range_start = $split_data[1];
        $range_length = $split_data[2];

        $maps[$map_count-1][] = [
            'destination_range_start' => $destination_range_start,
            'source_range_start' => $source_range_start,
            'range_length' => $range_length
        ];
    }
}

$seed_start = 0;
sort($seeds);

// Part 1 - iterate through seeds
foreach ($seeds as $seed) {
    $seed_end = $seed;
    echo "Seeds: ". $seed_start ." to ". $seed_end . PHP_EOL;

    // check maps
    foreach ($maps as $map_id => $map) {
        // check first range...
        if ($map_id > 0) continue;
        
        foreach ($map as $range_id => $range) {
            $_source_start = $range['source_range_start'];
            $_destination = $range['destination_range_start'];
            $_length = $range['range_length']-1;

            $matches_found = 0;
            // check range?
            $_source_end = $_source_start + $_length;

            if ($seed_start >= $_source_start && $_source_end >= $seed_end) {
                echo "within range...". PHP_EOL;
                $matches_found += 1;
            }
        }
    }
    echo "Matches found: ". $matches_found. PHP_EOL;
    $seed_start = $seed_end + 1;
}

//var_dump( $maps );
/*

[seed_start, seed_end] and [source_start, source_end]

source_end >= seed_start and seed_end >= source_start

seed_start >= source_start && source_end >= seed_end


*/