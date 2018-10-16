<?php
defined('_JEXEC') or die;
?>
<h5>
    <?php echo JText::_('COM_LICENSING_SERVICE_SOFTWARE_DIFFERENT');?>
</h5>
<?php if (!empty($this->check)): ?>
    <?php foreach ($this->check as $item):?>
    <?php echo $item['name'];?>
    <ul>
         <li>
             <?php echo sprintf(JText::_('COM_LICENSING_SERVICE_SOFTWARE_DIFFERENT_ALL'), $item['all']);?>
         </li>
         <li>
             <?php echo sprintf(JText::_('COM_LICENSING_SERVICE_SOFTWARE_DIFFERENT_AVALIABLE'), $item['avaliable']);?>
         </li>
         <li>
             <?php echo sprintf(JText::_('COM_LICENSING_SERVICE_SOFTWARE_DIFFERENT_ORDER'), $item['order']);?>
         </li>
         <li>
             <?php echo sprintf(JText::_('COM_LICENSING_SERVICE_SOFTWARE_DIFFERENT_DIFF'), $item['diff']);?>
         </li>
    </ul>
    <?php endforeach;?>
<?php else: echo JText::_('COM_LICENSING_SERVICE_SOFTWARE_DIFFERENT_OK'); endif;?>
