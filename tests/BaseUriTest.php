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

    public function testGetMethods()
    {
        $factory = new Psr17Factory();
        $uri = $factory->createUri('https://user:pass@example.com:8080/base/path?q=abc#fragment');
        $uri = new BaseUri($uri);
        
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('user:pass@example.com:8080', $uri->getAuthority());
        $this->assertSame('user:pass', $uri->getUserInfo());
        $this->assertSame('example.com', $uri->getHost());
        $this->assertSame(8080, $uri->getPort());
        $this->assertSame('/base/path', $uri->getPath());
        $this->assertSame('q=abc', $uri->getQuery());
        $this->assertSame('fragment', $uri->getFragment());
    }
    
    public function testWithMethods()
    {
        $factory = new Psr17Factory();
        $uri = $factory->createUri('https://example.com/base/path/');
        $uri = new BaseUri($uri);
        
        $this->assertSame(
            'http://example.com/base/path/',
            (string)$uri->withScheme('http')
        );
        
        $this->assertSame(
            'https://user:password@example.com/base/path/',
            (string)$uri->withUserInfo('user', 'password')
        );
        
        $this->assertSame(
            'https://example.ch/base/path/',
            (string)$uri->withHost('example.ch')
        );
        
        $this->assertSame(
            'https://example.com:8080/base/path/',
            (string)$uri->withPort(8080)
        );
        
        $this->assertSame(
            'https://example.com/new',
            (string)$uri->withPath('new')
        );
        
        $this->assertSame(
            'https://example.com/base/path/?q=abc',
            (string)$uri->withQuery('q=abc')
        );
        
        $this->assertSame(
            'https://example.com/base/path/#fragment',
            (string)$uri->withFragment('fragment')
        );
    }
}