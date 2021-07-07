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
use Tobento\Service\Uri\BasePathResolver;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * BasePathResolverTest tests
 */
class BasePathResolverTest extends TestCase
{    
    public function testResolveMethod()
    {
        $factory = new Psr17Factory();
        
        $serverRequest = $factory->createServerRequest(
            'GET',
            'https://example.com',
            ['SCRIPT_NAME' => '/foo/uri.php']
        );
        
        $this->assertSame('/foo', (new BasePathResolver($serverRequest))->resolve());
    }
    
    public function testResolveMethodWithoutScriptNameReturnsEmptyString()
    {
        $factory = new Psr17Factory();
        
        $serverRequest = $factory->createServerRequest(
            'GET',
            'https://example.com',
            []
        );
        
        $this->assertSame('', (new BasePathResolver($serverRequest))->resolve());
    }    
}