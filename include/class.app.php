<?php
/*********************************************************************
    class.app.php

    Application registration system
    Apps, usually to be distributed as plugins, can register themselves
    using this utility class, and navigation links will be added to the
    staff and client interfaces.

    Copyright (c)  2013-2014 DQSupport
    http://www.dqserv.com/
    
	Released under the GNU General Public License WITHOUT ANY WARRANTY.
    Derived from osTicket.
    Peter Rotich <peter@osticket.com>
    Jared Hancock <jared@osticket.com>
	
	See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

class Application {
    private static $client_apps;
    private static $staff_apps;
    private static $admin_apps;

    function registerStaffApp($desc, $href, $info=array()) {
        self::$staff_apps[] = array_merge($info,
            array('desc'=>$desc, 'href'=>$href));
    }

    function getStaffApps() {
        return self::$staff_apps;
    }

    function registerClientApp($desc, $href, $info=array()) {
        self::$client_apps[] = array_merge($info,
            array('desc'=>$desc, 'href'=>$href));
    }

    function getClientApps() {
        return self::$client_apps;
    }

    function registerAdminApp($desc, $href, $info=array()) {
        self::$admin_apps[] = array_merge($info,
            array('desc'=>$desc, 'href'=>$href));
    }

    function getAdminApps() {
        return self::$admin_apps;
    }
}
