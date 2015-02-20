<?php
/*********************************************************************
    class.serial.php

    Serial class

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2013-2014 DQSupport
	http://www.dqserv.com/
	Released under the GNU General Public License WITHOUT ANY WARRANTY.      
	Derived from osTicket by Peter Rotich <peter@osticket.com>.    
	
	See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

class Serial {
    var $id;

    var $email;
    var $sla;
    var $manager;
    var $members;
    var $groups;
	var $serial;

    var $ht;

    const ALERTS_DISABLED = 2;
    const ALERTS_DEPT_AND_GROUPS = 1;
    const ALERTS_DEPT_ONLY = 0;

    function Serial($id) {
        $this->id=0;
        $this->load($id);
    }

    function load($id=0) {
        global $cfg;

        if(!$id && !($id=$this->getId()))
            return false;

        $sql='SELECT slno.*,slno.serial_id as id,slno.serial_no as serialno '
			.' ,slno.model_no as model, slno.manufacturer as manu, slno.invoice_date as invoicedate ' 
			.' ,slno.invoice_no as invoiceno, slno.billing_name as billname, slno.billing_address as billadd ' 
			.' ,slno.billing_location as blocn, slno.billing_customer_type as billtype '
			
            .' FROM '.SERIAL_TABLE.' slno '
            .' WHERE slno.serial_id='.db_input($id)
            .' GROUP BY slno.serial_id';

        if(!($res=db_query($sql)) || !db_num_rows($res))
            return false;



        $this->ht=db_fetch_array($res);
        $this->id=$this->ht['serial_id'];
        
        return true;
    }

    function reload() {
        return $this->load();
    }

    function asVar() {
        return $this->getSerial();
    }

    function getId() {
        return $this->id;
    }

    function getSerialId() {
        return $this->ht['serial_id'];
    }
	
	function getSerial() {
        return $this->ht['serial_no'];
    }
	
	function getModel() {
        return $this->ht['model_no'];
    }
	
	function getManufacturer() {
        return $this->ht['manufacturer'];
    }
	
	
	function getInvoiceDate() {
        return $this->ht['invoice_date'];
    }

	function getInvoiceNo() {
        return $this->ht['invoice_no'];
    }
	
	
	function getBillingName() {
        return $this->ht['billing_name'];
    }
	
	function getBillingAddress() {
        return $this->ht['billing_address'];
    }
	
	function getBillingLocation() {
        return $this->ht['billing_location'];
    }
	
	function getBillingType() {
        return $this->ht['billing_customer_type'];
    }
	
	
	function getSerialNo() {

        if(!$this->serial && $this->getSerialId())
            $this->serial=Serial::lookup($this->getSerialId());

        return $this->serial;
    }


    function getHashtable() {
        return $this->ht;
    }

    function getInfo() {
        return  $this->getHashtable();
    }

   

   
    function update($vars, &$errors) {

        if(!$this->save($this->getId(), $vars, $errors))
            return false;

        
        $this->reload();

        return true;
    }

   
    function __toString() {
        return $this->getSerial();
    }

    /*----Static functions-------*/
	function getIdBySerial($name) {
        $id=0;
        $sql ='SELECT serial_id FROM '.SERIAL_TABLE.' WHERE serial_no='.db_input($name);
        if(($res=db_query($sql)) && db_num_rows($res))
            list($id)=db_fetch_row($res);

        return $id;
    }

    function lookup($id) {
        return ($id && is_numeric($id) && ($serial = new Serial($id)) && $serial->getId()==$id)?$serial:null;
    }

    function getSerialById($id) {

        if($id && ($serial=Serial::lookup($id)))
            $name= $serial->getSerial();

        return $name;
    }

    

    function getSerials( $criteria=null) {

        $serials=array();
        $sql='SELECT serial_id, serial_no FROM '.SERIAL_TABLE.' WHERE 1';

        $sql.=' ORDER BY serial_no';

        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($id, $name)=db_fetch_row($res))
                $serials[$id] = $name;
        }

        return $serials;
    }

    
  
	function create($vars, &$errors) {
        return self::save(0, $vars, $errors);
    }


    function save($id, $vars, &$errors) {
        global $cfg;

        if($id && $id!=$vars['id'])
            $errors['err']=__('Missing or invalid Serial ID (internal error).');

        if(!$vars['serialno']) {
            $errors['serialno']=__('Serial No required');
        } elseif(strlen($vars['serialno'])<4) {
            $errors['serialno']=__('Serial No is too short.');
        } elseif(($did=Serial::getIdBySerial($vars['serialno'])) && $did!=$id) {
            $errors['serialno']=__('Serial No already exists');
        }

        
        if($errors) return false;


        $sql='SET updated=NOW() '
            .' ,model_no='.db_input(isset($vars['model'])?$vars['model']:0)
            .' ,manufacturer='.db_input(isset($vars['manu'])?$vars['manu']:'Posiflex')
            .' ,serial_no='.db_input(isset($vars['serialno'])?$vars['serialno']:0)
            .' ,invoice_no='.db_input(isset($vars['invoiceno'])?$vars['invoiceno']:0)
            .' ,invoice_date='.($vars['invoicedate']?db_input(date('Y-m-d G:i',Misc::dbtime($vars['invoicedate']))):'NULL')
			.' ,billing_name='.db_input($vars['billname']?$vars['billname']:0)
            .' ,billing_location='.db_input(Format::striptags($vars['blocn']))
            .' ,billing_address='.db_input(Format::sanitize($vars['billadd']))
			.' ,billing_customer_type='.db_input(Format::sanitize($vars['billtype']));
        

        if($id) {
            $sql='UPDATE '.SERIAL_TABLE.' '.$sql.' WHERE serial_id='.db_input($id);
            if(db_query($sql) && db_affected_rows())
                return true;

            $errors['err']=sprintf(__('Unable to update %s.'), __('this serial number'))
               .' '.__('Internal error occurred');

        } else {
            if (isset($vars['id']))
                $sql .= ', serial_id='.db_input($vars['id']);

            $sql='INSERT INTO '.SERIAL_TABLE.' '.$sql.',created=NOW()';
            if(db_query($sql) && ($id=db_insert_id()))
                return $id;


            $errors['err']=sprintf(__('Unable to create %s.'), __('this serial'))
               .' '.__('Internal error occurred');

        }


        return false;
    }

}
?>
