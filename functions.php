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
        $sql_add_task = 'INSERT INTO task (dt_add, status, user_id, title, file, dt_completion, project_id) VALUES (NOW(), 0, 2, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($con, $sql_add_task, $new_task);

        return mysqli_stmt_execute($stmt);
}
