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

$sql_task = "SELECT * FROM task WHERE user_id = $user_id";
$result = mysqli_query($con, $sql_task);
$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$tasks) {
    exit('Ошибка базы данных');
}

if ($project_id) {
    $sql_task_project = $sql_task . " AND project_id = $project_id";
    $result = mysqli_query($con, $sql_task_project);
    $task_project = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if(!isset($project_id) OR !$task_project) {
        header("HTTP/1.0 404 Not Found");
        exit;
    }

    $content = include_template('main.php', [
    'projects' => $projects,
    'task_project' => $task_project,
    'show_complete_tasks' => $show_complete_tasks,
    'project_id' => $project_id,
    'tasks' => $tasks,
    ]);
} else {

    $content = include_template('main.php', [
        'projects' => $projects,
        'tasks' => $tasks,
        'show_complete_tasks' => $show_complete_tasks,
        'project_id' => $project_id,
        ]);
    }

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
]);

print($layout_content);
