<?php

session_start();

$con = mysqli_connect("localhost", "root", "root", "doingsdone");

if (!$con) {
    $error = mysqli_connect_error();
    exit('Ошибка базы данных' . $error);
};

mysqli_set_charset($con, "utf8");
