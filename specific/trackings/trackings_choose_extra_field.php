<?php
// Paramètres de connexion
include '../inc/inc-param-base.php';
// Connexion
include '../inc/inc-connex.php';

$sql = 
"SELECT
field_name,
field_id
FROM ".$prefix."fields
WHERE field_active LIKE 'on'
";
$result = mysql_query($sql,$id_connex);
if (!$result) {
exit('<p>Error performing query: ' . mysql_error() .
'</p>');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /> 
<title>Choose up to 2 extras fields that will be displayed in Trackings</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
</head>
<body>
<h3>Choose up to 2 extras fields that will be displayed in Trackings</h3>
<table>
<form action="trackings_extra_fields_generator.php" method="get">
<?php
while($row = mysql_fetch_array($result))
	{
	echo "<tr><td><b>$row[field_name]</b></td><td>Extra field 1 <input name='val_1' type='radio' value='$row[field_id]'></td><td>Extra field 2<input name='val_2' type='radio' value='$row[field_id]'></td><tr>";
	}
mysql_free_result($result);

?>
</table>
<input type="reset" name="Reset" value="Reset">
<input type="submit" name="Submit" value="Send">
</form>
</body>
</html>