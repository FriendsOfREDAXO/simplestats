<?php

rex_response::cleanOutputBuffers();

ob_start(function ($output) {
    // ---- rewrite asset-urls to redaxo assets folder
    $output = preg_replace('#(?<==(?:"|\'))js/#', '../assets/addons/simplestats/js/', $output);
    $output = preg_replace('#(?<==(?:"|\'))css/#', '../assets/addons/simplestats/css/', $output);
    $output = preg_replace('#(?<==(?:"|\'))images/#', '../assets/addons/simplestats/images/', $output);

    // ---- rewrite remaining urls to point to the backend-page-url

    // ./?p=paths
    $output = preg_replace('#(?<==(?:"|\'))\./\?#', 'index.php?page=simplestats&amp;', $output);
    // ./
    $output = preg_replace('#(?<==(?:"|\'))\./#', 'index.php?page=simplestats&amp;', $output);
    // ?p=setup
    $output = preg_replace('#(?<==(?:"|\'))\?#', 'index.php?page=simplestats&amp;', $output);
    return $output;
});

// declare all globals required by Simple-Stats
global $ss, $filters, $page, $ajax, $script_i18n, $is_archive, $loaded_data, $date_label, $has_filters, $field_names, $ua;

require_once( $this->getPath('vendor/Simple-Stats/index.php') );

// make sure the output buffer callback is called
while (ob_get_level()) {
    ob_end_flush();
}
exit;