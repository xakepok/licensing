<?php
defined('_JEXEC') or die;

abstract class LicensingHtmlFilters
{
    //Фильтр состояний
    public static function state($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'JOPTION_SELECT_PUBLISHED');
        $options = array_merge($options, self::stateOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_state', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр заявок
    public static function claim($selected)
    {
        $options = array();
        $options[] = JHtml::_('select.option', '', 'COM_LICENSING_FILTER_CLAIM');
        $options = array_merge($options, self::claimOptions());
        $attribs = 'class="inputbox" onchange="this.form.submit()"';
        return JHtml::_('select.genericlist', $options, 'filter_claim', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр компаний
    public static function company($selected)
    {
        $options = array();
        $options[] = JHtml::_('select.option', '', 'COM_LICENSING_LICTYPE_AUTHOR');
        $options = array_merge($options, self::companiesOptions());
        $attribs = 'class="inputbox" onchange="this.form.submit()"';
        return JHtml::_('select.genericlist', $options, 'filter_company', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр компаний
    public static function lictype($selected)
    {
        $options = array();
        $options[] = JHtml::_('select.option', '', 'COM_LICENSING_FILTER_LICTYPE');
        $options = array_merge($options, self::lictypesOptions());
        $attribs = 'class="inputbox" onchange="this.form.submit()"';
        return JHtml::_('select.genericlist', $options, 'filter_licenseType', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр типов ключей
    public static function keytype($selected)
    {
        $options = array();
        $options[] = JHtml::_('select.option', '', 'COM_LICENSING_FILTER_KEYTYPE');
        $options = array_merge($options, self::keytypesOptions());
        $attribs = 'class="inputbox" onchange="this.form.submit()"';
        return JHtml::_('select.genericlist', $options, 'filter_keytype', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр ПО
    public static function software($selected)
    {
        $options = array();
        $options[] = JHtml::_('select.option', '', 'COM_LICENSING_FILTER_SOFTWARE');
        $options = array_merge($options, self::softwareOptions());
        $attribs = 'class="inputbox" onchange="this.form.submit()"';
        return JHtml::_('select.genericlist', $options, 'filter_software', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр лицензий
    public static function license($selected)
    {
        $options = array();
        $options[] = JHtml::_('select.option', '', 'COM_LICENSING_FILTER_LICENSE');
        $options = array_merge($options, self::licensesOptions());
        $attribs = 'class="inputbox" onchange="this.form.submit()"';
        return JHtml::_('select.genericlist', $options, 'filter_license', $attribs, 'value', 'text', $selected, null, true);
    }

    //Список заявок
    protected function claimOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `empl_fio`, DATE_FORMAT(`dat`,'%d.%m.%Y') as `dat`")
            ->from('#__licensing_claims')
            ->where('`state` = 0')
            ->order("`dat` DESC");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $name = sprintf("№%s, %s - %s", $item->id, $item->empl_fio, $item->dat);
            $options[] = JHtml::_('select.option', $item->id, $name);
        }

        return $options;
    }

    //Список ПО
    protected function softwareOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `name`")
            ->from('#__licensing_software')
            ->order("`name`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->name);
        }

        return $options;
    }

    //Список типов ключей
    protected function keytypesOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `type`")
            ->from('#__licensing_type_keys')
            ->order("`type`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->type);
        }

        return $options;
    }

    //Список компаний
    protected function companiesOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `name`")
            ->from('#__licensing_companies')
            ->order("`name`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->name);
        }

        return $options;
    }

    //Список лицензий
    protected function licensesOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `name`")
            ->from('#__licensing_licenses')
            ->order("`name`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->name);
        }

        return $options;
    }

    //Список типов лицензий
    protected function lictypesOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `type`")
            ->from('#__licensing_type_licenses')
            ->order("`type`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->type);
        }

        return $options;
    }

    //Список состояний модели
    public static function stateOptions()
    {
        $options = array();

        $options[] = JHtml::_('select.option', '1', 'JPUBLISHED');
        $options[] = JHtml::_('select.option', '0', 'JUNPUBLISHED');
        $options[] = JHtml::_('select.option', '2', 'JARCHIVED');
        $options[] = JHtml::_('select.option', '-2', 'JTRASHED');
        $options[] = JHtml::_('select.option', '*', 'JALL');

        return $options;
    }
}