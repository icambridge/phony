<?php
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    print('Dependecies required. Run composer update.' . PHP_EOL);
    exit(-1);
}

include __DIR__ . '/../vendor/autoload.php';