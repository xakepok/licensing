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
                '`Expire`'
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`name` as `software`, `l`.`name` as `license`, `l`.`number`")
            ->select("IF(`s`.`unlim`=1,'".JText::_('COM_LICENSING_LICENSES_LIC_SOFT_UNLIM')."',`s`.`count`)as `cnt`")
            ->select("IF(`l`.`dogovor` IS NULL,'".JText::_('COM_LICENSING_LICENSES_LIC_NO_INFO')."',`l`.`dogovor`) as `dogovor`")
            ->select("DATE_FORMAT(`l`.`dateStart`,'%d.%m.%Y') as `start`")
            ->select("IF(`l`.`unlim`=1,'".JText::_('COM_LICENSING_LICENSES_LIC_UNEXPECT')."',DATE_FORMAT(`l`.`dateExpires`,'%d.%m.%Y')) as `expire`")
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

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`software`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('`software`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}
