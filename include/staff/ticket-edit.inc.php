<script>

var rowNum = 0;
function addRow(frm) {
rowNum ++;
var row = '<p id="rowNum'+rowNum+'">Serial No: <input type="text" name="add_serial[]" size="10" value="'+frm.serialno.value+'"> Model : <input type="text" name="add_model[]" value="'+frm.model.value+'"> Manufacturer : <input type="text" name="add_manu[]" value="'+frm.manu.value+'"> <input type="button" value="Remove" onclick="removeRow('+rowNum+');"></p>';
jQuery('#itemRows').append(row);
frm.serialno.value = '';
frm.model.value = '';
frm.manu.value = '';
}

function removeRow(rnum) {
jQuery('#rowNum'+rnum).remove();
}


var city = [
		'Ahmedabad', 'Bangalore', 'Chennai', 'Delhi', 'Gujarat', 'Hyderabad', 'Jaipur', 'Kochi', 'Kolkata', 'Mumbai', 'Patna', 'Pune', 'Surat'
		];

		var manufacture = [
		'Posiflex', 'Honeywell', 'Other'
		];


$(function() {

	$( "#calllog" ).autocomplete(
	{
		 source:city
	});
		
});


$(function() {

	$( "#manufacturer" ).autocomplete(
	{
		 source:manufacture
	});
		
});
</script>


<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canEditTickets() || !$ticket) die('Access Denied');

$info=Format::htmlchars(($errors && $_POST)?$_POST:$ticket->getUpdateInfo());
if ($_POST)
    $info['duedate'] = Format::date($cfg->getDateFormat(),
       strtotime($info['duedate']));
