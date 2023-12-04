<?php

require_once('../daily.php');

$daily = new Daily(4, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

$winnings = [];

if (false) {
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
}

// Part 2
function processScratchCard($card, &$copies, &$totalCards) {

    // Check card
    $card_meta = cardMeta($card);

    $card = $card_meta['card'];
    $winning = $card_meta['winning'];
    $given = $card_meta['given'];

    $matches = count(array_intersect($given, $winning));

    $total = 1;
    if(isset($copies[$card])) {
        $total += $copies[$card];
        unset($copies[$card]);
    }

    // referenced: https://github.com/TomKauffeld/advent-of-code-2023/blob/main/day04/part2/main.php
    for ($i = 0; $i < $matches; ++$i) {
        $index = $card + $i + 1;
        $copies[$index] = $total + ($copies[$index]?? 0);
    }

    $totalCards += $total;
}

$totalCards = 0;
$copies = [];

$i = 0;

foreach ($lines as $line) {
    processScratchCard($line, $copies, $totalCards);
}

echo "Total Scratchcards: $totalCards\n";

function cardMeta($string) : array {
    if (empty($string)) return [];

    // card : values
    $split_card = explode(': ', $string);

    preg_match('/(\d+)/', $split_card[0], $card_info);
    $card = $card_info[1];

    $values = trim(preg_replace('/\s+/', ' ', $split_card[1]));

    // winning : given values
    $split_values = explode(' | ', $values);

    $winning = explode(' ', $split_values[0]);
    $given = explode(' ', $split_values[1]);

    return [
        'card' => $card,
        'winning' => $winning,
        'given' => $given
    ];
}