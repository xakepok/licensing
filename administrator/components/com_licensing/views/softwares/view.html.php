<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class LicensingViewSoftwares extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $state, $pagination, $reserv, $filterForm, $activeFilters;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->state = $this->get('State');
	    $this->pagination = $this->get('Pagination');
	    $this->reserv = LicensingHelperKeys::getSoftwareReserv();
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new LicensingHelper();
		$this->helper->addSubmenu('softwares');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_LICENSING_MENU_SOFTWARES'), '');

        if (Factory::getUser()->authorise('core.create', 'com_licensing'))
        {
            JToolbarHelper::addNew('software.add');
        }
        if (Factory::getUser()->authorise('core.edit', 'com_licensing'))
        {
            JToolbarHelper::editList('software.edit');
        }
        if ($this->state->get('filter.state') == -2 && Factory::getUser()->authorise('core.delete', 'com_licensing'))
        {
            JToolbarHelper::deleteList('', 'softwares.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_licensing'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('softwares.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('softwares.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::archiveList('softwares.archive');
            JToolBarHelper::trash('softwares.trash');
        }
        if (Factory::getUser()->authorise('core.edit', 'com_licensing'))
        {
            JToolbarHelper::custom('softwares.setUnlim', '', '', 'COM_LICENSING_USERMENU_SETUNLIM');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_licensing'))
		{
			JToolBarHelper::preferences('com_licensing');
		}
	}
}
