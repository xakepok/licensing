<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $order) : ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $order->id); ?>
        </td>
        <td>
            <?php
            if ($order->state == '0')
            {
                $link = JRoute::_('index.php?option=com_licensing&view=order&layout=edit&id='.$order->id.'&claimID='.$order->claimID);
                echo JHtml::link($link, $order->product);
            }
            else
            {
                echo $order->product;
            }
            ?>
        </td>
        <td>
            <?php echo $order->cnt; ?>
        </td>
        <td>
            <?php echo $order->countAvalible; ?>
        </td>
        <td>
            <?php echo $order->empl_fio; ?>
        </td>
        <td>
            <?php echo $order->structure; ?>
        </td>
        <td>
            <?php echo $order->dat; ?>
        </td>
        <td>
            <?php echo $order->id; ?>
        </td>
    </tr>
<?php endforeach; ?>