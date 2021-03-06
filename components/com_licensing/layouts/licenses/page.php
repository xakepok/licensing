<?php
defined('_JEXEC') or die;
extract($displayData);
?>
<form action="<?php echo JRoute::_('index.php?option=com_licensing&view=licenses'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="forPrint">
        <table class="table table-stripped">
            <thead>
                <tr>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_NAME', '`l`.`name`', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_LICENSING_LICENSES_LIC_TYPE'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_LICENSING_LICENSES_LIC_NUMBER'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_LICENSING_LICENSES_LIC_DOGOVOR'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_LICENSING_LICENSES_HEAD_FREEWARE_SHORT'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_LICENSING_LICENSES_HEAD_FILES_TEXT'); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_START', '`start`', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_EXP', '`Expire`', $listDirn, $listOrder); ?>
                    </th>
                </tr>
            </thead>
            <tdoby>
                <?php foreach ($licenses as $license): ?>
                <tr>
                    <td><?php echo $license['name'];?></td>
                    <td><?php echo $license['type'];?></td>
                    <td><?php echo $license['number'];?></td>
                    <td><?php echo $license['contract'];?></td>
                    <td><?php echo $license['freeware'];?></td>
                    <td><?php echo $license['files'];?></td>
                    <td><?php echo $license['dateStart'];?></td>
                    <td><?php echo $license['dateExpires'];?></td>
                </tr>
                <?php endforeach; ?>
            </tdoby>
        </table>
    </div>
    <input type="hidden" name="filter_order" value="<?php echo $this->escape($state->get('list.ordering')); ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($state->get('list.direction')); ?>" />
    <div class="clear" style="text-align: center"><?php echo $pagination->getListFooter(); ?></div>
</form>
<div><a href="javascript://" onclick="atoprint('forPrint');">Версия для печати</a></div>