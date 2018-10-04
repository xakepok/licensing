<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldSoftware extends JFormFieldList  {
    protected  $type = 'Software';
    protected $needFilter = array('key'); //Список вьюшек, нуждающихся в фильтрации по активности

    protected function getOptions()
    {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select("`s`.`id`, `s`.`name`")
                ->from('#__licensing_software as `s`')
                ->leftJoin('#__licensing_licenses as `l` ON `l`.`id` = `s`.`licenseID`')
                ->order("`s`.`name`")
                ->where('`s`.`state` = 1 AND `l`.`state` = 1')
                ->where('`l`.`available` = 1');

            $view = JFactory::getApplication()->input->getString('view');
            $id = JFactory::getApplication()->input->getInt('id', 0);

            /*Фильтр по активности*/
            if (in_array($view, $this->needFilter) && $id == 0)
            {
                $query
                    ->where('(`l`.`dateExpires` > CURRENT_DATE()) OR (`l`.`unlim` = 1)');
            }

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