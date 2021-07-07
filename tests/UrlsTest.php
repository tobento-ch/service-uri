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
use Tobento\Service\Uri\Urls;
use Tobento\Service\Uri\UrlsInterface;
use Tobento\Service\Uri\UrlsException;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * UrlsTest tests
 */
class UrlsTest extends TestCase
{
    protected function createUrls(): UrlsInterface
    {        
        return new Urls(new Psr17Factory());
    }
    
    public function testSetMethod()
    {
        $urls = $this->createUrls();
        
        $urls->set('home', 'https://www.example.com');
        
        $this->assertSame('https://www.example.com', $urls->get('home'));
    }
    
    public function testSetMethodOverwritesKey()
    {
        $urls = $this->createUrls();
        
        $urls->set('home', 'https://www.example.com');
        $urls->set('home', 'https://www.example.com/foo');
        
        $this->assertSame('https://www.example.com/foo', $urls->get('home'));
    }    
    
    public function testGetMethodReturnsDefaultIfNotExists()
    {
        $urls = $this->createUrls();
        
        $this->assertSame(null, $urls->get('home'));
        
        $this->assertSame('https://www.example.com', $urls->get('home', 'https://www.example.com'));
    }
    
    public function testGetUriMethod()
    {
        $urls = $this->createUrls();
        
        $this->assertSame('', (string)$urls->getUri('home'));
        
        $this->assertSame('https://www.example.com', (string)$urls->getUri('home', 'https://www.example.com'));
        
        $urls->set('home', 'https://www.example.com');
        $this->assertSame('https://www.example.com', (string)$urls->getUri('home'));
    }
    
    public function testAllMethod()
    {
        $urls = $this->createUrls();
        
        $urls->set('home', 'https://www.example.com');
        $urls->set('backend', 'https://www.example.com/backend/');
        
        $this->assertSame(
            [
                'home' => 'https://www.example.com',
                'backend' => 'https://www.example.com/backend/',
            ],
            $urls->all()
        );
    }
    
    public function testBuildMethodThrowsUrlsExceptionIfKeyDoesNotExist()
    {
        $this->expectException(UrlsException::class);
        
        $urls = $this->createUrls();
        
        $urls->build('home');
    }
    
    public function testBuildMethod()
    {        
        $urls = $this->createUrls();
        
        $urls->set('home', 'https://www.example.com');
        
        $this->assertSame('https://www.example.com', $urls->build('home'));
    }
    
    public function testBuildMethodWithPath()
    {        
        $urls = $this->createUrls();
        
        $urls->set('home', 'https://www.example.com');
        $urls->set('backend', 'https://www.example.com/backend/');
        
        $this->assertSame(
            'https://www.example.com',
            $urls->build('home', '')
        );
        
        $this->assertSame(
            'https://www.example.com/',
            $urls->build('home', '/')
        );
        
        $this->assertSame(
            'https://www.example.com/foo/bar',
            $urls->build('home', 'foo/bar')
        );
        
        $this->assertSame(
            'https://www.example.com/foo/bar',
            $urls->build('home', '/foo/bar')
        );
        
        $this->assertSame(
            'https://www.example.com/foo/bar/',
            $urls->build('home', '/foo/bar/')
        );
        
        $this->assertSame(
            'https://www.example.com/backend/foo/bar',
            $urls->build('backend', 'foo/bar')
        );
        
        $this->assertSame(
            'https://www.example.com/backend/foo/bar',
            $urls->build('backend', '/foo/bar')
        );
        
        $this->assertSame(
            'https://www.example.com/backend/foo/bar/',
            $urls->build('backend', '/foo/bar/')
        );
    }

    public function testBuildMethodWithPathFragment()
    {        
        $urls = $this->createUrls();
        
        $urls->set('home', 'https://www.example.com');
        $urls->set('backend', 'https://www.example.com/backend/');
        
        $this->assertSame(
            'https://www.example.com#',
            $urls->build('home', '#')
        );
        
        $this->assertSame(
            'https://www.example.com/backend#',
            $urls->build('backend', '#')
        );
        
        $this->assertSame(
            'https://www.example.com/backend/foo/bar#',
            $urls->build('backend', 'foo/bar#')
        );
    }
    
    public function testBuildMethodWithPathRelativeUrl()
    {        
        $urls = $this->createUrls();
        
        $urls->set('home', 'home/path');
        
        $this->assertSame(
            'home/path',
            $urls->build('home', '')
        );
        
        $this->assertSame(
            'home/path/',
            $urls->build('home', '/')
        );
        
        $this->assertSame(
            'home/path/foo/bar',
            $urls->build('home', 'foo/bar')
        );
        
        $this->assertSame(
            'home/path/foo/bar',
            $urls->build('home', '/foo/bar')
        );
        
        $this->assertSame(
            'home/path/foo/bar/',
            $urls->build('home', '/foo/bar/')
        );
    }
    
    public function testBuildMethodWithBuilder()
    {        
        $urls = $this->createUrls();
        
        $urls->set('home', 'https://www.example.com', function($url, $path) {
            return $url;
        });
        
        $this->assertSame(
            'https://www.example.com',
            $urls->build('home', '/foo/bar/')
        );
    }    
}