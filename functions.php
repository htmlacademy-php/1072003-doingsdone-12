<?php

/**
 * Подсчёт количества задач в проекте
 * @param array $tasks Параметры подключения к базе данных
 * @param int $project_id Идентификатор проекта
 *
 * @return int Количество задач
 */
function tasck_count($tasks, $project_id)
{
    $task_сount = 0;
    foreach ($tasks as $task) {
        if ($task['project_id'] == $project_id) {
            $task_сount++;
        }
    }
    return $task_сount;
}

/**
 * Подсчёт разницы между датой выполнения задачи и текущей датой
 * @param string $date Дата выполнения задачи
 *
 * @return bool Возвращает true если осталось меньше суток или false
 */
function check_time_completed($date)
{
    $dt_completion = date_create($date);
    $dt_now = date_create("now");
    $diff = date_diff($dt_completion, $dt_now);

    $days = $diff->format("%d");

    return !$days;
}

/**
 * Получение списка проектов пользователя
 * @param mysqli $con Параметры подключения к базе данных
 * @param int $user_id Идентификатор пользователя
 *
 * @return array Возвращает проекты пользователя
 */
function get_user_projects($con, $user_id)
{
    $sql_projects = 'SELECT * FROM project WHERE user_id = ' . $user_id;
    $result = mysqli_query($con, $sql_projects);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение списка задач пользователя
 * @param mysqli $con Параметры подключения к базе данных
 * @param int $user_id Идентификатор пользователя
 *
 * @return array Возвращает список задач пользователя
 */
function get_user_tasks($con, $user_id)
{
    $sql_tasks = 'SELECT * FROM task WHERE user_id = ' . $user_id;
    $result = mysqli_query($con, $sql_tasks);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение списка задач пользователя в выбранном проекте
 * @param mysqli $con Параметры подключения к базе данных
 * @param int $user_id Идентификатор пользователя
 * @param int $project_id Идентификатор проекта
 *
 * @return array Возвращает список задач пользователя в выбранном проекте
 */
function get_task_project($con, $user_id, $project_id)
{
    $sql_project_tasks = 'SELECT * FROM task WHERE user_id = ' . $user_id . ' AND project_id = ' . $project_id;
    $result = mysqli_query($con, $sql_project_tasks);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получение списка задач пользователя в выбранном проекте
 * @param mysqli $con Параметры подключения к базе данных
 * @param int $user_id Идентификатор пользователя
 * @param string $filter Выбранный фильтр
 *
 * @return array Возвращает список задач пользователя в выбранном проекте
 */
function get_tasks_filter($con, $user_id, $filter)
{
    $filterSql = "";
    if ($filter === 'today') {
        $filterSql = "dt_completion = CURDATE() OR dt_completion IS NULL";
    } elseif ($filter === 'tomorrow') {
        $filterSql = "dt_completion = ADDDATE(CURDATE(),INTERVAL 1 DAY)";
    } elseif ($filter === 'expired') {
        $filterSql = "dt_completion < CURDATE()";
    } elseif ($filter === '' || $filter === 'all') {
        $filterSql = '1';
    }

    $sql_filter_tasks = 'SELECT * FROM task WHERE user_id = ' . $user_id . ' AND (' . $filterSql . ')';
    $result = mysqli_query($con, $sql_filter_tasks);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Добавление новой задачи в базу данных
 * @param mysqli $con Параметры подключения к базе данных
 * @param array $new_task Массив данных для добавления новой задачи
 * @param int $project_id Идентификатор проекта
 *
 * @return bool в случае успешного выполнения возвращает true, с помощью подготовленного выражения добавляет новую задачу пользователя в базу данных
 */
function add_new_task($con, $new_task)
{
    $sql_add_task = 'INSERT INTO task (status, user_id, title, file, dt_completion, project_id)
                        VALUES (0, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql_add_task, $new_task);

    return mysqli_stmt_execute($stmt);
}

/**
 * Добавление нового пользователя в базу данных
 * @param mysqli $con Параметры подключения к базе данных
 * @param array $new_user Массив данных для добавления нового пользователя
 *
 * @return bool в случае успешного выполнения возвращает true, с помощью подготовленного выражения добавляет нового пользователя в базу данных
 */

function add_new_user($con, $new_user)
{
    $sql_add_user = 'INSERT INTO user (email, name, password)
                    VALUES (?, ?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql_add_user, $new_user);

    return mysqli_stmt_execute($stmt);
}

/**
 * Добавление нового проекта в базу данных
 * @param mysqli $con Параметры подключения к базе данных
 * @param array $new_project Массив данных для добавления нового проекта пользователя
 *
 * @return bool в случае успешного выполнения возвращает true, с помощью подготовленного выражения добавляет новый проект в базу данных
 */
function add_new_project($con, $new_project)
{
    $sql_add_project = 'INSERT INTO project (name, user_id) VALUES (?, ?)';

    $stmt = db_get_prepare_stmt($con, $sql_add_project, $new_project);

    return mysqli_stmt_execute($stmt);
}

/**
 * Возвращает данные пользователя по его email
 * @param mysqli $con Параметры подключения к базе данных
 * @param string $email Строка с email переданным через форму
 *
 * @return array Возвращает массив с данными пользователя
 */
function get_user_data($con, $email)
{
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    return mysqli_fetch_array($res, MYSQLI_ASSOC);
}

/**
 * Получение списка задач по поисковому запросу
 * @param mysqli $con Параметры подключения к базе данных
 * @param array $search Значение из строки запроса
 * @param int $user_id Идентификатор пользователя
 *
 * @return array Возвращает список задач пользователя
 */
function search_task($con, $search, $user_id)
{
    $sql_search_task = "SELECT * FROM task WHERE MATCH(title) AGAINST (?) AND user_id = $user_id";
    $stmt = db_get_prepare_stmt($con, $sql_search_task, $search);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Устанавливает или снимает флаг выполнения задачи
 * @param mysqli $con Параметры подключения к базе данных
 * @param int $task_id Идентификатор задачи
 *
 */
function set_task_status($con, $task_id)
{
    $sql_task_status = 'SELECT status FROM task WHERE id = ' . $task_id;
    $res = mysqli_query($con, $sql_task_status);
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);

    if ($result['status'] === "0") {
        $task_status = 1;
    } elseif ($result['status'] === "1") {
        $task_status = 0;
    }

    $sql_status_update = 'UPDATE task SET status = ' . $task_status . ' WHERE id = ' . $task_id;

    mysqli_query($con, $sql_status_update);
}

/**
 * Возвращает массив задач срок выполнения которых истекает в текущую дату
 * @param mysqli $con Параметры подключения к базе данных
 *
 * @return array Возвращает массив с задачами пользователей на сегодня
 */
function get_users_today_tasks($con)
{
    $sql_today_tasks = 'SELECT user.id, user.name, user.email, task.title, task.dt_completion
    FROM user JOIN task ON task.user_id = user.id
    WHERE task.status = 0 AND task.dt_completion = CURRENT_DATE()';

    $stmt = db_get_prepare_stmt($con, $sql_today_tasks);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}
