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

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Urls collection
 */
class Urls implements UrlsInterface
{    
    /**
     * @var array The urls
     */    
    protected array $urls = [];

    /**
     * @var array The builders helper.
     */    
    protected array $builders = [];    

    /**
     * Create a new Urls collection.
     * 
     * @param UriFactoryInterface $uriFactory
     */         
    public function __construct(
        protected UriFactoryInterface $uriFactory
    ) {}
    
    /**
     * Set an url.
     * 
     * @param string $key The key.
     * @param string $url The url.
     * @param null|callable $builder function(string $url, ?string $path): ?string {}
     * @return static $this
     */         
    public function set(string $key, string $url, ?callable $builder = null): static
    {
        $this->urls[$key] = $url;
        
        if ($builder !== null) {
            $this->builders[$key] = $builder;
        }
        
        return $this;
    }    
                
    /**
     * Get an url by key.
     *
     * @param string $key The key.
     * @param null|string $default A default url.
     * @return null|string The url or the default url if not exist.
     */
    public function get(string $key, ?string $default = null): ?string
    {
        if (array_key_exists($key, $this->urls)) {
            return $this->urls[$key];
        }
        
        return $default;
    }

    /**
     * Get an uri by key.
     *
     * @param string $key The key.
     * @param null|string $default A default url.
     * @return UriInterface
     */
    public function getUri(string $key, ?string $default = null): UriInterface
    {
        $url = $this->get($key, $default);
        
        return $this->uriFactory->createUri($url ?: '');
    }

    /**
     * Get all urls.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->urls;
    }

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
    public function build(string $key, ?string $path = null): string
    {
        $url = $this->get($key);
        
        if ($url === null) {
            throw new UrlsException('Url "'.$key.'" is not set.');
        }
        
        if (array_key_exists($key, $this->builders)) {
            $url = call_user_func_array($this->builders[$key], [$url, $path]);
            return is_string($url) ? $url : '';
        }
        
        if (empty($path)) {
            return $url;
        }
        
        $uri = $this->uriFactory->createUri($path);
        
        if ($this->isRelative($uri) === false) {
            return $path;
        }
        
        if (substr($path, 0, 1) === '#') {
            return rtrim($url, '/').$path;
        }
        
        return rtrim($url, '/').'/'.ltrim($path, '/');
    }

    /**
     * If the uri is relative.
     *
     * @param UriInterface $uri
     * @return bool True if relative, otherwise false.
     */
    protected function isRelative(UriInterface $uri): bool
    {
        if (!empty($uri->getScheme())) {
            return false;
        }
            
        if (!empty($uri->getAuthority())) {
            return false;
        }

        return true;
    }    
}