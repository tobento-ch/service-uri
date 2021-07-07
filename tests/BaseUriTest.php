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

namespace Tobento\Service\Uri\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Uri\BaseUri;
use Tobento\Service\Uri\BaseUriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * BaseUriTest tests
 */
class BaseUriTest extends TestCase
{    
    public function testCreateBaseUri()
    {
        $factory = new Psr17Factory();
        
        $uri = $factory->createUri('https://example.com/base/path/');
        
        $this->assertInstanceof(BaseUriInterface::class, new BaseUri($uri));
    }    
}