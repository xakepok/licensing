<?php
defined('_JEXEC') or die;

foreach ($this->items as $i => $license) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.license.' . $item->id);
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $license->id); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $license->state, $i, 'licenses.', $canChange); ?>
        </td>
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=license&layout=edit&id='.$license->id);
            echo JHtml::link($link, $license->name);
            ?>
        </td>
        <td>
            <?php echo $license->number; ?>
        </td>
        <td>
            <?php echo $license->dogovor; ?>
        </td>
        <td>
            <?php echo ($license->freeware != 1) ? JText::sprintf('JNO') : JText::sprintf('JYES') ; ?>
        </td>
        <td>
            <?php echo $license->dat; ?>
        </td>
        <td>
            <?php echo $license->licenseType; ?>
        </td>
        <td>
            <?php echo $license->company; ?>
        </td>
        <td>
            <?php echo $license->id; ?>
        </td>
    </tr>
<?php endforeach; ?>