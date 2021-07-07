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
use Tobento\Service\Uri\UriRequest;
use Tobento\Service\Uri\UriPath;
use Tobento\Service\Uri\UriQuery;

/**
 * UriRequestTest tests
 */
class UriRequestTest extends TestCase
{    
    public function testPathMethod()
    {        
        $this->assertInstanceof(UriPath::class, (new UriRequest(''))->path());
        
        $this->assertSame('foo/bar', (new UriRequest('foo/bar'))->path()->get());
    }
    
    public function testQueryMethod()
    {        
        $this->assertInstanceof(UriQuery::class, (new UriRequest('foo?bar=1'))->query());
        
        $this->assertSame('bar=1', (new UriRequest('foo?bar=1'))->query()->get());
        
        $this->assertSame(null, (new UriRequest(''))->query());
    }
    
    public function testWithPathMethodWithString()
    {        
        $this->assertSame(
            'bar?arg=value',
            (new UriRequest('foo?arg=value'))->withPath('bar')->get()
        );
    }
    
    public function testWithPathMethodWithUriPath()
    {        
        $this->assertSame(
            'bar?arg=value',
            (new UriRequest('foo?arg=value'))->withPath(new UriPath('bar'))->get()
        );
    }
    
    public function testWithQueryMethodWithString()
    {        
        $this->assertSame(
            'foo?arg1=value1',
            (new UriRequest('foo?arg=value'))->withQuery('arg1=value1')->get()
        );
    }
    
    public function testWithQueryMethodWithArray()
    {        
        $this->assertSame(
            'foo?arg1=value1',
            (new UriRequest('foo?arg=value'))->withQuery(['arg1' => 'value1'])->get()
        );
    }
    
    public function testWithQueryMethodWithUriQuery()
    {
        $this->assertSame(
            'foo?arg1=value1',
            (new UriRequest('foo?arg=value'))->withQuery(new UriQuery('arg1=value1'))->get()
        );
    }    
}