{include file="Gemstone/admin/header.tpl"}
<div class="box">
	<h1>Projects</h1>
	<div class="admin-links">
		{foreach item=project from=$projects}
			<a class="ui-widget-content ui-state-default ui-corner-all" href="projects.php?project={$project.project_id}">{$project.project_name}</a>
		{/foreach}
		<hr />
		<a class="ui-widget-content ui-state-active ui-corner-all" href="projects.php?project">Add new</a>
	</div>
</div>
{include file="Gemstone/admin/footer.tpl"}
