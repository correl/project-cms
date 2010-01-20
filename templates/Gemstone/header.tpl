<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<meta name="description" content="description"/>
<meta name="keywords" content="keywords"/>
<meta name="author" content="author"/>
<link rel="alternate" type="application/rss+xml" title="correl.phoenixinquis.net" href="{link type=feed resource=rss}" />
{if $active_project}
	<link rel="alternate" type="application/rss+xml" title="correl.phoenixinquis.net: {$active_project.project_name}" href="{link type=feed project=$active_project.project_short_name resource=rss}" />
{/if}
<link rel="stylesheet" type="text/css" href="{link resource="templates/Gemstone/default.css"}"/>
<link rel="stylesheet" type="text/css" href="{link resource="templates/Gemstone/extra.css"}"/>
<link rel="stylesheet" type="text/css" href="{link resource="js/highlight.css"}"/>
<link rel="stylesheet" type="text/css" href="{link resource="js/shadowbox-build-3.0b/shadowbox.css"}"/>
<script type="text/javascript" src="{link resource="js/jquery-1.3.2.min.js"}"></script>
<script type="text/javascript" src="{link resource="js/shadowbox-build-3.0b/shadowbox.js"}"></script>
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
<title>correl.phoenixinquis.net{if $active_project}: {$active_project.project_name}{/if}</title>
</head>
<body{if isset($error) && true === $error} class="no_sidebar"}{/if}>
<div class="top"><h1>correl.phoenixinquis.net<span>Projects and coding adventures</span></h1></div>
<div class="header">
	<div class="menu horizontal">
		<ul>
			<li><div><a href="{link resource=""}"{if !$active_page} id="current"{/if}>News</a></div></li>
			{foreach name=pages from=$pages item=page}
				{*
				{if $smarty.foreach.pages.index > 0 && (0 == $smarty.foreach.pages.iteration % 5)}
					</ul>
					<ul>
				{/if}
				*}
				<li><div><a href="{link resource="pages/`$page.page_name`"}"{if $active_page && $active_page.page_id eq $page.page_id} id="current"{/if}>{$page.post_title}</a></div></li>
			{/foreach}
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
