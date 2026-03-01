<?php
// src/functions.php

function low_quantity(int $data) {
    return $data - ($data * 0.5);
}

function high_quantity(int $data) {
    return $data * 0.5;
}

function medium_quantity(int $data) {
    return 0;
}

function process_data(int $data): int {
    if ($data === 10) {
        $res = medium_quantity($data);
    } elseif ($data < 7) {
        $res = low_quantity($data);
    } elseif ($data > 40) {
        $res = high_quantity($data);
    } else {
        $res = $data;
    }

    return (int) round($res);
}

function count_unique_results(int $start, int $end): int {
    $a = min($start, $end);
    $b = max($start, $end);
    $set = [];
    for ($i = $a; $i <= $b; $i++) {
        $set[ process_data($i) ] = true;
    }
    return count($set);
}