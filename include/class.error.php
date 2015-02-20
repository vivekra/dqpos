<?php
/*********************************************************************
    class.error.php

    Error handling for PHP < 5.0. Allows for returning a formal error from a
    function since throwing it isn't available. Also allows for consistent
    logging and debugging of errors in the osTicket system log.

    Peter Rotich <peter@osticket.com>
    Jared Hancock <jared@osticket.com>
    Copyright (c)  2013-2014 DQSupport   http://www.dqserv.com/   Released under the GNU General Public License WITHOUT ANY WARRANTY.      Derived from osTicket by Peter Rotich <peter@osticket.com>.    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

class Error extends Exception {
    static $title = '';
    static $sendAlert = true;

    function __construct($message) {
        global $ost;

        parent::__construct(__($message));
        $message = str_replace(ROOT_DIR, '(root)/', _S($message));

        if ($ost->getConfig()->getLogLevel() == 3)
            $message .= "\n\n" . $this->getBacktrace();

        $ost->logError($this->getTitle(), $message, static::$sendAlert);
    }

    function getTitle() {
        return get_class($this) . ': ' . _S(static::$title);
    }

    function getBacktrace() {
        return str_replace(ROOT_DIR, '(root)/', $this->getTraceAsString());
    }
}

class InitialDataError extends Error {
    static $title = 'Problem with install initial data';
}

function raise_error($message, $class=false) {
    if (!$class) $class = 'Error';
    new $class($message);
}

// File storage backend exceptions
class IOException extends Error {
    static $title = 'Unable to read resource content';
}

?>
