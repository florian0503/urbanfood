<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

// Prepare la base de test : schema a jour + fixtures.
if ('test' === ($_SERVER['APP_ENV'] ?? null)) {
    $console = escapeshellarg(dirname(__DIR__).'/bin/console');
    passthru(sprintf('php %s doctrine:schema:update --force --complete --env=test --quiet', $console));
    passthru(sprintf('php %s doctrine:fixtures:load --no-interaction --env=test --quiet', $console));
}
