<tbody>
	
		<tr>
            <th colspan="2">
                <em><strong><?php echo __('Billing Information');?></strong>:</em>
            </th>
        </tr>
	
	<tr>
            <td width="160" class="required">
                <?php echo __('Invoice Date');?>:
            </td>
		<td>
                <span style="display:inline-block;">
                    <input type="text" name="invoicedate" id="invoicedate" value="<?php echo $info['invoicedate']; ?>" /> </span>
                <font class="error"><?php echo $errors['invoicedate']; ?></font>
            </td>	
	
	<tr>
            <td width="160" class="required">
                <?php echo __('Invoice No');?>:
            </td>
		<td>
                <span style="display:inline-block;">
                    <input type="text" name="invoiceno" id="invoiceno" value="<?php echo $info['invoiceno']; ?>" /> </span>
                <font class="error"><?php echo $errors['invoiceno']; ?></font>
            </td>
	
	<tr>
            <td width="160" class="required">
                <?php echo __('Billing Customer Name');?>:
            </td>
		<td>
                <span style="display:inline-block;">
                    <input type="text" size=45 name="billname" id="billname" value="<?php echo $info['billname']; ?>" /> </span>
                <font class="error"><?php echo $errors['billname']; ?></font>
            </td>
	
	<tr>
            <td width="160" class="required">
                <?php echo __('Billing Customer Address');?>:
            </td>
		<td>
                <span style="display:inline-block;">
                    <input type="text" size=45 name="billadd" id="billadd" value="<?php echo $info['billadd']; ?>" /> </span>
                <font class="error"><?php echo $errors['billadd']; ?></font>
            </td>
	
	
	<tr>
            <td width="160" class="required">
                <?php echo __('Billing Customer Location');?>:
            </td>
		<td>
                <span style="display:inline-block;">
                    <input type="text" name="billloc" id="billloc" value="<?php echo $info['billloc']; ?>" /> </span>
                <font class="error"><?php echo $errors['billloc']; ?></font>
            </td>
	
	<tr>
            <td width="160" class="required">
                <?php echo __('Billing Customer Type');?>:
            </td>
		<td>
                <span style="display:inline-block;">
                    <input type="text" name="billtype" id="billtype" value="<?php echo $info['billtype']; ?>" /> </span>
                <font class="error"><?php echo $errors['billtype']; ?></font>
            </td>
	
	</tbody>