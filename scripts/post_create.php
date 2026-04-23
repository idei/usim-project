<?php

function run($cmd) {
    echo "\n> $cmd\n";
    passthru($cmd, $exit);
    if ($exit !== 0) exit($exit);
}

echo "\n=== USIM Project Bootstrap ===\n";

// .env
if (!file_exists('.env')) {
    copy('.env.example', '.env');
    echo ".env creado\n";
}

// key
run("php artisan key:generate");

// migraciones (opcional)
// run("php artisan migrate");

// mensaje final
echo "\nProyecto listo para ejecutar:\n";
echo "php artisan usim:install\n";
