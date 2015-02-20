<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($serial && $_REQUEST['a']!='add') {
    //Editing Department.
    $title=__('Update Serial');
    $action='update';
    $submit_text=__('Save Changes');
    $info=$serial->getInfo();
    $info['id']=$serial->getId();
  //  $info['groups'] = $serial->getAllowedGroups();

    $qstr.='&id='.$serial->getId();
} else {
    $title=__('Add New Serial');
    $action='create';
    $submit_text=__('Create Serial');
 //   $info['ispublic']=isset($info['ispublic'])?$info['ispublic']:1;
  //  $info['ticket_auto_response']=isset($info['ticket_auto_response'])?$info['ticket_auto_response']:1;
  //  $info['message_auto_response']=isset($info['message_auto_response'])?$info['message_auto_response']:1;
    //if (!isset($info['group_membership']))
     //   $info['group_membership'] = 1;

    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<form action="serials.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
 <h2><?php echo __('Serials');?></h2>
 <table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4><?php echo $title; ?></h4>
                <em><?php echo __('Product Information');?></em>
            </th>
        </tr>
    </thead>
   <tbody>

            <tr>
            <td width="160" nowrap required="required"><?php echo __('Serial No'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="serialno" id="serialno" value="<?php echo $info['serialno']; ?>" /> </span>
                <font class="error"><?php echo $errors['serialno']; ?></font>
            </td>
        </tr>	
		
			
		<tr>
            <td width="160" nowrap required="required"><?php echo __('Model No'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="model" id="model" value="<?php echo $info['model']; ?>" /> </span>
                 <font class="error">* <?php echo $errors['model']; ?></font>
            </td>
        </tr>	
		
		
		<tr>
            <td width="160" nowrap required="required"><?php echo __('Manufacturer'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="manu" id="manu" value="<?php echo $info['manu']; ?>" /> </span>
                 <font class="error">* <?php echo $errors['manu']; ?></font>
            </td>
        </tr>			
			
		
			
			
			
			
			
	
	</tbody>
	
		<tbody>
        <tr>
            <th colspan="2">
                <em><strong><?php echo __('Billing Information');?></strong>:</em>
            </th>
        </tr>
		
		<tr>
            <td width="160" nowrap required="required"><?php echo __('Invoice No'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="invoiceno" id="invoiceno" value="<?php echo $info['invoiceno']; ?>" /> </span>
                <font class="error">* <?php echo $errors['invoiceno']; ?></font>
            </td>
        </tr>	

        
		 <tr>
            <td width="160">
                <?php echo __('Invoice Date');?>:
            </td>
            <td>
                <input id="invoicedate" name="invoicedate" value="<?php echo Format::htmlchars($info['invoicedate']); ?>" size="12" autocomplete=OFF>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['invoicedate']; ?></font>
                
            </td>
        </tr>	
		
				
		
       <tr>
            <td width="160" nowrap required="required"><?php echo __('Billing Name'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="billname" id="billname" value="<?php echo $info['billname']; ?>" /> </span>
                <font class="error">* <?php echo $errors['billname']; ?></font>
            </td>
        </tr>	
		
		
		<tr>
            <td width="160" nowrap required="required"><?php echo __('Billing Address'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="billadd" id="billadd" value="<?php echo $info['billadd']; ?>" /> </span>
                <font class="error"><?php echo $errors['billadd']; ?></font>
            </td>
        </tr>	
		
		<tr>
            <td width="160" nowrap required="required"><?php echo __('Billing Location'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="blocn" id="blocn" value="<?php echo $info['blocn']; ?>" /> </span>
                <font class="error"><?php echo $errors['blocn']; ?></font>
            </td>
        </tr>	
		
		<tr>
            <td width="160">
                <?php echo __('Billing Type');?>:
            </td>
            <td>
                <select name="billtype">
                    <option value="Direct" selected="selected"><?php echo __('Direct'); ?></option>
                    <option value="Indirect" <?php echo ($info['billtype']=='Indirect')?'selected="selected"':''; ?>><?php echo __('Indirect'); ?></option>
                    <option value="Service Provider" <?php echo ($info['billtype']=='Service Provider')?'selected="selected"':''; ?>><?php echo __('Service Provider'); ?></option>
					<option value="Sales Partner" <?php echo ($info['billtype']=='Sales Partner')?'selected="selected"':''; ?>><?php echo __('Sales Partner'); ?></option>
					<option value="Other" <?php echo ($info['billtype']=='Other')?'selected="selected"':''; ?>><?php echo __('Other'); ?></option>
				</select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['billtype']; ?></font>
            </td>
        </tr>
		
		
	</tbody>
	
</table>
<p style="text-align:center">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>">
    <input type="reset"  name="reset"  value="<?php echo __('Reset');?>">
    <input type="button" name="cancel" value="<?php echo __('Cancel');?>" onclick='window.location.href="departments.php"'>
</p>
</form>
