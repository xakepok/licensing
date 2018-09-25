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
        $options = array();
        if ($this->guid != "") {
            $ldap = BaseDatabaseModel::getInstance('Ldap', 'LicensingModel');
            $users = $ldap->searchStructure($this->guid);
            foreach ($users['0'] as $item => $p) {
                if (gettype('p') == 'integer') continue;
                foreach ($p as $structure) {
                    if (gettype($structure) == 'integer') continue;
                    if (mb_eregi('OU=ORG', $structure) !== false)
                    {
                        $arr = explode(',', $structure);
                        $structure = explode('=', $arr[0]);
                        $structure = $structure[1];
                        if ($structure == 'ORG') continue;
                        $options[] = JHtml::_('select.option', $structure, $structure);
                    }
                }
            }
        }

        reset($options);

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    private $guid;
}