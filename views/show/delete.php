<form class="studip_form" method="post" action="<?= $controller->url_for('show/delete/' . $overview->id) ?>">
    <?= CSRFProtection::tokenTag() ?>
    <p><?= sprintf(_('Die Statistik %s wirklich löschen?'), htmlReady($overview->title)) ?></p>
    <?= Studip\Button::create(_('Löschen'), 'delete') ?>
    <?= Studip\Button::create(_('Abbrechen'), 'abort'); ?>
</form>