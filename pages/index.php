<?php

require_once __DIR__ .'/../functions/function_simplestats.php';

rex_response::cleanOutputBuffers();

ob_start(function ($output) {
    // ---- rewrite asset-urls to redaxo assets folder
    $output = preg_replace('#(?<==(?:"|\'))js/#', '../assets/addons/simplestats/js/', $output);
    $output = preg_replace('#(?<==(?:"|\'))css/#', '../assets/addons/simplestats/css/', $output);
    $output = preg_replace('#(?<==(?:"|\'))images/#', '../assets/addons/simplestats/images/', $output);

    // ---- rewrite remaining urls to point to the backend-page-url
    $output = rex_simplestat_add_page_param($output);

    return $output;
});

// declare all globals required by Simple-Stats
global $ss, $filters, $page, $ajax, $script_i18n, $is_archive, $loaded_data, $date_label, $has_filters, $field_names, $ua;

echo '<style>
#options-form tr:nth-child(2),/* site-name */
#options-form tr:nth-child(3),/* require-login-to-edit-config*/
#options-form tr:nth-child(4),/* require-login-to-view-stats*/
#options-form tr:nth-child(5),/* require-login: username*/
#options-form tr:nth-child(6),/* require-login: passwords*/
#options-form tr:nth-child(8) /* language */
{
	display: none;
}

#main > p /* setup/config instructions*/
{
    display: none;
}
</style>';

// prevent "Argument $new is no longer supported in PHP > 7"
$oldReporting = error_reporting(error_reporting() &~E_DEPRECATED  &~E_WARNING &~E_NOTICE);

require_once( $this->getPath('vendor/Simple-Stats/index.php') );

// make sure the output buffer callback is called
while (ob_get_level()) {
    ob_end_flush();
}
exit;