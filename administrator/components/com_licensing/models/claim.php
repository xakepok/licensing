<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class LicensingModelClaim extends AdminModel {
    public function getTable($name = 'Claims', $prefix = 'TableLicensing', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    /* Доступное к выдаче ПО */
    public function getSoftware()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from('#__licensing_order_software');
        return $db->setQuery($query)->loadObjectList();
    }

    /* Прибавляем количество ПО при отклонении заявки */
    public function decline()
    {
        $cid = implode(', ', JFactory::getApplication()->input->get('cid', array(), 'array'));
        $arr = array();
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('`claimID`, `softwareID`, `cnt`')
            ->from('#__licensing_orders')
            ->where("`claimID` IN ({$cid})");
        $result = $db->setQuery($query)->loadAssocList();
        foreach ($result as $item)
        {
            if (!isset($arr[$item['softwareID']])) $arr[$item['softwareID']] = 0;
            $arr[$item['softwareID']] += $item['cnt'];
        }
        foreach ($arr as $id => $cnt) {
            $query = "UPDATE `#__licensing_software` SET `countAvalible` = `countAvalible`+{$cnt} WHERE `id`={$id} LIMIT 1";
            $db->setQuery($query)->execute();
        }
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.claim', 'claim', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.claim.' . (int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.claim.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getId()
    {
        return JFactory::getApplication()->input->getInt('id', 0);
    }

    protected function prepareTable($table)
    {
    	$nulls = array('comment', 'status_user', 'status_time'); //Поля, которые NULL
	    foreach ($nulls as $field)
	    {
		    if (!strlen($table->$field)) $table->$field = NULL;
    	}
        $table->user_id = JFactory::getUser()->id;
        $table->status_user = JFactory::getUser()->id;
        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id))
        {
            return $user->authorise('core.edit.state', $this->option . '.claim.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/claim.js';
    }
}