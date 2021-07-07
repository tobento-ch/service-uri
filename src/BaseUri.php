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
 * BaseUri
 */
class BaseUri implements BaseUriInterface
{
    use HasUri;
        
    /**
     * Create a new BaseUri
     *
     * @param UriInterface $uri
     */    
    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
    }  
}