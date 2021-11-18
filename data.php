<?php

$show_complete_tasks = rand(0, 1);

$title = "Дела в порядке";

$categories = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

$tasks = [
    [
        "task" => "Собеседование в IT компании",
        "task_date" => "01.12.2021",
        "category" => "Работа",
        "completed" => false,
    ],
    [
        "task" => "Выполнить тестовое задание",
        "task_date" => "25.12.2021",
        "category" => "Работа",
        "completed" => false,
    ],
    [
        "task" => "Сделать задание первого раздела",
        "task_date" => "21.12.2021",
        "category" => "Учеба",
        "completed" => true,
    ],
    [
        "task" => "Встреча с другом",
        "task_date" => "18.11.2021",
        "category" => "Входящие",
        "completed" => false,
    ],
    [
        "task" => "Купить корм для кота",
        "task_date" => null,
        "category" => "Домашние дела",
        "completed" => false,
    ],
    [
        "task" => "Заказать пиццу",
        "task_date" => null,
        "category" => "Домашние дела",
        "completed" => false,
    ],

];
