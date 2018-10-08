<?php
defined('_JEXEC') or die;

class LicensingHelperClaims
{
    /* Возвращаем читабельный статус заявки на ПО во фронтенде */
    public static function getStatus(int $id): string
    {
        $statuses = array(
            0 => 'COM_LICENSING_CLAIMS_STATUS_IN_WORK',
            1 => 'COM_LICENSING_CLAIMS_STATUS_ACCEPT',
            -2 => 'COM_LICENSING_CLAIMS_STATUS_DECLINE',
            2 => 'COM_LICENSING_CLAIMS_STATUS_ARCHIVED'
        );
        return JText::_($statuses[$id] ?? 'COM_LICENSING_CLAIMS_STATUS_UNDEFINED');
    }

}