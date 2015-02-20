
<?php 
	//include 'import/db.php';
	require('admin.inc.php');
?>	

		
<?php
		
$page='imports.inc.php';
$tip_namespace = 'product.serial';
//if($serial || ($_REQUEST['a'] && !strcasecmp($_REQUEST['a'],'add'))) {
  //  $page='serial.inc.php';
//}				
				
$nav->setTabActive('product');
$ost->addExtraHeader('<meta name="tip-namespace" content="' . $tip_namespace . '" />',
    "$('#content').data('tipNamespace', '".$tip_namespace."');");
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
			?>
		
