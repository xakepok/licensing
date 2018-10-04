<?php defined('_JEXEC') or die; ?>
<div id="j-main-container" class="span10">
    <?php if (empty($this->item)): ?>
    <div>
        <?php echo $this->loadTemplate('error'); return;?>
    </div>
    <?php endif; ?>
    <div>
        <h2><?php echo $this->item['product'];?></h2>
    </div>
    <div>
        <h5><?php echo JText::_('COM_LICENSING_LICENSES_LIC_NAME'), ": ", $this->item['license'];?></h5>
    </div>
    <div>
        <h5><?php echo JText::_('COM_LICENSING_LICENSES_LIC_NUMBER'), ": ", $this->item['number'];?></h5>
    </div>
    <div>
        <h5><?php echo JText::_('COM_LICENSING_LICENSES_LIC_DOGOVOR'), ": ", $this->item['dogovor'];?></h5>
    </div>
    <div>
        <h5><?php echo JText::_('COM_LICENSING_SOFTWARE_BYIED'), ": ", $this->item['buy'];?></h5>
    </div>
    <div>
        <h5><?php echo JText::_('COM_LICENSING_SOFTWARE_AVALIABLE'), ": ", $this->item['cnt'];?></h5>
    </div>
    <div>
        <h5><?php echo $this->item['dates'];?></h5>
    </div>
    <div>
        <?php echo $this->item['description'];?>
    </div>
</div>
