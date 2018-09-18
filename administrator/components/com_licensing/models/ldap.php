<?php
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
defined('_JEXEC') or die;

class LicensingModelLdap extends BaseDatabaseModel
{
    private $options, $employer;

    public function __construct(array $config = array())
    {
        $this->options = JComponentHelper::getComponent('com_licensing')->getParams();
        $this->employer = JFactory::getApplication()->input->getString('fio');
        parent::__construct($config);
    }

    public static function getInstance($type='Ldap', $prefix = 'LicensingModel', $config = array())
    {
        return parent::getInstance($type, $prefix, $config);
    }

    public function searchUsers($fio, $uid=false)
    {
        $ldap = $this->connect('ais');
        ldap_bind($ldap, $this->options->get("ldap_ais_username"), $this->options->get("ldap_ais_password"));
        if (!$uid)
        {
            $filter = sprintf($this->options->get("ldap_ais_filter_employer"), $fio);
        }
        else
        {
            $filter = "uid={$uid}";
        }
        $search = ldap_search($ldap, $this->options->get("ldap_ais_base_dn", ""), $filter, array("x500UniqueIdentifier", "cn", "mail", "ou", "telephoneNumber", "title", "uid"));
        $results = ldap_get_entries($ldap, $search);
        ldap_close($ldap);
        return $results;
    }

    private function connect($prefix)
    {
        if ($this->options->get("ldap_{$prefix}_ignore_cert", false)) putenv('LDAPTLS_REQCERT=never');
        $ds = ldap_connect($this->options->get("ldap_{$prefix}_server"), (int) $this->options->get("ldap_{$prefix}_port"));
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, $this->options->get("ldap_{$prefix}_protocol", 3));
        ldap_set_option($ds, LDAP_OPT_REFERRALS, $this->options->get("ldap_{$prefix}_referals", 0));
        return $ds;
    }
}
