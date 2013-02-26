<?php header("Content-Type:text/html; charset=utf-8"); require_once 'config.php'; ?>
<!doctype html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="style.css" />
		<title>Simple Down简单下载网</title>
</head>
	<body>
		<div class="navbar">
			<div class="navbar-inner">
				<div class="container">
					<ul class="nav">
						<li>
							<a href="http://www.hbdx.cc" target="_blank">主 页</a>
						</li>
<?php
	@$type=strval($_GET['type']);  //得到type的值
	if (empty($type)) //默认为movie
	{
		$type = "movie";
	}
	$classmovie = "";
	$classvideo = "";
	$classmusic = "";
	$classimage = "";
	$classsoft = "";
	$classcode = "";
	$classother = "";
	if($type == "movie")
	{
		$classmovie = "active";
	}
	if($type == "video")
	{
		$classvideo = "active";
	}
	if($type == "music")
	{
		$classmusic = "active";
	}
	if($type == "image")
	{
		$classimage = "active";
	}
	if($type == "soft")
	{
		$classsoft = "active";
	}
	if($type == "code")
	{
		$classcode = "active";
	}
	if($type == "other")
	{
		$classother = "active";
	}
						
						echo "
						<li class='".$classmovie."'>
							<a href='index.php'>电影</a>
						</li>
						<li class='".$classvideo."'>
							<a href='index.php?type=video'>视频</a>
						</li>
						<li class='".$classmusic."'>
							<a href='index.php?type=music'>音乐</a>
						</li>
						<li class='".$classimage."'>
							<a href='index.php?type=image'>图片</a>
						</li>
						<li class='".$classsoft."'>
							<a href='index.php?type=soft'>软件</a>
						</li>
						<li class='".$classcode."'>
							<a href='index.php?type=code'>源码</a>
						</li>
						<li class='".$classother."'>
							<a href='index.php?type=other'>其他</a>
						</li>
						"
?>
						<li>
							<a href='upload.html'>上传文件</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div style="text-align:center;margin-top:10px;margin-bottom:10px;">  
			<a href="http://hbdx.cc" target="_blank">
				<img src="logo.png" width="189" height="75" border="0" title="HBDX" /></a>
			<form action="index.php" name="hbdxsearch" id="search">
				<input  type="text" name="search" maxlength="64" style="width:400px;" autocomplete="off" />
				<input type="submit" value="搜索"/>
			</form>
			<b>热门搜索：</b>
			<?php
				$result=mysql_query("SELECT * FROM HBDX_SEACHER ORDER BY SNUM DESC LIMIT 0,8"); 
				while($row = mysql_fetch_array($result))
				{
					echo "<a href='index.php?search=".$row['DATA']."'>".$row['DATA']."&nbsp;&nbsp;</a>";
				}
			?>
		</div>
		<div class="container">
			<div class="hero-unit">
<?php
if($_SERVER['REQUEST_URI']) 
{
	$temp = urldecode($_SERVER['REQUEST_URI']);
	if(strpos($temp, '<') !== false || strpos($temp, '>') !== false || strpos($temp, '(') !== false || strpos($temp, '"') !== false) 
	{
		exit('Request Bad url');
	}
}

$perNumber=10;  //每页显示的最大记录数
$limit_page = 10; //

@$page=intval($_GET['page']);  //得到page的值
$page=isset($_GET['page'])?intval($_GET['page']):1; //当前页数
//@$ext=strval($_GET['ext']);  //得到ext的值
//if (empty($ext)) //默认为torrent
//{
//	$ext = "torrent";
//}

$result=mysql_query("SELECT * FROM HBDX_BLUE"); 
$num_all=mysql_numrows($result); 
echo "本站共收藏资源<b>".$num_all."</b>个";

