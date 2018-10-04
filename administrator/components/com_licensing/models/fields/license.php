<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldLicense extends JFormFieldList  {
    protected  $type = 'License';
    protected $needFilter = array('software'); //Список вьюшек, нуждающихся в фильтрации по активности

    protected function getOptions()
    {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select("`id`, `name`, `number`")
                ->from('#__licensing_licenses')
                ->order("`name`")
                ->where('`state` > 0');
            $view = JFactory::getApplication()->input->getString('view');

            /*Фильтр по активности*/
            if (in_array($view, $this->needFilter))
            {
                $query
                    ->where('`dateStart` <= CURRENT_DATE()')
                    ->where('`dateExpires` > CURRENT_DATE()')
                    ->where('`state` = 1');
            }

            $result = $db->setQuery($query)->loadObjectList();

            $options = array();

            foreach ($result as $item)
            {
                $name = sprintf("%s (%s)", $item->name, $item->number);
                $options[] = JHtml::_('select.option', $item->id, $name);
            }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}