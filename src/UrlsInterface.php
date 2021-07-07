<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Uri;

use Psr\Http\Message\UriInterface;

/**
 * UrlsInterface
 */
interface UrlsInterface
{
    /**
     * Set an url.
     * 
     * @param string $key The key.
     * @param string $url The url.
     * @param null|callable $builder A builder
     * @return static $this
     */         
    public function set(string $key, string $url, ?callable $builder = null): static;    
                
    /**
     * Get an url by key.
     *
     * @param string $key The key.
     * @param null|string $default A default url.
     * @return null|string The url or the default url if not exist.
     */
    public function get(string $key, ?string $default = null): ?string;

    /**
     * Get an uri by key.
     *
     * @param string $key The key.
     * @param null|string $default A default url.
     * @return UriInterface
     */
    public function getUri(string $key, ?string $default = null): UriInterface;

    /**
     * Get all urls.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Build an url by key.
     *
     * @param string $key The key.
     * @param null|string $path The path.
     *
     * @throws UrlsException If url key is not set
     *
     * @return string The url build.
     */
    public function build(string $key, ?string $path = null): string;
}