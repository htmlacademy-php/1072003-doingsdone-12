<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

$user_id = $_SESSION['user']['id'];
$projects = get_user_projects($con, $user_id);
$errors = [];

$projects_id = array_column($projects, 'id');
$projects_name = array_column($projects, 'name');

$all_tasks = get_user_tasks($con, $user_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['name'])) {
        $errors['name'] = 'Поле не заполнено';
    }

    if (in_array(($_POST['name']), $projects_name)) {
        $errors['name'] = 'Проект с таким названием уже существует';
    }

    $errors = array_filter($errors);

    if (!count($errors)) {
        $new_project = [getPostVal('name'), $user_id];

        $res = add_new_project($con, $new_project);

        if(!$res) {
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
