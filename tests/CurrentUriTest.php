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
use Tobento\Service\Uri\CurrentUri;
use Tobento\Service\Uri\CurrentUriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * CurrentUriTest tests
 */
class CurrentUriTest extends TestCase
{    
    public function testCreateCurrentUri()
    {
        $factory = new Psr17Factory();
        
        $uri = $factory->createUri('https://example.com/base/path/');
        
        $this->assertInstanceof(CurrentUriInterface::class, new CurrentUri($uri));
    }
    
    public function testIsHomeMethod()
    {
        $factory = new Psr17Factory();
        
        $uri = $factory->createUri('https://example.com/base/path/');
        
        $this->assertfalse((new CurrentUri($uri, false))->isHome());
        $this->assertTrue((new CurrentUri($uri, true))->isHome());
    }    
}