<?php

require_once('connection.php');
require_once('helpers.php');
require_once('functions.php');

$show_complete_tasks = rand(0, 1);
$title = "Дела в порядке";
$user_id = 2;
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);

$projects = get_user_projects($con, $user_id);

$all_tasks = get_user_tasks($con, $user_id);

if (!$all_tasks) {
    exit('Ошибка базы данных');
}

if ($project_id) {
    $project_tasks = get_task_project($con, $user_id, $project_id);

    if(!isset($project_id) || !$project_tasks) {
        header("HTTP/1.0 404 Not Found");
        exit;
    }
} else {
    $project_tasks = $all_tasks;
}

$content = include_template('main.php', [
    'projects' => $projects,
    'all_tasks' => $all_tasks,
    'project_tasks' => $project_tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'project_id' => $project_id
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
]);

print($layout_content);
