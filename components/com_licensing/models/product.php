<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ItemModel;

class LicensingModelProduct extends ItemModel
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

    public function getItem()
    {
        $arr = array();
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('`s`.`name` as `product`, `l`.`name` as `license`, `l`.`dogovor`, `s`.`countAvalible` as `cnt`, `s`.`unlim`, `l`.`dateExpires`, `s`.`about`')
            ->from('#__licensing_software as `s`')
            ->leftJoin('#__licensing_licenses as `l` on `l`.`id` = `s`.`licenseID`')
            ->where("`s`.`id` = {$this->id}");
        $result = $db->setQuery($query, 0, 1)->loadObject();
        $arr['dateExpires'] = $result->dateExpires;
        $arr['description'] = $result->about;
        $arr['product'] = $result->product;
        $arr['license'] = (!empty($result->license)) ? $result->license : JText::_('COM_LICENSING_LICENSES_LIC_NUMBER_NO');
        $arr['dogovor'] = (!empty($result->dogovor)) ? $result->dogovor : JText::_('COM_LICENSING_LICENSES_LIC_NO_INFO');
        $arr['cnt'] = ($result->unlim != 1) ? $result->cnt : JText::_('COM_LICENSING_LICENSES_LIC_SOFT_UNLIM');
        return $arr;
    }

    protected $_item;
}