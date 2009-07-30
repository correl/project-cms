{include file="Gemstone/admin/header.tpl"}
<div class="box">
	<h1>Pages</h1>
	<div class="admin-links">
		{foreach item=page from=$pages}
			<a class="ui-widget-content ui-state-default ui-corner-all" href="edit.php?page={$page.page_id}">{$page.post_title}</a>
		{/foreach}
		<hr />
		<a class="ui-widget-content ui-state-active ui-corner-all" href="edit.php?page">Add new</a>
	</div>
</div>
{include file="Gemstone/admin/footer.tpl"}
