<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/page/about-mukmin', 'GET');
$response = $app->handle($request);
$content = $response->getContent();

// Find the main-wrap or body content
if (preg_match('/<main[^>]*>(.*?)<\/main>/s', $content, $matches)) {
    echo "=== MAIN CONTENT ===\n";
    echo $matches[1] . "\n";
} else {
    echo "Could not find <main> tag. Full HTML:\n";
    echo $content . "\n";
}
