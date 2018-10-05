<?php
defined('_JEXEC') or die;
foreach ($this->items as $i => $software) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.software.' . $item->id);
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $software->id); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $software->state, $i, 'softwares.', $canChange); ?>
        </td>
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=software&layout=edit&id='.$software->id);
            echo JHtml::link($link, $software->name);
            ?>
        </td>
        <td class="center">
            <?php echo $software->count; ?>
        </td>
        <td class="center">
            <?php echo $software->countAvalible; ?>
        </td>
        <td class="center">
            <?php echo ($this->reserv[$software->id] != null) ? $this->reserv[$software->id] : 0 ; ?>
        </td>
        <td>
            <?php echo $software->license; ?>
        </td>
        <td>
            <?php echo $software->dat; ?>
        </td>
        <td>
            <?php echo $software->id; ?>
        </td>
    </tr>
<?php endforeach; ?>