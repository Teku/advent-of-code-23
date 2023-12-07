<?php

require_once('../daily.php');

$daily = new Daily(5, 2023);
$data = $daily->get();

$others = explode("\n\n", $data);

// Part 1 was wrong, misunderstood ranges for seeds
// Referencing: https://www.youtube.com/watch?v=iqTopXV13LE

$seeds = array_shift($others);
$seeds = array_map( 'intval', explode(" ", explode(":", $seeds)[1]));

$ranges = [];

foreach ($others as $i => $mapping) {
    $_ranges = [];
    foreach (explode("\n", $mapping) as $line => $map) {
        if ($line == 0) continue;

        $range = array_map( 'intval', explode(' ', $map));
        $_ranges[] = $range;
    }
    $ranges[] = $_ranges;
}

// referenced: https://github.com/jvancoillie/advent-of-code/blob/main/src/Puzzle/Year2023/Day05/PuzzleResolver.php
function part_one($seeds, $ranges) {

    $mapped = [];

    foreach ($seeds as $seed) {
        foreach ($ranges as $index =>  $type) {
            foreach ($type as [$to, $from, $length]) {
                if ($from <= $seed && $seed < ($from + $length)) {
                    $seed = $to + $seed - $from;
                    break;
                }
            }
        }

        $mapped[] = $seed;
    }
    return min($mapped);
}

echo "Part 1: " . part_one($seeds, $ranges) . PHP_EOL;

function part_two() {
    // oh lord
}