<?php

require_once('../daily.php');

$daily = new Daily(5, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

$seeds = [];
$maps = [];
$map_count = 0;

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

// $map[] = ['source' => 'destination']


// Part 1 - iterate through seeds
foreach ($seeds as $seed) {
    $seed_end = $seed;
    echo "Source: ". $seed_start ." to ". $seed_end . PHP_EOL;

    // check maps
    foreach ($maps as $map_id => $map) {
        // check first range...
        if ($map_id > 0) continue;
        
        $matches_found = 0;

        foreach ($map as $range_id => $range) {
            $_destination = $range['destination_range_start'];
            $_source_start = $range['source_range_start'];
            $_length = $range['range_length']-1;

            
            // check range?
            $_source_end = $_source_start + $_length;

            // seed start, seed end (update end until no match)
            // get new seed end within match
            if ($seed_start >= $_source_start && $seed_end <= $_source_end) {
                $matches_found += 1;
                $seed_end = $_source_end;
                break;
            }

            // output matches
            if ($matches_found == 1) {
                echo "Matches: ". $_source_start. " to ". $_source_end. PHP_EOL;
            }
            
        }
    }
    echo "\tMatches found: ". $matches_found ."\n". PHP_EOL;
    $seed_start = $seed_end + 1;
}
/*

[seed_start, seed_end] and [source_start, source_end]

source_end >= seed_start and seed_end >= source_start

seed_start >= source_start && source_end >= seed_end


*/