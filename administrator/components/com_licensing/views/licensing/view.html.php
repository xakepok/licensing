<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class LicensingViewLicensing extends HtmlView
{
	protected $helper, $check;

	protected $sidebar = '';

	public function display($tpl = null)
	{
		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new LicensingHelper;
		$this->helper->addSubmenu('licensing');
		$this->sidebar = JHtmlSidebar::render();

		$this->check = $this->get('SoftwareCheck');

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_LICENSING')." - ".JText::_('COM_LICENSING_SERVICE'), '');

		// Options button.
		if (Factory::getUser()->authorise('core.admin', 'com_licensing'))
		{
			JToolBarHelper::preferences('com_licensing');
		}
	}
}
