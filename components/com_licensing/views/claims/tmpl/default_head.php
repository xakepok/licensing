<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_CLAIMS_HEAD_DATE', '`dat`', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JText::_('COM_LICENSING_CLAIMS_HEAD_MANAGER'); ?>
    </th>
    <th>
		<?php echo JText::_('COM_LICENSING_CLAIMS_HEAD_STATUS'); ?>
    </th>
    <th width="1%">
        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_CLAIMS_HEAD_ID', '`id`', $listDirn, $listOrder); ?>
    </th>
</tr>