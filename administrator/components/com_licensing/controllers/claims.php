<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class LicensingControllerClaims extends AdminController
{
    public function getModel($name = 'Claim', $prefix = 'LicensingModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    /* Прибавляем количество ПО при отклонении заявки */
    public function publish()
    {
        $model = $this->getModel();
        if ($this->getTask() == 'trash')
        {
            $model->decline();
        }
        if ($this->getTask() == 'publish')
        {
            $keys = $model->accept();
            LicensingHelper::sendKeys($keys);
        }
        parent::publish();
    }
}
