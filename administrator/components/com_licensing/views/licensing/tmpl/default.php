<?php
defined('_JEXEC') or die;
?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
    <div style="max-height: 400px; width: 300px; overflow: auto;">
        <?php echo $this->loadTemplate('check'); ?>
    </div>
</div>
