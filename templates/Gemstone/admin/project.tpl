{include file="Gemstone/admin/header.tpl"}
<div class="box">
	<h1>Project</h1>
	<h2>Project Details</h2>
	<form method="POST">
		<input type="hidden" name="project_id" value="{$project.project_id}" />
		<fieldset>
			<dl>
				<dt>Project Name</dt>
				<dd>
					<input type="text" name="project_name" value="{$project.project_name}" />
				</dd>
				<dt>Short Name</dt>
				<dd>
					<input type="text" name="project_short_name" value="{$project.project_short_name}" />
				</dd>
			</dl>
		</fieldset>
	</form>
	
	<h2>Pages</h2>
	<div class="admin-links">
		{foreach item=page from=$pages}
			<a class="ui-widget-content ui-state-default ui-corner-all" href="edit.php?page={$page.page_id}">{$page.post_title}</a>
		{/foreach}
		<a class="ui-widget-content ui-state-active ui-corner-all" href="edit.php?page&project={$project.project_id}">Add new</a>
	</div>
	<h2>Posts</h2>
	<form method="POST">
		<fieldset>
			<dl>
				<dt>Subject</dt>
				<dd>
					<input type="text" name="post_title" value="" />
				</dd>
				<dt>Body</dt>
				<dd>
					<textarea name="post_text"></textarea>
				</dd>
				<dt>Read More</dt>
				<dd>
					<textarea name="post_additional_text"></textarea>
				</dd>
			</dl>
		</fieldset>
	</form>
	{foreach item=post from=$posts}
		<div class="newsitem">
			<div class="title">{$post.post_title}</div>
			<div class="body">{$post.text}</div>
			<div class="clearer"><span></span></div>
		</div>
	{/foreach}
</div>
{include file="Gemstone/admin/footer.tpl"}
