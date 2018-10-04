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
        $format = LicensingHelper::getParams("format_date_site", '%d.%m.%Y');
        $query = $db->getQuery(true);
        $query->select('`s`.`name` as `product`, `s`.`unlim`, `l`.`unlim` as `unlimLic`, `s`.`about`')
            ->select('`l`.`name` as `license`, `l`.`number` as `number`, `l`.`dogovor`')
            ->select('`s`.`countAvalible` as `cnt`, `s`.`count` as `buy`')
            ->select("DATE_FORMAT(`l`.`dateStart`,'{$format}') as `dateStart`, DATE_FORMAT(`l`.`dateExpires`,'{$format}') as `dateExpires`")
            ->from('#__licensing_software as `s`')
            ->leftJoin('#__licensing_licenses as `l` on `l`.`id` = `s`.`licenseID`')
            ->where("`s`.`id` = {$this->id}");
        $result = $db->setQuery($query, 0, 1)->loadObject();
        $dateStart = $result->dateStart;
        $dateExpires = ($result->unlimLic != 1) ? $result->dateExpires : JText::_('COM_LICENSING_LICENSES_LIC_SOFT_UNLIM');
        $arr['dates'] = sprintf("%s: %s - %s", JText::_('COM_LICENSING_SOFTWARE_DATES'), $dateStart, $dateExpires);
        $arr['description'] = $result->about;
        $arr['product'] = $result->product;
        $arr['license'] = (!empty($result->license)) ? $result->license : JText::_('COM_LICENSING_LICENSES_LIC_NUMBER_NO');
        $arr['number'] = (!empty($result->number)) ? $result->number : JText::_('COM_LICENSING_LICENSES_LIC_NUMBER_NO');
        $arr['dogovor'] = (!empty($result->dogovor)) ? $result->dogovor : JText::_('COM_LICENSING_LICENSES_LIC_NO_INFO');
        $arr['buy'] = ($result->unlim != 1) ? $result->buy : JText::_('COM_LICENSING_LICENSES_LIC_SOFT_UNLIM');
        $arr['cnt'] = ($result->unlim != 1) ? $result->cnt : JText::_('COM_LICENSING_LICENSES_LIC_SOFT_UNLIM');
        return $arr;
    }

    protected $_item;
}