<?php
if(!defined('OSTADMININC') || !$thisstaff->isAdmin()) die('Access Denied');

$qstr='';

$sql='SELECT serial.* '.
	' FROM '.SERIAL_TABLE.' serial ';
$sql.=' WHERE 1';


$select = 'SELECT serial.* ';

$from = 'FROM '.SERIAL_TABLE.' serial ';
      

$where='WHERE 1 ';


if ($_REQUEST['query']) {

    $from .=' LEFT JOIN '.SERIAL_TABLE.' seri
                ON (seri.serial_id=serial.serial_id) ';
              

    $search = db_input(strtolower($_REQUEST['query']), false);
    $where .= ' AND (
                    serial.serial_no LIKE \'%'.$search.'%\'
                    
                    
                    
                )';

    $qstr.='&query='.urlencode($_REQUEST['query']);
}

$sortOptions=array('serialno'=>'serial.serial_no','invoice'=>'invoice_no','billing'=>'billing_name','address'=>'billing_address, billing_location','date'=>'invoice_date');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'serial_id';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'serial.serial_id';

if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])]) {
    $order=$orderWays[strtoupper($_REQUEST['order'])];
}
$order=$order?$order:'ASC';

if($order_column && strpos($order_column,',')){
    $order_column=str_replace(','," $order,",$order_column);
}
$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';
$order_by="$order_column $order ";

