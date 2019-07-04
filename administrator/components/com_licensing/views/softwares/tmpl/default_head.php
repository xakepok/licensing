<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th style="width: 1%;" class="hidden-phone">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th style="width: 5%;">
        <?php echo JHtml::_('grid.sort', 'JSTATUS', 's.state', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_SOFTWARE_HEAD_NAME', 's.name', $listDirn, $listOrder); ?>
    </th>
    <th class="center">
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_SOFTWARE_HEAD_COUNT', 's.count', $listDirn, $listOrder); ?>
    </th>
    <th class="center">
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_SOFTWARE_HEAD_COUNT_AVALIBLE', 's.countAvalible', $listDirn, $listOrder); ?>
    </th>
    <th class="center">
		<?php echo JText::_('COM_LICENSING_SOFTWARE_HEAD_COUNT_RESERV'); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_HEAD_NAME_DESC', 'l.name', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_START', 'l.dateStart', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_END', 'l.dateExpires', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 1%;">
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_CLAIMS_HEAD_ID', 's.id', $listDirn, $listOrder); ?>
    </th>
</tr>