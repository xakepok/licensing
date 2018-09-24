<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class LicensingViewClaims extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $state, $pagination, $uid;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->state = $this->get('State');
	    $this->pagination = $this->get('Pagination');
		// Display it all
		return parent::display($tpl);
	}
}
