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
			<a class="ui-widget-content ui-state-default ui-corner-all" href="javascript:editor.editPage({$page.page_id})">{$page.post_title}</a>
		{/foreach}
		<a class="ui-widget-content ui-state-active ui-corner-all" href="javascript:editor.editPage(0, {$project.project_id})">Add new</a>
	</div>
	<h2>Posts</h2>
	{foreach item=post from=$posts}
		<div class="newsitem">
			<div class="title">{$post.post_title}</div>
			<div class="body">{$post.text}</div>
			<div class="clearer"><span></span></div>
		</div>
	{/foreach}
</div>

{*
	TODO:
	This could be loaded on demand as a jQuery dialog. Would look much nicer,
	the form can be posted to edit.php to save using ajax.
*}
<div id="editor" style="display: none;"></div>
<script type="text/javascript" src="{link resource="js/posteditor.js"}"></script>

{include file="Gemstone/admin/footer.tpl"}
