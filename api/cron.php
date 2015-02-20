<?php
/*********************************************************************
    cron.php

    File to handle LOCAL cron job calls.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2013-2014 DQSupport   http://www.dqserv.com/   Released under the GNU General Public License WITHOUT ANY WARRANTY.      Derived from osTicket by Peter Rotich <peter@osticket.com>.    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
@chdir(dirname(__FILE__).'/'); //Change dir.
require('api.inc.php');

if (!osTicket::is_cli())
    die(__('cron.php only supports local cron calls - use http -> api/tasks/cron'));

require_once(INCLUDE_DIR.'api.cron.php');
LocalCronApiController::call();
?>
