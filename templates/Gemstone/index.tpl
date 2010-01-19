{include file="Gemstone/header.tpl"}
	{if $active_project}
		<h1>Project: {$active_project.project_name}</h1>
		<hr />
	{/if}
	{if $active_page}
		<div class="newsitem">
			<div class="title">
				{if $active_page.project_id}{$active_page.project_name} :: {/if}
				{$active_page.post_title}
			</div>
			<div class="body">
				{$active_page.text}
				{$active_page.additional_text}
			</div>
			<div class="clearer"><span></span></div>
		</div>
	{else}
		{if $active_project && !$active_post && $active_project.project_main_page}
			<div class="newsitem">
				<div class="body">
					{$active_project.project_main_page.text}
				</div>
				{if $active_project.project_main_page.additional_text}
					<a href="{link type=page project=$active_project.project_short_name resource=$active_project.project_main_page.page_name}">Read more</a>
				{/if}
			</div>
			<div class="clearer"><span></span></div>
		{/if}
		{foreach from=$posts item=post}
			<div class="date">
				{$post.post_date|date_format:"%A %e of %B, %Y %I:%M %p"}
			</div>
			<div class="newsitem">
				<div class="title">
					{if $post.project_id}{$post.project_name} :: {/if}
					{$post.post_title}
				</div>
				{* <div class="author">{$post.user_name}</div> *}
				{if $active_post}
					<div class="body">
						{$post.text}
						{$post.additional_text}
					</div>
				{else}
					<div class="body">{$post.text}</div>
					{if $post.additional_text}
						<a href="{link type=post project=$post.project_short_name resource=$post.post_id}">Read more</a>
					{/if}
				{/if}
				<div class="clearer"><span></span></div>
			</div>
		{/foreach}
		{if $active_post}
			<div class="comments">
				<div class="title">Comments</div>
				<div class="newsitem">
					<div class="body">
						Comment #1...
					</div>
					<div class="clearer"><span></span></div>
				</div>
			</div>
		{/if}
	{/if}
{include file="Gemstone/footer.tpl"}