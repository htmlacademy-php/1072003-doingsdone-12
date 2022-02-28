<div class="content">
  <section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach($projects as $project): ?>
                <li class="main-navigation__list-item <?= $project_id === $project['id'] ? 'main-navigation__list-item--active' : '' ?>">
                    <a class="main-navigation__list-item-link" href="
                                ?project_id=<?=$project['id']?>"><?= htmlspecialchars($project['name']) ?></a>
                    <span class="main-navigation__list-item-count"><?= tasck_count($all_tasks, $project['id']) ?>
                    </span>
                </li>
            <?php endforeach; ?>
            </ul>
        </nav>

    <a class="button button--transparent button--plus content__side-button" href="project.php">Добавить проект</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form"  action="project.php" method="post" autocomplete="off">
      <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <input class="form__input" type="text" name="name" id="project_name" value="<?= getPostVal('name') ?>" placeholder="Введите название проекта">
        <p class="form__message"><?= isset($errors['name']) ? $errors['name'] : '' ?></p>
      </div>

      <div class="form__row form__row--controls">
        <p class="error-message"><?= !empty($errors) ? 'Пожалуйста, исправьте ошибки в форме' : '' ?></p>
        <input class="button" type="submit" name="" value="Добавить">
      </div>
    </form>
  </main>
</div>
