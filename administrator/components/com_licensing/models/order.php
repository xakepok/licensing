<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class LicensingModelOrder extends AdminModel {
    public function getTable($name = 'Orders', $prefix = 'TableLicensing', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.order', 'order', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.order.data', array());
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
    	$nulls = array(); //Поля, которые NULL
	    foreach ($nulls as $field)
	    {
		    if (!strlen($table->$field)) $table->$field = NULL;
    	}
        parent::prepareTable($table);
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/order.js';
    }
}