$total=db_count('SELECT count(DISTINCT serial.serial_id) '.$from.' '.$where);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total,$page,PAGE_LIMIT);
$pageNav->setURL('serials.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));


$qstr.='&order='.($order=='DESC'?'ASC':'DESC');

/*
$query="$sql GROUP BY serial.serial_id ORDER BY $order_by";
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing=sprintf(_N('Showing %d serial', 'Showing %d serials',
        $num),$num);
else
    $showing=__('No serials found!');
*/
	
$select .= ', count(DISTINCT serial.serial_id) as serials ';
$from .= ' LEFT JOIN '.SERIAL_TABLE.' slno ON (serial.serial_id = slno.serial_id) ';

$query="$select $from $where GROUP BY slno.serial_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;

$qhash = md5($query);
$_SESSION['serials_qs_'.$qhash] = $query;	
	
	
	
	
	
?>
<div class="pull-left" style="width:700px;padding-top:5px;">
 <h2><?php echo __('Serials');?></h2>
 </div>
 
 
 <div class="pull-left" style="width:700px;">
    <form action="serials.php" method="get">
        <?php csrf_token(); ?>
        <input type="hidden" name="a" value="search">
        <table>
            <tr>
                <td><input type="text" id="basic-serial-search" name="query" size=30 value="<?php echo Format::htmlchars($_REQUEST['query']); ?>"
                autocomplete="off" autocorrect="off" autocapitalize="off"></td>
                <td><input type="submit" name="basic_search" class="button" value="<?php echo __('Search'); ?>"></td>
                <!-- <td>&nbsp;&nbsp;<a href="" id="advanced-user-search">[advanced]</a></td> -->
            </tr>
        </table>
    </form>
 </div>
 
 
 
<div class="pull-left flush-right" style="padding-top:5px;padding-right:5px;">
    <b><a href="serials.php?a=add" class="Icon newDepartment"><?php echo __('Add New Serial');?></a></b>
	|
    <b><a href="import-serials.php" class="popup-dialog"><i class="icon-cloud-upload icon-large"></i>
    <?php echo __('Import'); ?></a></b>
</div>
	
<div class="clear"></div>

<?php
$showing = $search ? __('Search Results').': ' : '';
$res = db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing .= $pageNav->showing();
else
    $showing .= __('No Serial found!');
?>


<form action="serials.php" method="POST" name="serials">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
 <input type="hidden" id="action" name="a" value="" >
 <table class="list" border="0" cellspacing="1" cellpadding="0" width="940">
    <caption><?php echo $showing; ?></caption>
    <thead>
        <tr>
            <th width="7px">&nbsp;</th>
            <th width="80"><a  <?php echo $type_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=type"><?php echo __('Model');?></a></th>
			<th width="180"><a <?php echo $name_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=name"><?php echo __('Serial No');?></a></th>
            <th width="70"><a  <?php echo $users_sort; ?>href="serials.php?<?php echo $qstr; ?>&sort=users"><?php echo __('Manufacturer');?></a></th>
            <th width="70"><a  <?php echo $manager_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Invoice Date');?></a></th>
			<th width="200"><a  <?php echo $manager_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Invoice Number');?></a></th>
			<th width="100"><a  <?php echo $email_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=email"><?php echo __('Billing Name');?></a></th>
            <th width="200"><a  <?php echo $manager_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Billing Address');?></a></th>
			<th width="70"><a  <?php echo $manager_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Billing Location');?></a></th>
		
			</tr>
    </thead>
    <tbody>
    <?php
        $total=0;
        $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
        if($res && db_num_rows($res)):
          
            while ($row = db_fetch_array($res)) {
                $sel=false;
                if($ids && in_array($row['serial_id'],$ids))
                    $sel=true;
                                
                ?>
            <tr id="<?php echo $row['serial_id']; ?>">
                <td width=7px>
                  <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['serial_id']; ?>"
                            <?php echo $sel?'checked="checked"':''; ?> >
                </td>
				<td><a href="serials.php?id=<?php echo $row['serial_id']; ?>"><?php echo $row['model_no']; ?></a></td>
				<td><a href="serials.php?id=<?php echo $row['serial_id']; ?>"><?php echo $row['serial_no']; ?></a></td>
				<td><a href="serials.php?id=<?php echo $row['serial_id']; ?>"><?php echo $row['manufacturer']; ?></td>
				<td><a href="serials.php?id=<?php echo $row['serial_id']; ?>"><?php echo Format::db_date($row['invoice_date']); ?></a></td>
                <td><a href="serials.php?id=<?php echo $row['serial_id']; ?>"><?php echo $row['invoice_no']; ?></a></td>
                <td><a href="serials.php?id=<?php echo $row['serial_id']; ?>"><?php echo $row['billing_name']; ?></td> 
                <td><a href="serials.php?id=<?php echo $row['serial_id']; ?>"><?php echo $row['billing_address']; ?></a></td>
				<td><a href="serials.php?id=<?php echo $row['serial_id']; ?>"><?php echo $row['billing_location']; ?></a></td>
				
            </tr>
            <?php
            } //end of while.
        endif; ?>
    <tfoot>
     <tr>
        <td colspan="6">
            <?php if($res && $num){ ?>
            <?php echo __('Select');?>:&nbsp;
            <a id="selectAll" href="#ckb"><?php echo __('All');?></a>&nbsp;&nbsp;
            <a id="selectNone" href="#ckb"><?php echo __('None');?></a>&nbsp;&nbsp;
            <a id="selectToggle" href="#ckb"><?php echo __('Toggle');?></a>&nbsp;&nbsp;
            <?php }else{
                echo __('No Serial Found');
            } ?>
        </td>
     </tr>
    </tfoot>
</table>
<?php
if($res && $num): //Show options..
 echo sprintf('<div>&nbsp;%s: %s &nbsp; <a class="no-pjax"
            href="serials.php?a=export&qh=%s">%s</a></div>',
            __('Page'),
            $pageNav->getPageLinks(),
            $qhash,
            __('Export'));


?>
<p class="centered" id="actions">
    <input class="button" type="submit" name="delete" value="<?php echo __('Delete Serial(s)');?>" >
</p>
<?php
endif;
?>
</form>

<div style="display:none;" class="dialog" id="confirm-action">
    <h3><?php echo __('Please Confirm');?></h3>
    <a class="close" href=""><i class="icon-remove-circle"></i></a>
    <hr/>
    
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong><?php echo sprintf(__('Are you sure you want to DELETE %s?'),
            _N('selected Serial No', 'selected Serial No', 2));?></strong></font>
        <br><br><?php echo __('Deleted data CANNOT be recovered.'); ?>
    </p>
    <div><?php echo __('Please confirm to continue.');?></div>
    <hr style="margin-top:1em"/>
    <p class="full-width">
        <span class="buttons pull-left">
            <input type="button" value="<?php echo __('No, Cancel');?>" class="close">
        </span>
        <span class="buttons pull-right">
            <input type="button" value="<?php echo __('Yes, Do it!');?>" class="confirm">
        </span>
     </p>
    <div class="clear"></div>
</div>

<script type="text/javascript">
$(function() {
    $('input#basic-serial-search').typeahead({
        source: function (typeahead, query) {
            $.ajax({
                url: "ajax.php/serials/search?q="+query,
                dataType: 'json',
                success: function (data) {
                    typeahead.process(data);
                }
            });
        },
        onselect: function (obj) {
            window.location.href = 'serials.php?id='+obj.id;
        },
        property: "/bin/true"
    });
	
    
});


 


</script>