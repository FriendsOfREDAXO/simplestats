<?php

$sql = rex_sql::factory();
$sql->setQuery('DROP TABLE IF EXISTS '. rex::getTable('simple_stats_archive'));
$sql->setQuery('DROP TABLE IF EXISTS '. rex::getTable('simple_stats_options'));
$sql->setQuery('DROP TABLE IF EXISTS '. rex::getTable('simple_stats_visits'));