<?php

require_once __DIR__ .'/functions/function_simplestats.php';

/* @var $this rex_addon*/

$fakeConfig = <<<'EOD'
<?php
// intentionally left empty, as we bootstrap the config in our addons' boot.php.
// this file is required by Simple-Stats
EOD;

rex_file::put($this->getPath('vendor/Simple-Stats/config.php'), $fakeConfig);

foreach(['css/', 'js/', 'images/'] as $folder) {
    rex_dir::copy($this->getPath('vendor/Simple-Stats/'. $folder), $this->getAssetsPath($folder));
}

// fix hardcoded urls in the provided js files
foreach(['js/overview.js', 'js/paths.js'] as $file) {
    $js = rex_file::get($this->getPath('vendor/Simple-Stats/'. $file));
    $js = rex_simplestat_add_page_param($js);

    rex_file::put($this->getAssetsPath($file), $js);
}
