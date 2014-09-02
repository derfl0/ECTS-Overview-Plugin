<? if ($edit): ?>
<form class="studip_form" method="post" action="<?= $controller->url_for('show/index/'.$overview->id) ?>">
    <? endif; ?>
    <table class="default">
        <caption>
            <? if ($edit): ?>
                <input type="text" name="title" required="true" value="<?= htmlReady($overview->title) ?>">
            <? else: ?>
                <?= htmlReady($overview->title) ?>
                <a href="<?= URLHelper::getLink('', array('edit' => 1)) ?>">
                    <?= Assets::img('icons/16/blue/admin.png') ?>
                </a>
            <? endif; ?>
        </caption>
        <thead>
            <tr>
                <th>
                    <?= _('Veranstaltung') ?>
                </th>
                <th>
                    <?= _('ECTS') ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <? foreach (array_filter($semester, 'count') as $name => $courses): ?>
                <tr>
                    <th>
                        <?= htmlReady($name) ?>
                    </th>
                    <th>
                        <?= array_sum($courses->findBy('active', 1)->pluck('ects')) ?>
                    </th>
                </tr>
                <? foreach ($courses as $course): ?>
                    <tr>
                        <td>
                            <? if ($edit): ?>
                            <input name="active[<?= $course->seminar_id ?>]" type="checkbox" <?= $course->active ? 'checked' : '' ?> value="1">
                            <? endif; ?>
                            <?= ObjectdisplayHelper::link($course->course) ?>
                        </td>
                        <td>
                            <?= $course->ects ?>
                        </td>
                    </tr>
                <? endforeach; ?>
            <? endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <? if ($edit): ?>
                    <td colspan="2">
                        <label>
                            <input name="autofill" type="checkbox" <?= $overview->autofill ? 'checked' : '' ?> value="1">
                            <?= _('Automatisch mit neuen Kursen befüllen') ?>
                        </label>
                        <?= Studip\Button::create(_('Speichern'), 'save') ?>
                    </td>
                <? else: ?>
                    <td>
                        <?= _('Summe') ?>
                    </td>
                    <td>
                        <?= $sum ?>
                    </td>
                <? endif; ?>
            </tr>
        </tfoot>
    </table>
    <? if ($edit): ?>
    </form>
<? endif; ?>