<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

/**
 * @var mysqli $con
 */

$user_id = $_SESSION['user']['id'] ?? '';
$errors = [];
$new_project_name = filter_input(INPUT_POST, 'name');

if (empty($user_id)) {
    header('Location: index.php');
    exit;
}

$projects = get_user_projects($con, $user_id);
$all_tasks = get_user_tasks($con, $user_id);
$projects_id = array_column($projects, 'id');
$projects_name = array_column($projects, 'name');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($new_project_name)) {
        $errors['name'] = 'Поле не заполнено';
    }

    if (in_array($new_project_name, $projects_name)) {
        $errors['name'] = 'Проект с таким названием уже существует';
    }

    $errors = array_filter($errors);

    if (!count($errors)) {
        $new_project = [getPostVal('name'), $user_id];

        $res = add_new_project($con, $new_project);

        if (!$res) {
            exit('Ошибка базы данных');
        }

        header('Location: index.php');
        exit;
    }
}

$content = include_template('project-template.php', [
    'errors' => $errors,
    'projects' => $projects,
    'all_tasks' => $all_tasks,
    'projects_id' => $projects_id
    ]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Добавить проект',
]);

print($layout_content);
