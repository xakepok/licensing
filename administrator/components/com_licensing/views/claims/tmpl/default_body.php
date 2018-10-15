<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $item) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.claim.' . $item['id']); ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $item['state'], $i, 'claims.', $canChange); ?>
        </td>
        <td>
            <?php echo $item['employer']; ?>
        </td>
        <td>
            <?php echo $item['structure']; ?>
        </td>
        <td>
            <?php echo $item['soft']; ?>
        </td>
        <td>
            <?php echo $item['dat']; ?>
        </td>
        <td>
            <?php echo $item['email']; ?>
        </td>
        <td>
            <?php echo $item['phone']; ?>
        </td>
        <td>
            <?php echo $item['manager']; ?>
        </td>
        <td>
            <?php echo $item['status']; ?>
        </td>
        <td>
            <?php echo $item['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>