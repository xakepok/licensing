<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class LicensingModelSoftwares extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`s`.`id`', '`s`.`id`',
                '`s`.`licenseID`', '`s`.`licenseID`',
                '`s`.`name`', '`s`.`name`',
                '`l`.`id', '`l`.`id',
                '`t`.`id', '`t`.`id',
                '`c`.`id', '`c`.`id',
                'state`', '`state`',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, `s`.`name`, IF(`s`.`unlim`=1,'∞',`s`.`count`) as `count`, IF(`s`.`unlim`=1,'∞',`s`.`countAvalible`) as `countAvalible`, IF(`s`.`unlim`=1,'∞',`s`.`countReserv`) as `countReserv`, `l`.`name` as `license`, `l`.`number`, CONCAT(DATE_FORMAT(`l`.`dateStart`,'%d.%m.%Y'),' - ',DATE_FORMAT(`l`.`dateExpires`,'%d.%m.%Y')) as `dat`, `t`.`type` as `licenseType`, `c`.`name` as `company`, `s`.`state`")
            ->from("`#__licensing_software` as `s`")
            ->leftJoin('`#__licensing_licenses` as `l` ON `l`.`id` = `s`.`licenseID`')
            ->leftJoin('`#__licensing_type_licenses` as `t` ON `t`.`id` = `l`.`licenseType`')
            ->leftJoin('`#__licensing_companies` as `c` ON `c`.`id` = `t`.`companyID`');

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`s`.`name` LIKE ' . $search);
        }
        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`s`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`s`.`state` = 0 OR `s`.`state` = 1)');
        }
        // Фильтруем по лицензии.
        $license = $this->getState('filter.license');
        if (is_numeric($license))
        {
            $query->where('`s`.`licenseID` = ' . (int) $license);
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
        $license = $this->getUserStateFromRequest($this->context . '.filter.license', 'filter_license', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.company', $company);
        $this->setState('filter.state', $published);
        $this->setState('filter.licenseType', $licenseType);
        $this->setState('filter.license', $license);
        parent::populateState('`type`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.company');
        $id .= ':' . $this->getState('filter.licenseType');
        $id .= ':' . $this->getState('filter.license');
        return parent::getStoreId($id);
    }
}