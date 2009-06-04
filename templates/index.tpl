<html>
	<head>
		<title>Projects</title>
	</head>
	<body>
		{foreach from=$posts item=post}
			<b>{$post.post_title} - {$post.user_name}</b>
			<p>{$post.post_text}</p>
		{/foreach}
	</body>
</html>
