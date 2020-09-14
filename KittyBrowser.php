<?php

/*
 * This file extends the Symfony Browser-Kit package.
 *
 * (c) Thiago Melo <thiagomp@gmail.com>
 */

namespace Clyo;

use Clyo\HttpBrowser;

class KittyBrowser
    extends HttpBrowser{

    /**
     * configures whether or not the browser should make a secure request
     */
    protected $upgradeInsecureRequests = false;

    /**
     * returns how many time the request needed to be redirect until it's final URL
     * 
     * @return int
     */
    public function getRedirectCount() {
        return $this->redirectCount;
    }

    /**
     * returns a stack of Response objects for each redirect that the original URL caused
     * 
     * @return Array
     */
    public function getRedirectInfo() {
        return $this->redirectInfo;
    }

    /**
     * sets whether to automatically follow instructions to redirect to a secure connection.
     * by upgrading to secure requests, a header will be added to the request and, if the
     *   server offers a secured connection, it will usually return a 307 redirect instructing
     *   the location for the secure connection.
     * 
     * @see https://www.w3.org/TR/upgrade-insecure-requests/
     */
    public function upgradeInsecureRequests(bool $uir = false) {
        $this->upgradeInsecureRequests = $uir;
    }

    /**
     * customize the request to upgrade insecure requests
     */
    protected function doRequest($request): Response {
        if ( $this->shouldUpgradeInsecureRequests() ) {
            $request->addServer('http_upgrade-insecure-requests', 1);
        }

        return parent::doRequest($request);
    }

    /**
     * checks if the browser should request to upgrade insecure requests
     */
    public function shouldUpgradeInsecureRequests(): bool {
        return $this->upgradeInsecureRequests;
    }

}