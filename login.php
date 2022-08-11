<?php

require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

/**
 * @var mysqli $con
 */

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['email', 'password'];

    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $email = mysqli_real_escape_string($con, $form['email']);

    $user = get_user_data($con, $email);

    if (!count($errors) and $email !== $user['email']) {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (!count($errors) and $user) {
        if (!password_verify($form['password'], $user['password'])) {
            $errors['password'] = 'Неверный пароль';
        }
    }

    if (!count($errors)) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    }
}

$content = include_template('login-template.php', [
    'errors' => $errors,
    ]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Страница авторизации'
    ]);

print($layout_content);
