<?php
defined('_JEXEC') or die;
?>
<h5>
    <?php echo JText::_('COM_LICENSING_SERVICE_POP_SOFTWARE');?>
</h5>
<?php if (!empty($this->populate)): ?>
    <ul>
    <?php foreach ($this->populate as $product => $cnt):?>
        <li>
            <?php echo sprintf(JText::_('COM_LICENSING_SERVICE_POP_SOFTWARE_CNT'), $product, $cnt);?>
        </li>
    <?php endforeach;?>
    </ul>
<?php else: echo JText::_('COM_LICENSING_SERVICE_SOFTWARE_DIFFERENT_OK'); endif;?>
