<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<img src="<?= base_url() ?>/assets/images/logo-dark.png" alt="user" class="align-self-center">
<br>
<h4><?= lang('Auth.loginTitle') ?></h4>
<hr>
<div>

    <?= view('Myth\Auth\Views\_message_block') ?>

    <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>
        <?php if ($config->validFields === ['email']) : ?>
        <div class="form-group">
            <label for="login"><?= lang('Auth.email') ?></label>
            <input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                name="login" placeholder="<?= lang('Auth.email') ?>">
            <div class="invalid-feedback">
                <?= session('errors.login') ?>
            </div>
        </div>
        <?php else : ?>
        <div class="form-group">
            <label for="login"><?= lang('Auth.emailOrUsername') ?></label>
            <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                name="login" placeholder="<?= lang('Auth.emailOrUsername') ?>">
            <div class="invalid-feedback">
                <?= session('errors.login') ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="password"><?= lang('Auth.password') ?></label>
            <input type="password" name="password"
                class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                placeholder="<?= lang('Auth.password') ?>">
            <div class="invalid-feedback">
                <?= session('errors.password') ?>
            </div>
        </div>

        <?php if ($config->allowRemembering) : ?>
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked
                    <?php endif ?>>
                <?= lang('Auth.rememberMe') ?>
            </label>
        </div>
        <?php endif; ?>

        <br>
        <div class="text-end">
            <button type="submit" class="btn btn-primary"><?= lang('Auth.loginAction') ?></button>
        </div>
    </form>

    <hr>

    <?php if ($config->allowRegistration) : ?>
    <p><a href="<?= url_to('register') ?>"><?= lang('Auth.needAnAccount') ?></a></p>
    <?php endif; ?>
    <?php if ($config->activeResetter) : ?>
    <p><a href="<?= url_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a></p>
    <?php endif; ?>
</div>


<?= $this->endSection() ?>