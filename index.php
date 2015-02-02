<?php
include 'includes/config.php';

$ogImage = "http://su-rl.co/images/facebook.png";

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$URLTitle = "SURL - The URL shortening service with custom thumbnails";
$URLDesc = "SURL is a URL shortening service that allows you to use custom thumbnails, these are the images that show up when you link the URL on social media";

if (isset($_GET['u'])) {
	// If linked by short url
	$shortLink = $_GET['u'];

	$result = mysql_query("SELECT * FROM surl_generated WHERE generate_short_url = '$shortLink'");
	if(mysql_num_rows($result) < 1) {
		header("Location: http://www.su-rl.co/");
	}
	$row = mysql_fetch_array($result);

	$longURL = $row['generate_long_url'];
	$ogImage = $row['generate_thumbnail_url'];
	$URLTitle = $row['generate_title'];
	$URLDesc = $row['generate_description'];

	if($URLTitle == "") {
		$URLTitle = "SURL - The URL shortening service with custom thumbnails";
	}

	if($URLDesc == "") {
		$URLDesc = "SURL is a URL shortening service that allows you to use custom thumbnails, these are the images that show up when you link the URL on social media";
	}

	$linkedFrom = true;
}

if (isset($_POST['submit'])) {
	// Submit hit
	$longURL = $_POST['longURL'];
	$thumbURL = $_POST['thumbURL'];
	$URLTitle = $_POST['URLTitle'];
	$URLDesc = $_POST['URLDesc'];

	if(filter_var($longURL, FILTER_VALIDATE_URL) AND filter_var($thumbURL, FILTER_VALIDATE_URL)){ 
	  // you're good

		if (strpos($longURL,'<script>') !== false) {
		    header("Location: http://su-rl.co/?m=NOURL");
		}

		if (strpos($thumbURL,'<script>') !== false) {
		    header("Location: http://su-rl.co/?m=NOURL");
		}

		if (strpos($longURL,'xss') !== false) {
		    header("Location: http://su-rl.co/?m=NOURL");
		}

		if (strpos($thumbURL,'xss') !== false) {
		    header("Location: http://su-rl.co/?m=NOURL");
		}

		if (strpos($URLTitle,'<script>') !== false) {
		    header("Location: http://su-rl.co/?m=NOURL");
		}

		if (strpos($URLDesc,'<script>') !== false) {
		    header("Location: http://su-rl.co/?m=NOURL");
		}

		if (strpos($URLTitle,'xss') !== false) {
		    header("Location: http://su-rl.co/?m=NOURL");
		}

		if (strpos($URLDesc,'xss') !== false) {
		    header("Location: http://su-rl.co/?m=NOURL");
		}

		function generate_random_string($name_length = 7) {
			$alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			return substr(str_shuffle($alpha_numeric), 0, $name_length);
		}

		$shortURL = generate_random_string();

		$userIP = $_SERVER['REMOTE_ADDR'];

		date_default_timezone_set('GMT+1');

		$fdate = date("Y-m-d H:i:s");

		if($thumbURL == "") {
			$thumbURL = "http://su-rl.co/images/facebook.png";
		}
	 
		mysql_query("INSERT INTO surl_generated (generate_long_url, generate_title, generate_description, generate_thumbnail_url, generate_short_url, generate_ip, generate_date) 
			VALUES ('$longURL', '$URLTitle', '$URLDesc', '$thumbURL', '$shortURL', '$userIP', '$fdate')");

		$shortened = true;
	} else {
		header("Location: http://su-rl.co/?m=NOURL");
	}

	
}

$messageText = "";

if(isset($_GET['m'])) {
	$message = $_GET['m'];

	if($message = "NOURL") {
		$messageText = "Please enter URL's.";
	}
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $URLTitle; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    	<link rel="stylesheet" href="../style/foundation.css">
    	<link rel="stylesheet" href="../style/normalize.css">
    	<link rel="stylesheet" href="../style/main.css">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    	<!-- for Google -->
		<meta name="description" content="<?php echo $URLDesc; ?>" />
		<meta name="keywords" content="url, shortener, thumbnail, custom" />

		<meta name="author" content="Eamon Boyle" />
		<meta name="copyright" content="http://su-rl.co/" />
		<meta name="application-name" content="SURL" />

		<!-- for Facebook -->          
		<meta property="og:title" content="<?php echo $URLTitle; ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:image" content="<?php echo $ogImage; ?>" />
		<meta property="og:url" content="<?php echo $actual_link; ?>" />
		<meta property="og:description" content="<?php echo $URLDesc; ?>" />

		<!-- for Twitter -->          
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:title" content="<?php echo $URLTitle; ?>" />
		<meta name="twitter:description" content="<?php echo $URLDesc; ?>" />
		<meta name="twitter:image" content="<?php echo $ogImage; ?>" />

		<link rel="shortcut icon" href="http://su-rl.co/favicon.ico" type="image/x-icon">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-55525657-1', 'auto');
		  ga('require', 'displayfeatures');
		  ga('send', 'pageview');

		</script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <div class="site_header_container">
	        <div class="row">
				<div class="site_header large-12 columns">
					<h1><a href="http://su-rl.co/">SURL - The URL shortening service with custom thumbnails!</a></h1>
				</div>
	        </div>
	    </div>

		<?php 
		if($messageText != "") { ?>
	    <div style="color: #FFF; text-align: center; top: 150px; position: absolute; width: 100%; background: #FF0000; padding: 10px 0px;"><?php echo $messageText; ?></div>
		<?php } ?>

	    <?php if($shortened) { ?>

	    <div class="form_container row clearfix">
			<div class="form_container2 large-12 columns">
				<p>Short URL</p>
				<input type="text" onClick="this.select();" value="<?php echo 'su-rl.co/' . $shortURL; ?>/">
				<a href="http://su-rl.co/"><button>Shorten Another</button></a>
				<p class="hint">If it doesn't load the thumbnail image straight away, wait a few minutes then try again.</p>
			</div>
	    </div>

	    <?php } else if ($linkedFrom) { ?>

	    <div class="form_container row clearfix">
			<div class="form_container2 large-12 columns">
				<p>Linked URL:<br> <?php echo $longURL; ?></p>
				<a href="<?php echo $longURL; ?>"><button style="color: #FFF;">Click here to go to URL</button></a>
				<a href="http://su-rl.co/"><button>Shorten your own</button></a>
			</div>
	    </div>

	    <?php } else { ?>

	    <div class="form_container row clearfix">
			<div class="form_container2 large-12 columns">
				<form action="" method="post">
					<p>URL to shorten (Required)</p>
					<input type="text" name="longURL" required>
					<p>URL Title</p>
					<input type="text" name="URLTitle">
					<p>URL Description</p>
					<input type="text" name="URLDesc">
					<p>URL of image thumbnail (Required)</p>
					<input type="text" name="thumbURL">
					<input type="submit" class="button" value="Shorten!" name="submit">
				</form>
				<a href="http://su-rl.co/own/"><button style="margin-right: 10px; margin-top: 2px; padding: 13px;">Use your own image</button></a>
			</div>
	    </div>

	    <?php } ?>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    </body>
</html>