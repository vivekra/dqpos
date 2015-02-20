<?php

if(!defined('OSTADMININC') || !$thisstaff->isAdmin()) die('Access Denied');
/*

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

/*

$total=db_count('SELECT count(DISTINCT serial.serial_id) '.$from.' '.$where);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total,$page,PAGE_LIMIT);
$pageNav->setURL('serials.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));


$qstr.='&order='.($order=='DESC'?'ASC':'DESC');


$query="$sql GROUP BY serial.serial_id ORDER BY $order_by";
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing=sprintf(_N('Showing %d serial', 'Showing %d serials',
        $num),$num);
else
    $showing=__('No serials found!');

	
$select .= ', count(DISTINCT serial.serial_id) as serials ';
$from .= ' LEFT JOIN '.SERIAL_TABLE.' slno ON (serial.serial_id = slno.serial_id) ';

$query="$select $from $where GROUP BY slno.serial_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;

$qhash = md5($query);
$_SESSION['serials_qs_'.$qhash] = $query;

*/

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
/*
$showing = $search ? __('Search Results').': ' : '';
$res = db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing .= $pageNav->showing();
else
    $showing .= __('No Serial found!');
*/
	
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
			<th width="200"><a  <?php echo $manager_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Invoice Date');?></a></th>
			<th width="200"><a  <?php echo $manager_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Invoice Number');?></a></th>
			<th width="300"><a  <?php echo $email_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=email"><?php echo __('Billing Name');?></a></th>
            <th width="200"><a  <?php echo $manager_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Billing Address');?></a></th>
			<th width="200"><a  <?php echo $manager_sort; ?> href="serials.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Billing Location');?></a></th>
		
			</tr>
    </thead>
    <tbody>






<?php

ini_set("display_errors",1);
ini_set('max_execution_time', 12000);
require_once 'excel_reader2.php';
require_once 'db.php';


