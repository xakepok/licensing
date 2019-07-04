<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldLicense extends JFormFieldList  {
    protected  $type = 'License';
    protected $needFilter = array('software'); //Список вьюшек, нуждающихся в фильтрации по активности
    protected $loadExternally = 0;

    protected function getOptions()
    {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select("`id`, `name`, `number`")
                ->from('#__licensing_licenses')
                ->order("`name`");
            $view = JFactory::getApplication()->input->getString('view');
            $id = JFactory::getApplication()->input->getInt('id', 0);

            /*Фильтр по активности*/
            if (in_array($view, $this->needFilter) && $id == 0)
            {
                $query
                    ->where('(`dateExpires` > CURRENT_DATE() OR `unlim` = 1)');
            }

            $result = $db->setQuery($query)->loadObjectList();

            $options = array();

            foreach ($result as $item)
            {
                $name = ($item->number != null) ? sprintf("%s (%s)", $item->name, $item->number) : sprintf("%s", $item->name);
                $options[] = JHtml::_('select.option', $item->id, $name);
            }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}