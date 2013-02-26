<?php
header("Content-Type:text/html; charset=utf-8");
header("Cache-control: no-cache, no-store,must-revalidate");
require_once 'config.php';
?>
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
						<li>
							<a href="index.php">电影</a>
						</li>
						<li>
							<a href="index.php?type=video">视频</a>
						</li>
						<li>
							<a href="index.php?type=music">音乐</a>
						</li>
						<li>
							<a href="index.php?type=image">图片</a>
						</li>
						<li>
							<a href="index.php?type=soft">软件</a>
						</li>
						<li>
							<a href="index.php?type=code">源码</a>
						</li>
						<li>
							<a href="index.php?type=other">其他</a>
						</li>
						<li class="active">
							<a href="upload.html">上传文件</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<div class="container">
		<div class="hero-unit">

<?php
@$type = $_POST['select'];

if($_FILES['file']['size'] != 0)
{
	if(isset($_FILES['file']) && is_array($_FILES['file'])) 
	{
		$attach = $_FILES['file'];
	}
	$max_upload_size = 10485760; //单位字节
	$old_attachName = mb_detect_encoding($attach['name'])=='UTF-8'?$attach['name']:iconv('gbk',"utf-8",$attach['name']);
	$attach['ext']  = explode('.', $attach['name']);
	if (($length = count($attach['ext'])) > 1) 
	{
		$ext = strtolower($attach['ext'][$length - 1]);
	}
	if ($ext == NULL)
	{
		$ext = 'hibcs';
	}
	$ksize=number_format($attach['size']/(1024),2);
}
	$year      = date("Y");
    $month     = date("m");
    $day       = date("d");
    $fnamehash = md5(uniqid(microtime())); // fnamehash变量为当前时间的MD5散列,重命名附件名
    $object   = $fnamehash . '.' . $ext;
    $path     = $attach['tmp_name'];

if ($_FILES["file"]["error"] > 0) 
{
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
} 
else 
{
	echo "<b>上传完成</b><br />";
    echo "文件名称: " . $old_attachName . "<br />";
    echo "文件类型: " . $type . "<br />";
	echo "文件后缀: " . $ext . "<br />";
    echo "文件大小: " . $ksize . " Kb<br />";
    echo "临时文件: " . $path . "<br />";  
}

	$upfile = './upload/'.$object;
	$url = $upfile;
if (is_uploaded_file($_FILES['file']['tmp_name']))
{ 
     if (!move_uploaded_file($_FILES['file']['tmp_name'], $upfile))
	 {
		echo '问题: 不能将文件移动到指定目录。';     
		exit;    
	 }
}
else 
{
 		echo '问题: 上传文件不是一个合法文件: ';
 		echo $_FILES['file']['name'];    
		exit;  
}


$datetime = date("Y-m-d H:i:s");
$sql      = "INSERT INTO HBDX_BLUE (ID,NAME,SIZE,TYPE,URL,EXT,NUM,DATETIME) VALUES ('','$old_attachName','$ksize','$type','$url','$ext',0,'$datetime')";
mysql_query($sql) OR die("<p>写入数据库错误！</p>");
mysql_close($conn);
?>


<div><a href="index.php"><b>首页</b>  </a><a href="upload.html"> <b>继续上传</b></a></div>

		</div>
	</div>
		<div style="text-align:center;margin-top:5px;"> 
			<a href="about.html">关于 |</a><a href="help.html"> 帮助 |</a><a href="http://hbdx.cc/msg.php" target="_blank"> 留言 |</a><a href="#"> 统计</a>
		</div>
		<div style="text-align:center;margin-top:10px;"> 
			@Copyright 2013 <a href="http://d.hbdx.cc">Simple Down</a> By <a href="http://hbdx.cc">海兵大侠</a>
		</div>
</body>
</html>
