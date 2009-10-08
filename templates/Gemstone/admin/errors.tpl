{include file="Gemstone/admin/header.tpl"}
<h1>Error Log</h1>

<table>
	<tr>
		<th>Caught</th>
		<th>Severity</th>
		<th>Message</th>
	</tr>
	{foreach item=error from=$errors}
		<tr>
			<td></td>
			<td>{$error.severity}</td>
			<td>{$error.message}</td>
		</tr>
	{/foreach}
</table>
{include file="Gemstone/admin/footer.tpl"}
