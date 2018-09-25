<?php defined('_JEXEC') or die; ?>
<h5><?php echo JText::_('COM_LICENSING_CLAIM_INFO_ABOUT');?></h5>
<table>
    <tr>
        <td><?php echo JText::_('COM_LICENSING_CLAIM_INFO_ID');?></td>
        <td><?php echo $this->item['id'];?></td>
    </tr>
    <tr>
        <td><?php echo JText::_('COM_LICENSING_CLAIM_INFO_AUTHOR');?></td>
        <td><?php echo $this->item['empl_fio'];?></td>
    </tr>
    <tr>
        <td><?php echo JText::_('COM_LICENSING_CLAIM_INFO_DATE');?></td>
        <td><?php echo $this->item['dat'];?></td>
    </tr>
    <tr>
        <td><?php echo JText::_('COM_LICENSING_CLAIM_INFO_EMAIL');?></td>
        <td><?php echo $this->item['email'];?></td>
    </tr>
    <tr>
        <td><?php echo JText::_('COM_LICENSING_CLAIM_INFO_PHONE');?></td>
        <td><?php echo $this->item['phone'];?></td>
    </tr>
    <tr>
        <td><?php echo JText::_('COM_LICENSING_CLAIM_INFO_MANAGER');?></td>
        <td><?php echo $this->item['manager'];?></td>
    </tr>
    <tr>
        <td><?php echo JText::_('COM_LICENSING_CLAIM_INFO_STATUS');?></td>
        <td><?php echo $this->item['status']['text'];?></td>
    </tr>
</table>