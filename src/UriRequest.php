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

/**
 * The uri request.
 */
class UriRequest
{
    /**
     * @var UriPath
     */    
    protected UriPath $path;

    /**
     * @var null|UriQuery
     */    
    protected null|UriQuery $query = null;
            
    /**
     * Create a new UriRequest.
     *
     * @param string $uri The request uri. 'path/to?arg=value'
     */    
    public function __construct(
        string $uri = ''
    ) {    
        // parse path and query.
        if (strpos($uri, '?') !== false)
        {
            [$uri, $query] = explode('?', $uri, 2);
            
            $this->query = new UriQuery($query);
        }
        
        $this->path = new UriPath($uri);
    }

    /**
     * Returns a new instance with the specified path.
     *
     * @param string|UriPath $path The path. 'path/to'
     * @return static
     */
    public function withPath(string|UriPath $path): static
    {        
        if (is_string($path)) {
            $path = new UriPath($path);
        }

        $new = clone $this;
        $new->path = $path;

        return $new;
    }
    
    /**
     * Returns a new instance with the specified query.
     *
     * @param string|array|UriQuery $query 'arg=value&arg1=value1', ['arg' => 'val']
     * @return static
     */
    public function withQuery(string|array|UriQuery $query): static
    {        
        if (! $query instanceof UriQuery) {
            $query = new UriQuery($query);
        }

        $new = clone $this;
        $new->query = $query;

        return $new;
    }    
        
    /**
     * Get the request uri.
     *
     * @return string The request uri.
     */
    public function get(): string
    {
        $uri = $this->path()->get();
        
        if (!empty($this->query()?->get()))
        {
            $uri .= '?'.$this->query()->get();
        }    
        
        return $uri;    
    }

    /**
     * Returns the string representation of the request uri.
     *
     * @return string
     */  
    public function __toString(): string
    {
        return $this->get();
    }
    
    /**
     * Get the uri path.
     *
     * @return UriPath
     */
    public function path(): UriPath
    {
        return $this->path;
    }

    /**
     * If it has a query.
     *
     * @return bool
     */
    public function hasQuery(): bool
    {
        return !is_null($this->query);
    }
    
    /**
     * Get the uri query or null if none.
     *
     * @return null|UriQuery
     */
    public function query(): null|UriQuery
    {
        return $this->query;
    }        
}