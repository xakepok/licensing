<?php
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
defined('_JEXEC') or die;

class LicensingModelLdap extends BaseDatabaseModel
{
    private $employer;

    public function __construct(array $config = array())
    {
        $this->employer = JFactory::getApplication()->input->getString('fio');
        parent::__construct($config);
    }

    public static function getInstance($type='Ldap', $prefix = 'LicensingModel', $config = array())
    {
        return parent::getInstance($type, $prefix, $config);
    }

    /* Получение подразделения */
    public function searchStructure($guid)
    {
        $ldap = $this->connect('ok');
        ldap_bind($ldap, LicensingHelper::getParams("ldap_ok_username"), LicensingHelper::getParams("ldap_ok_password"));
        $filter = sprintf("title=%s", $guid);

        $search = ldap_search($ldap, LicensingHelper::getParams("ldap_ok_base_dn"), $filter, array("memberOf"));
        $results = ldap_get_entries($ldap, $search);
        ldap_close($ldap);
        return $results;
    }

    /* Получение пользователя */
    public function searchUsers($fio = false, $guid = false)
    {
        $ldap = $this->connect('ais');
        ldap_bind($ldap, LicensingHelper::getParams("ldap_ais_username"), LicensingHelper::getParams("ldap_ais_password"));
        if ($guid !== false)
        {
            $filter = "x500UniqueIdentifier={$guid}";
        }
        if ($fio !== false)
        {
            $filter = sprintf(LicensingHelper::getParams("ldap_ais_filter_employer"), $fio);
        }
        $search = ldap_search($ldap, LicensingHelper::getParams("ldap_ais_base_dn"), $filter, array("x500UniqueIdentifier", "cn", "mail", "ou", "telephoneNumber", "title", "uid"));
        $results = ldap_get_entries($ldap, $search);
        ldap_close($ldap);
        return $results;
    }

    private function connect($prefix)
    {
        if (LicensingHelper::getParams("ldap_{$prefix}_ignore_cert", false)) putenv('LDAPTLS_REQCERT=never');
        $ds = ldap_connect(LicensingHelper::getParams("ldap_{$prefix}_server"), (int) LicensingHelper::getParams("ldap_{$prefix}_port"));
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, LicensingHelper::getParams("ldap_{$prefix}_protocol", 3));
        ldap_set_option($ds, LDAP_OPT_REFERRALS, LicensingHelper::getParams("ldap_{$prefix}_referals", 0));
        return $ds;
    }
}
