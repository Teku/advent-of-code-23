<?php

require_once('../daily.php');

$daily = new Daily(4, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

$winnings = [];

// Part 1
foreach ($lines as $line) {

    if($line === "") continue;

    // card : values
    $split_card = explode(': ', $line);

    preg_match('/(\d+)/', $split_card[0], $card_info);
    $card = $card_info[1];

    $values = trim(preg_replace('/\s+/', ' ', $split_card[1]));

    // winning : given values
    $split_values = explode(' | ', $values);

    $winning = explode(' ', $split_values[0]);
    $given = explode(' ', $split_values[1]);

    $total = 0;
    $value = 1;

    foreach($winning as $number) {
        if(in_array($number, $given)) {
            if($total === 0) $total = 1;

            $total = $total * $value;
            $value++;
            if($value >= 2) $value = 2;
        }
    }

    $winnings[$card] = $total;

}

$total_winnings = 0;
foreach($winnings as $card => $total) {
    $total_winnings += $total;
}

echo "Part 1: ". $total_winnings. PHP_EOL;

// Part 2
$won = [];
$copies = [];

foreach ($lines as $i => $line) {

    if($line === "") continue;

    $copies = check_cards($line, $lines, $i);
}

var_dump($copies);

function check_cards($line, $lines, $index, $copies = []) {
    // card : values
    $split_card = explode(': ', $line);

    preg_match('/(\d+)/', $split_card[0], $card_info);
    $card = $card_info[1];

    $values = trim(preg_replace('/\s+/', ' ', $split_card[1]));

    // winning : given values
    $split_values = explode(' | ', $values);

    $winning = explode(' ', $split_values[0]);
    $given = explode(' ', $split_values[1]);

    $draw = 0;

    foreach($winning as $number) {
        if(in_array($number, $given)) {
            $draw += 1;// Draw +1 cards
        }
    }

    echo "card $card draws $draw " . PHP_EOL;

    // Draw cards...
    $start_pos = $index + 1;

    for($i = $start_pos; $i < $start_pos + $draw; $i++) {
        echo $lines[$i] . PHP_EOL;
        $copies[] = $lines[$i];
    }

    if ($i >= count($lines)) return $copies;

    if ($draw > 0) {
        echo "draw > 0 ". PHP_EOL;
        unset($lines[$index]);
    }

    check_cards($line, $lines, $index, $copies);
}