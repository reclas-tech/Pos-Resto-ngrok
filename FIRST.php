<?php

$target = __dir__ . '/vendor/autoload.php';
$link = __dir__ . '/storage/app/public';

symlink($target, $link);

echo readlink($link);