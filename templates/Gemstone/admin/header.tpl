<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<meta name="description" content="description"/>
<meta name="keywords" content="keywords"/>
<meta name="author" content="author"/>
<link rel="stylesheet" type="text/css" href="{link resource="templates/Gemstone/default.css"}"/>
<link rel="stylesheet" type="text/css" href="{link resource="templates/Gemstone/extra.css"}"/>
<link rel="stylesheet" type="text/css" href="{link resource="templates/Gemstone/admin/admin.css"}"/>
<link rel="stylesheet" type="text/css" href="{link resource="js/highlight.css"}"/>
<link rel="stylesheet" type="text/css" href="{link resource="js/shadowbox-build-3.0b/shadowbox.css"}"/>
<link rel="stylesheet" type="text/css" href="{link resource="templates/Gemstone/css/ui-lightness/jquery-ui-1.7.2.custom.css"}"/>
<script type="text/javascript" src="{link resource="js/jquery-1.3.2.min.js"}"></script>
<script type="text/javascript" src="{link resource="js/jquery-ui-1.7.2.custom.min.js"}"></script>
<script type="text/javascript" src="{link resource="js/shadowbox-build-3.0b/shadowbox.js"}"></script>
<script type="text/javascript" src="{link resource="js/wymeditor/jquery.wymeditor.min.js"}"></script>
<script type="text/javascript">
var highlighter_url = "{link resource="js/highlighter.php"}";
{literal}
	Shadowbox.init();
	$(document).ready(function() {
			$("code[class]").each(function(i, e) {
					$(e).load(highlighter_url, {
							language: $(e).attr("class"),
							code: $(e).html()
					});
			});
	});
{/literal}
</script>
<title>correl.phoenixinquis.net: Admin</title>
</head>
<body class="no_sidebar">
<div class="top"><h1>correl.phoenixinquis.net<span>Projects and coding adventures</span></h1></div>
<div class="header">
	<div class="menu">
		<ul>
			<li><div><a href="index.php">Index</a></div></li>
			<li><div><a href="projects.php">Projects</a></div></li>
			<li><div><a href="pages.php">Pages</a></div></li>
			<li><div><a href="pages.php">Updates</a></div></li>
		</ul>
		<ul>
			<li><div><a href="{link resource=""}">View Site</a></div></li>
			<li><div><a href="errors.php">Error Log</a></div></li>
		</ul>
		{*
		<ul>
			<li><div><a href="#">Profiles</a></div></li>
			<li><div><a href="#">Columns</a></div></li>
			<li><div><a href="#">Hosted Sites</a></div></li>
			<li><div><a href="#">Forum</a></div></li>
		</ul>
		<ul>
			<li><div><a href="#">About</a></div></li>
			<li><div><a href="#">Guidelines</a></div></li>
			<li><div><a href="#">RSS Feeds</a></div></li>
			<li><div><a href="#">Buttons</a></div></li>
		</ul>
		*}
	</div>
</div>
<div class="content_left">
