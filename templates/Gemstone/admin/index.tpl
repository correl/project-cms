{include file="Gemstone/admin/header.tpl"}
<div class="box">
	<h1>Administration</h1>
	<div class="admin-links">
		<h2>Content</h2>
		{if $auth->has_perm('projects_view')}
			<a class="ui-widget-content ui-state-default ui-corner-all" href="projects.php">Projects</a>
		{/if}
		<a class="ui-widget-content ui-state-default ui-corner-all" href="pages.php">Pages</a>
		<a class="ui-widget-content ui-state-default ui-corner-all" href="posts.php">Updates</a>

		<h2>Users</h2>
		<a class="ui-widget-content ui-state-default ui-corner-all" href="users.php">Users</a>
		<a class="ui-widget-content ui-state-default ui-corner-all" href="groups.php">Groups</a>
		<a class="ui-widget-content ui-state-default ui-corner-all" href="permissions.php">Permissions</a>
	</div>
</div>
{include file="Gemstone/admin/footer.tpl"}
