

    <?php
    include 'db.php';
    if(isset($_POST["Import"])){
     
     
    echo $filename=$_FILES["file"]["tmp_name"];
     
     
    if($_FILES["file"]["size"] > 0)
    {
     
    $file = fopen($filename, "r");
    while (($emapData = fgetcsv($file, 10000, "\t")) !== FALSE)
    {
     
    //It wiil insert a row to our subject table from our csv file`
    $sql = "INSERT into dq_serial (`model_no`, `serial_no`, `manufacturer`, `invoice_date`, `invoice_no`, `billing_name`, `billing_address`, `billing_location`)
    values('$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','$emapData[8]')";
    //we are using mysql_query function. it returns a resource on true else False on error
    $result = mysqli_query( $connection, $sql);
    if(! $result )
    {
    echo "<script type=\"text/javascript\">
    alert(\"Invalid File:Please Upload CSV File.\");
    window.location = \"test.php\"
    </script>";
     
    }
     
    }
    fclose($file);
    //throws a message if data successfully imported to mysql database from excel file
    echo "<script type=\"text/javascript\">
    alert(\"CSV File has been successfully Imported.\");
    window.location = \"test.php\"
    </script>";
     
     
     
    //close of connection
    mysqli_close($conn);
     
     
     
    }
    }	
    ?>	