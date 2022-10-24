<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<img src="<?= base_url() ?>/assets/images/logo-dark.png" alt="user" class="align-self-center">
<br>
<h4><?= lang('Auth.register') ?></h4>
<hr>
<div>

    <?= view('Myth\Auth\Views\_message_block') ?>

    <form action="<?= url_to('register') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="email"><?= lang('Auth.email') ?></label>
            <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>"
                value="<?= old('email') ?>">
            <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
        </div>

        <div class="form-group">
            <label for="username"><?= lang('Auth.username') ?></label>
            <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>"
                name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>">
        </div>

        <div class="form-group">
            <label for="password"><?= lang('Auth.password') ?></label>
            <input type="password" name="password"
                class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
            <input type="password" name="pass_confirm"
                class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>"
                placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off">
        </div>

        <br>

        <button type="submit" class="btn btn-primary"><?= lang('Auth.register') ?></button>
    </form>


    <hr>

    <p><?= lang('Auth.alreadyRegistered') ?> <a href="<?= url_to('login') ?>"><?= lang('Auth.signIn') ?></a>
    </p>
</div>

<?= $this->endSection() ?>