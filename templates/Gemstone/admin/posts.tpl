{include file="Gemstone/admin/header.tpl"}
<script type="text/javascript" src="{link resource="js/posteditor.js"}"></script>
<div class="box">
	<h1>Updates</h1>
	<div class="admin-links">
		{foreach item=post from=$posts}
			<a class="ui-widget-content ui-state-default ui-corner-all" href="javascript:editor.editPost({$post.post_id})">{$post.post_title}</a>
		{/foreach}
		<hr />
		<a class="ui-widget-content ui-state-active ui-corner-all" href="javascript:editor.editPost(0)">Add new</a>
	</div>
</div>
{include file="Gemstone/admin/footer.tpl"}
