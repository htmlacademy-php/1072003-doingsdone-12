<?php

function tasck_count ($tasks, $projectId) {
    $tasckCount = 0;
    foreach ($tasks as $task) {
        if($task['project_id'] == $projectId) {
            $tasckCount++;
        }
    }
    return $tasckCount;
};

function check_time_completed ($date) {
    $dt_completion = date_create($date);
    $dt_now = date_create("now");
    $diff = date_diff($dt_completion, $dt_now);

    $days = $diff->format("%d");

    return !$days;
};
