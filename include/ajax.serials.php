<?php
/*********************************************************************
    ajax.serials.php

    AJAX interface for  serials (based on submitted tickets)
    Copyright (c)  2013-2014 DQSupport   
	http://www.dqserv.com/   
	Released under the GNU General Public License WITHOUT ANY WARRANTY.      
	Derived from osTicket by Peter Rotich <peter@osticket.com>.    
	
	See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

if(!defined('INCLUDE_DIR')) die('403');

include_once(INCLUDE_DIR.'class.ticket.php');
require_once INCLUDE_DIR.'class.note.php';

class SerialsAjaxAPI extends AjaxController {

    /* Assumes search by emal for now */
    function search($type = null) {

        if(!isset($_REQUEST['q'])) {
            Http::response(400, 'Query argument is required');
        }

        $limit = isset($_REQUEST['limit']) ? (int) $_REQUEST['limit']:25;
        $serials=array();

        $escaped = db_input(strtolower($_REQUEST['q']), false);
        		
		$sql='SELECT DISTINCT slno.serial_id, slno.serial_no, slno.model_no, slno.manufacturer, slno.invoice_no, slno.invoice_date, slno.billing_name,  slno.billing_address, slno.billing_location '
			 .' FROM '.SERIAL_TABLE.' slno '
			 .' WHERE slno.serial_no LIKE \'%'.$escaped.'%\''
			 .' ORDER BY slno.created '
             .' LIMIT '.$limit;
		
        if(($res=db_query($sql)) && db_num_rows($res)){
            while(list($id,$slno,$model,$manu,$invno,$invdate,$billname,$billadd,$blocn)=db_fetch_row($res)) {
						
				$slno = Format::htmlchars($slno);
				$model = Format::htmlchars($model);
				$manu = Format::htmlchars($manu);
				$invno = Format::htmlchars($invno);
				$invdate = Format::db_date($invdate);				
				$billname = Format::htmlchars($billname);
				$billadd = Format::htmlchars($billadd);				
				$blocn = Format::htmlchars($blocn);	
                $serials[] = array('serialno'=>$slno, 'model'=>$model, 'manu'=>$manu, 'invoiceno'=>$invno, 'invoicedate'=>$invdate, 'billname'=>$billname, 'billadd'=>$billadd, 'blocn'=>$blocn, 'info' =>"$slno - $invno - $billname",
                    'id' => $id, '/bin/true' => $_REQUEST['q']);
            }
        }

        return $this->json_encode(array_values($serials));

    }

}
?>
				
				
				
