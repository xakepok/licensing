<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class LicensingModelKeys extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`k`.`id`', '`k`.`id`',
                '`k`.`softwareID`', '`k`.`softwareID`',
                '`s`.`licenseID`', '`s`.`licenseID`',
                '`s`.`name`', '`s`.`name`',
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
            ->select("`k`.`id`, `k`.`text` as `text`, `s`.`name` as `software`, IF(`s`.`unlim`=1,'∞',`s`.`count`) as `count`, IF(`s`.`unlim`=1,'∞',`s`.`countAvalible`) as `countAvalible`, IF(`s`.`unlim`=1,'∞',`s`.`countReserv`) as `countReserv`, CONCAT(`l`.`name`,' (№',`l`.`number`,')') as `license`, CONCAT(DATE_FORMAT(`l`.`dateStart`,'%d.%m.%Y'),' - ',DATE_FORMAT(`l`.`dateExpires`,'%d.%m.%Y')) as `dat`, `k`.`state`")
            ->from("`#__licensing_keys` as `k`")
            ->leftJoin('`#__licensing_software` as `s` ON `s`.`id` = `k`.`softwareID`')
            ->leftJoin('`#__licensing_licenses` as `l` ON `l`.`id` = `s`.`licenseID`')
            ->leftJoin('`#__licensing_type_keys` as `t` ON `t`.`id` = `k`.`type`');

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
            $query->where('`k`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`k`.`state` = 0 OR `k`.`state` = 1)');
        }
        // Фильтруем по лицензии.
        $license = $this->getState('filter.license');
        if (is_numeric($license))
        {
            $query->where('`s`.`licenseID` = ' . (int) $license);
        }
        // Фильтруем по продукту.
        $software = $this->getState('filter.software');
        if (is_numeric($software))
        {
            $query->where('`k`.`softwareID` = ' . (int) $software);
        }
        // Фильтруем по типку ключа.
        $company = $this->getState('filter.keytype');
        if (is_numeric($company))
        {
            $query->where('`k`.`type` = ' . (int) $company);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`s`.`name`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $keytype = $this->getUserStateFromRequest($this->context . '.filter.keytype', 'filter_keytype', '', 'string');
        $license = $this->getUserStateFromRequest($this->context . '.filter.license', 'filter_license', '', 'string');
        $software = $this->getUserStateFromRequest($this->context . '.filter.$software', 'filter_software', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $published);
        $this->setState('filter.keytype', $keytype);
        $this->setState('filter.license', $license);
        $this->setState('filter.software', $software);
        parent::populateState('`s`.`name`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.keytype');
        $id .= ':' . $this->getState('filter.license');
        $id .= ':' . $this->getState('filter.software');
        return parent::getStoreId($id);
    }
}