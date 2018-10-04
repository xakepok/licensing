<?php
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class LicensingHelper
{
	public function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING'), 'index.php?option=com_licensing&view=licensing', $vName == 'licensing');
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING_MENU_CLAIMS'), 'index.php?option=com_licensing&view=claims', $vName == 'claims');
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING_MENU_ORDERS'), 'index.php?option=com_licensing&view=orders', $vName == 'orders');
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING_MENU_KEYS'), 'index.php?option=com_licensing&view=keys', $vName == 'keys');
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING_MENU_SOFTWARES'), 'index.php?option=com_licensing&view=softwares', $vName == 'softwares');
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING_MENU_LICENSES'), 'index.php?option=com_licensing&view=licenses', $vName == 'licenses');
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING_MENU_COMPANIES'), 'index.php?option=com_licensing&view=companies', $vName == 'companies');
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING_MENU_LICTYPES'), 'index.php?option=com_licensing&view=lictypes', $vName == 'lictypes');
		JHtmlSidebar::addEntry(Text::_('COM_LICENSING_MENU_KEYTYPES'), 'index.php?option=com_licensing&view=keytypes', $vName == 'keytypes');
	}

	/*Получаем ID пункта меню*/
    public static function getItemid($view)
    {
        $items = JFactory::getApplication()->getMenu( 'site' )->getItems( 'component', 'com_licensing' );
        foreach ( $items as $item ) {
            if($item->query['view'] === $view){
                return $item->id;
            }
        }
    }

	/* Проверяем, студент или нет */
    public static function isStudent($guid)
    {
        $xml = @file_get_contents("http://ud-dream.eu.bmstu.ru/api/v2/php_get_student?api_key=ed5b34dbbf2ae840af4e23084502794d16a258eb28ef52bda0b0cac03f49a53c&guid={$guid}");
        return ($xml === false) ? false : true;
    }

	/* Получаем GUID юзера из базы */
    public static function getUserGuid()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fieldID = $db->quote(self::getGuidField('guid'));
        $userID = $db->quote(JFactory::getUser()->id);
        $query->select('`value`')
            ->from('#__fields_values')
            ->where("`field_id` = {$fieldID} AND `item_id` = {$userID}");
        return $db->setQuery($query, 0, 1)->loadResult();
    }

    /* Получаем ID дополнительного поля для хранения GUIDа */
    public static function getGuidField($name)
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("`id`")
            ->from("`#__fields`")
            ->where("`name` LIKE '{$name}' AND `context` LIKE 'com_users.user'");
        return $db->setQuery($query, 0, 1)->loadResult();
    }

    /* Возвращаем читабельный статус заявки на ПО во фронтенде */
	public static function getStatus($id)
    {
        $statuses = array(
            0 => 'COM_LICENSING_CLAIMS_STATUS_IN_WORK',
            1 => 'COM_LICENSING_CLAIMS_STATUS_ACCEPT',
            -2 => 'COM_LICENSING_CLAIMS_STATUS_DECLINE',
            2 => 'COM_LICENSING_CLAIMS_STATUS_ARCHIVED'
        );
        return JText::_($statuses[$id]);
    }

	/* Уведомляем юзера об отклонении заявки */
	public static function sendDecline()
    {
        if (self::getParams('notify_users_decline') == false) return false;
        $claims = self::getEmailUserNotify();
        foreach ($claims as $claim)
        {
            $mailer =& JFactory::getMailer();
            $config =& JFactory::getConfig();
            $sender = array($config->get('config.mailfrom'), $config->get('config.fromname'));
            $mailer->setSender($sender);
            $mailer->setSubject(self::getParams('notify_user_theme_decline'));
            $mailer->addReplyTo(self::getParams('notify_user_replyto_decline'));
            $email = $claim->email;
            $fio = $claim->empl_fio;
            $body = sprintf(self::getParams('notify_user_text_decline'), $claim->id);
            $mailer->addRecipient($email, $fio);
            $mailer->isHtml(true);
            $mailer->setBody($body);
            $mailer->Send();
        }
        return true;
    }

	/* Уведомление админов о новой заявке на лицензию */
	public static function sendKeys($keys)
    {
        if (self::getParams('notify_users') == false) return false;
        $claims = self::getEmailUserNotify();
        foreach ($claims as $claim)
        {
            $mailer =& JFactory::getMailer();
            $config =& JFactory::getConfig();
            $sender = array($config->get('config.mailfrom'), $config->get('config.fromname'));
            $mailer->setSender($sender);
            $mailer->setSubject(self::getParams('notify_user_theme'));
            $mailer->addReplyTo(self::getParams('notify_user_replyto'));
            $email = $claim->email;
            $fio = $claim->empl_fio;
            $body = "";
            $Itemid = self::getItemid('claim');
            $app    = JApplication::getInstance('site');
            $router =& $app->getRouter();
            $url = $router->build("index.php?id={$claim->id}&Itemid={$Itemid}")->toString();
            $eventLink = "http://ais.bmstu.ru".str_replace('/administrator', '', $url);
            $link = JHtml::link($eventLink, $eventLink);
            $body .= sprintf(self::getParams('notify_user_text'), $claim->id, $link);
            $mailer->addRecipient($email, $fio);
            $mailer->isHtml(true);
            $mailer->setBody($body);
            $mailer->Send();
        }
        return true;
    }

	/* Уведомление админов о новой заявке на лицензию */
	public static function notifyAdmin($claimID)
    {
        if (self::getParams('notify_new_order') == false) return false;
        $mailer =& JFactory::getMailer();
        $config =& JFactory::getConfig();
        $sender = array($config->get('config.mailfrom'), $config->get('config.fromname'));
        $mailer->setSender($sender);
        $mailer->setSubject(self::getParams('notify_new_order_theme'));
        $users = self::getEmailsNotify();
        foreach ($users as $user)
        {
            if (self::checkUserNotify($user->id)) $mailer->addRecipient($user->email, $user->name);
        }
        $url = JHtml::link("http://ais.bmstu.ru/administrator/index.php?option=com_licensing&view=orders&filter_claim={$claimID}", 'Посмотреть');
        $body = sprintf(self::getParams('notify_new_order_text'), $url);
        $mailer->isHtml(true);
        $mailer->setBody($body);
        $mailer->Send();
        return true;
    }

    /* Проверка прав на получение email */
    static function checkUserNotify($id)
    {
        $user = JFactory::getUser($id);
        return $user->authorise('core.notify.neworder', 'com_licensing');
    }

    /*
     * Список email админов для уведомления о новой заявке
     */
    static function getEmailsNotify()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $mingroup = self::getParams('notify_new_order_group');
        $query
            ->select('`u`.`email`')
            ->from('`#__user_usergroup_map` as `l`')
            ->leftJoin('`#__users` as `u` ON `u`.`id` = `l`.`user_id`')
            ->where($db->quoteName('group_id')." >= {$mingroup}");
        return $db->setQuery($query)->loadObjectList();
    }

    /*
     * Данные пользователя для отправки ключей
     */
    static function getEmailUserNotify()
    {
        $cid = implode(', ', JFactory::getApplication()->input->get('cid', array(), 'array'));
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`id`, `email`, `empl_fio`')
            ->from('`#__licensing_claims`')
            ->where("`id` IN ({$cid})");
        return $db->setQuery($query)->loadObjectList();
    }

    public static function getParams($name, $default = false)
    {
        $options = JComponentHelper::getComponent('com_licensing')->getParams();
        return $options->get($name, $default);
    }
}
