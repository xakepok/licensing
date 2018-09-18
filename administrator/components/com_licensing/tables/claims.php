<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableLicensingClaims extends Table
{
    var $id = null;
    var $user_id = null;
    var $empl_guid = null;
    var $uid = null;
    var $empl_fio = null;
    var $email = null;
    var $phone = null;
    var $dat = null;
    var $structure = null;
    var $software = null;
    var $status = null;
    var $status_user = null;
    var $status_time = null;
    var $comment = null;
    var $scan_pic = null;
    var $state = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__licensing_claims', 'id', $db);
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
    public function store($updateNulls = false)
    {
        parent::store($updateNulls);
        if ($_POST['jform']['id'] == '0') return true;
        $db = $this->getDbo();
        $insertID = $db->insertid();
        $query = $db->getQuery(true);
        $columns = array('claimID', 'softwareID', 'cnt');
        $query
            ->insert('#__licensing_orders')->columns($columns);
        $need = false;
        foreach ($_POST['jform']['software'] as $id => $cnt) {
            if ($cnt > 0)
            {
                $val = array($db->quote($insertID), $db->quote($id), $db->quote($cnt));
                $query->values(implode(',', $val));
                $need = true;
            }
        }
        if ($need)
        {
            $db->setQuery($query)->execute();
            LicensingHelper::notifyAdmin($insertID);
        }

        return true;
    }
}