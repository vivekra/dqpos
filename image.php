<?php
/*********************************************************************
    image.php

    Simply downloads the file...on hash validation as follows;

    * Hash must be 64 chars long.
    * First 32 chars is the perm. file hash
    * Next 32 chars  is md5(file_id.session_id().file_hash)

    Copyright (c)  2013-2014 DQSupport
    http://www.dqserv.com/
    
    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    Derived from osTicket by Peter Rotich <peter@osticket.com>.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

require('client.inc.php');
require_once(INCLUDE_DIR.'class.file.php');
$h=trim($_GET['h']);
//basic checks
if(!$h  || strlen($h)!=64  //32*2
        || !($file=AttachmentFile::lookup(substr($h,0,32))) //first 32 is the file hash.
        || strcasecmp($h, $file->getDownloadHash())) //next 32 is file id + session hash.
    Http::response(404, __('Unknown or invalid file'));

$file->display();
?>
