<?php

require_once('connection.php');
require_once('helpers.php');
require_once('functions.php');

$show_complete_tasks = rand(0, 1);
$title = "Дела в порядке";
$user_id = $_SESSION['user']['id'] ?? '';
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
$error = '';

if(empty($user_id)) {
    $content = include_template('guest.php');
    $layout_content = include_template('layout-guest.php', [
    'content' => $content,
    ]);
} else {
    $projects = get_user_projects($con, $user_id);

    $all_tasks = get_user_tasks($con, $user_id);

    if ($project_id) {
        $project_tasks = get_task_project($con, $user_id, $project_id);
    } else {
        $project_tasks = $all_tasks;
    }

    mysqli_query($con, 'CREATE FULLTEXT INDEX search_task ON task(title)');

    if($_GET['search_submit']) {
       $project_tasks = search_task($con, [$_GET['search']]) ?? '';
       if (empty($project_tasks)) {
            $error = 'По вашему запросу ничего не найдено';
        }
    }

$content = include_template('main.php', [
    'projects' => $projects,
    'all_tasks' => $all_tasks,
    'project_tasks' => $project_tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'project_id' => $project_id,
    'error' => $error,
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
]);
}

print($layout_content);
