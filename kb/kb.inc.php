<?php
/*********************************************************************
    kb.inc.php

    File included on every knowledgebase file.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2013-2014 DQSupport   http://www.dqserv.com/   Released under the GNU General Public License WITHOUT ANY WARRANTY.      Derived from osTicket by Peter Rotich <peter@osticket.com>.    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require_once('../client.inc.php');
require_once(INCLUDE_DIR.'class.faq.php');
/* Bail out if knowledgebase is disabled or if we have no public-published FAQs. */
if(!$cfg || !$cfg->isKnowledgebaseEnabled() || !FAQ::countPublishedFAQs()) {
    header('Location: ../');
    exit;
}

$nav = new UserNav($thisclient, 'kb');
?>
