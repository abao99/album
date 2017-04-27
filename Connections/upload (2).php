<?php require_once('albumConn.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
  $countNum=count($_POST['Comment']);
  for ($i=0; $i<$countNum; $i++) {
	  if ($_FILES['Photo']['name'][$i] != "") {
  /*$insertSQL = sprintf("INSERT INTO album (Name, Name_thum, `Comment`) VALUES (%s, %s, %s)",
                       GetSQLValueString('a', "text"),
                       GetSQLValueString('b', "text"),
                       GetSQLValueString('c', "text"));

  mysql_select_db($database_albumConn, $albumConn);
  $Result1 = mysql_query($insertSQL, $albumConn) or die(mysql_error());

  $insertGoTo = "upload.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));*/
	  }
  }
}
 
$countNum=count($_POST['Comment']);
for ($i=0; $i<$countNum; $i++)  
	echo '上傳檔案名稱:'.@$_FILES['Photo']['name'][$i].'<br>'; 	// 印出檔案名稱

if (!isset($_GET['Num']))   
	$Num=3;
else
	$Num=$_GET['Num'];
?>


<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>網路相簿</title>
</head>

<body>
<h3 align="center">網路相簿</h3>
<hr />
<form name="form1" action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" id="form1">
<table width="540" border="0" align="center" cellpadding="0" cellspacing="0">

<?php for ($i=0; $i<$Num; $i++)  {  ?>
  <tr>
    <td width="540"><label for="Photo[]"></label>
      <input name="Photo[]" type="file" id="Photo[]" size="50"></td>
  </tr>
  <tr>
    <td>說明:
      <label for="Comment[]"></label>
      <input name="Comment[]" type="text" id="Comment[]" size="50"></td>
    </tr>
<?php }?>

</table>

<p></p>

 
  
    <table width="500" border="1" align="center" cellpadding="0" cellspacing="0">
      <tr align="center" valign="middle">
        <td width="138" align="center"><a href="../upload.php?Num=<?php echo ++$Num; ?>">新增檔案欄位</a></td>
        <td width="356"><input name="checkResize" type="checkbox" value="">&nbsp;縮圖為<input name="px" type="text">px<input name="send" type="submit" value="送出"></td>
      </tr>
    	</table>
    <input type="hidden" name="MM_insert" value="form1">
</table>





</form>





</body>
</html>