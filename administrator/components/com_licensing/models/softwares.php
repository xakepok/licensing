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
                's.id',
                's.name',
                's.count',
                's.countAvalible',
                'l.dateStart',
                'l.dateExpires',
                'l.name',
                's.tip', 'tip',
                's.licenseID', 'license',
                'lictype',
                'c.id',
                's.state', 'state',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, `s`.`name`, `s`.`count`, `s`.`countAvalible`, `s`.`unlim`, `s`.`state`")
            ->select("`l`.`name` as `license`, `l`.`number`, `l`.`unlim` as `license_unlim`")
            ->select("DATE_FORMAT(`l`.`dateStart`,'%d.%m.%Y') as `dat_start`, DATE_FORMAT(`l`.`dateExpires`,'%d.%m.%Y') as `dat_end`")
            ->select("`c`.`name` as `company`")
            ->select("`t`.`type` as `licenseType`")
            ->from("`#__licensing_software` as `s`")
            ->leftJoin('`#__licensing_licenses` as `l` ON `l`.`id` = `s`.`licenseID`')
            ->leftJoin('`#__licensing_type_licenses` as `t` ON `t`.`id` = `l`.`licenseType`')
            ->leftJoin('`#__licensing_companies` as `c` ON `c`.`id` = `t`.`companyID`');

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->q("%{$search}%");
            $query->where("`s`.`name` LIKE {$search}");
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
        // Фильтруем по типу ПО.
        $tip = $this->getState('filter.tip');
        if (is_numeric($tip))
        {
            if ($tip == 0) $query->where('`s`.`tip` is null');
            if ($tip != 0) $query->where('`s`.`tip` = ' . (int) $tip);
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

    public function getItems(): array
    {
        $result = array();
        $items = parent::getItems();
        foreach ($items as $i => $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option={$this->option}&amp;task=software.edit&amp;id={$item->id}");
            $arr['software'] = JHtml::link($url, $item->name);
            $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_licensing.software.' . $item->id);
            $arr['state'] = JHtml::_('jgrid.published', $item->state, $i, 'softwares.', $canChange);
            $arr['grid'] = JHtml::_('grid.id', $i, $item->id);
            $arr['count'] = ($item->unlim != '1') ? $item->count : '∞';
            $arr['available'] = ($item->unlim != '1') ? $item->countAvalible : '∞';
            $arr['license'] = $item->license;
            $arr['dat_start'] = $item->dat_start;
            $arr['dat_end'] = ($item->license_unlim != '1') ? $item->dat_end : JText::sprintf('COM_LICENSING_LICENSES_UNLIM');
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $published);
        $company = $this->getUserStateFromRequest($this->context . '.filter.company', 'filter_company', '', 'string');
        $this->setState('filter.company', $company);
        $licenseType = $this->getUserStateFromRequest($this->context . '.filter.licenseType', 'filter_licenseType', '', 'string');
        $this->setState('filter.licenseType', $licenseType);
        $license = $this->getUserStateFromRequest($this->context . '.filter.license', 'filter_license', '', 'string');
        $this->setState('filter.license', $license);
        $tip = $this->getUserStateFromRequest($this->context . '.filter.tip', 'filter_tip', '', 'string');
        $this->setState('filter.tip', $tip);
        parent::populateState('s.name', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.tip');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.company');
        $id .= ':' . $this->getState('filter.licenseType');
        $id .= ':' . $this->getState('filter.license');
        return parent::getStoreId($id);
    }
}