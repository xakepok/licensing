<?php
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

HTMLHelper::_('script', 'com_licensing/script.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_licensing/style.css', array('version' => 'auto', 'relative' => true));

?>
<?php defined('_JEXEC') or die; ?>
<div id="j-main-container" class="span10">
    <?php
    foreach ($this->items as $structure => $software): ?>
    <h5>
        <?php echo $structure; ?>
    </h5>
    <ul>
        <?php foreach ($software as $product => $info): ?>
        <li>
            <?php echo sprintf(JText::_('COM_LICENSING_STAT_SOFTWARE_COUNT'), $product, $info['count']);?>
            <?php if ($info['expire'] !== null) echo sprintf(JText::_('COM_LICENSING_STAT_SOFTWARE_EXPIRE'), $info['expire']); ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endforeach; ?>
</div>

