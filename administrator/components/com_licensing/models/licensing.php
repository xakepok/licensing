<?php
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

class LicensingModelLicensing extends BaseDatabaseModel
{
    /*Сравниваем количество выданного ПО с количеством оставшегося*/
    public function getSoftwareCheck(): array
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`o`.`softwareID` as `id`, `o`.`cnt` as `ordered`")
            ->select("`s`.`countAvalible` as `avaliable`, `s`.`count` as `all`, `s`.`name`")
            ->from("`#__licensing_orders` as `o`")
            ->leftJoin("`#__licensing_software` as `s` ON `s`.`id` = `o`.`softwareID`")
            ->leftJoin("`#__licensing_claims` as `c` ON `c`.`id` = `o`.`claimID`")
            ->where("`s`.`unlim` != 1 AND `c`.`state` = 1");
        $soft = $db->setQuery($query)->loadObjectList();
        $check = array();
        foreach ($soft as $item) {
            if (!isset($check[$item->id])) $check[$item->id] = array(
                'name' => $item->name,
                'all' => $item->all,
                'avaliable' => $item->avaliable,
                'order' => 0
            );
            $check[$item->id]['order'] += $item->ordered;
        }
        foreach ($check as $id => $item)
        {
            $diff = (int) $item['all'] - $item['avaliable'] - $item['order'];
            if ($diff === 0)
            {
                unset($check[$id]);
                continue;
            }
            $check[$id]['diff'] = $diff;
        }
        return $check;
    }
}
