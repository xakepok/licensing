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
            $mailer->setSubject('Ключи');

            $email = $claim->email;
            $fio = $claim->empl_fio;
            $body = "";
            foreach ($keys as $key)
            {
                if ($key->id == $claim->id)
                {
                    $body .= sprintf("%s: %s (%s шт.)", $key->software, $key->key, $key->cnt);
                    $body .= "<br>";
                }
            }
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
        $url = JHtml::link("http://localhost/administrator/index.php?option=com_licensing&view=orders&filter_claim={$claimID}", 'Посмотреть');
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
     * Список email пользователей для уведомления о новой заявке
     */
    static function getEmailsNotify()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`u`.`email`')
            ->from('`#__user_usergroup_map` as `l`')
            ->leftJoin('`#__users` as `u` ON `u`.`id` = `l`.`user_id`')
            ->where($db->quoteName('group_id')." > 4");
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

    public function getParams($name, $default = false)
    {
        $options = JComponentHelper::getComponent('com_licensing')->getParams();
        return $options->get($name, $default);
    }
}
