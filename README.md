BrowserKitty Component
======================

BrowserKitty is a fork of Symfony's BrowserKit component and simulates the behavior of a web browser,
allowing you to make requests, click on links and submit forms programmatically.

The component comes with a concrete implementation that uses the HttpClient
component to make real HTTP requests.

BrowserKitty extends BrowserKit's capabilities by exposing details related to HTTP redirects.
It allows you to get the response details of each redirect. See a quick example below:
```php
require("../vendor/autoload.php");

use Clyo\Kitty;

$url = "example.com";

$web = new KittyBrowser();
$web->followRedirects(true);
$web->followMetaRefresh(true);
$web->setMaxRedirects(5);
$web->upgradeInsecureRequests(true);

$web->request('GET', $url);

foreach ( $web->getRedirectInfo() as $count => $response ) {
    echo str_repeat(" ", $count) . "$previousUrl [{$response->getStatusCode()}]\n";
    echo str_repeat(" ", $count) . "|-> ";
    $previousUrl = $response->getHeader('location');
}
```
You can also see a working example in the examples/ folder

BrowserKitty also implements the upgrade-insecure-requests on the request.
That allows the server to respond with a 307 redirect informing the secure location.
To activate that, make sure you `upgradeInsecureRequests(true)` before calling the `request()` method.

Resources from Symphony Browser-Kit
-----------------------------------

  * [Documentation](https://symfony.com/doc/current/components/browser_kit/introduction.html)
  * [Contributing](https://symfony.com/doc/current/contributing/index.html)
  * [Report issues](https://github.com/symfony/symfony/issues) and
    [send Pull Requests](https://github.com/symfony/symfony/pulls)
    in the [main Symfony repository](https://github.com/symfony/symfony)
