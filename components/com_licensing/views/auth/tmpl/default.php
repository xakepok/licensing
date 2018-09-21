<?php
use Joomla\CMS\HTML\HTMLHelper;
defined('_JEXEC') or die;
HTMLHelper::_('script', 'com_licensing/script.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_licensing/style.css', array('version' => 'auto', 'relative' => true));
var_dump($this->user);