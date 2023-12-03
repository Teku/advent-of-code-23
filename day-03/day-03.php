<?php

require_once('../daily.php');

$daily = new Daily(3, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

// Part 1
$matched_parts = [];
$smush = [];
$unchanged = '';

// PADDING
$lines = pad_data_y($lines);
foreach ($lines as $i => $line) {
    $lines[$i] = pad_data_x($line);
}



foreach ($lines as $i => $line) {
    // skip first... and last row
    if ($i == 0 || $i == count($lines) - 1) continue;

    // padding
    $unchanged = $line;

    // current line
    $chars = str_split($line);

    $smush = array_fill(0, count($chars), 0);

    foreach ($chars as $ii => $char) {
        $current[$ii] = symbol($char);
        $smush[$ii] += symbol($char);

        // check next & previous lines (quiet)
        $smush[$ii] += symbol(str_split($lines[$i - 1])[$ii]);
        $smush[$ii] += symbol(str_split($lines[$i + 1])[$ii]);
    }

    $_squash = implode($smush);
    $part_num = false;
    $found_number = matched_position($line);

    // Check if we have a match in the range...
    while (!empty($found_number)) {

        $part_num = null;

        // Check range
        for($offset=$found_number['start']; $offset <= $found_number['end']; $offset++) {
            if($_squash[$offset] > 0 && $part_num === null) {
                $part_num = $found_number['matched'];
                array_push($matched_parts, $part_num);
            }
        }

        // Remove found number...
        for($offset=$found_number['start'] + 1; $offset < $found_number['end']; $offset++) {
            if ($part_num !== null) {
                $line[$offset] = ':';
            } else {
                $line[$offset] = '.';
            }
        }

        $found_number = matched_position($line);
    }

    var_dump([
        'original' => $unchanged,
        'line' => $line,
        'current' => implode($current),
        'smush' => implode($smush),
        'parts' => $matched_parts
    ]);

    if($i > 2) die();
}

$total = 0;
foreach ($matched_parts as $part_num) {
    $total += $part_num;
}

echo "Part 1: ". $total. "\n";

function symbol($char): int|false {
    // Not a number or period
    return preg_match('/[\*]/', $char);
}

function matched_position($string): array {
    preg_match('/(\w+)/', $string, $matches, PREG_OFFSET_CAPTURE);

    if(empty($matches)) return [];

    // Return padded start/end
    return [
        'matched' => $matches[0][0],
        'start' => $matches[0][1] - 1,
        'length' => strlen($matches[0][0]),
        'end' => $matches[0][1] + strlen($matches[0][0])
    ];
}


/*

Line 1:
............................................................................................................................................
.242......276....234............682.......................958..695..742................714......574..............833.........159....297.686.
.............*............................612*......304..*..........*.......@175...#...*...........*890...........*.............*..*........

Line 2:
.242......276....234............682.......................958..695..742................714......574..............833.........159....297.686.
.............*............................612*......304..*..........*.......@175...#...*...........*890...........*.............*..*........
..........346......................997........923......*..253..........698........122.746.....-832..........766.432..229.....674....415.....

276*346
612*923
958*253
714*746
574*890
833*432
159*674
297*415


..........346......................997........923......*..253..........698........122.746.....-832..........766.432..229.....674....415.....
...............#76...........332....*...............111...........785..............................=..720..*........*.......................
........204............396..*.....357..438*694...............154.................................26...*....422...200.../201.................
....859*......496.598.+....810........................816.......*713...........802#.........330......540...........................%344.....
..............*.....*..........344.......................*.............671............994.................467...............................
........$..388.........152*141..*......73.719...$526....830...759......%......943............541.624.781...*...$150.............966.........
.....877.......................67.....*.....*.............................859..*..502+........$..*.....*.425........778.../........*........

*/

// Pad data, to simplify checks.
function pad_data_y ($lines): array {
    $_array = [];
    $_line = '';
    // count the number of characters in a line (assumes all lines are the same length)
    $character_count = strlen($lines[0]);
    $_array = array_fill(0, $character_count, '.');
    $_line = implode($_array);

    // add a row of '.'s to the start of the data array
    array_unshift($lines, $_line);

    // add a row of '.'s to the end of the data array
    array_push($lines, $_line);
    
    // reset array keys...
    return $lines;
}

function pad_data_x ($line): string {
    // add a '.' to the start/end of the line
    return str_pad($line, strlen($line) + 2, '.', STR_PAD_BOTH);
}