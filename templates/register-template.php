 <div class="content">
        <section class="content__side">
          <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

          <a class="button button--transparent content__side-button" href="login.php">Войти</a>
        </section>

        <main class="content__main">
          <h2 class="content__main-heading">Регистрация аккаунта</h2>

          <form class="form" action="register.php" method="post" autocomplete="off">
            <div class="form__row">
              <label class="form__label" for="email">E-mail <sup>*</sup></label>

              <input class="form__input <?= isset($errors['email']) ? 'form__input--error' : '' ?>" type="text" name="email" id="email" value="<?= getPostVal('email') ?>" placeholder="Введите e-mail">

              <p class="form__message"><?= isset($errors['email']) ? $errors['email'] : '' ?></p>
            </div>

            <div class="form__row">
              <label class="form__label" for="password">Пароль <sup>*</sup></label>

              <input class="form__input <?= isset($errors['password']) ? 'form__input--error' : '' ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">

              <p class="form__message"><?= isset($errors['password']) ? $errors['password'] : '' ?></p>
            </div>

            <div class="form__row">
              <label class="form__label" for="name">Имя <sup>*</sup></label>

              <input class="form__input <?= isset($errors['name']) ? 'form__input--error' : '' ?>" type="text" name="name" id="name" value="<?= getPostVal('name') ?>" placeholder="Введите имя">

              <p class="form__message"><?= isset($errors['name']) ? $errors['name'] : '' ?></p>
            </div>

            <div class="form__row form__row--controls">
              <p class="error-message"><?= !empty($errors) ? 'Пожалуйста, исправьте ошибки в форме' : '' ?></p>

              <input class="button" type="submit" name="" value="Зарегистрироваться">
            </div>
          </form>
        </main>
      </div>
