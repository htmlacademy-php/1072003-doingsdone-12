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
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <input class="form__input <?= isset($errors['name']) ? 'form__input--error' : '' ?>" type="text" name="name" id="name" value="<?= getPostVal('name') ?>" placeholder="Введите название">
            <p class="form__message"><?= isset($errors['name']) ? $errors['name'] : '' ?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <select class="form__input form__input--select <?= isset($errors['project']) ? 'form__input--error' : '' ?>" name="project" id="project">
              <?php foreach ($projects as $project) : ?>
                <option value=<?=$project['id'] ?>
                <?= (int)getPostVal('project') === (int)$project['id'] ? "selected" : ""; ?>
                ><?=$project['name'] ?></option>
              <?php endforeach; ?>
            </select>
            <p class="form__message"><?= isset($errors['project']) ? $errors['project'] : '' ?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>
            <input class="form__input form__input--date <?= isset($errors['date']) ? 'form__input--error' : '' ?>" type="text" name="date" id="date" value="<?= getPostVal('date') ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <p class="form__message"><?= isset($errors['date']) ? $errors['date'] : '' ?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="file" id="file" value="<?= isset($_POST['file']) ? ($_POST['file']) : '' ?>">

              <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
              </label>
            </div>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
        <p class="form__message"><?= !empty($errors) ? 'Пожалуйста, исправьте ошибки в форме' : '' ?></p>
      </main>
    </div>
