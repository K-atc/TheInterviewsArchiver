<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The Interveiws Archiver</title>
<meta name="keywords" content="" />
<meta name="description" content="The Interviewsをアーカイブする。" />
<meta name="copyright" content="Copyright K_atc" />

<link rel="shortcut icon" href="img/favicon.png">
<link rel="stylesheet" type="text/css" media="all" href="../../css/font-awesome.css">
<link rel="stylesheet" type="text/css" media="all" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
<link rel="stylesheet" type="text/css" media="all" href="css/main.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
</head>

<body>
<div class="wrapper">
	<header>
        <a href="./"><h1>The Interviews Archiver v0.2</h1></a>
        <p>お気に入りのユーザーのThe Interviewsをアーカイブできます。</p>
        <hr>
	</header>

<?php

	include_once("php/Katc_class_TI.php");
	// echo array_key_exists('t', $_GET);
	if(isset($_GET) && array_key_exists('t', $_GET) && $_GET['t']!=""){

		//ユーザー名をディレクトリ名に変換
		$t = Katc_TI::get_filter($_GET['t']);
		echo "<p>Update: " . date("Y-m-d") . "</p>";
		echo "<h2>You Selected: $t</h2>";

		$header = @get_headers("http://theinterviews.jp/" . $t . "/");
		//404エラーがあった
		if(strpos($header[0], '400') or strpos($header[0], '404') or strpos($header[0], '301') or strpos($header[0], '302') !== false) {
			// echo "404/301/302 Error";
			// header("location: ./");
			echo '<div class="panel alert">
			<small>300, 301, 302, 440 Error</small><br />
			ユーザー名（スクリーンネーム）またはURLを確認して下さい。<br />もしくはスクリーンネームを解析できませんでした。
			</div>';
			Katc_TI::welcome();
		}
		//正常処理
		else {
			// echo "正常";
			Katc_TI::get_dom($t, 1);
			$limit = Katc_TI::get_finalPage(); 
			// echo $limit;
			for($i=1; $i<=$limit; $i++){
				Katc_TI::get_dom($t, $i);
				Katc_TI::print_QA();
			}
		}
	}
	//初期画面
	else {
		Katc_TI::welcome();
	}

?>
    <footer>
        <hr>
        <a href="../../"><h4>K_atc Works</h4></a>
    </footer>
    
</div>
<!-- GA -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46606290-1', 'sakura.ne.jp');
  ga('send', 'pageview');

</script>
</body>
</html>