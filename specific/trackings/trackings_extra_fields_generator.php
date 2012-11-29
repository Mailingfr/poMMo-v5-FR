<?php
include '../inc/inc-param-base.php';
include '../inc/inc-connex.php';
$trackings_extra_fields="";
$sql_extra_field_1_clickedurl ="sql_extra_field_1_clickedurl";
$sql_extra_field_1_opentracking ="sql_extra_field_1_opentracking"; 
if(isset($_GET['val_1'])){
$trackings_extra_fields.="$$sql_extra_field_1_clickedurl =',(SELECT value FROM ".$prefix."subscriber_data WHERE subscriber_id = clickedurl.subscriber_id AND field_id = ".$_GET['val_1'].") AS val_1';";
$trackings_extra_fields.="$$sql_extra_field_1_opentracking =',(SELECT value FROM ".$prefix."subscriber_data WHERE subscriber_id = opentracking.subscriber_id AND field_id = ".$_GET['val_1'].") AS val_1';";
}else{
$trackings_extra_fields.="$$sql_extra_field_1_clickedurl ='';";
$trackings_extra_fields.="$$sql_extra_field_1_opentracking ='';";
}
$sql_extra_field_2_clickedurl ="sql_extra_field_2_clickedurl";
$sql_extra_field_2_opentracking ="sql_extra_field_2_opentracking"; 
if(isset($_GET['val_2'])){
$trackings_extra_fields.="$$sql_extra_field_2_clickedurl =',(SELECT value FROM ".$prefix."subscriber_data WHERE subscriber_id = clickedurl.subscriber_id AND field_id = ".$_GET['val_2'].") AS val_2';";
$trackings_extra_fields.="$$sql_extra_field_2_opentracking =',(SELECT value FROM ".$prefix."subscriber_data WHERE subscriber_id = opentracking.subscriber_id AND field_id = ".$_GET['val_2'].") AS val_2';";
}else{
$trackings_extra_fields.="$$sql_extra_field_2_clickedurl ='';";
$trackings_extra_fields.="$$sql_extra_field_2_opentracking ='';";
}

$var_field_name_val_1='field_name_val_1';
if(isset($_GET['val_1'])){
$sql = 
"SELECT
field_name
FROM ".$prefix."fields
WHERE field_id = '".$_GET['val_1']."'";
$result = mysql_query($sql,$id_connex);
if (!$result) {
exit('<p>Error performing query: ' . mysql_error() .
'</p>');
}
while($row = mysql_fetch_array($result))
	{
	$field_name_val_1=$row[field_name];
	}
mysql_free_result($result);
$trackings_extra_fields.="$$var_field_name_val_1 ='".$field_name_val_1."';";
}else{
$trackings_extra_fields.="$$var_field_name_val_1 ='0';";
}

$var_field_name_val_2='field_name_val_2';
if(isset($_GET['val_2'])){
$sql = 
"SELECT
field_name
FROM ".$prefix."fields
WHERE field_id = '".$_GET['val_2']."'";
$result = mysql_query($sql,$id_connex);
if (!$result) {
exit('<p>Error performing query: ' . mysql_error() .
'</p>');
}

while($row = mysql_fetch_array($result))
	{
	$field_name_val_2=$row[field_name];
	}
mysql_free_result($result);
$trackings_extra_fields.="$$var_field_name_val_2 ='".$field_name_val_2."';";
}else{
$trackings_extra_fields.="$$var_field_name_val_2 ='0';";
}


$trackings_extra_fields="<?php ".$trackings_extra_fields." ?>" 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Trackings extra fields generator</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
</head>
<body>
<?php
if(file_exists("trackings_extra_fields.php")){
 		unlink("trackings_extra_fields.php");
 		echo 'Previous version of trackings_extra_fields.php erased.<br>';
 		}
if($fp = fopen("trackings_extra_fields.php","a")){
		fputs($fp, $trackings_extra_fields);
		fclose($fp);
 		echo 'Creation of trackings_extra_fields.php successful';
		}else{
 		echo 'Creation of trackings_extra_fields.php UNSUCCESSFUL';
  			exit();
			}
?>
</body>
</html>