<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class LicensingModelLictypes extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`id`', '`id`',
                '`company`', '`company`',
                '`type`', '`type`',
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
            ->select('`t`.`id`, `c`.`name` as `company`, `t`.`type`, `t`.`state`')
            ->from("`#__licensing_type_licenses` as `t`")
            ->leftJoin('`#__licensing_companies` as `c` ON `c`.`id` = `t`.`companyID`');

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`c`.`name` LIKE ' . $search .' OR `t`.`type` LIKE ' . $search);
        }
        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`t`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`t`.`state` = 0 OR `t`.`state` = 1)');
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
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $published);
        $this->setState('filter.company', $company);
        parent::populateState('`type`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.company');
        return parent::getStoreId($id);
    }
}