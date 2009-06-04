{include file="Gemstone/header.tpl"}
<script type="text/javascript">
{literal}
function show404() {
	var errors = [
		"404",
		"0x194",
		"110010100",
		"Four-Oh-Four"
	];
	var messages = [
		"These are not the pages you're looking for",
		"Perhaps it never really existed",
		"It wuz here, but I eated it"
	];
	$("#404").html(errors[Math.floor(Math.random() * 100 % errors.length)]);
	$("#404-message").html(messages[Math.floor(Math.random() * 100 % messages.length)]);
}
$("#404-message").ready(function() {
		show404();
});
{/literal}
</script>
<div id="404" style="font-size: 100pt; font-weight: bold; text-align: center;"><noscript>404</noscript></div>
<div id="404-message" style="text-align: center; font-style: italic; margin-bottom: 2em;"></div>
{include file="Gemstone/footer.tpl"}
