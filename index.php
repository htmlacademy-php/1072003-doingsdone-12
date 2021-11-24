<?php

require_once('connection.php');
require_once('helpers.php');
require_once('functions.php');

$show_complete_tasks = rand(0, 1);
$title = "Дела в порядке";
$userId = 2;

$sql_project = "SELECT * FROM project WHERE user_id = $userId";
$result = mysqli_query($con, $sql_project);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$projects) {
    exit('Ошибка базы данных');
}

$sql_task = "SELECT * FROM task WHERE user_id = $userId";
$result = mysqli_query($con, $sql_task);
$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$tasks) {
    exit('Ошибка базы данных');
}


$content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    ]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
]);

print($layout_content);
