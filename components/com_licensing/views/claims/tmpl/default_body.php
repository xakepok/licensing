<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $claim) : ?>
    <tr class="row0">
        <td>
            <?php
            $Itemid = LicensingHelper::getItemid('claim');
            $link = JRoute::_("index.php?id={$claim['id']}&Itemid={$Itemid}");
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