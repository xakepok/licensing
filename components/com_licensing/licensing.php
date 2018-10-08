<?php
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

JLoader::register('LicensingHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/licensing.php');
JLoader::register('LicensingHtmlFilters', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/html/filters.php');
JLoader::register('LicensingModelLdap', JPATH_COMPONENT_ADMINISTRATOR.'/models/ldap.php');
JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/keys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/claims.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/users.php';

$controller = BaseController::getInstance('licensing');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
