<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class LicensingViewClaim extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items;

	public function display($tpl = null)
	{
	    $this->item = $this->get('Item');
        $this->_prepareDocument();
		return parent::display($tpl);
	}

    protected function _prepareDocument()
    {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet('/media/com_licensing/css/style.css');
    }
}
