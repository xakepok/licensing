<?php
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;
class LicensingControllerApi extends BaseController
{
    public function getModel($name = '', $prefix = 'LicensingModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function getSoftware()
    {
        jexit(JText::_('COM_LICENSING_ERROR_METHOD_OBSOLETE'));
    }

    public function getLicenses()
    {
        jexit(JText::_('COM_LICENSING_ERROR_METHOD_OBSOLETE'));
    }

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }
}
