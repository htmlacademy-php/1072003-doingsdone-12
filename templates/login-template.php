<div class="content">

  <section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="/login.php">Войти</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Вход на сайт</h2>

    <form class="form" action="/login.php" method="post" autocomplete="off">
      <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?= isset($errors['email']) ? 'form__input--error' : '' ?>" type="text" name="email" id="email" value="<?= htmlspecialchars(getPostVal('email')) ?>" placeholder="Введите e-mail">

        <p class="form__message"><?= $errors['email'] ?? '' ?></p>
      </div>

      <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?= isset($errors['password']) ? 'form__input--error' : '' ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">

        <p class="form__message"><?= $errors['password'] ?? '' ?></p>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Войти">
      </div>
    </form>

  </main>

</div>
