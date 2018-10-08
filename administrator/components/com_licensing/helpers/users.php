<?php
defined('_JEXEC') or die;

class LicensingHelperUsers
{
    /* Проверяем, студент или нет */
    public static function isStudent(string $guid): bool
    {
        //TODO: Url api from component config
        $xml = @file_get_contents("http://ud-dream.eu.bmstu.ru/api/v2/php_get_student?api_key=ed5b34dbbf2ae840af4e23084502794d16a258eb28ef52bda0b0cac03f49a53c&guid={$guid}");
        return ($xml === false) ? false : true;
    }

    /* Получаем пользователя по API-ключу */
    public static function getApiKey(string $apikey): int
    {
        $db =& JFactory::getDbo();
        $apikey = $db->quote($apikey);
        $query = $db->getQuery(true);
        $fieldID = $db->quote(self::getAdvancedField('apikey'));
        $query->select('`item_id`')
            ->from('#__fields_values')
            ->where("`field_id` = {$fieldID} AND `value` LIKE {$apikey}");
        $result = (int) $db->setQuery($query, 0, 1)->loadResult();
        return $result;
    }

    /* Получаем GUID текущего юзера из базы */
    public static function getUserGuid(): string
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $fieldID = $db->quote(self::getAdvancedField('guid'));
        $userID = $db->quote(JFactory::getUser()->id);
        $query->select('`value`')
            ->from('#__fields_values')
            ->where("`field_id` = {$fieldID} AND `item_id` = {$userID}");
        return $db->setQuery($query, 0, 1)->loadResult();
    }

    /* Получаем ID дополнительного поля для хранения GUIDа */
    public static function getAdvancedField(string $name): int
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("`id`")
            ->from("`#__fields`")
            ->where("`name` LIKE '{$name}' AND `context` LIKE 'com_users.user'");
        return (int) $db->setQuery($query, 0, 1)->loadResult();
    }
}