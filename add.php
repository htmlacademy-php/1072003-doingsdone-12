<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

/**
 * @var mysqli $con
 */

$user_id = $_SESSION['user']['id'] ?? '';
$errors = [];

if (empty($user_id)) {
    header('Location: index.php');
    exit;
}

$projects = get_user_projects($con, $user_id);

if (!$projects) {
    exit('Ошибка: для добавления новой задачи необходимо создать проект');
}

$projects_id = array_column($projects, 'id');
$all_tasks = get_user_tasks($con, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['name', 'project'];

    $rules = [
        'name' => function ($value) {
            return validateLength($value, 3, 128);
        },
        'project' => function ($value) use ($projects_id) {
            return validateProject($value, $projects_id);
        },
        'date' => function ($value) {
            return validateDate($value);
        }
    ];

    $new_task = filter_input_array(
        INPUT_POST,
        [
            'name' => FILTER_DEFAULT,
            'project' => FILTER_DEFAULT,
            'date' => FILTER_DEFAULT
        ]
    );

    foreach ($new_task as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле надо заполнить";
        }
    }

    $errors = array_filter($errors);


    if (!count($errors)) {
        if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
            $tmp_name = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $filename = uniqid() . $filename;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $task_file = 'uploads/' . $filename;
        } else {
            $task_file = null;
        }


        if (!empty(getPostVal('date'))) {
            $dt_completion = getPostVal('date');
        } else {
            $dt_completion = null;
        }

        $new_task = [
            $user_id,
            getPostVal('name'),
            $task_file,
            $dt_completion,
            getPostVal('project')
        ];

        $res = add_new_task($con, $new_task);

        if (!$res) {
            exit('Ошибка базы данных');
        }

        header('Location: index.php');
        exit;
    }
}

$content = include_template('add-template.php', [
    'errors' => $errors,
    'projects' => $projects,
    'all_tasks' => $all_tasks
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Добавить задачу',
]);

print($layout_content);
