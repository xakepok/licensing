<?php
defined('_JEXEC') or die;

foreach ($this->items as $i => $keys) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.key.' . $item->id);
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $keys->id); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $keys->state, $i, 'keys.', $canChange); ?>
        </td>
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=key&layout=edit&id='.$keys->id);
            echo JHtml::link($link, $keys->text);
            ?>
        </td>
        <td>
            <?php echo $keys->software; ?>
        </td>
        <td class="center">
            <?php echo $keys->countAvalible; ?>
        </td>
        <td class="center">
            <?php echo $keys->countReserv; ?>
        </td>
        <td>
            <?php echo $keys->license; ?>
        </td>
        <td>
            <?php echo $keys->dat; ?>
        </td>
        <td>
            <?php echo $keys->id; ?>
        </td>
    </tr>
<?php endforeach; ?>