<?php

require_once('../daily.php');

$daily = new Daily(5, 2023);
$data = $daily->get();

$lines = explode("\n", $data);

// Part 1 was wrong, misunderstood ranges for seeds