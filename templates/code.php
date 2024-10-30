<?php

/**
 * required defaults
 */
if (!isset($lang)) {
    $lang = 'html';
}

if (!isset($code)) {
    $code = '';
}

return "<pre class=\"{$lang}\"> <div class=\"code-curly\"></div> <code>{$code}</code></pre>";
