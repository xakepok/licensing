<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class LicensingControllerOrder extends FormController {
    public function display($cachable = false, $urlparams = array())
    {
        if (!$this->checkState())
        {
            $this->setRedirect('index.php?option=com_licensing&view=orders', JText::_('COM_LICENSING_ERROR_ORDER_STATE'));
            jexit();
        }
        return parent::display($cachable, $urlparams);
    }

    /* Проверка возможности редактирования списка ПО */
    private function checkState()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        if ($id == 0) return true;
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`c`.`state`')
            ->from('`#__licensing_orders` as `o`')
            ->leftJoin('`#__licensing_claims` as `c` ON `c`.`id` = `o`.`claimID`')
            ->where("`o`.`id` = {$id}");
        $state = $db->setQuery($query, 0, 1)->loadResult();
        return ($state == '0') ? true : false;
    }
}