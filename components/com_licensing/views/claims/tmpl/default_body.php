<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $claim) : ?>
    <tr class="row0">
        <td>
            <?php $link = JRoute::_('index.php?option=com_licensing&view=claim&id='.$claim['id']);
            echo JHtml::link($link, $claim['dat']);
            ?>
        </td>
        <td>
            <?php echo $claim['manager']; ?>
        </td>
        <td>
            <?php echo $claim['status']; ?>
        </td>
        <td>
            <?php echo $claim['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>