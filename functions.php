<?php

function tasck_count ($tasks, $project_id) {
    $task_сount = 0;
    foreach ($tasks as $task) {
        if($task['project_id'] == $project_id) {
            $task_сount++;
        }
    }
    return $task_сount;
};

function check_time_completed ($date) {
    $dt_completion = date_create($date);
    $dt_now = date_create("now");
    $diff = date_diff($dt_completion, $dt_now);

    $days = $diff->format("%d");

    return !$days;
};

/**
 * Получение списка проектов пользователя
 * @param $con Параметры подключения к базе данных
 * @param $user_id Идентификатор пользователя
 *
 * @return array Возвращает проекты пользователя
 */
function get_user_projects ($con, $user_id) {
    $sql_projects = 'SELECT * FROM project WHERE user_id = ' . $user_id;
    $result = mysqli_query($con, $sql_projects);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение списка задач пользователя
 * @param $con Параметры подключения к базе данных
 * @param $user_id Идентификатор пользователя
 *
 * @return array Возвращает список задач пользователя
 */
function get_user_tasks ($con, $user_id) {
    $sql_tasks = 'SELECT * FROM task WHERE user_id = ' . $user_id;
    $result = mysqli_query($con, $sql_tasks);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение списка задач пользователя в выбранном проекте
 * @param $con Параметры подключения к базе данных
 * @param $user_id Идентификатор пользователя
 * @param $project_id Идентификатор проекта
 *
 * @return array Возвращает список задач пользователя в выбранном проекте
 */
function get_task_project ($con, $user_id, $project_id) {
    $sql_project_tasks = 'SELECT * FROM task WHERE user_id = ' . $user_id . ' AND project_id = ' . $project_id;
    $result = mysqli_query($con, $sql_project_tasks);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Добавление новой задачи в базу данных
 * @param $con Параметры подключения к базе данных
 * @param $new_task Массив данных для добавления новой задачи
 * @param $project_id Идентификатор проекта
 *
 * @return С помощью подготовленного выражения добавляет новую задачу пользователя в базу данных
 */
function add_new_task ($con, $new_task) {
        $sql_add_task = 'INSERT INTO task (dt_add, status, user_id, title, file, dt_completion, project_id)
                        VALUES (NOW(), 0, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($con, $sql_add_task, $new_task);

        return mysqli_stmt_execute($stmt);
}

/**
 * Добавление нового пользователя в базу данных
 * @param $con Параметры подключения к базе данных
 * @param $new_user Массив данных для добавления нового пользователя
 *
 * @return С помощью подготовленного выражения добавляет нового пользователя в базу данных
 */

function add_new_user ($con, $new_user) {
    $sql_add_user = 'INSERT INTO user (dt_add, email, name, password)
                    VALUES (NOW(), ?, ?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql_add_user, $new_user);

    return mysqli_stmt_execute($stmt);
}

/**
 * Добавление нового проекта в базу данных
 * @param $con Параметры подключения к базе данных
 * @param $new_project Массив данных для добавления нового проекта пользователя
 *
 * @return С помощью подготовленного выражения добавляет новый проект в базу данных
 */
function add_new_project ($con, $new_project) {
    $sql_add_project = 'INSERT INTO project (name, user_id) VALUES (?, ?)';

    $stmt = db_get_prepare_stmt($con, $sql_add_project, $new_project);

    return mysqli_stmt_execute($stmt);
}

/**
 * Возвращает данные пользователя по его email
 * @param $con Параметры подключения к базе данных
 * @param $email Строка с email переданным через форму
 *
 * @return Возвращает массив с данными пользователя
 */
function get_user_data ($con, $email) {
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    return mysqli_fetch_array($res, MYSQLI_ASSOC);
}

function search_task($con, $search) {
    $sql_search_task = 'SELECT * FROM task WHERE MATCH(title) AGAINST (?)';
    $stmt = db_get_prepare_stmt($con, $sql_search_task, $search);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function set_task_status ($con) {
        $sql_task_status = 'SELECT status FROM task WHERE id = ' . $_GET['task_id'];
        $res = mysqli_query($con, $sql_task_status);
        $result = mysqli_fetch_array($res, MYSQLI_ASSOC);

        if($result['status'] === "0") {
            $task_status = 1;
        } elseif ($result['status'] === "1") {
            $task_status = 0;
        }

        $sql_status_update = 'UPDATE task SET status = ' . $task_status . ' WHERE id = ' . $_GET['task_id'];

        mysqli_query($con, $sql_status_update);
}
