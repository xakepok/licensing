<?php
defined('_JEXEC') or die;

foreach ($this->items as $i => $keytype) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.keytype.' . $item->id);
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $keytype->id); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $keytype->state, $i, 'keytypes.', $canChange); ?>
        </td>
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=keytype&layout=edit&id='.$keytype->id);
            echo JHtml::link($link, $keytype->type);
            ?>
        </td>
        <td>
            <?php echo $keytype->id; ?>
        </td>
    </tr>
<?php endforeach; ?>