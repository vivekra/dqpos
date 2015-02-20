<?php
/*********************************************************************
    http.php

    HTTP controller for the TESNOW API

    Copyright (c)  2013-2014 DQSupport
    http://www.dqserv.com/
    
	Released under the GNU General Public License WITHOUT ANY WARRANTY.
    Derived from osTicket.
    Jared Hancock <jared@osticket.com>
	
	See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require 'api.inc.php';

# Include the main api urls
require_once INCLUDE_DIR."class.dispatcher.php";

$dispatcher = patterns('',
        url_post("^/tickets\.(?P<format>xml|json|email)$", array('api.tickets.php:TicketApiController','create')),
        url('^/tasks/', patterns('',
                url_post("^cron$", array('api.cron.php:CronApiController', 'execute'))
         ))
        );

Signal::send('api', $dispatcher);

# Call the respective function
print $dispatcher->resolve($ost->get_path_info());
?>
