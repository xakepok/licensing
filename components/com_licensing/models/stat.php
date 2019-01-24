<?php
use Joomla\CMS\MVC\Model\ListModel;
defined('_JEXEC') or die;

class LicensingModelStat extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`structure`', '`empl_fio`'
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $format = (JFactory::getApplication()->input->getString('format', 'html') == 'html') ? 'site' : 'api';
        $format = LicensingHelper::getParams("format_date_{$format}", '%d.%m.%Y');
        $query
            ->select("`o`.`id`, `c`.`empl_fio`, `c`.`structure`, `s`.`name` as `software`, `o`.`cnt`, `l`.`unlim`")
            ->select("DATE_FORMAT(`l`.`dateExpires`,'{$format}') as `dateExpires`")
            ->from("`#__licensing_orders` as `o`")
            ->leftJoin("`#__licensing_claims` as `c` ON `c`.`id` = `o`.`claimID`")
            ->leftJoin("`#__licensing_software` as `s` ON `s`.`id` = `o`.`softwareID`")
            ->leftJoin("`#__licensing_licenses` as `l` ON `l`.`id` = `s`.`licenseID`")
            ->where("`c`.`state` = 1 AND `s`.`state` = 1 AND `l`.`state` = 1");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`structure` LIKE ' . $search . ' OR `empl_fio` LIKE ' . $search);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`structure`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            if (!isset($result[$item->structure])) $result[$item->structure] = array();
            if (!isset($result[$item->structure][$item->software])) $result[$item->structure][$item->software] = array('count' => 0);
            $result[$item->structure][$item->software]['count'] += $item->cnt;
            $result[$item->structure][$item->software]['expire'] = ($item->unlim != 1) ? $item->dateExpires : null;
            $d1 = new DateTime($item->dateExpires);
            $d2 = new DateTime();
            $status = (($d1 < $d2) && ($item->unlim != 1)) ? JText::_('COM_LICENSING_STAT_LICENSE_EXPIRE') : JText::_('COM_LICENSING_STAT_LICENSE_ACTIVE');
            $result[$item->structure][$item->software]['status'] = $status;
        }
        return $result;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('`structure`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}
