<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class LicensingViewLicenses extends HtmlView
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
		$this->helper->addSubmenu('licenses');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_LICENSING_MENU_LICENSES'), '');

        if (Factory::getUser()->authorise('core.create', 'com_licensing'))
        {
            JToolbarHelper::addNew('license.add');
        }
        if (Factory::getUser()->authorise('core.edit', 'com_licensing'))
        {
            JToolbarHelper::editList('license.edit');
        }
        if ($this->state->get('filter.state') == -2 && Factory::getUser()->authorise('core.delete', 'com_licensing'))
        {
            JToolbarHelper::deleteList('', 'licenses.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_licensing'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('licenses.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('licenses.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::archiveList('licenses.archive');
            JToolBarHelper::trash('licenses.trash');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_licensing'))
		{
			JToolBarHelper::preferences('com_licensing');
		}
	}
}
