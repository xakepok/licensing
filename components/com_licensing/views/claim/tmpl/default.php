<?php defined('_JEXEC') or die; ?>
<div id="j-main-container" class="span10">
    <?php if (empty($this->item)): ?>
    <div>
        <?php echo $this->loadTemplate('error'); return;?>
    </div>
    <?php endif; ?>
    <div>
        <h4><?php echo JText::_('COM_LICENSING_CLAIM_INFO');?></h4>
    </div>
    <div class="claim-info">
        <?php echo $this->loadTemplate('info');?>
    </div>
    <div style="width: 700px; float: left;">
        <?php if ($this->item['status']['code'] == '1') echo $this->loadTemplate('software');?>
    </div>
    <div style="clear: left;"></div>
</div>
