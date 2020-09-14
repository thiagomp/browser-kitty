<?php

/**
 * Example of how to use Kitty Client extension features
 * How to use: php cli.php http://example.com
 * 
 * (c) Thiago Melo <thiagomp@gmail.com>
 */

require("../vendor/autoload.php");

use Clyo\KittyBrowser;

$url = $argv[1];

$web = new KittyBrowser();
$web->followRedirects(true);
$web->followMetaRefresh(true);
$web->setMaxRedirects(5);
$web->upgradeInsecureRequests(true);

$web->request('GET', $url);

$status = $web->getResponse()->getStatusCode();
$redirectCount = $web->getRedirectCount();

echo "requested url was $url\n";

if ( $redirectCount ) {
    $finalUrl = $web->getRequest()->getUri();
    $redirectTxt = $redirectCount > 1 ? "$redirectCount times" : "$redirectCount time";
    echo "but after it was redirected $redirectTxt, it became $finalUrl\n";

    $previousUrl = $url;
    echo "\nredirections:\n";
    foreach ( $web->getRedirectInfo() as $count => $response ) {
        echo str_repeat(" ", $count) . "$previousUrl [{$response->getStatusCode()}]\n";
        echo str_repeat(" ", $count) . "|-> ";
        $previousUrl = $response->getHeader('location');
    }
    echo str_repeat(" ", $count) . "$previousUrl [$status]\n";
} else {
    echo "final status is $status\n";
}