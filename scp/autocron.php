<?php
/*********************************************************************
    cron.php

    Auto-cron handle.
    File requested as 1X1 image on the footer of every staff's page

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2013-2014 DQSupport   http://www.dqserv.com/   Released under the GNU General Public License WITHOUT ANY WARRANTY.      Derived from osTicket by Peter Rotich <peter@osticket.com>.    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
define('AJAX_REQUEST', 1);
require('staff.inc.php');
ignore_user_abort(1);//Leave me a lone bro!
@set_time_limit(0); //useless when safe_mode is on
$data=sprintf ("%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%",
        71,73,70,56,57,97,1,0,1,0,128,255,0,192,192,192,0,0,0,33,249,4,1,0,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59);

header('Content-type:  image/gif');
header('Cache-Control: no-cache, must-revalidate');
header('Content-Length: '.strlen($data));
header('Connection: Close');
print $data;
// Flush the request buffer
while(@ob_end_flush());
flush();
//Terminate the request
if (function_exists('fastcgi_finish_request'))
    fastcgi_finish_request();

ob_start(); //Keep the image output clean. Hide our dirt.
//TODO: Make cron DB based to allow for better time limits. Direct calls for now sucks big time.
//We DON'T want to spawn cron on every page load...we record the lastcroncall on the session per user
$sec=time()-$_SESSION['lastcroncall'];
$caller = $thisstaff->getUserName();

if($sec>180 && $ost && !$ost->isUpgradePending()): //user can call cron once every 3 minutes.
require_once(INCLUDE_DIR.'class.cron.php');

$thisstaff = null; //Clear staff obj to avoid false credit internal notes & auto-assignment
Cron::TicketMonitor(); //Age tickets: We're going to age tickets regardless of cron settings.

// Run file purging about every 30 minutes
if (mt_rand(1, 9) == 4)
    Cron::CleanOrphanedFiles();

if($cfg && $cfg->isAutoCronEnabled()) { //ONLY fetch tickets if autocron is enabled!
    Cron::MailFetcher();  //Fetch mail.
    $ost->logDebug(_S('Auto Cron'), sprintf(_S('Mail fetcher cron call [%s]'), $caller));
}

$data = array('autocron'=>true);
Signal::send('cron', $data);

$_SESSION['lastcroncall']=time();
endif;
ob_end_clean();
?>
