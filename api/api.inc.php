<?php
/*********************************************************************
    api.inc.php

    File included on every API page...handles common includes.

    Copyright (c)  2013-2014 DQSupport   
	http://www.dqserv.com/   
	
	Released under the GNU General Public License WITHOUT ANY WARRANTY.      
	Derived from osTicket by Peter Rotich <peter@osticket.com>.    
	
	See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
file_exists('../main.inc.php') or die('System Error');

// Disable sessions for the API. API should be considered stateless and
// shouldn't chew up database records to store sessions
define('DISABLE_SESSION', true);

require_once('../main.inc.php');
require_once(INCLUDE_DIR.'class.http.php');
require_once(INCLUDE_DIR.'class.api.php');

?>
