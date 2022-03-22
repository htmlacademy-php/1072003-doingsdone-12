<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password', 'name'];

    $rules = [
        'email' => function($value) use ($con) {
            return validateEmail($value, $con);
        },
        'password' => function($value) {
            return validateLength($value, 6, 255);
        },
        'name' => function($value) {
            return validateLength($value, 3, 64);
        },
    ];

    $new_user = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT, 'name' => FILTER_DEFAULT], true);

    foreach ($new_user as $key => $value) {
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
        $password = password_hash($new_user['password'], PASSWORD_DEFAULT);

        $new_user = [getPostVal('email'), getPostVal('name'), $password];

        $res = add_new_user($con, $new_user);

        if(!$res) {
            exit('Ошибка базы данных');
        }

        header('Location: index.php');
        exit;
    }
}

$content = include_template('register-template.php', [
    'errors' => $errors,
    ]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Регистрация нового пользователя',
]);

print($layout_content);
