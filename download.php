<?php header("Content-Type:text/html; charset=utf-8"); require_once 'config.php'; ?>
<?php

  @$down=strval($_GET['id']);  //得到type的值
	if (empty($down)) //默认为movie
	{
		$down = "1";
	}    
	$result=mysql_query("SELECT * FROM HBDX_BLUE WHERE ID = '".$down."'");
	$row = mysql_fetch_array($result);
	$stringurl = "Location:".$row['URL'];
	$num = $row['NUM'];
	$num = $num + 1;
	$result=mysql_query("UPDATE HBDX_BLUE SET NUM =".$num." WHERE ID = '".$down."'");
	header($stringurl); 
	exit;

?>    
