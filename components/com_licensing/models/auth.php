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
        $users = $ldap->searchUsers('', '', $this->guid);
        $user = $this->parseUser($users);
        try
        {
            $u = JUser::getInstance();
            $u->bind($user);
            $u->save();
            $credentials = array('username' => $u->username, 'password' => 'test');
            $options = array('remember' => true);
            JFactory::getApplication()->login($credentials, $options);
        }
        catch (Exception $exception)
        {
            sprintf("Error: %s", $exception->getMessage());
        }
        JFactory::getApplication()->redirect('index.php');
        jexit();

        return $user;
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
