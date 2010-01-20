</div>
<div class="content_right">
	<div class="links">
		{if $active_project}
			<div class="project">
				{if $project_pages}
					<div class="title">{$active_project.project_short_name|capitalize} Pages</div>
					{foreach from=$project_pages item=page}
						<a href="{link type=page project=$active_project.project_short_name resource=$page.page_name}">{$page.post_title}</a>
					{/foreach}
					<div class="line"><span></span></div>
				{/if}
				<div class="title">{$active_project.project_short_name|capitalize} Downloads</div>
				<a href="#">... downloads here ...</a>
				<div class="line"><span></span></div>
			</div>
		{/if}
		<div class="title">Projects</div>
		{foreach name=projects from=$projects item=project}
			<a href="{link type=project resource=$project.project_short_name}">{$project.project_name}</a>
			{if $smarty.foreach.projects.last}
				<div class="line"><span></span></div>
			{/if}
		{/foreach}
		<div class="title">Links</div>
		<a href="{link resource="admin/"}">Login</a>
		<div class="line"><span></span></div>
		<a href="{link type=feed resource=rss}"><img src="{link resource="templates/Gemstone/img/feed-icon-14x14.png"}" alt="RSS" /> RSS Feed</a>
		{*<a href="{link type=feed resource=atom}"><img src="{link resource="templates/Gemstone/img/feed-icon-14x14.png"}"/> Atom Feed</a>*}
		{if $active_project}
			<a href="{link type=feed project=$active_project.project_short_name resource=rss}"><img src="{link resource="templates/Gemstone/img/feed-icon-14x14.png"}"/> RSS Feed ({$active_project.project_short_name|capitalize})</a>
			{*<a href="{link type=feed project=$active_project.project_short_name resource=atom}"><img src="{link resource="templates/Gemstone/img/feed-icon-14x14.png"}"/> Atom Feed ({$active_project.project_short_name|capitalize})</a>*}
		{/if}
		<div class="line"><span></span></div>
		{*
		<div class="title">Latest 5 QW Demos</div>
		<a href="#">Disconnect vs. Badname</a>
		<div class="line"><span></span></div>
		<a href="#">Bewi vs. goldenboy</a>
		<div class="line"><span></span></div>
		<a href="#">Xalibur vs. Hawkkii</a>
		<div class="line"><span></span></div>
		<a href="#">Me vs. you</a>
		<div class="line"><span></span></div>
		<a href="#">you vs. me</a>
		<div class="line"><span></span></div>
		<a href="#"><b>&amp; more</b></a>
		<div class="title">Statistics</div>
		<a href="#">Hits Today: 1632</a>
		<div class="line"><span></span></div>
		<a href="#">Hits Alltime: 369843</a>
		<div class="line"><span></span></div>
		<a href="#">Users: 1991</a>
		<div class="line"><span></span></div>
		<a href="#">Online: 16</a>
		<div class="line"><span></span></div>
		<a href="#"><b>&amp; more</b></a>
		<div class="line"><span></span></div>
		*}
	</div>
</div>
<div class="footer">
	<div class="copyright">&copy; 2006 <a href="#">Correl Roush</a>. Layout design by <a href="http://arcsin.se">Arcsin</a>. Header design by <a href="#">Jen Davis</a>.</div>
</div>
</body>
</html>
