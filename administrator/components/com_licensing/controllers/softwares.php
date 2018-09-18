<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class LicensingControllerSoftwares extends AdminController
{
    public function getModel($name = 'Software', $prefix = 'LicensingModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function setUnlim()
    {
        $model = $this->getModel();
        $model::setUnlim();
        $this->setRedirect('index.php?option=com_licensing&view=softwares');
    }
}
