<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $claim) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.claim.' . $item->id); ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $claim->id); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $claim->state, $i, 'claims.', $canChange); ?>
        </td>
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=claim&layout=edit&uid='.$claim->uid.'&id='.$claim->id);
            echo JHtml::link($link, $claim->empl_fio);
            ?>
        </td>
        <td>
            <?php echo $claim->structure; ?>
        </td>
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=orders&filter_claim='.$claim->id);
            echo JHtml::link($link, JText::_('COM_LICENSING_CLAIMS_HEAD_SOFTWARE'));
            ?>
        </td>
        <td>
            <?php echo $claim->dat; ?>
        </td>
        <td>
            <?php echo $claim->email; ?>
        </td>
        <td>
            <?php echo $claim->phone; ?>
        </td>
        <td>
            <?php echo $claim->manager; ?>
        </td>
        <td>
            <?php echo $claim->id; ?>
        </td>
    </tr>
<?php endforeach; ?>