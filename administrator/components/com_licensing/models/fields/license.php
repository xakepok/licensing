<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldLicense extends JFormFieldList  {
    protected  $type = 'License';

    protected function getOptions()
    {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select("`id`, `name`, `number`")
                ->from('#__licensing_licenses')
                ->order("`name`")
                ->where('`state` > 0');
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