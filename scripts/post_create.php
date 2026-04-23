<?php
const GITHUB_FOLDER = '.github';
const USIM_FRAMEWORK_REPOSITORY_URL = 'https://github.com/idei/usim-framework';
const USIM_INSTRUCTIONS_FILE = 'copilot_instructions.md';

function run($cmd) {
    echo "\n> $cmd\n";
    passthru($cmd, $exit);
    if ($exit !== 0) exit($exit);
}

function getCopilotInstructions() {
    $usimFrameworkRepositoryUrl = USIM_FRAMEWORK_REPOSITORY_URL;
    $instructionsUrl = $usimFrameworkRepositoryUrl .
        '/blob/main/' .
        GITHUB_FOLDER . '/' .
        USIM_INSTRUCTIONS_FILE . '?raw=true';
    echo "Fetching USIM Copilot instructions from oficial repository\n";
    $markdown = file_get_contents($instructionsUrl);
    if ($markdown === false) {
        echo "Error fetching instructions.\n";
        return null;
    }
    return $markdown;
}

function saveInstructionsToFile($markdown) {
    $filename = GITHUB_FOLDER . '/' . USIM_INSTRUCTIONS_FILE;
    file_put_contents($filename, $markdown);
    echo "Instructions saved to $filename\n";
}

echo "\n=== USIM Project Bootstrap ===\n";

// .env
if (!file_exists('.env')) {
    copy('.env.example', '.env');
    echo ".env creado\n";
}

// key
run("php artisan key:generate");

run("composer require idei/usim -W");

// Install USIM dependencies and publish assets
run("php artisan usim:install");

// Fetch and save Copilot instructions
$markdown = getCopilotInstructions();
if ($markdown !== null) {
    saveInstructionsToFile($markdown);
} else {
    echo "Unable to fetch USIM Copilot instructions.\n";
}

// last step: instructions for the user
echo "\nProject Ready for USIM:\n";
echo "Start the server:\n";
echo "./start.sh [-r]\n";
echo "Note: -r, removes existing database and start fresh.\n";
