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
        $model = $this->getModel('Software');
        $items = $model->getItems();
        jexit(json_encode($items));
    }

    public function getLicenses()
    {
        $model = $this->getModel('Licenses');
        $items = $model->getItems();
        jexit(json_encode($items));
    }

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }
}
