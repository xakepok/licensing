<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldKeytype extends JFormFieldList  {
    protected  $type = 'Keytype';

    protected function getOptions()
    {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select("`id`, `type`")
                ->from('#__licensing_type_keys')
                ->order("`id`")
                ->where('`state` > 0');
            $result = $db->setQuery($query)->loadObjectList();

            $options = array();

            foreach ($result as $item)
            {
                $options[] = JHtml::_('select.option', $item->id, $item->type);
            }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}