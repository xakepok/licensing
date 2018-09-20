<?php
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

JLoader::register('LicensingHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/licensing.php');

$controller = BaseController::getInstance('licensing');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