@$seacher=strval($_GET['search']);  //得到seacher的值
$startCount=($page-1)*$perNumber;
if (empty($seacher))
{
	$result=mysql_query("SELECT * FROM HBDX_BLUE WHERE TYPE = '".$type."'"); 
	$num_max=mysql_numrows($result); 

	$result=mysql_query("SELECT * FROM HBDX_BLUE WHERE TYPE = '".$type."' order by DATETIME DESC LIMIT $startCount,$perNumber"); 
	$num=mysql_numrows($result); 
}
else
{	
	$datetime = date("Y-m-d H:i:s");
	$seacherresult = mysql_query("SELECT * FROM HBDX_SEACHER WHERE DATA = '".$seacher."'");
	$seachernum = mysql_numrows($seacherresult);
	if($seachernum == 0)
	{
		mysql_query("INSERT INTO HBDX_SEACHER (ID,DATA,SNUM,DATETIME) VALUES ('','$seacher',1,'$datetime')");
	}
	else
	{
		$row = mysql_fetch_array($seacherresult);
		mysql_query("UPDATE HBDX_SEACHER SET SNUM = '".($row['SNUM'] + 1)."' WHERE DATA = '".$seacher."'");
	}

	$result=mysql_query("SELECT * FROM HBDX_BLUE WHERE NAME LIKE '%".$seacher."%'"); 
	$num_max=mysql_numrows($result); 

	$result=mysql_query("SELECT * FROM HBDX_BLUE WHERE NAME LIKE '%".$seacher."%' order by DATETIME DESC LIMIT $startCount,$perNumber"); 
	$num=mysql_numrows($result); 
}


echo '<table class="table"><tr>';
echo '<th>序号</th><th>操  作</th><th>文件名</th><th>文件大小</th><th>下载次数</th><th>上传时间</th>';
echo '</tr>';
while($row = mysql_fetch_array($result))
{
	echo "<td>".$row['ID']."</td>";
	echo "<td><a href='download.php?id=".$row['ID']."'><span>下载</a></span></td>";     
	echo "<td><b>".$row['NAME']."</b></td>";
	echo "<td>".$row['SIZE']." KB</td>";
	echo "<td>".$row['NUM']."</td>";
	echo "<td>".$row['DATETIME']."</td>";
	echo '</tr>';
}
echo "</table>";

$pagecount = ceil($num_max/$perNumber);   //总页数

if ($page == 1) {echo "<a href='index.php?type=".$type."'><strong>首页&nbsp;&nbsp;</strong></a>";}
if ($page != 1) 
{
	echo "<a href='index.php?type=".$type."'><strong>首页&nbsp;&nbsp;</strong></a>";
	echo "<a href='index.php?type=".$type."&page=".($page-1)."'><strong>上一页&nbsp;&nbsp;</strong></a>";
}
for ($i=1;$i<=$pagecount;$i++)
{
	if($i <= $limit_page)
	{
		echo "<a href='index.php?type=".$type."&page=".$i."' >$i&nbsp;&nbsp;<a>";
	}
}
if ($page<$pagecount)
{
	echo "<a href='index.php?type=".$type."&page=".($page+1)."'><strong>下一页&nbsp;&nbsp;</strong></a>";
	echo "<a href='index.php?type=".$type."&page=".$pagecount."'><strong>尾页&nbsp;&nbsp;</strong></a>";
}

if ($page==$pagecount)
{
	echo "<a href='index.php?type=".$type."&page=".$pagecount."'><strong>尾页&nbsp;&nbsp;</strong></a>";
}

echo "第<b>".$page."</b>页 ";
echo " 共<b>".$pagecount."</b>页 ";


echo '<span class="pagelist"></span>跳转到第<select onchange="window.location=this.value">';
for ($i=1;$i<=$pagecount;$i++) 
{
	echo "<option value='index.php?type=".$type."&page=".$i."'>".$i."</option>";
}
echo '</select>页 ';

mysql_free_result($result);
mysql_close($conn);
?>
			</div>
		</div>
		<div style="text-align:center;margin-top:5px;"> 
			<a href="about.html">关于 |</a><a href="help.html"> 帮助 |</a><a href="http://hbdx.cc/msg.php" target="_blank"> 留言 |</a><a href="#"> 统计</a>
		</div>
		<div style="text-align:center;margin-top:10px;"> 
			@Copyright 2013 <a href="http://d.hbdx.cc">Simple Down</a> By <a href="http://hbdx.cc">海兵大侠</a>
		</div>
	</div>
</body></html>
