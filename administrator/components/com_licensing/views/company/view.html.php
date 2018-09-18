<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class LicensingViewCompany extends HtmlView {
    protected $item, $form, $script, $id;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->id = $this->get('Id');
        $this->script = $this->get('Script');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = JText::_('COM_LICENSING_MENU_COMPANIES');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('company.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('company.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('company.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        $document = JFactory::getDocument();
        $document->addScript(JURI::root() . $this->script);
    }
}