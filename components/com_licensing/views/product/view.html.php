<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class LicensingViewProduct extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $item;

	public function display($tpl = null)
	{
	    $this->item = $this->get('Item');
        $this->_prepareDocument();
		return parent::display($tpl);
	}

    protected function _prepareDocument()
    {
        $doc = JFactory::getDocument();
        $doc->setTitle($this->item['product']);
    }
}
