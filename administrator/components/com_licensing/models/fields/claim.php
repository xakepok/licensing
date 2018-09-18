<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldClaim extends JFormFieldList  {
    protected  $type = 'Claim';

    protected function getOptions()
    {
        $claimID = JFactory::getApplication()->input->getInt('claimID', 0);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `empl_fio`, `dat`")
            ->from('#__licensing_claims')
            ->order("`id`");
        if ($claimID > 0) $query->where("`id` = {$claimID}");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $name = sprintf("№%s, %s - %s", $item->id, $item->empl_fio, $item->dat);
            $options[] = JHtml::_('select.option', $item->id, $name);
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}