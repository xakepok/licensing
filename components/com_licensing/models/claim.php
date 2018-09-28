<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ItemModel;

class LicensingModelClaim extends ItemModel
{
    public $id;
    public function __construct(array $config)
    {
        $this->id = JFactory::getApplication()->input->getInt('id', null);
        parent::__construct($config);
    }

    public function getTable($name = 'Claims', $prefix = 'TableLicensing', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getSoftware()
    {
        $db =& $this->getDbo();
        $guid = $db->quote(LicensingHelper::getUserGuid());
        $query = $db->getQuery(true);
        $query->select('`s`.`id`, `s`.`name` as `product`, `k`.`text` as `key`, `o`.`cnt`, `l`.`dateExpires`')
            ->from('#__licensing_orders as `o`')
            ->leftJoin('#__licensing_claims as `c` on `c`.`id` = `o`.`claimID`')
            ->leftJoin('#__licensing_software as `s` on `s`.`id` = `o`.`softwareID`')
            ->leftJoin('#__licensing_keys as `k` on `k`.`softwareID` = `o`.`softwareID`')
            ->leftJoin('#__licensing_licenses as `l` on `l`.`id` = `s`.`licenseID`')
            ->where("`o`.`claimID` = {$this->id}")
            ->where("`c`.`state` > 0")
            ->where("`c`.`empl_guid` LIKE {$guid}");
        return $db->setQuery($query)->loadAssocList();
    }

    public function getItem()
    {
        if ($this->_item === null)
        {
            $this->_item = array();
        }
        if (!isset($this->_item[$this->id]))
        {
            $table = $this->getTable();

            $table->load($this->id);
            if ($table->empl_guid != LicensingHelper::getUserGuid()) return;

            $this->_item['id'] = $table->id;
            $this->_item['empl_fio'] = $table->empl_fio;
            $dat = new DateTime($table->dat);
            $this->_item['dat'] = $dat->format(str_ireplace('%','', LicensingHelper::getParams('format_date_site')));
            $this->_item['email'] = $table->email;
            $this->_item['phone'] = $table->phone;
            $this->_item['structure'] = $table->structure;
            $this->_item['status'] = array();
            $this->_item['status']['code'] = $table->state;
            $this->_item['status']['text'] = LicensingHelper::getStatus($table->state);
            if ($table->state > 0) $this->_item['software'] = $this->getSoftware();
        }
        return $this->_item;
    }
    protected $_item;
}