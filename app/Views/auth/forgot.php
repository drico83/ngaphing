<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<img src="<?= base_url() ?>/assets/images/logo-dark.png" alt="user" class="align-self-center">
<br>
<h4><?= lang('Auth.forgotPassword') ?></h4>
<hr>
<div>

    <?= view('Myth\Auth\Views\_message_block') ?>

    <p><?= lang('Auth.enterEmailForInstructions') ?></p>

    <form action="<?= url_to('forgot') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="email"><?= lang('Auth.emailAddress') ?></label>
            <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>">
            <div class="invalid-feedback">
                <?= session('errors.email') ?>
            </div>
        </div>

        <br>

        <button type="submit" class="btn btn-primary"><?= lang('Auth.sendInstructions') ?></button>
    </form>

</div>


<?= $this->endSection() ?>