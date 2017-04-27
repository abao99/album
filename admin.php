<?php require_once('Connections/album.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_POST['check_del'])) && ($_POST['check_del'] != "")) {
  
  $sql=sprintf("select * from album where id in (%s)",
  				implode(",",$_POST['check_del']));
	$delquery=mysql_query($sql);
	while($row = mysql_fetch_array($delquery)){
		unlink($row['Name']);
		unlink($row['Name_thum']);
		}
  
  
  $deleteSQL = sprintf("DELETE FROM album WHERE ID IN (%s)",
                       implode(",",$_POST['check_del']));

  mysql_select_db($database_album, $album);
  $Result1 = mysql_query($deleteSQL, $album) or die(mysql_error());
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  
  $countNum=count($_POST['ID']);
for($i=0; $i<$countNum; $i++){
  
  $updateSQL = sprintf("UPDATE album SET `Comment`=%s WHERE ID=%s",
                       GetSQLValueString($_POST['Comment'][$i], "text"),
                       GetSQLValueString($_POST['ID'][$i], "int"));

  mysql_select_db($database_album, $album);
  $Result1 = mysql_query($updateSQL, $album) or die(mysql_error());
}
  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$maxRows_Recordset1 = 9;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysql_select_db($database_album, $album);
$query_Recordset1 = "SELECT * FROM album ORDER BY ID DESC";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $album) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無標題文件</title>
<style type="text/css">
#button {
	text-align: center;
}
</style>
</head>

<body>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
 <table align="center">
 	<tr>
    <td>
  <table align="center" >
    <tr>
      <?php
$Recordset1_endRow = 0;
$Recordset1_columns = 3; // number of columns
$Recordset1_hloopRow1 = 0; // first row flag
do {
    if($Recordset1_endRow == 0  && $Recordset1_hloopRow1++ != 0) echo "<tr>";
   ?>
      <td><table width="200" border="1" align="center">
        <tr>
          <td align="center" valign="middle"><img src="<?php echo $row_Recordset1['Name_thum']; ?>"></td>
        </tr>
        <tr>
          <td align="center" valign="middle"><input name="ID[]" type="hidden" id="ID[]" value="<?php echo $row_Recordset1['ID']; ?>">
            <input name="check_del[]" type="checkbox" id="check_del[]" value="<?php echo $row_Recordset1['ID']; ?>">
            <label for="check_del[]"></label></td>
        </tr>
        <tr>
          <td align="center" valign="middle"><label for="textarea"></label>
            <label for="Comment[]"></label>
            <input name="Comment[]" type="text" id="Comment[]" value="<?php echo $row_Recordset1['Comment']; ?>"></td>
        </tr>
      </table></td>
      <?php  $Recordset1_endRow++;
if($Recordset1_endRow >= $Recordset1_columns) {
  ?>
    </tr>
    <?php
 $Recordset1_endRow = 0;
  }
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
if($Recordset1_endRow != 0) {
while ($Recordset1_endRow < $Recordset1_columns) {
    echo("<td>&nbsp;</td>");
    $Recordset1_endRow++;
}
echo("</tr>");
}?>
  </table>
  	</td>
    </tr>
    <tr>
    <td align="center" valign="middle">
    <input type="submit" name="button" id="button" value="送出">
    </td>
    </tr>
    <tr>
      <td align="right" valign="middle"><a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>">上一頁</a>&nbsp; <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>">下一頁</a>&nbsp; </td>
    </tr>
  </table>
  <p>
    
  </p>
  <p>
    <input type="hidden" name="MM_update" value="form1">
  </p>
</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
