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

		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new LicensingHelper;
		$this->helper->addSubmenu('claims');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_LICENSING_MENU_CLAIMS'), '');

        if (Factory::getUser()->authorise('core.create', 'com_licensing'))
        {
            JToolbarHelper::addNew('claim.add');
        }
        if (Factory::getUser()->authorise('core.edit', 'com_licensing'))
        {
            JToolbarHelper::editList('claim.edit');
        }
        if ($this->state->get('filter.state') == -2 && Factory::getUser()->authorise('core.delete', 'com_licensing'))
        {
            JToolbarHelper::deleteList('', 'claims.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_licensing'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('claims.publish', 'COM_LICENSING_ACTION_CLAIM_ACCEPT', true);
            //JToolbarHelper::unpublish('claims.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            if ($this->state->get('filter.state') == 1)
            {
                JToolBarHelper::archiveList('claims.archive');
            }
            JToolBarHelper::trash('claims.trash', 'COM_LICENSING_ACTION_CLAIM_DECLINE');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_licensing'))
		{
			JToolBarHelper::preferences('com_licensing');
		}
	}
}
