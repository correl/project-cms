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
						{if isset($errors.page_name)}{include file="Gemstone/admin/error.tpl" error=$errors.page_name}{/if}
					</dd>
				{/if}
				
				<dt>Author</dt>
				<dd>
					{if $auth->has_perm('proxy_all')}
						<select name="user_id">
							{html_options options=$auth->get_user_names() selected=$auth->user('id')}
						</select>
					{else}
						{$auth->user('name')}
					{/if}
				</dd>
				
				<dt>Project</dt>
				<dd>
					<select name="project_id">
						<option value="0">N/A</option>
						{html_options options=$projects selected=$post.project_id}
					</select>
				</dd>
				
				<dt>Post title</dt>
				<dd>
					<input type="text" name="post_title" value="{$post.post_title}" />
					{if isset($errors.post_title)}{include file="Gemstone/admin/error.tpl" error=$errors.post_title}{/if}
				</dd>
				<dt>Post text</dt>
				<dd>
					<input type="hidden" name="post_text_id" value="{$post.text_id}" />
					<textarea name="post_text">{$post.text}</textarea>
					{if isset($errors.post_text)}{include file="Gemstone/admin/error.tpl" error=$errors.post_text}{/if}
				</dd>
				
				{* TODO: DHTML to show / hide & clear additional text area *}
				<dt>Additional text</dt>
				<dd>
					<input type="hidden" name="post_additional_text_id" value="{$post.additional_text_id}" />
					<textarea name="post_additional_text">{$post.additional_text}</textarea>
				</dd>
			</dl>
		</fieldset>
		<input type="submit" name="save" value="Save changes" /><input type="submit" name="cancel" value="Cancel" />
	</form>
</div>
{include file="Gemstone/admin/footer.tpl"}
