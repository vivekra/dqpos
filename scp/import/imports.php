<?php

ini_set("display_errors",1);
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
			for($k=1;$k<=count($data->sheets[$i]['cells'][$j]);$k++) // This loop is created to get data in a table format.
			{
				$html.="<td>";
				$html.=$data->sheets[$i]['cells'][$j][$k];
				$html.="</td>";
			}
			$model_no = mysqli_real_escape_string($connection,$data->sheets[$i]['cells'][$j][1]);
			$serial_no = mysqli_real_escape_string($connection,$data->sheets[$i]['cells'][$j][2]);
			$manufacturer = mysqli_real_escape_string($connection,$data->sheets[$i]['cells'][$j][3]);
			$invoice_date = mysqli_real_escape_string($connection,$data->sheets[$i]["cells"][$j][4]);
			$invoice_no = mysqli_real_escape_string($connection,$data->sheets[$i]["cells"][$j][5]);
			$billing_name = mysqli_real_escape_string($connection,$data->sheets[$i]["cells"][$j][6]);
			$billing_add = mysqli_real_escape_string($connection,$data->sheets[$i]["cells"][$j][7]);
			$billing_loc = mysqli_real_escape_string($connection,$data->sheets[$i]["cells"][$j][8]);
			
			$query = "insert into dq_serial(model_no,serial_no,manufacturer,invoice_date,invoice_no,billing_name,billing_address,billing_location) values('".$model_no."','".$serial_no."','".$manufacturer."','".$invoice_date."','".$invoice_no."','".$billing_name."','".$billing_add."','".$billing_loc."')";
			
			$result = mysqli_query($connection,$query);

			if($result){
				echo "<br />$serial_no - $model_no - $billing_name Inserted into database";
			}
			else {
				echo "";
				 echo "<script type=\"text/javascript\">
				alert(\"ERROR: Inserting data into database - Contact the sites administrator\");
				window.location = \"test.php\"
				</script>";
			}
			
			
			
			
			
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
    window.location = \"test.php\"
    </script>";
}


?>