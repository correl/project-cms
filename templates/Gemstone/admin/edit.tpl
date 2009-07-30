{include file="Gemstone/admin/header.tpl"}
<div class="box">
	<h1>
		{if $page}
			{if $post.post_id}Editing page{else}New page{/if}
		{else}
			{if $post.post_id}Editing post{else}New post{/if}
		{/if}
	</h1>
	<form method="POST">
		<input type="hidden" name="post_id" value="{$post.post_id}" />
		<fieldset>
			<dl>
				{if $page}
					<dt>Page name</dt>
					<dd>
						<input type="hidden" name="page_id" value="{$post.page_id}" />
						<input type="text" name="page_name" value="{$post.page_name}" />
					</dd>
				{/if}
				
				<dt>Project</dt>
				<dd>
					N/A
					<input type="hidden" name="project_id" value="{$post.project_id}" />
				</dd>
				
				<dt>Post title</dt>
				<dd><input type="text" name="post_title" value="{$post.post_title}" /></dd>
				<dt>Post text</dt>
				<dd>
					<input type="hidden" name="post_text_id" value="{$post.text_id}" />
					<textarea name="post_text">{$post.text}</textarea>
				</dd>
				
				{* TODO: DHTML to show / hide & clear additional text area *}
				<dt>Additional text</dt>
				<dd>
					<input type="hidden" name="post_additional_text_id" value="{$post.additional_text_id}" />
					<textarea name="post_additional_text">{$post.additional_text}</textarea>
				</dd>
			</dl>
		</fieldset>
		<input type="submit" name="save" value="Save changes" />
	</form>
</div>
{include file="Gemstone/admin/footer.tpl"}
