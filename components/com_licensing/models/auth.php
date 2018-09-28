<?php
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
defined('_JEXEC') or die;

class LicensingModelAuth extends BaseDatabaseModel
{
    public function __construct(array $config = array())
    {
        $this->guid = JFactory::getApplication()->input->getString('guid');
        parent::__construct($config);
    }

    public function getUser()
    {
        $ldap = BaseDatabaseModel::getInstance('Ldap', 'LicensingModel');
        $users = $ldap->searchUsers(false, $this->guid);

        $user = $this->parseUser($users);

        $fieldID = LicensingHelper::getGuidField('guid');
        unset($user['guid']);
        try
        {
            $u = JUser::getInstance();
            $u->bind($user);
            $u->save();
            $credentials = array('username' => $u->username, 'password' => 'test');
            $options = array('remember' => (bool) LicensingHelper::getParams('auth_save'));
            JFactory::getApplication()->login($credentials, $options);
            $u = JFactory::getUser();
            $userID = $u->id;
            if ($userID != 0 && $fieldID != 0) $this->insertNewGuid($fieldID, $userID, $this->guid);
        }
        catch (Exception $exception)
        {
            sprintf("Error: %s", $exception->getMessage());
        }
        JFactory::getApplication()->redirect('/');
        jexit();

        return $user;
    }

    /* Вставляем запись с новым юзером гуида */
    private function insertNewGuid($fieldID, $uid, $guid)
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query->select("value")
            ->from("#__fields_values")
            ->where("`field_id` = '{$fieldID}' AND `item_id` = '{$uid}'");
        $cnt = count($db->setQuery($query, 0, 1)->loadObjectList());
        if ($cnt < 1)
        {
            $values = array($db->quote($fieldID), $db->quote($uid), $db->quote($guid));
            $query = $db->getQuery(true);
            $query->insert("`#__fields_values`")
                ->columns("`field_id`, `item_id`, `value`")
                ->values(implode(", ", $values));
            $db->setQuery($query)->execute();
        }
    }

    private function parseUser($users)
    {
        $config = JComponentHelper::getParams('com_users');
        $arr = array();
        foreach ($users as $item => $p) {
            if ($p["cn"][0] === NULL) continue;
            $arr["name"] = $p["cn"][0];
            $arr["username"] = $p["uid"][0];
            $arr["email"] = $p["mail"][0];
            $arr["password"] = 'test';
            $arr["groups"] = array($config->get('new_usertype'));
            $arr["sendEmail"] = 1;
            $arr["activation"] = 0;
            break;
        }
        return $arr;
    }

    private $guid;
}
