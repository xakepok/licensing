<?php
defined('_JEXEC') or die;
error_reporting(0);
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class JFormFieldStructure extends JFormFieldList  {
    protected  $type = 'Structure';

    protected function getOptions()
    {
        $this->guid = JFactory::getApplication()->input->getString('guid', false);
        $this->claim_id = JFactory::getApplication()->input->getString('id', false);

        $options = array();
        if ($this->claim_id === false) {
            if ($this->guid !== false) {
                $ldap = BaseDatabaseModel::getInstance('Ldap', 'LicensingModel');
                $users = $ldap->searchStructure($this->guid);
                foreach ($users['0'] as $item => $p) {
                    if (gettype('p') == 'integer') continue;
                    foreach ($p as $structure) {
                        if (gettype($structure) == 'integer') continue;
                        if (mb_eregi('OU=ORG', $structure) !== false) {
                            $arr = explode(',', $structure);
                            $structure = explode('=', $arr[0]);
                            $structure = $structure[1];
                            if ($structure == 'ORG') continue;
                            $options[] = JHtml::_('select.option', $structure, $structure);
                        }
                    }
                }
            }
        }
        else
        {
            $db =& JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('`structure`')->from('#__licensing_claims')->where("`id` = {$this->claim_id}");
            $result = $db->setQuery($query)->loadResult();
            $options[] = JHtml::_('select.option', $result, $result);
        }

        reset($options);

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    private $guid, $claim_id;
}