<?php defined('_JEXEC') or die; ?>
<h5><?php echo JText::_('COM_LICENSING_CLAIM_INFO_SOFTWARE');?></h5>
<table class="software-order-table">
    <thead>
    <tr>
        <th><?php echo JText::_('COM_LICENSING_CLAIM_INFO_PRODUCT');?></th>
        <th><?php echo JText::_('COM_LICENSING_CLAIM_INFO_KEY');?></th>
        <th><?php echo JText::_('COM_LICENSING_CLAIM_INFO_COUNT');?></th>
        <th><?php echo JText::_('COM_LICENSING_CLAIM_INFO_EXPIRE');?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->item['software'] as $item) : ?>
        <tr>
            <td><?php echo $item['product'];?></td>
            <td><?php echo $item['key'];?></td>
            <td><?php echo $item['cnt'];?></td>
            <td><?php echo $item['dateExpires'];?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>