?>
<form action="tickets.php?id=<?php echo $ticket->getId(); ?>&a=edit" method="post" id="save"  enctype="multipart/form-data">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="update">
 <input type="hidden" name="a" value="edit">
 <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
 <h2><?php echo sprintf(__('Update Ticket #%s'),$ticket->getNumber());?></h2>
 <table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <tbody>
        <tr>
            <th colspan="2">
                <em><strong><?php echo __('Customer Information'); ?></strong>: <?php echo __('Currently selected customer'); ?></em>
            </th>
        </tr>
    <?php
    if(!$info['user_id'] || !($user = User::lookup($info['user_id'])))
        $user = $ticket->getUser();
    ?>
    <tr><td><?php echo __('Customer'); ?>:</td><td>
        <div id="client-info">
            <a href="#" onclick="javascript:
                $.userLookup('ajax.php/users/<?php echo $ticket->getOwnerId(); ?>/edit',
                        function (user) {
                            $('#client-name').text(user.name);
                            $('#client-email').text(user.email);
                        });
                return false;
                "><i class="icon-user"></i>
            <span id="client-name"><?php echo Format::htmlchars($user->getName()); ?></span>
            &lt;<span id="client-email"><?php echo $user->getEmail(); ?></span>&gt;
            </a>
            <a class="action-button" style="overflow:inherit" href="#"
                onclick="javascript:
                    $.userLookup('ajax.php/tickets/<?php echo $ticket->getId(); ?>/change-user',
                            function(user) {
                                $('input#user_id').val(user.id);
                                $('#client-name').text(user.name);
                                $('#client-email').text('<'+user.email+'>');
                    });
                    return false;
                "><i class="icon-edit"></i> <?php echo __('Change'); ?></a>
            <input type="hidden" name="user_id" id="user_id"
                value="<?php echo $info['user_id']; ?>" />
        </div>
        </td></tr>
		
		 <tr>
            <td width="160" nowrap><?php echo __('Customer Ticket Ref No'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="custrefno" id="custrefno" value="<?php echo $ticket->getCustRefNo(); ?>" /> </span>
                <font class="error"><?php echo $errors['custrefno']; ?></font>
            </td>
        </tr>	
	
	<tbody>
        <tr>
            <th colspan="2">
            <em><strong><?php echo __('Product Information'); ?></strong>: <?php echo __("Add multiple Serial Nos"); ?></em>
            </th>
        </tr>
       
	</tbody>
	
	<tbody>
	
	    <tr>
            <td width="160" nowrap required="required"><?php echo __('Addition'); ?>:</td>
			<td>
            <div id="itemRows">
			<span style="display:inline-block;">
            <?php echo __('Serial No'); ?>: <input type="text" name="serialno" id="serial-no" value="<?php echo $ticket->getSerialNo(); ?>" /> </span>
				<?php echo __('Model No'); ?>: <input type="text" name="model" id="prod-model" value="<?php echo $ticket->getModel(); ?>" /> </span>
				<?php echo __('Manufacturer'); ?>: <input type="text" name="manu" id="manu" value="<?php echo $ticket->getManufacturer(); ?>" /> </span>
					<input onclick="addRow(this.form);" type="button" value="Add" />
                <font class="error">* <?php echo $errors['serialno']; ?></font>
			</div>
		</td>
		</tr>	
	</tbody>
    <tbody>
        <tr>
            <th colspan="2">
            <em><strong><?php echo __('Ticket Information'); ?></strong>: <?php echo __("Due date overrides SLA's grace period."); ?></em>
            </th>
        </tr>
        <tr>
            <td width="160" class="required">
                <?php echo __('Ticket Source');?>:
            </td>
            <td>
                <select name="source">
                    <option value="" selected >&mdash; <?php echo __('Select Source');?> &mdash;</option>
                    <option value="Phone" <?php echo ($info['source']=='Phone')?'selected="selected"':''; ?>><?php echo __('Phone');?></option>
                    <option value="Email" <?php echo ($info['source']=='Email')?'selected="selected"':''; ?>><?php echo __('Email');?></option>
                    <option value="Web"   <?php echo ($info['source']=='Web')?'selected="selected"':''; ?>><?php echo __('Web');?></option>
                    <option value="API"   <?php echo ($info['source']=='API')?'selected="selected"':''; ?>><?php echo __('API');?></option>
                    <option value="Other" <?php echo ($info['source']=='Other')?'selected="selected"':''; ?>><?php echo __('Other');?></option>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font>
            </td>
        </tr>
		
		
		<tr>
            <td width="160" class="required">
                <?php echo __('Nature of Call');?>:
            </td>
            <td>
                <select name="natureofcall">
                    <option value="Warranty" selected="selected"><?php echo __('Warranty'); ?></option>
                    <option value="SMS" <?php echo ($info['natureofcall']=='SMS')?'selected="selected"':''; ?>><?php echo __('SMS'); ?></option>
                    <option value="AMC" <?php echo ($info['natureofcall']=='AMC')?'selected="selected"':''; ?>><?php echo __('AMC'); ?></option>
					<option value="Per Call" <?php echo ($info['natureofcall']=='Per Call')?'selected="selected"':''; ?>><?php echo __('Per Call'); ?></option>
					<option value="Free Call" <?php echo ($info['natureofcall']=='Free Call')?'selected="selected"':''; ?>><?php echo __('Free Call'); ?></option>
				</select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['natureofcall']; ?></font>
            </td>
        </tr>
		
		<tr>
		<td width="160" class="required">
                <?php echo __('Ticket Created Location');?>:
            </td>
		<td><input type="text" id="calllog" name="calllog" value="<?php echo $ticket->getCallLoggedIn(); ?>" /></td> 
		</tr>
		
		<tr>
            <td width="160" nowrap><?php echo __('Vendor Ticket Ref No'); ?>:</td>
            <td>
                <span style="display:inline-block;">
                    <input type="text" name="venrefno" id="venrefno" value="<?php echo $ticket->getVendorRefNo(); ?>" /> </span>
                <font class="error"><?php echo $errors['venrefno']; ?></font>
            </td>
        </tr>	
		
		
        <tr>
            <td width="160" class="required">
                <?php echo __('Ticket Type');?>:
            </td>
            <td>
                <select name="topicId">
                    <option value="" selected >&mdash; <?php echo __('Select Ticket Type');?> &mdash;</option>
                    <?php
                    if($topics=Topic::getHelpTopics()) {
                        foreach($topics as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['topicId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['topicId']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160">
                <?php echo __('SLA Plan');?>:
            </td>
            <td>
                <select name="slaId">
                    <option value="0" selected="selected" >&mdash; <?php echo __('None');?> &mdash;</option>
                    <?php
                    if($slas=SLA::getSLAs()) {
                        foreach($slas as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['slaId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['slaId']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160">
                <?php echo __('Due Date');?>:
            </td>
            <td>
                <input class="dp" id="duedate" name="duedate" value="<?php echo Format::htmlchars($info['duedate']); ?>" size="12" autocomplete=OFF>
                &nbsp;&nbsp;
                <?php
                $min=$hr=null;
                if($info['time'])
                    list($hr, $min)=explode(':', $info['time']);

                echo Misc::timeDropdown($hr, $min, 'time');
                ?>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['duedate']; ?>&nbsp;<?php echo $errors['time']; ?></font>
                <em><?php echo __('Time is based on your time zone');?> (GMT <?php echo $thisstaff->getTZoffset(); ?>)</em>
            </td>
        </tr>
    </tbody>
</table>
<table class="form_table dynamic-forms" width="940" border="0" cellspacing="0" cellpadding="2">
        <?php if ($forms)
            foreach ($forms as $form) {
                $form->render(true, false, array('mode'=>'edit','width'=>160,'entry'=>$form));
                print $form->getForm()->getMedia();
        } ?>
</table>
<table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <tbody>
        <tr>
            <th colspan="2">
                <em><strong><?php echo __('Internal Note');?></strong>: <?php echo __('Reason for editing the ticket (required)');?> <font class="error">&nbsp;<?php echo $errors['note'];?></font></em>
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <textarea class="richtext no-bar" name="note" cols="21"
                    rows="6" style="width:80%;"><?php echo $info['note'];
                    ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
<p style="padding-left:250px;">
    <input type="submit" name="submit" value="<?php echo __('Save');?>">
    <input type="reset"  name="reset"  value="<?php echo __('Reset');?>">
    <input type="button" name="cancel" value="<?php echo __('Cancel');?>" onclick='window.location.href="tickets.php?id=<?php echo $ticket->getId(); ?>"'>
</p>
</form>
<div style="display:none;" class="dialog draggable" id="user-lookup">
    <div class="body"></div>
</div>
<script type="text/javascript">
$('table.dynamic-forms').sortable({
  items: 'tbody',
  handle: 'th',
  helper: function(e, ui) {
    ui.children().each(function() {
      $(this).children().each(function() {
        $(this).width($(this).width());
      });
    });
    ui=ui.clone().css({'background-color':'white', 'opacity':0.8});
    return ui;
  }
});


$(function() {
    $('input#serial-no').typeahead({
        source: function (typeahead, query) {
            $.ajax({
                url: "ajax.php/serials?q="+query,
                dataType: 'json',
                success: function (data) {
                    typeahead.process(data);
                }
            });
        },
        onselect: function (obj) {
            //$('#uid').val(obj.id);
            $('#serial-no').val(obj.serialno);
			$('#prod-model').val(obj.model);
			$('#manu').val(obj.manu);
			$('#invoiceno').val(obj.invoiceno);
			$('#invoicedate').val(obj.invoicedate);
			$('#billname').val(obj.billname);
			$('#billadd').val(obj.billadd);
			$('#blocn').val(obj.blocn);
			
			
        },
        property: "/bin/true"
    });


 
});



</script>
