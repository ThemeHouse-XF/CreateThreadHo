<?php

class ThemeHouse_CreateThreadHo_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_CreateThreadHo' => array(
                'controller' => array(
                    'XenForo_ControllerPublic_Forum'
                ),
            ),
        );
    }

    public static function loadClassController($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_CreateThreadHo_Listener_LoadClass', $class, $extend, 'controller');
    }
}