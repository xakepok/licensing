<?php
use Joomla\CMS\Language\Text;
jimport('joomla.log.logger.formattedtext');

defined('_JEXEC') or die;

class LicensingHelper
{
	public function addSubmenu(string $vName): void
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

	/*Добавляем запись в историю API-запросов*/
	public static function addApiHistory(int $uid): void
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $uri = JUri::getInstance();
        $uid = $db->quote($uid);
        $getQuery = $db->quote($uri->toString());
        $values = array($uid, $getQuery);
        $query
            ->insert("`#__licensing_api_history`")
            ->columns(array($db->quoteName("uid"), $db->quoteName("query")))
            ->values(implode(', ', $values));
        $db->setQuery($query)->execute();
    }

	/*Получаем ID пункта меню*/
    public static function getItemid(string $view): int
    {
        $id = 0;
        $items = JFactory::getApplication()->getMenu( 'site' )->getItems( 'component', 'com_licensing');
        foreach ($items as $item) {
            if($item->query['view'] === $view){
                $id = (int) $item->id;
            }
        }
        return $id;
    }

	/* Уведомляем юзера об отклонении заявки */
	public static function sendDecline(): void
    {
        if (self::getParams('notify_users_decline') == false) return;
        $claims = self::getEmailsNotify('users');
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
    }

	/* Отправка ссылок на ПО юзеру при одобрении заявки */
	public static function sendKeys(): void
    {
        if (self::getParams('notify_users') == false) return;
        $claims = self::getEmailsNotify('users');
        $config =& JFactory::getConfig();
        foreach ($claims as $claim)
        {
            $mailer =& JFactory::getMailer();
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
    }

    /* Уведомление юзеров о новой заявке на лицензию */
    public static function notifyUser(int $claimID): void
    {
        if (self::getParams('notify_users_create') == false) return;
        $config =& JFactory::getConfig();
        $mailer =& JFactory::getMailer();
        $sender = array($config->get('config.mailfrom'), $config->get('config.fromname'));
        $mailer->setSender($sender);
        $mailer->setSubject(self::getParams('notify_user_theme_create'));
        $mailer->addRecipient($_POST['jform']['email']);
        $body = sprintf(self::getParams('notify_user_text_create'), $claimID);
        $mailer->isHtml(true);
        $mailer->setBody($body);
        $mailer->Send();
    }

	/* Уведомление админов о новой заявке на лицензию */
	public static function notifyAdmin(int $claimID): void
    {
        if (self::getParams('notify_new_order') == false) return;
        $logger = new JLogLoggerFormattedtext(self::getLogOptions());
        $mailer =& JFactory::getMailer();
        $config =& JFactory::getConfig();
        $sender = array($config->get('config.mailfrom'), $config->get('config.fromname'));
        $mailer->setSender($sender);
        $mailer->setSubject(self::getParams('notify_new_order_theme'));
        $users = self::getEmailsNotify('admins');

        foreach ($users as $user)
        {
            if (self::checkAdminNotify($user->id)) $mailer->addRecipient($user->email, $user->name);
            $log = new JLogEntry($user->email, JLog::INFO, 'com_licensing');
            $logger->addEntry($log);
        }
        $url = JHtml::link("http://ais.bmstu.ru/administrator/index.php?option=com_licensing&view=orders&filter_claim={$claimID}", 'Посмотреть');
        $body = sprintf(self::getParams('notify_new_order_text'), $url);
        $mailer->isHtml(true);
        $mailer->setBody($body);

        if (count($mailer->getAllRecipientAddresses()) > 0)
        {
            try {
                $mailer->Send();
            }
            catch (Exception $exception)
            {
                $log = new JLogEntry($exception->getMessage(), JLog::ALERT, 'com_licensing');
                $logger->addEntry($log);
            }
        }
        else
        {
            $log = new JLogEntry("Empty list of Emails. ClaimID: {$claimID}", JLog::WARNING, 'com_licensing');
            $logger->addEntry($log);
        }
    }

    /* Проверка прав на получение email */
    static function checkAdminNotify(int $id): bool
    {
        $user = JFactory::getUser($id);
        return $user->authorise('core.notify.neworder', 'com_licensing');
    }

    /*
     * Список email админов или пользователей для уведомления о новой заявке
     * $type = 'admins' для получения email администраторов при уведомлении о новой заявке
     * $type = 'users' для получения email пользователей при одобрении заявки
     * */
    static function getEmailsNotify(string $type): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        if ($type == 'admins')
        {
            $mingroup = self::getParams('notify_new_order_group');
            $query
                ->select('`u`.`email`, `u`.`id`')
                ->from('`#__user_usergroup_map` as `l`')
                ->leftJoin('`#__users` as `u` ON `u`.`id` = `l`.`user_id`')
                ->where("`l`.`group_id` >= {$mingroup}");
        }
        if ($type == 'users')
        {
            $cid = implode(', ', JFactory::getApplication()->input->get('cid', array(), 'array'));
            $query
                ->select('`id`, `email`, `empl_fio`')
                ->from('`#__licensing_claims`')
                ->where("`id` IN ({$cid})");
        }
        return $db->setQuery($query)->loadObjectList();
    }

    public static function getParams($name, $default = false)
    {
        $options = JComponentHelper::getComponent('com_licensing')->getParams();
        return $options->get($name, $default);
    }

    public static function dump($var): void
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    static function getLogOptions(): array
    {
        return array('text_file' => 'error.php', 'text_file_path' => null, 'text_file_no_php' => false, 'text_entry_format' => '');
    }
}