if(isset($_POST["Import"])){
 
 
echo $filename=$_FILES["file"]["tmp_name"];



$data = new Spreadsheet_Excel_Reader($filename);





echo "Total Sheets in this xls file: ".count($data->sheets)."<br /><br />";

$html="<table border='1'>";
for($i=0;$i<count($data->sheets);$i++) // Loop to get all sheets in a file.
{	
	if(count($data->sheets[$i]['cells'])>0) // checking sheet not empty
	{
		echo "Sheet $i:<br /><br />Total rows in sheet $i  ".count($data->sheets[$i]['cells'])."<br />";
		for($j=2;$j<=count($data->sheets[$i]['cells']);$j++) // loop used to get each row of the sheet
		{ 
			$html.="<tr>";
			
			$model_no = mysqli_real_escape_string($conn,$data->sheets[$i]['cells'][$j][1]);
			$serial_no = mysqli_real_escape_string($conn,$data->sheets[$i]['cells'][$j][2]);
			$manufacturer = mysqli_real_escape_string($conn,$data->sheets[$i]['cells'][$j][3]);
			$invoice_date = mysqli_real_escape_string($conn,$data->sheets[$i]["cells"][$j][4]);
			$invoice_no = mysqli_real_escape_string($conn,$data->sheets[$i]["cells"][$j][5]);
			$billing_name = mysqli_real_escape_string($conn,$data->sheets[$i]["cells"][$j][6]);
			$billing_add = mysqli_real_escape_string($conn,$data->sheets[$i]["cells"][$j][7]);
			$billing_loc = mysqli_real_escape_string($conn,$data->sheets[$i]["cells"][$j][8]);
						
			$select ='SELECT serial.* ';
			
			
			$from  =' FROM '.SERIAL_TABLE.' serial '; 
			
		//	$where ='WHERE '.serial.serial_no='$serial_no';
			$query = "$select $from WHERE serial_no='$serial_no'";
			//echo $query;
			
			$result = mysqli_query($conn, $query);
			$res = db_query($query);
			if($result){
				$rowcount=mysqli_num_rows($result);
				if($rowcount > 0) {
				// echo "<br />$serial_no already exists<br />";
				while($row = db_fetch_array($res)) { 
				$status = "InDB"; ?>
				<tr>
                <td><?php echo $status; ?></td>
				<td><?php echo $row['model_no']; ?></td>
				<td><?php echo $row['serial_no']; ?></td>
				<td><?php echo $row['manufacturer']; ?></td>
				<td><?php echo $row['invoice_date']; ?></td>
                <td><?php echo $row['invoice_no']; ?></td>
                <td><?php echo $row['billing_name']; ?></td> 
                <td><?php echo $row['billing_address']; ?></td>
				<td><?php echo $row['billing_location']; ?></td>
				<td><?php //echo $status; ?></td>
				
            </tr>
				<?php
				
				}
			
				//$status = "Updated";
				
				$status ="IN Excel";
				?>
				<tr>
                <td><?php echo $status; ?></td>
				<td><?php echo $model_no; ?></td>
				<td><?php echo $serial_no; ?></td>
				<td><?php echo $manufacturer; ?></td>
				<td><?php echo $invoice_date; ?></td>
                <td><?php echo $invoice_no; ?></td>
                <td><?php echo $billing_name; ?></td> 
                <td><?php echo $billing_add; ?></td>
				<td><?php echo $billing_loc; ?></td>
				<td><?php echo $status; ?></td>
				
            </tr>
			<?php 
				
				   
				
					$query='UPDATE '.SERIAL_TABLE.' SET updated=NOW(), invoice_no='.db_input($invoice_no).' '
							.' ,model_no='.db_input($model_no)
							.' ,manufacturer='.db_input($manufacturer)
							.' ,invoice_date='.db_input($invoice_date)
							.' ,billing_name='.db_input($billing_name)
							.' ,billing_address='.db_input($billing_add)
							.' ,billing_location='.db_input($billing_loc)
							.' WHERE serial_no='.db_input($serial_no);
					
					$result = mysqli_query($conn,$query);
					if($result){
						//echo "<br />$serial_no - $model_no - $billing_name Inserted into database";
						$status = "Updated";
					}
				
					else {
					
						$status = "Not Updated";
					
					}
				
							
				}		
			else {
						
					$query='INSERT INTO '.SERIAL_TABLE.' SET created=NOW() '
						.' ,model_no='.db_input($model_no)
						.' ,`serial_no`='.db_input($serial_no)
						.' ,manufacturer='.db_input($manufacturer)
						.' ,invoice_date='.db_input($invoice_date)
						.' ,invoice_no='.db_input($invoice_no)
						.' ,billing_name='.db_input($billing_name)
						.' ,billing_address='.db_input($billing_add)
						.' ,billing_location='.db_input($billing_loc);	
					
					//$query = 'INSERT into  (model_no,serial_no,manufacturer,invoice_date,invoice_no,billing_name,billing_address,billing_location) values('".$model_no."','".$serial_no."','".$manufacturer."','".$invoice_date."','".$invoice_no."','".$billing_name."','".$billing_add."','".$billing_loc."')";
					$result = mysqli_query($conn,$query);
					if($result){
						//echo "<br />$serial_no - $model_no - $billing_name Inserted into database";
						$status = "Inserted";
					}
					else {
						echo "";
						$status = "Not Inserted";
						echo "<script type=\"text/javascript\">
						// alert(\"ERROR: $serial_no Inserting error into database - Contact the sites administrator\");
						/*	 window.location = \"test.php\" */
						</script>";
					}
				}	
			  
			}
			else {
				echo "";
				$status = "Query Error";
			}	
			
				
				
				
			/*	
			for($k=1;$k<=count($data->sheets[$i]['cells'][$j]);$k++) // This loop is created to get data in a table format.
			{
				$html.="<td>";
				$html.=$data->sheets[$i]['cells'][$j][$k];
				$html.="</td>";
			}
			*/
		?>	
			<tbody>
    
				<tr>
                <td><?php echo $status; ?></td>
				<td><?php echo $model_no; ?></td>
				<td><?php echo $serial_no; ?></td>
				<td><?php echo $manufacturer; ?></td>
				<td><?php echo $invoice_date; ?></td>
                <td><?php echo $invoice_no; ?></td>
                <td><?php echo $billing_name; ?></td> 
                <td><?php echo $billing_add; ?></td>
				<td><?php echo $billing_loc; ?></td>
				<td><?php echo $status; ?></td>
				
            </tr>
           
    	

<?php
			
			
			$html.="</tr>";
			
				
		}
	}

}

$html.="</table>";
echo $html;

}

else {

echo "contact admin no file uploaded";
echo "data not inserted";
echo "<script type=\"text/javascript\">
    alert(\"Invalid File:Please Upload XLS File.\");
    window.location = \"serials.php\"
    </script>";
}
		

		?>
		
		
 <tfoot>
    </tfoot>
</table>
</form>


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