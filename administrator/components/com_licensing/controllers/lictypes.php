<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class LicensingControllerLictypes extends AdminController
{
    public function getModel($name = 'Lictype', $prefix = 'LicensingModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
}
