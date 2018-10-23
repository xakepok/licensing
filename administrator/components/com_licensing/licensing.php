<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_licensing'))
{
	throw new InvalidArgumentException(Text::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Require the helper
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/licensing.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/keys.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/claims.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/users.php';
JLoader::register('LicensingHtmlFilters', dirname(__FILE__) . '/helpers/html/filters.php');
JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');

// Access view check
if (!LicensingHelper::checkAccessView())
{
    throw new InvalidArgumentException(Text::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Execute the task
$controller = BaseController::getInstance('licensing');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
