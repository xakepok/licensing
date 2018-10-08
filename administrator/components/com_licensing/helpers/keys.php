<?php
defined('_JEXEC') or die;

class LicensingHelperKeys
{
    /*Получаем список резерва*/
    public static function getSoftwareReserv(): array
    {
        $arr = array();
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`o`.`id`, `o`.`softwareID`, `o`.`cnt`")
            ->from("#__licensing_orders as `o`")
            ->leftJoin("`#__licensing_claims` as `c` ON `c`.`id` = `o`.`claimID`")
            ->where("`c`.`state` = 0");
        $result = $db->setQuery($query)->loadAssocList();
        foreach ($result as $item) {
            if (!isset($arr[$item['softwareID']])) $arr[$item['softwareID']] = 0;
            $arr[$item['softwareID']] += $item['cnt'];
        }
        return $arr;
    }
}