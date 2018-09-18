<?php
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;

defined('_JEXEC') or die;

HTMLHelper::_('script', 'com_licensing/script.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_licensing/style.css', array('version' => 'auto', 'relative' => true));

$data['listOrder']    = $this->escape($this->state->get('list.ordering'));
$data['listDirn']    = $this->escape($this->state->get('list.direction'));

$layout = new FileLayout('licenses.page');
$data = array();
$data['licenses'] = $this->items;
$data['pagination'] = $this->pagination;
$data['state'] = $this->state;
echo $layout->render($data);