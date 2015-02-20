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
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'serial_no';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'serial.serial_no';

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
	






<div class="span6" id="form-login">
				<form class="form-horizontal well" action="importserials.php" method="post" name="upload_excel" enctype="multipart/form-data">
					<?php csrf_token(); ?>
					<fieldset>
						<legend>Import CSV/Excel file</legend>
						<div class="control-group">
							<div class="control-label">
								<label>CSV/Excel File:</label>
							</div>
							<div class="controls">
								<input type="file" name="file" id="file" class="input-large">
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
							<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			



 

 <table class="list" border="0" cellspacing="0" cellpadding="0" width="940">
    
</table>


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