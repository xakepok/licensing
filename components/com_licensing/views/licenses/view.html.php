<?php
use Joomla\CMS\MVC\View\HtmlView;
defined('_JEXEC') or die;

class LicensingViewLicenses extends HtmlView
{
    public $items, $pagination, $state;
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        parent::display($tpl);
    }
}
