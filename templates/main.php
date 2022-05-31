<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($projects as $project): ?>
                <li class="main-navigation__list-item <?= $project_id === $project['id'] ? 'main-navigation__list-item--active' : '' ?>">
                    <a class="main-navigation__list-item-link" href="
                                ?project_id=<?=$project['id']?>"><?= htmlspecialchars($project['name']) ?></a>
                    <span class="main-navigation__list-item-count"><?= tasck_count($all_tasks, $project['id']) ?>
                    </span>
                </li>
            <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button"
           href="project.php" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="get" autocomplete="off">
            <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="search_submit" value="Искать">
            <p class="form__message"><?= isset($error) ? $error : '' ?></p>
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/index.php?filter=all" class="tasks-switch__item <?= $filter == '' || $filter == 'all' ? 'tasks-switch__item--active' : '' ?> ">Все задачи</a>
                <a href="/index.php?filter=today" class="tasks-switch__item <?= $filter == 'today' ? 'tasks-switch__item--active' : '' ?> ">Повестка дня</a>
                <a href="/index.php?filter=tomorrow" class="tasks-switch__item <?= $filter == 'tomorrow' ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
                <a href="/index.php?filter=expired" class="tasks-switch__item <?= $filter == 'expired' ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
            </nav>

            <label class="checkbox">
                <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= $show_complete_tasks ? "checked" : "" ?>>
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">

            <?php foreach ($project_tasks as $key => $task): ?>
            <?php if ($task['status'] and !$show_complete_tasks) {
    continue;
} ?>
                <tr class="tasks__item <?=$task['status'] ? 'task--completed' : ''?> <?= (check_time_completed($task['dt_completion']) and ! $task['status'] and $task['dt_completion']) ? 'task--important' : '' ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?= $task['id'] ?>" <?= $task['status'] === "1" ? "checked" : "" ?>>
                            <span class="checkbox__text"><?= htmlspecialchars($task['title']) ?></span>
                        </label>
                    </td>
                    <td class="task__file">
                        <a class="<?=$task['file'] ? 'download-link' : ''?>" href="<?= htmlspecialchars($task['file']) ?>"> <?= substr($task['file'], 21); ?> </a>
                    </td>

                    <td class="task__date"><?= htmlspecialchars($task['dt_completion']) ?></td>
                </tr>

            <?php endforeach; ?>

        </table>
    </main>
</div>
