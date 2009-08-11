{include file="Gemstone/admin/header.tpl"}
<script type="text/javascript" src="{link resource="js/posteditor.js"}"></script>
<div class="box">
	<h1>Pages</h1>
	<div class="admin-links">
		{foreach item=page from=$pages}
			<a class="ui-widget-content ui-state-default ui-corner-all" href="javascript:editor.editPage({$page.page_id})">{$page.post_title}</a>
		{/foreach}
		<hr />
		<a class="ui-widget-content ui-state-active ui-corner-all" href="javascript:editor.editPage(0)">Add new</a>
	</div>
</div>
{include file="Gemstone/admin/footer.tpl"}
