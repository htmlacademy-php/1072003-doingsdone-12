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
