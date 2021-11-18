<?php

function tasckCount ($array, $category) {
    $tasckCount = 0;
    foreach ($array as $val) {
        if($val['category'] == $category) {
            $tasckCount++;
        }
    }
    return $tasckCount;
};

function check_time_completed ($date) {
    $dt_end = date_create($date);
    $dt_now = date_create("now");
    $diff = date_diff($dt_end, $dt_now);

    $days = $diff -> format("%d");

    if(!$days) {
        return true;
    }

    return false;
};
