<?php
use Joomla\CMS\MVC\Model\ListModel;
defined('_JEXEC') or die;

class LicensingModelSoftware extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`software`',
                '`license`',
                '`number`',
                '`start`',
                'tip',
                '`Expire`'
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
            ->select("`s`.`id`, `s`.`name` as `software`, `l`.`name` as `license`, `l`.`number`, `l`.`unlim` as `unlimLic`, `s`.`unlim` as `unlimSoft`, `s`.`countAvalible` as `cnt`, `l`.`dogovor`")
            ->select("DATE_FORMAT(`l`.`dateStart`,'{$format}') as `dateStart`, DATE_FORMAT(`l`.`dateExpires`,'{$format}') as `dateExpires`")
            ->select("`l`.`freeware`, IFNULL(`s`.`tip`,0) as `tip`")
            ->from("`#__licensing_software` as `s`")
            ->leftJoin("`#__licensing_licenses` as `l` ON `l`.`id` = `s`.`licenseID`")
            ->where("(`s`.`state` = 1 AND `l`.`state` = 1) AND (`l`.`dateExpires` > CURRENT_DATE() OR `l`.`unlim` = 1)");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`software` LIKE ' . $search . ' OR `license` LIKE ' . $search);
        }
        // Фильтруем по типу ПО.
        $tip = $this->getState('filter.tip');
        if (is_numeric($tip))
        {
            if ($tip == 0) $query->where('`s`.`tip` is null');
            if ($tip != 0) $query->where('`s`.`tip` = ' . (int) $tip);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`software`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $Itemid = LicensingHelper::getItemid('product');
            $url = JRoute::_("index.php?id={$item->id}&Itemid={$Itemid}");
            $arr['license'] = $item->license;
            $arr['dateStart'] = $item->dateStart;
            if (JFactory::getApplication()->input->getString('format', 'html') == 'html')
            {
                $arr['software'] = JHtml::link($url, $item->software);
                $dogovor = $item->dogovor ?? JText::sprintf('COM_LICENSING_LICENSES_LIC_NO_INFO');
                $arr['number'] = $item->number ?? JText::sprintf('COM_LICENSING_LICENSES_LIC_NUMBER_NO');
                $arr['dateExpires'] = ($item->unlimLic != 1) ? $item->dateExpires : JText::sprintf('COM_LICENSING_LICENSES_LIC_UNEXPECT');
                $arr['cnt'] = ($item->unlimSoft != 1) ? $item->cnt : JText::sprintf('COM_LICENSING_LICENSES_LIC_SOFT_UNLIM');
                $arr['tip'] = JText::sprintf('COM_LICENSING_SOFTWARE_HEAD_TIP_' . $item->tip ?? '0');
            }
            else
            {
                $arr['software'] = $item->software;
                $arr['freeware'] = $item->freeware;
                $dogovor = (!empty($item->dogovor)) ? $item->dogovor : JText::sprintf('COM_LICENSING_LICENSES_LIC_NO_INFO');
                $arr['number'] = $item->number;
                if ($item->unlimLic != 1)
                {
                    $arr['dateExpires'] = $item->dateExpires;
                }
                else
                {
                    $arr['unlimited'] = 1;
                }
                if ($item->unlimSoft != 1)
                {
                    $arr['soft']['cnt'] = $item->cnt;
                }
                else
                {
                    $arr['soft']['unlimited'] = '1';
                }
            }
            $arr['contract'] = $dogovor;
            $result[] = $arr;
        }
        return $result;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $tip = $this->getUserStateFromRequest($this->context . '.filter.tip', 'filter_tip', '', 'string');
        $this->setState('filter.tip', $tip);
        parent::populateState('`software`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.tip');
        return parent::getStoreId($id);
    }
}
