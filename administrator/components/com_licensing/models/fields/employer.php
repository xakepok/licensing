<?php
defined('_JEXEC') or die;
error_reporting(0);
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class JFormFieldEmployer extends JFormFieldList
{
    protected $type = 'Employer';

    protected function getOptions()
    {
        $this->employer = JFactory::getApplication()->input->getString('fio', false);
        $this->claim_id = JFactory::getApplication()->input->getString('id', false);
        $this->guid = JFactory::getApplication()->input->getString('guid', false);

        $options = array();
        if ($this->claim_id === false && $this->guid === false && $this->employer === false) $options[] = JHtml::_('select.option', '', '');
        if ($this->claim_id === false) {
            $ldap = BaseDatabaseModel::getInstance('Ldap', 'LicensingModel');
            if ($this->employer !== false) {
                $users = $ldap->searchUsers($this->employer);
            }
            if ($this->guid !== false) {
                $users = $ldap->searchUsers(false, $this->guid);
            }

            foreach ($users as $item => $p) {
                if ($p["cn"][0] === NULL) continue;
                $arr = array();
                $arr["data-fio"] = $p["cn"][0];
                $arr["data-podrazd"] = $p["ou"][0];
                $arr["data-phone"] = $p["telephonenumber"][0];
                $arr["data-dolzhnost"] = $p["title"][0];
                $arr["data-login"] = $p["uid"][0];
                $arr["data-uid"] = $p["x500uniqueidentifier"][0];
                $arr["data-email"] = $p["mail"][0];

                $params = array(
                    "attr" => $arr,
                    "option.attr" => "optionattr",
                );

                $options[] = JHtml::_('select.option', $arr["data-uid"], $arr["data-fio"], $params);
                if ($this->guid !== false) break;
            }
        } else {
            $db =& JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('`empl_guid`, `empl_fio`')->from('#__licensing_claims')->where("`id` = {$this->claim_id}");
            $result = $db->setQuery($query)->loadAssoc();
            $options[] = JHtml::_('select.option', $result['empl_guid'], $result["empl_fio"]);
        }

        reset($options);

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    private $employer, $claim_id, $guid;
}