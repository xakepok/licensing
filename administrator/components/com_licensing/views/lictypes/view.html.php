<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class LicensingViewLictypes extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $state, $pagination;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->state = $this->get('State');
	    $this->pagination = $this->get('Pagination');

		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new LicensingHelper;
		$this->helper->addSubmenu('lictypes');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_LICENSING_MENU_LICTYPES'), '');

		// Options button.
		if (Factory::getUser()->authorise('core.admin', 'com_licensing'))
		{
		    JToolbarHelper::addNew('lictype.add');
		    JToolbarHelper::editList('lictype.edit');
		    JToolbarHelper::deleteList();
			JToolBarHelper::preferences('com_licensing');
		}
	}
}
