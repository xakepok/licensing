<?php
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

class LicensingModelLicensing extends BaseDatabaseModel
{
    /*Сравниваем количество выданного ПО с количеством оставшегося*/
    public function getSoftwareCheck(): array
    {
        $soft = $this->getSoftList();
        foreach ($soft as $id => $item)
        {
            $diff = (int) $item['all'] - $item['avaliable'] - $item['order'] - $item['reserv'];
            if ($diff === 0)
            {
                unset($soft[$id]);
                continue;
            }
            $soft[$id]['diff'] = $diff;
        }
        return $soft;
    }

    /*Статистика выданного ПО*/
    public function getPopulateSoft(): array
    {
        $soft = $this->getSoftList();
        $arr = array();
        foreach ($soft as $id => $item)
        {
            if (!isset($arr[$item['name']])) $arr[$item['name']] = 0;
            $arr[$item['name']] += $item['order'];
        }
        arsort($arr);
        return $arr;
    }

    /*Список выданного софта*/
    private function getSoftList(): array
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`o`.`softwareID` as `id`, `o`.`cnt` as `ordered`, `c`.`state`")
            ->select("`s`.`countAvalible` as `avaliable`, `s`.`count` as `all`, `s`.`name`")
            ->from("`#__licensing_orders` as `o`")
            ->leftJoin("`#__licensing_software` as `s` ON `s`.`id` = `o`.`softwareID`")
            ->leftJoin("`#__licensing_claims` as `c` ON `c`.`id` = `o`.`claimID`")
            ->leftJoin("`#__licensing_licenses` as `l` ON `l`.`id` = `s`.`licenseID`")
            ->where("`s`.`unlim` != 1")
            ->where("`l`.`dateExpires` > CURRENT_DATE()");
        $soft = $db->setQuery($query)->loadObjectList();
        $check = array();
        foreach ($soft as $item) {
            if (!isset($check[$item->id])) $check[$item->id] = array(
                'name' => $item->name, //Название продукта
                'all' => $item->all, //Всего закуплено
                'avaliable' => $item->avaliable, //Доступно
                'order' => 0, //Выдано
                'reserv' => 0 //В резерве
            );
            $check[$item->id][($item->state == '1') ? 'order' : 'reserv'] += $item->ordered;
        }
        return $check;
    }
}
