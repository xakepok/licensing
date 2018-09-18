<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%" class="hidden-phone">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th width="5%">
        <?php echo JHtml::_('grid.sort', 'JSTATUS', '`state`', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_COMPANY_NAME', '`name`', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JText::_('COM_LICENSING_COMPANY_WEBSITE'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_LICENSING_COMPANY_EMAIL'); ?>
    </th>
    <th>
		<?php echo JText::_('COM_LICENSING_COMPANY_PHONE'); ?>
    </th>
    <th width="1%">
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_CLAIMS_HEAD_ID', '`id`', $listDirn, $listOrder); ?>
    </th>
</tr>