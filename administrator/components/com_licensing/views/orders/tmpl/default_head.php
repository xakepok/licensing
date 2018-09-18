<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%" class="hidden-phone">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th>
        <?php echo JText::_('COM_LICENSING_SOFTWARE_HEAD_NAME'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_LICENSING_ORDER_COUNT'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_LICENSING_CLAIMS_HEAD_EMPLOYER'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_LICENSING_CLAIMS_HEAD_STRUCTURE'); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_CLAIMS_HEAD_DATE', '`c`.`dat`', $listDirn, $listOrder); ?>
    </th>
    <th width="1%">
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_CLAIMS_HEAD_ID', '`id`', $listDirn, $listOrder); ?>
    </th>
</tr>