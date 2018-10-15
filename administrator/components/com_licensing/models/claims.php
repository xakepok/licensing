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
                '`empl_fio`',
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
        $query
            ->select('`c`.`id`, `empl_fio`, `empl_guid`, `c`.`uid`, `c`.`email`, `phone`, `structure`, `u`.`name` as `manager`, `state`')
            ->select("DATE_FORMAT(`dat`,'%d.%m.%Y') as `dat`")
            ->from("#__licensing_claims as `c`")
            ->leftJoin('#__users as `u` ON `u`.`id` = `c`.`user_id`');

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`empl_fio` LIKE ' . $search);
        }
        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`state` = ' . (int) $published);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`dat`');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $arr = array();
            $url['employer'] = JRoute::_('index.php?option=com_licensing&amp;view=claim&amp;layout=edit&amp;uid='.$item->uid.'&amp;id='.$item->id);
            $url['soft'] = JRoute::_('index.php?option=com_licensing&amp;view=orders&amp;filter_claim='.$item->id);
            $link['employer'] = JHtml::link($url['employer'], $item->empl_fio);
            $link['soft'] = JHtml::link($url['soft'], JText::_('COM_LICENSING_CLAIMS_HEAD_SOFTWARE'));
            $arr['id'] = $item->id;
            $arr['employer'] = $link['employer'];
            $arr['soft'] = $link['soft'];
            $arr['structure'] = $item->structure;
            $arr['dat'] = $item->dat;
            $arr['email'] = $item->email;
            $arr['phone'] = $item->phone;
            $arr['manager'] = $item->manager;
            $arr['status'] = LicensingHelperClaims::getStatus($item->state);
            $arr['state'] = $item->state;

            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $published);
        $this->setState('filter.search', $search);
        parent::populateState('`dat`', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        return parent::getStoreId($id);
    }
}