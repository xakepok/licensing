<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Component\Router\RouterBase;
class LicensingRouter extends RouterBase
{
    public function build(&$query)
    {
        $segments = array();
        if (isset($query['view']))
        {
            $segments[] = $query['view'];
        }
        if (isset($query['id']))
        {
            $segments[] = $query['id'];
            unset($query['id']);
        };
        unset($query['view']);

        return $segments;
    }

    public function parse(&$segments)
    {
        $vars = array();
        $menu = JMenu::getInstance('site')->getActive();

        switch ($menu->query["view"])
        {
            case 'licenses':
                {
                    $vars['view'] = 'licenses';
                    break;
                }
            case 'software':
                {
                    $vars['view'] = 'software';
                    break;
                }
            case 'claims':
                {
                    $vars['view'] = 'claims';
                    break;
                }
            case 'claim':
                {
                    $vars['view'] = 'claim';
                    $vars['id'] = $segments[0];
                    break;
                }
            case 'product':
                {
                    $vars['view'] = 'product';
                    $vars['id'] = $segments[0];
                    break;
                }
        }
        return $vars;
    }
}