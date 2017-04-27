<?php require_once('Connections/album.php'); ?>
<?php
include("resize.php");
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
  
  $countNum = count($_POST['Comment']);
for($i=0;$i<$countNum;$i++){ 
   if ($_FILES['Photo']['name'][$i] != "") {
	   if ($_FILES['Photo']['error'][$i] >0) {
		   echo '上傳檔案名稱:'.$_FILES['Photo']['name'][$i].'<br>';
		   switch ($_FILES['Photo']['error'][$i]) {
    			case 1: die("超出PHP.INI限制") ;
      	
      				break;    
    			
    			case 2 : die("超出限制"); 
      
      				break;
    			case 3  : die("部分上傳限制");
      				break;
    			case 4  : die("未上傳");
      				break;
			  }
	   }
	   $destDir="photos";
	   if(!is_dir($destDir)|| !is_writeable($destDir))
	   		die("目錄不存在或無法寫入");
		//檔案格式判斷	
	   $checkExt   = getimagesize ($_FILES['Photo']['tmp_name'][$i]);
	   if($checkExt[2]== null)
	   		die("檔案格式不符");
			
		//取得副檔名	
		 switch ($checkExt[2]) {
    			case 1: $Ext = "gif" ;
      	
      				break;    
    			
    			case 2 : $Ext = "jpg"; 
      
      				break;
    			case 3  : $Ext = "png";
      				break;
		 }
		 //'檔案命名
		 $Name = date("Ymd") . "_" . substr(md5(uniqid(rand())),0,5) . "." . $Ext;
		 //複製暫存檔
		 move_uploaded_file($_FILES['Photo']['tmp_name'][$i] , $destDir . "/" . $Name );
		 
		 //判斷是否縮圖
		 	if($_POST['checkResize']){
				$src = $destDir."/".$Name;
				$dest = $src;
				$destW = $_POST['px'];
				$destH = $destW;
				imagesResize($src,$dest,$destW,$destH);
				
				}
		  //產生預覽圖
		  		$src = $destDir."/".$Name;
				$dest = $destDir."/thum/thum_".$Name;
				$destW = 100;
				$destH = 100;
				imagesResize($src,$dest,$destW,$destH);

  $insertSQL = sprintf("INSERT INTO album (Name, Name_thum, `Comment`) VALUES (%s, %s, %s)",
                       GetSQLValueString($src, "text"),
                       GetSQLValueString($dest, "text"),
                       GetSQLValueString($_POST['Comment'][$i], "text"));

  mysql_select_db($database_album, $album);
  $Result1 = mysql_query($insertSQL, $album) or die(mysql_error());

  $insertGoTo = "upload.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
}
/*$countNum = count($_POST['Comment']);
for($i=0;$i<$countNum;$i++)
	
	echo '上傳檔案名稱:'.@$_FILES['Photo']['name'][$i].'<br>';*/
	
	if (!isset($_GET['Num']))   
		$Num=3;
	else
		$Num=$_GET['Num'];
	

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無標題文件</title>
<style type="text/css">
.cenn {
	text-align: center;
}
</style>
</head>

<body>


<p class="cenn">&nbsp;<a href="index.php">網路相簿</a></p>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
  <table width="600" border="1" align="center">
    <?php for ( $i=0 ; $i < $Num ; $i++){ ?>
    <tr>
      <td align="left" valign="middle"><label for="Photo[]"></label>
      <input  accept="image/gif,image/png,image/jpeg," name="Photo[]" type="file" id="Photo[]" size="50"></td>
    </tr>
    <tr>
      <td align="left" valign="middle">說明:
        <label for="Comment[]"></label>
      <input name="Comment[]" type="text" id="Comment[]" size="30"></td>
    </tr>
	<?php }?>
  </table>
  <p>&nbsp;</p>
  <table width="400" border="1" align="center">
    <tr>
      <td width="98"><a href="upload.php? Num=<?php echo ++$Num; ?>">新增檔案欄位</a></td>
      <td width="286"><input type="checkbox" name="checkResize" id="checkResize">
        <label for="checkResize"></label>
      縮圖為
      <label for="px"></label>
      <input name="px" type="text" id="px" size="15">
      px&nbsp;&nbsp; <input type="submit" name="button" id="button" value="送出"></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>