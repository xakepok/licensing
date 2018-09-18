<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldCompany extends JFormFieldList  {
    protected  $type = 'Company';

    protected function getOptions()
    {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select("`id`, `name`")
                ->from('#__licensing_companies')
                ->order("`name`")
                ->where('`state` > 0');
            $result = $db->setQuery($query)->loadObjectList();

            $options = array();

            foreach ($result as $item)
            {
                $options[] = JHtml::_('select.option', $item->id, $item->name);
            }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}