<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

$user_id = 2;

$sql_projects = "SELECT * FROM project WHERE user_id = $user_id";
$result = mysqli_query($con, $sql_projects);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$projects) {
    exit('Ошибка базы данных');
}

$projects_id = array_column($projects, 'id');

$sql_tasks = 'SELECT * FROM task WHERE user_id = ' . $user_id;
$result = mysqli_query($con, $sql_tasks);
$all_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$all_tasks) {
    exit('Ошибка базы данных');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['name', 'project'];
    $errors = [];

    $rules = [
        'name' => function($value) {
            return validateLength($value, 10, 128);
        },
        'project' => function($value) use ($projects_id) {
            return validateProject($value, $projects_id);
        },
        'date' => function($value) {
            return validateDate($value);
        }
    ];

    $new_task = filter_input_array(INPUT_POST, ['name' => FILTER_DEFAULT, 'project' => FILTER_DEFAULT, 'date' => FILTER_DEFAULT], true);

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


    if (count($errors)) {
        $content = include_template('add-template.php', ['errors' => $errors, 'projects' => $projects, 'all_tasks' => $all_tasks, 'project_id' => $project_id]);
    } else {
        if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {

            $tmp_name = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $filename = uniqid() . $filename;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $task_file = 'uploads/' . $filename;
        } else {
            $task_file = NULL;
        }


        if(empty(getPostVal('date'))) {
            $dt_completion = NULL;
        } else {
            $dt_completion = getPostVal('date');
        }

        $new_task = [getPostVal('name'), $task_file, $dt_completion, getPostVal('project')];

        $sql_add_task = 'INSERT INTO task (dt_add, status, user_id, title, file, dt_completion, project_id) VALUES (NOW(), 0, 2, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($con, $sql_add_task, $new_task);
        $res = mysqli_stmt_execute($stmt);

        if(!$res) {
            exit('Ошибка базы данных');
        }

        header('Location: index.php');
        exit;
    }

} else {

    $content = include_template('add-template.php', [
        'projects' => $projects,
        'all_tasks' => $all_tasks,
        'projects_id' => $projects_id
        ]);
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Добавить задачу',
]);

print($layout_content);
