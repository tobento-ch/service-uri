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
use Tobento\Service\Uri\AssetUri;
use Tobento\Service\Uri\AssetUriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * AssetUriTest tests
 */
class AssetUriTest extends TestCase
{    
    public function testCreateAssetUri()
    {
        $factory = new Psr17Factory();
        
        $uri = $factory->createUri('https://example.com/base/path/');
        
        $this->assertInstanceof(AssetUriInterface::class, new AssetUri($uri));
    }

    public function testGetMethods()
    {
        $factory = new Psr17Factory();
        $uri = $factory->createUri('https://user:pass@example.com:8080/base/path?q=abc#fragment');
        $uri = new AssetUri($uri);
        
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
        $uri = new AssetUri($uri);
        
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