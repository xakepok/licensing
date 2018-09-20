<?php
defined('_JEXEC') or die;
extract($displayData);
?>
<form action="<?php echo JRoute::_('index.php?option=com_licensing&view=software'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="forPrint">
        <table style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_PROD_NAME', '`software`', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_LICENSING_LICENSES_LIC_COUNT'); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_NAME', '`license`', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_LICENSING_LICENSES_LIC_NUMBER', '`number`', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_LICENSING_LICENSES_LIC_DOGOVOR'); ?>
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
                <?php foreach ($software as $soft): ?>
                <tr>
                    <td style="border: 1px solid black;"><?php echo $soft['software'];?></td>
                    <td style="border: 1px solid black;"><?php echo $soft['cnt'];?></td>
                    <td style="border: 1px solid black;"><?php echo $soft['license'];?></td>
                    <td style="border: 1px solid black;"><?php echo $soft['number'];?></td>
                    <td style="border: 1px solid black;"><?php echo $soft['contract'];?></td>
                    <td style="border: 1px solid black;"><?php echo $soft['dateStart'];?></td>
                    <td style="border: 1px solid black;"><?php echo $soft['dateExpires'];?></td>
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