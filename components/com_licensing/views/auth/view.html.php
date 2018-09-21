<?php
use Joomla\CMS\MVC\View\HtmlView;
defined('_JEXEC') or die;

class LicensingViewAuth extends HtmlView
{
    public $user;
    public function display($tpl = null)
    {
        $this->user = $this->get('User');
        parent::display($tpl);
    }
}
