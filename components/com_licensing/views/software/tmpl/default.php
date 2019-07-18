<?php
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;

defined('_JEXEC') or die;

HTMLHelper::_('script', 'com_licensing/script.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_licensing/style.css', array('version' => 'auto', 'relative' => true));
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));

?>
<form action="/licenses/software" method="post" name="adminForm" id="adminForm">
    <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));?>
    <div id="forPrint">
        <table class="table table-stripped">
            <thead>
            <tr>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_PROD_NAME', 's.name', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_LICENSING_SOFTWARE_HEAD_TIP', 's.tip', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JText::sprintf('COM_LICENSING_LICENSES_LIC_COUNT'); ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_NAME', 'l.name', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_NUMBER', 'l.number', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JText::sprintf('COM_LICENSING_LICENSES_LIC_DOGOVOR'); ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_START', 'l.dateStart', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_EXP', 'l.dateExpires', $listDirn, $listOrder); ?>
                </th>
            </tr>
            </thead>
            <tdoby>
                <?php foreach ($this->items as $soft): ?>
                    <tr>
                        <td><?php echo $soft['software'];?></td>
                        <td><?php echo $soft['tip'];?></td>
                        <td><?php echo $soft['cnt'];?></td>
                        <td><?php echo $soft['license'];?></td>
                        <td><?php echo $soft['number'];?></td>
                        <td><?php echo $soft['contract'];?></td>
                        <td><?php echo $soft['dateStart'];?></td>
                        <td><?php echo $soft['dateExpires'];?></td>
                    </tr>
                <?php endforeach; ?>
            </tdoby>
        </table>
    </div>
    <input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->state->get('list.direction')); ?>" />
    <div class="clear" style="text-align: center"><?php echo $this->pagination->getListFooter(); ?></div>
</form>
<div><a href="javascript://" onclick="atoprint('forPrint');">Версия для печати</a></div>
