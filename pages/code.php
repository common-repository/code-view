<?php

/**
 * required defaults
 */
if ( ! isset( $lang ) ) {
	$lang = 'html';
}

if ( ! isset( $code ) ) {
	$code = '';
}

$emblem = '';
if ( CodeView::getSetting( 'include_emblem' ) ) {
	$emblem = " <div class=\"code-curly\"></div> ";
}

$hide_numbers = '';
if ( ! CodeView::getSetting( 'line_numbers' ) ) {
	$hide_numbers = ' hide_line_numbers';
}

return "<pre class=\"{$lang}{$hide_numbers}\">{$emblem}<code>{$code}</code></pre>";
