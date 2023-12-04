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
    if(strlen($line) > 0) {
        $lines[$i] = pad_data_x($line);
    }
}


// Part 1
if (false) {
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
}

$total = 0;
foreach ($matched_parts as $part_num) {
    $total += $part_num;
}

echo "Part 1: ". $total. "\n";

}

// Part 2
$resultList = [];
foreach ($lines as $y => $line) {

    $_line = str_split($line);
    $lineLength = count($_line);

    if($_line === null) continue;

    // Iterate over each character
    for ($x = 0; $x < $lineLength; $x++) {

        if(!isset($_line)) continue;

        if($_line[$x] === '*') {

            $directions = [
                'topLeft' => '',
                'left' => '',
                'bottomLeft' => '',
                'top' => '',
                'bottom' => '',
                'topRight' => '',
                'right' => '',
                'bottomRight' => '',
            ];

            foreach ($directions as $key => &$number) {
                $dx = $dy = 0;

                // Update coordinates based on direction
                switch ($key) {
                    case 'topLeft':
                        $dx = $x-1; $dy = $y-1; break;
                    case 'left':
                        $dx = $x-1; $dy = $y;   break;
                    case 'bottomLeft':
                        $dx = $x-1; $dy = $y+1; break;
                    case 'top':
                        $dx = $x;   $dy = $y-1; break;
                    case 'bottom':
                        $dx = $x;   $dy = $y+1; break;
                    case 'topRight':
                        $dx = $x+1; $dy = $y-1; break;
                    case 'right':
                        $dx = $x+1; $dy = $y;   break;
                    case 'bottomRight':
                        $dx = $x+1; $dy = $y+1; break;
                }

                // Iterate to extract adjacent numbers in the current direction
                $_comparison_line = $lines[$dy];
                $_char = str_split($lines[$dy]);

                if(isset($_char[$dx]) && is_numeric($_char[$dx])) {
                    // First number in the line that matches the range provided.
                    $found_number = matched_position($_comparison_line);
                    
                    // Check if we have a match in the range...
                    while (!empty($found_number)) {

                        // Check range
                        if ($found_number['start'] <= $x && $found_number['end'] >= $x) {
                            $part_num = $found_number['matched'];
                            $matched_parts[$y][$x]['matches'][] = $part_num;
                        }

                        // Remove found number...
                        for($offset=$found_number['start'] + 1; $offset < $found_number['end']; $offset++) {
                            $temp = str_split($_comparison_line);
                            $temp[$offset] = '.';
                            $_comparison_line = implode($temp);
                        }

                        $found_number = matched_position($_comparison_line);
                    }
                    
                    $matched_parts[$y][$x]['line'] = $lines[$dy];
                    $matched_parts[$y][$x]['debug'] = $debug;
                }

                // Check if any adjacent numbers are found in any direction
            }
        }
    }

    
}

foreach($matched_parts as $lines) {
    foreach ($lines as $gears) {
        $_matches = array_values(array_unique($gears['matches']));
        if (count($_matches) > 1) {
            $resultList[] =  $_matches[0] * $_matches[1];
        }
    }
}



$total = 0;
foreach ($resultList as $amount) {
    $total += $amount;
}

echo "Part 2: ". $total . "\n";



function symbol($char): int|false {
    // Not a number or period
    return preg_match('/[^\d\.]/', $char);
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
    // stip out all symbols but *
    $line = preg_replace('/[^\d\*\.]/', '.', $line);

    // add a '.' to the start/end of the line
    return str_pad($line, strlen($line) + 2, '.', STR_PAD_BOTH);
}