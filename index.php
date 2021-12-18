<?php

require_once('connection.php');
require_once('helpers.php');
require_once('functions.php');

$show_complete_tasks = rand(0, 1);
$title = "Дела в порядке";
$user_id = 2;
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);

$sql_project = "SELECT * FROM project WHERE user_id = $user_id";
$result = mysqli_query($con, $sql_project);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$projects) {
    exit('Ошибка базы данных');
}

$sql_tasks = 'SELECT * FROM task WHERE user_id = ' . $user_id;
$result = mysqli_query($con, $sql_tasks);
$all_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$all_tasks) {
    exit('Ошибка базы данных');
}

if ($project_id) {
    $sql_project_tasks = 'SELECT * FROM task WHERE user_id = ' . $user_id . ' AND project_id = ' . $project_id;
    $result = mysqli_query($con, $sql_project_tasks);
    $project_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
