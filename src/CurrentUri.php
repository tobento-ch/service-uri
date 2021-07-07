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
 * CurrentUri
 */
class CurrentUri implements CurrentUriInterface
{
    use HasUri;
        
    /**
     * Create a new CurrentUri
     *
     * @param UriInterface $uri
     * @param bool $isHome If it is the home uri.
     */    
    public function __construct(
        UriInterface $uri,
        protected bool $isHome = false
    ) {
        $this->uri = $uri;
    }

    /**
     * If the current uri is home.
     *
     * @return bool
     */    
    public function isHome(): bool
    {
        return $this->isHome;
    }
}