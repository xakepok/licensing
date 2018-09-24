<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class LicensingModelClaims extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`id`',
                '`dat`',
                '`state`'
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $format = LicensingHelper::getParams('format_date_site');
        $user = JFactory::getUser();
        $query
            ->select("`c`.`id`, DATE_FORMAT(`dat`,'{$format}') as `dat`, `u`.`name` as `manager`, `c`.`state`")
            ->from("`#__licensing_claims` as `c`")
            ->leftJoin('`#__users` as `u` ON `u`.`id` = `c`.`status_user`')
            ->where("`user_id` = {$user->id}");

        /* Фильтруем по состоянию. */
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`state` = 0 OR `state` = 1)');
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`c`.`dat`');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item)
        {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['dat'] = $item->dat;
            $arr['manager'] = $item->manager;
            $arr['status'] = LicensingHelper::getStatus($item->state);
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $published);
        parent::populateState('`c`.`dat`', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.state');
        return parent::getStoreId($id);
    }
}