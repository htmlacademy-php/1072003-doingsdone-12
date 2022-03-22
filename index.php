<?php

require_once('connection.php');
require_once('helpers.php');
require_once('functions.php');

$show_completed = filter_input(INPUT_GET, 'show_completed');
$show_complete_tasks = 0;
$check = filter_input(INPUT_GET, 'check');
$search = filter_input(INPUT_GET, 'search');
$search_submit = filter_input(INPUT_GET, 'search_submit');
$title = "Дела в порядке";
$user_id = $_SESSION['user']['id'] ?? '';
$filter = filter_input(INPUT_GET, 'filter');
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
$error = '';
$task_id = filter_input(INPUT_GET, 'task_id');

if(empty($user_id)) {
    $content = include_template('guest.php');
    $layout_content = include_template('layout-guest.php', [
    'content' => $content,
    ]);
} else {
    $projects = get_user_projects($con, $user_id);

    $all_tasks = get_user_tasks($con, $user_id);

    if (isset($check)) {
        set_task_status($con, $task_id);
        header('Location: /');
        exit;
    }


    if ($project_id) {
        $project_tasks = get_task_project($con, $user_id, $project_id);
    } else {
        $project_tasks = $all_tasks;
    }

    if (isset($search_submit)) {
       $project_tasks = search_task($con, [$search], $user_id) ?? '';
       if (empty($project_tasks)) {
            $error = 'По вашему запросу ничего не найдено';
        }
    }

    if (isset($show_completed)) {
        $_SESSION['user']['show_completed'] = $show_completed;
    }

    if (isset($_SESSION['user']['show_completed'])) {
        $show_complete_tasks = $_SESSION['user']['show_completed'];
    }

    if(isset($filter)) {
        $project_tasks = get_tasks_filter($con, $user_id, $filter);
    }

$content = include_template('main.php', [
    'projects' => $projects,
    'all_tasks' => $all_tasks,
    'project_tasks' => $project_tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'project_id' => $project_id,
    'error' => $error,
    'filter' => $filter,
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
]);
}

print($layout_content);
