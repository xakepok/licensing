<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class LicensingModelSoftware extends AdminModel {
    public function getTable($name = 'Software', $prefix = 'TableLicensing', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }


    /* Метод для установки значения "бесконечно" для группы ПО */
    public static function setUnlim()
    {
        $ids = implode(', ', JFactory::getApplication()->input->get('cid'));
        $where = sprintf("`id` IN (%s)", $ids);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->update('#__licensing_software')
            ->set('`unlim` = 1, `count`=NULL, `countAvalible`=NULL, `countReserv`=NULL')
            ->where($where);
        return $db->setQuery($query)->execute();
    }


    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.software', 'software', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.software.' . (int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.software.data', array());
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
    	$nulls = array('about', 'count', 'countAvalible', 'countReserv'); //Поля, которые NULL
	    foreach ($nulls as $field)
	    {
		    if (!strlen($table->$field)) $table->$field = NULL;
    	}
        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id))
        {
            return $user->authorise('core.edit.state', $this->option . '.software.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/software.js';
    }
}