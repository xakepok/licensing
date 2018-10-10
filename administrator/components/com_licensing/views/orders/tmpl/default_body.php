<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $order) : ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $order['id']); ?>
        </td>
        <td>
            <?php echo $order['product'];?>
        </td>
        <td>
            <?php echo $order['cnt']['need']; ?>
        </td>
        <td>
            <?php echo $order['cnt']['all']; ?>
        </td>
        <td>
            <?php echo $order['employer']; ?>
        </td>
        <td>
            <?php echo $order['structure']; ?>
        </td>
        <td>
            <?php echo $order['dat']; ?>
        </td>
        <td>
            <?php echo $order['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>