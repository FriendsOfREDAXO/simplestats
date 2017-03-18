<?php

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