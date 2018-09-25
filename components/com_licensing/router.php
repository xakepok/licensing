<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Component\Router\RouterView;
class LicensingRouter extends RouterView
{
    public function build(&$query)
    {
        $segments = array();
        if (isset($query['view']))
        {
            $segments[] = $query['view'];
            unset($query['view']);
        }
        /*(if (isset($query['id']))
        {
            $segments[] = $query['id'];
            unset($query['id']);
        };*/

        return $segments;
    }

    /*public function parse(&$segments)
    {
        $vars = array();

        switch ($segments[0])
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
                    $vars['id'] = $segments[1];
                    break;
                }
        }
        return $vars;
    }*/
}