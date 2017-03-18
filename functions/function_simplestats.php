<?php

function rex_simplestat_add_page_param($output) {
    // ./?p=paths
    $output = preg_replace('#(?<==(?:"|\'))\./\?#', './?page=simplestats&amp;', $output);
    // ?p=setup
    $output = preg_replace('#(?<==(?:"|\'))\?#', './?page=simplestats&amp;', $output);
    // ./
    $output = preg_replace('#("|\')\./("|\')#', '\\1./?page=simplestats\\2', $output);

    return $output;
}