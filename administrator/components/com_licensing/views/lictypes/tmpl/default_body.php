<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $lictype) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.lictype.' . $item->id); ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $lictype->id); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $lictype->state, $i, 'lictypes.', $canChange); ?>
        </td>
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=lictype&layout=edit&id='.$lictype->id);
            echo JHtml::link($link, $lictype->type);
            ?>
        </td>
        <td>
            <?php echo $lictype->company; ?>
        </td>
        <td>
            <?php echo $lictype->id; ?>
        </td>
    </tr>
<?php endforeach; ?>