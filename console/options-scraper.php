<?php

require 'vendor/autoload.php';

echo "Choose which website you want to scrap:".PHP_EOL;
$options = [];
$i = 0;
foreach (\App\OptionsWebpageParser::SOURCE_STRATEGY as $url => $strategy) {
    $options[$i] = $url;
    echo "{$i}: {$url}".PHP_EOL;
    $i++;
}
$i = (int) readline('Choose number: ');
echo (new \App\OptionsWebpageParser())->parseFromUrl($options[$i]);
