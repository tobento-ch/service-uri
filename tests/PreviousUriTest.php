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
use Tobento\Service\Uri\PreviousUri;
use Tobento\Service\Uri\PreviousUriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * PreviousUriTest tests
 */
class PreviousUriTest extends TestCase
{    
    public function testCreatePreviousUri()
    {
        $factory = new Psr17Factory();
        
        $uri = $factory->createUri('https://example.com/previous/path/');
        
        $this->assertInstanceof(PreviousUriInterface::class, new PreviousUri($uri));
    }
}