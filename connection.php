<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

$con = mysqli_connect("localhost", "root", "root", "doingsdone");

if (!$con) {
    $error = mysqli_connect_error();
    exit('Ошибка базы данных' . $error);
};

mysqli_set_charset($con, "utf8");
