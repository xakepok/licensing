<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class LicensingModelOrders extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`o`.`id`', '`o`.`id`',
                '`c`.`empl_fio`', '`c`.`empl_fio`',
                '`s`.`name`', '`s`.`name`',
                '`o`.`claimID`', '`o`.`claimID`',
                '`c`.`dat`', '`c`.`dat`',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`o`.`id`, `o`.`claimID`, DATE_FORMAT(`c`.`dat`,'%d.%m.%Y') as `dat`, `c`.`empl_fio`, `c`.`structure`, `s`.`name` as `product`, `o`.`cnt`, `c`.`state`")
            ->from("`#__licensing_orders` as `o`")
            ->leftJoin('`#__licensing_claims` as `c` ON `c`.`id` = `o`.`claimID`')
            ->leftJoin('`#__licensing_software` as `s` ON `s`.`id` = `o`.`softwareID`');

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`s`.`name` LIKE ' . $search);
        }
        // Фильтруем по заявке.
        $claim = $this->getState('filter.claim');
        if (is_numeric($claim))
        {
            $query->where('`o`.`claimID` = ' . (int) $claim);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`c`.`dat`');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $claim = $this->getUserStateFromRequest($this->context . '.filter.claim', 'filter_claim', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.company', $claim);
        parent::populateState('`c`.`dat`', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.claim');
        return parent::getStoreId($id);
    }
}