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
        $format = (JFactory::getApplication()->input->getString('format', 'html') == 'html') ? 'site' : 'api';
        $format = LicensingHelper::getParams("format_date_{$format}", '%d.%m.%Y');
        $query
            ->select('`l`.`id`, `l`.`name`, `t`.`type`, `l`.`number`, `l`.`dogovor`, `l`.`freeware`, `unlim`')
            ->select('`l`.`files`')
            ->select("DATE_FORMAT(`dateStart`,'{$format}') as `dateStart`, DATE_FORMAT(`dateExpires`,'{$format}') as `dateExpires`")
            ->from('`#__licensing_licenses` as `l`')
            ->leftJoin("`#__licensing_type_licenses` as `t` ON `t`.`id`=`l`.`licenseType`")
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

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item)
        {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['name'] = $item->name;
            $arr['dateStart'] = $item->dateStart;
            if (JFactory::getApplication()->input->getString('format', 'html') == 'html')
            {
                $arr['type'] = $item->type;
                $dogovor = (!empty($item->dogovor)) ? $item->dogovor : JText::_('COM_LICENSING_LICENSES_LIC_NO_INFO');
                $arr['dateExpires'] = ($item->unlim != 1) ? $item->dateExpires : JText::_('COM_LICENSING_LICENSES_LIC_UNEXPECT');
                $arr['number'] = (!empty($item->number)) ? $item->number : JText::_('COM_LICENSING_LICENSES_LIC_NUMBER_NO');
                $arr['freeware'] = ($item->freeware != 1) ? JText::sprintf('JNO') : JText::sprintf('JYES');
                $url = JRoute::_(JUri::base()."images/dogovors/".$item->files);
                $arr['files'] = (!empty($item->files)) ? JHtml::link($url, JText::sprintf('COM_LICENSING_LICENSES_HEAD_FILES_TEXT')) : JText::sprintf('JNO');
            }
            else
            {
                $arr['freeware'] = $item->freeware;
                $dogovor = $item->dogovor;
                if ($item->unlim != 1)
                {
                    $arr['dateExpires'] = $item->dateExpires;
                }
                else
                {
                    $arr['unlimited'] = 1;
                }
                $arr['number'] = $item->number;
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
        parent::populateState('`l`.`name`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}
