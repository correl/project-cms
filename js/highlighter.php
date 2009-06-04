<?php
include_once('../common.php');
include_once('Text/Highlighter.php');

$code = $_POST['code'];
$language = $_POST['language'];

$highlighter =& Text_Highlighter::factory($language);
if (!Pear::isError($highlighter)) {
	$code = str_replace(array('&gt;', '&lt;', '&amp;'), array('>', '<', '&'), $code);
	$code = $highlighter->highlight($code);
}
echo $code;
?>
