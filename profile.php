<?php
/*********************************************************************
    profile.php

    Manage client profile. This will allow a logged-in user to manage
    his/her own public (non-internal) information

    Peter Rotich <peter@osticket.com>
    Jared Hancock <jared@osticket.com>
    Copyright (c)  2013-2014 DQSupport   http://www.dqserv.com/   Released under the GNU General Public License WITHOUT ANY WARRANTY.      Derived from osTicket by Peter Rotich <peter@osticket.com>.    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
require 'secure.inc.php';

require_once 'class.user.php';
$user = User::lookup($thisclient->getId());

if ($user && $_POST) {
    $errors = array();
    if ($acct = $thisclient->getAccount()) {
       $acct->update($_POST, $errors);
    }
    if (!$errors && $user->updateInfo($_POST, $errors))
        Http::redirect('tickets.php');
}

$inc = 'profile.inc.php';

include(CLIENTINC_DIR.'header.inc.php');
include(CLIENTINC_DIR.$inc);
include(CLIENTINC_DIR.'footer.inc.php');

