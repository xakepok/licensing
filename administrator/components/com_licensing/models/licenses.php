<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class LicensingModelLicenses extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`id`', '`id`',
                '`licenseType`', '`licenseType`',
                '`l`.`name`', '`l`.`name`',
                '`dateStart`', '`dateStart`',
                '`dateExpires`', '`dateExpires`',
                '`state`', '`state`',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`l`.`id`, `l`.`name`, `l`.`number`, `l`.`dogovor`, `l`.`freeware`, `t`.`type` as `licenseType`, `c`.`name` as `company`, `l`.`state`")
            ->select("CONCAT(DATE_FORMAT(`l`.`dateStart`,'%d.%m.%Y'),' - ',IF(`l`.`unlim`=1,'".JText::_('COM_LICENSING_LICENSES_UNLIM')."',DATE_FORMAT(`l`.`dateExpires`,'%d.%m.%Y'))) as `dat`")
            ->from("`#__licensing_licenses` as `l`")
            ->leftJoin('`#__licensing_type_licenses` as `t` ON `t`.`id` = `l`.`licenseType`')
            ->leftJoin('`#__licensing_companies` as `c` ON `c`.`id` = `t`.`companyID`');

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`l`.`name` LIKE ' . $search);
        }
        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`l`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`l`.`state` = 0 OR `l`.`state` = 1)');
        }
        // Фильтруем по типу лицензии.
        $licenseType = $this->getState('filter.licenseType');
        if (is_numeric($licenseType))
        {
            $query->where('`l`.`licenseType` = ' . (int) $licenseType);
        }
        // Фильтруем по владелец.
        $company = $this->getState('filter.company');
        if (is_numeric($company))
        {
            $query->where('`t`.`companyID` = ' . (int) $company);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`type`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $company = $this->getUserStateFromRequest($this->context . '.filter.company', 'filter_company', '', 'string');
        $licenseType = $this->getUserStateFromRequest($this->context . '.filter.licenseType', 'filter_licenseType', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $published);
        $this->setState('filter.licenseType', $licenseType);
        parent::populateState('`type`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.company');
        $id .= ':' . $this->getState('filter.licenseType');
        return parent::getStoreId($id);
    }
}