<?php

// Routeur pour le serveur PHP integre (php -S) :
// sert les fichiers statiques existants (ex. /bundles/easyadmin/*),
// delegue tout le reste au front controller Symfony.
//
// Usage : php -S 127.0.0.1:8001 -t public bin/dev-router.php

$path = parse_url($_SERVER['REQUEST_URI'], \PHP_URL_PATH);

if (is_string($path) && '' !== $path && is_file(__DIR__.'/../public'.$path)) {
    return false;
}

$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/../public/index.php';

require $_SERVER['SCRIPT_FILENAME'];
