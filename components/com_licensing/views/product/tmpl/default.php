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
        <?php echo $this->item['cnt'];?>
    </div>
    <div>
        <?php echo $this->item['description'];?>
    </div>
</div>
