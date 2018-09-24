<?php
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

class LicensingController extends BaseController {
    public function display($cachable = false, $urlparams = array())
    {
        if ($this->checkAuth())
        {
            $this->setRedirect('/example.php')->redirect();
            jexit();
        }
        return parent::display($cachable, $urlparams);
    }

    /* Проверка авторизации */
    private function checkAuth()
    {
        $need_auth = array('claims'); //Views, требующие авторизации
        return (in_array($this->input->getString('view'), $need_auth) && JFactory::getUser()->guest);
    }
}
