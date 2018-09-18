<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableLicensingKeys extends Table
{
    var $id = null;
    var $softwareID = null;
    var $type = null;
    var $text = null;
    var $state = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__licensing_keys', 'id', $db);
    }

    public function publish($pks = null, $state = 1, $userId = 0)
    {
        $k = $this->_tbl_key;

        // Очищаем входные параметры.
        JArrayHelper::toInteger($pks);
        $state = (int) $state;

        // Если первичные ключи не установлены, то проверяем ключ в текущем объекте.
        if (empty($pks))
        {
            if ($this->$k)
            {
                $pks = array($this->$k);
            }
            else
            {
                throw new RuntimeException(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'), 500);
            }
        }

        // Устанавливаем состояние для всех первичных ключей.
        foreach ($pks as $pk)
        {
            // Загружаем сообщение.
            if (!$this->load($pk))
            {
                throw new RuntimeException(JText::_('COM_LICENSING_TABLE_ERROR_RECORD_LOAD'), 500);
            }

            $this->state = $state;

            // Сохраняем сообщение.
            if (!$this->store())
            {
                throw new RuntimeException(JText::_('COM_LICENSING_TABLE_ERROR_RECORD_STORE'), 500);
            }
        }

        return true;
    }
}