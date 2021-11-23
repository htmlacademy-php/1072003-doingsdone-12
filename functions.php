<?php

$show_complete_tasks = rand(0, 1);

$title = "Дела в порядке";

function tasckCount ($tasks, $projectId) {
    $tasckCount = 0;
    foreach ($tasks as $task) {
        if($task['project_id'] == $projectId) {
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
