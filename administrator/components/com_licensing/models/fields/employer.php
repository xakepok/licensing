<?php
defined('_JEXEC') or die;
error_reporting(0);
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class JFormFieldEmployer extends JFormFieldList  {
    protected  $type = 'Employer';

    protected function getOptions()
    {
        $this->employer = JFactory::getApplication()->input->getString('fio');
        $this->uid = JFactory::getApplication()->input->getString('uid', false);
        $this->claim_id = JFactory::getApplication()->input->getString('id', false);
        $this->guid = JFactory::getApplication()->input->getString('guid', false);

        if ($this->employer != "" || $this->guid !== false) {
            $ldap = BaseDatabaseModel::getInstance('Ldap', 'LicensingModel');
            if (!$this->uid && $this->guid === false && $this->uid === false)
            {
                $users = $ldap->searchUsers($this->employer);
            }
            else
            {
                $users = $ldap->searchUsers($this->employer, $this->uid);
            }
            if ($this->guid !== false)
            {
                $users = $ldap->searchUsers('', '', $this->guid);
            }

            $options = array();

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
                );

                $options[] = JHtml::_('select.option', $arr["data-uid"], $arr["data-fio"], $params);
            }
        }
        else
        {
            if (!$this->claim_id)
            {
                $options[] = JHtml::_('select.option', '', '');
            }
            else
            {
                $db =& JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('`empl_guid`, `empl_fio`')->from('#__licensing_claims')->where("`id` = {$this->claim_id}");
                $result = $db->setQuery($query)->loadAssoc();
                $options[] = JHtml::_('select.option', $result['empl_guid'], $result["empl_fio"]);
            }
        }

        reset($options);

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    private $employer, $uid, $claim_id, $guid;
}