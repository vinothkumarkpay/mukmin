<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$urls = [
    '/',
    '/contact-us',
    '/register',
    '/apply-scholarship',
    '/login',
];

// Let's also check some CMS page slugs if any exist
foreach (App\Models\CmsPage::all() as $p) {
    $urls[] = "/page/{$p->slug}";
}

foreach ($urls as $url) {
    $request = Illuminate\Http\Request::create($url, 'GET');
    try {
        $response = $app->handle($request);
        $content = $response->getContent();
        echo "URL: {$url}\n";
        echo "Status: " . $response->getStatusCode() . "\n";
        echo "Content Length: " . strlen($content) . " bytes\n";
        if ($response->getStatusCode() >= 400 || strlen($content) < 1000) {
            echo "Snippet: " . substr(strip_tags($content), 0, 500) . "\n";
        }
    } catch (\Throwable $e) {
        echo "URL: {$url} failed with Exception: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
    }
    echo "-----------------------------------------\n";
}
