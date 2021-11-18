<?php

require_once('helpers.php');
require_once('functions.php');
require_once('data.php');

$content = include_template('main.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    ]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => $title,
]);

print($layout_content);
