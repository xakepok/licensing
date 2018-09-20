<?php
use Joomla\CMS\MVC\Model\ListModel;
defined('_JEXEC') or die;

class LicensingModelLicenses extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`l`.`name`',
                '`dateStart`',
                '`dateExpires`'
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $format = LicensingHelper::getParams('format_date_site', '%d.%m.%Y');
        $query
            ->select("`l`.`name`, `t`.`type`, `l`.`number`")
            ->select("IF(`l`.`dogovor` IS NULL,'".JText::_('COM_LICENSING_LICENSES_LIC_NO_INFO')."',`l`.`dogovor`) as `dogovor`")
            ->select("DATE_FORMAT(`dateStart`,'{$format}') as `dateStart`")
            ->select("IF(`unlim`=1,'".JText::_('COM_LICENSING_LICENSES_LIC_UNEXPECT')."',DATE_FORMAT(`dateExpires`,'{$format}')) as `dateExpires`")
            ->from($db->quoteName("#__licensing_licenses")." as ".$db->quoteName("l"))
            ->leftJoin($db->quoteName("#__licensing_type_licenses")." as ".$db->quoteName("t")." ON `t`.`id`=`l`.`licenseType`")
            ->where("(`l`.`dateExpires` >= CURRENT_DATE() OR `l`.`unlim`=1) AND `l`.`state` = 1 AND `t`.`state` = 1")
        ;

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`l`.`name` LIKE ' . $search);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`l`.`name`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('`l`.`name`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}
