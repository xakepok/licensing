<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class LicensingViewClaim extends HtmlView {
    protected $item, $form, $script, $id, $software;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->id = $this->get('Id');
        $this->script = $this->get('Script');
        $this->software = $this->get('Software');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = JText::_('COM_LICENSING_MENU_CLAIMS');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('claim.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('claim.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('claim.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        $document = JFactory::getDocument();
        $document->addScript(JURI::root() . $this->script);
    }
}