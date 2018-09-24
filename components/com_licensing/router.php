<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class LicensingRouter extends JComponentRouterBase
{
    public function build(&$query)
    {
        $segments = array();
        if ($query['view'] == 'licenses' || $query['view'] == 'software' || $query['view'] == 'claims') {
            unset($query['view']);
        }
        return $segments;
    }
    public function parse(&$segments)
    {
        $vars = array();
        $menu = JMenu::getInstance('mainmenu')->getActive();
        switch ($menu->query["view"]) {
            case 'licenses': {
                $vars['view'] = 'licenses';
                break;
            }
            case 'software': {
                $vars['view'] = 'software';
                break;
            }
        }
        return $vars;
    }
}