<form class="studip_form" method="post" action="<?= $controller->url_for('show/delete/' . $overview->id) ?>">
    <?= CSRFProtection::tokenTag() ?>
    <p><?= sprintf(_('Die Statistik %s wirklich l�schen?'), htmlReady($overview->title)) ?></p>
    <?= Studip\Button::create(_('L�schen'), 'delete') ?>
    <?= Studip\Button::create(_('Abbrechen'), 'abort'); ?>
</form>