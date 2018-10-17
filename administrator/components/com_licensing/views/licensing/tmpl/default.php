<?php
defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('script', 'com_licensing/script.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_licensing/style.css', array('version' => 'auto', 'relative' => true));
?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
    <div class="service-stat">
        <div>
            <?php echo $this->loadTemplate('populate'); ?>
        </div>
        <div>
            <?php echo $this->loadTemplate('check'); ?>
        </div>
        <div>

        </div>
    </div>
    <div class="clr">

    </div>
</div>
