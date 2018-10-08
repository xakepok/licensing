<?php
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

class LicensingController extends BaseController {
    protected $view, $format;

    public function display($cachable = false, $urlparams = array())
    {
        $this->view = $this->input->getString('view');
        $this->format = $this->input->getString('format', 'html');

        if ($this->checkAuth())
        {
            $this->setRedirect('/example.php')->redirect();
            jexit();
        }

        if ($this->format != 'html')
        {
            $apikey = $this->input->getString('apikey', '');
            $uid = LicensingHelperUsers::getApiKey($apikey);
            if ($uid == 0)
            {
                $arr = array("result" => "error", "message" => 'Запрос должен содержать действующий ключ API. Для получения ключа обратитесь по адресу asharikov@bmstu.ru');
                echo json_encode($arr);
                jexit();
            }
            else
            {
                LicensingHelper::addApiHistory($uid);
            }
        }

        return parent::display($cachable, $urlparams);
    }

    /* Проверка авторизации */
    private function checkAuth()
    {
        $need_auth = array('claims'); //Views, требующие авторизации
        return (in_array($this->view, $need_auth) && JFactory::getUser()->guest);
    }
}
