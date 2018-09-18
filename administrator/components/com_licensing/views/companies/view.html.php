<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class LicensingViewCompanies extends HtmlView
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
		$this->helper->addSubmenu('companies');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_LICENSING_MENU_COMPANIES'), '');

        if (Factory::getUser()->authorise('core.create', 'com_licensing'))
        {
            JToolbarHelper::addNew('company.add');
        }
        if (Factory::getUser()->authorise('core.edit', 'com_licensing'))
        {
            JToolbarHelper::editList('company.edit');
        }
        if ($this->state->get('filter.state') == -2 && Factory::getUser()->authorise('core.delete', 'com_licensing'))
        {
            JToolbarHelper::deleteList('', 'companies.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_licensing'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('companies.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('companies.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::archiveList('companies.archive');
            JToolBarHelper::trash('companies.trash');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_licensing'))
		{
			JToolBarHelper::preferences('com_licensing');
		}
	}
}
