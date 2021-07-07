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
use Tobento\Service\Uri\UriPath;

/**
 * UriPathTest tests
 */
class UriPathTest extends TestCase
{
    public function testPaths()
    {        
        $this->assertSame('/foo', (new UriPath('/foo'))->get());
        
        $this->assertSame('/foo/', (new UriPath('/foo/'))->get());
        
        $this->assertSame('/foo/bar', (new UriPath('/foo/bar'))->get());
        
        $this->assertSame('/foo/bar/', (new UriPath('/foo/bar/'))->get());
        
        $this->assertSame('foo%20bar%40baz', (new UriPath('foo%20bar%40baz'))->get());
    }
    
    public function testPathWithQueryReturnsOnlyPath()
    {        
        $this->assertSame('/foo', (new UriPath('/foo?foo=1'))->get());
        
        $this->assertSame('foo/bar', (new UriPath('foo/bar?foo=1'))->get());
        
        $this->assertSame('/foo/bar', (new UriPath('/foo/bar?foo=1'))->get());
        
        $this->assertSame('/foo/bar/', (new UriPath('/foo/bar/?foo=1'))->get());
    }    
    
    public function testEmptyStringReturnsEmptyString()
    {        
        $this->assertSame('', (new UriPath(''))->get());
    }
    
    public function testSlashOnlyReturnsSlashOnly()
    {        
        $this->assertSame('/', (new UriPath('/'))->get());
    }
    
    public function testWithPathMethod()
    {
        $this->assertSame('foo', (new UriPath('bar'))->withPath('foo')->get());
    }
    
    public function testSubMethod()
    {        
        $this->assertSame('', (new UriPath('/'))->sub('/')->get());
        
        $this->assertSame('', (new UriPath('foo'))->sub('foo')->get());
        
        $this->assertSame('/foo', (new UriPath('/foo'))->sub('foo')->get());
        
        $this->assertSame('bar', (new UriPath('/foo/bar'))->sub('/foo/')->get());
        
        $this->assertSame('/foo/bar', (new UriPath('/foo/bar'))->sub('')->get());
    }
    
    public function testDecodeMethod()
    {        
        $this->assertSame('foo bar@baz', (new UriPath('foo%20bar%40baz'))->decode()->get());
    }
    
    public function testEncodeMethod()
    {        
        $this->assertSame('foo%20bar%40baz', (new UriPath('foo bar@baz'))->encode()->get());
        
        $this->assertSame('bar/foo%20bar%40baz', (new UriPath('bar/foo bar@baz'))->encode()->get());
    }
    
    public function testWithSegmentsMethod()
    {        
        $this->assertSame(
            '/path/to/',
            (new UriPath('foo'))->withSegments(['', 'path', 'to', ''])->get()
        );
        
        $this->assertSame(
            'path/to/',
            (new UriPath('foo'))->withSegments(['path', 'to', ''])->get()
        );
        
        $this->assertSame(
            '/path/to',
            (new UriPath('foo'))->withSegments(['', 'path', 'to'])->get()
        );
        
        $this->assertSame(
            'path/to',
            (new UriPath('foo'))->withSegments(['path', 'to'])->get()
        );
    }
    
    public function testGetSegmentsMethod()
    {        
        $this->assertSame(
            ['foo'],
            (new UriPath('foo'))->getSegments()
        );
        
        $this->assertSame(
            ['foo', ''],
            (new UriPath('foo/'))->getSegments()
        );
        
        $this->assertSame(
            ['', 'foo'],
            (new UriPath('/foo'))->getSegments()
        );
        
        $this->assertSame(
            ['', 'foo', ''],
            (new UriPath('/foo/'))->getSegments()
        );
        
        $this->assertSame(
            ['', 'foo', 'bar', ''],
            (new UriPath('/foo/bar/'))->getSegments()
        );
    }
    
    public function testGetSegmentsWithSlashesShouldBeSameAsWithSegments()
    {
        $segments = (new UriPath('/foo/bar/'))->getSegments();
        
        $this->assertSame(
            '/foo/bar/',
            (new UriPath('foo'))->withSegments($segments)->get()
        );
    }    
    
    public function testGetSegmentMethod()
    {        
        $this->assertSame(null, (new UriPath(''))->getSegment(0));
        $this->assertSame(null, (new UriPath(''))->getSegment(1));
        $this->assertSame('default', (new UriPath(''))->getSegment(1, 'default'));
        $this->assertSame(null, (new UriPath('foo'))->getSegment(0));
        $this->assertSame('foo', (new UriPath('foo'))->getSegment(1));
        $this->assertSame('', (new UriPath('/foo'))->getSegment(1));
        $this->assertSame('foo', (new UriPath('/foo'))->getSegment(2));
        $this->assertSame('bar', (new UriPath('/foo/bar'))->getSegment(3));
    }

    public function testPrependSegmentMethod()
    {        
        $this->assertSame(
            'prepended/foo',
            (new UriPath('foo'))->prependSegment('prepended', 1)->get()
        );
        
        $this->assertSame(
            'prepended//foo',
            (new UriPath('/foo'))->prependSegment('prepended', 1)->get()
        );
        
        $this->assertSame(
            '/prepended/foo',
            (new UriPath('/foo'))->prependSegment('prepended', 2)->get()
        );      
        
        $this->assertSame(
            'foo',
            (new UriPath('foo'))->prependSegment('prepended', 2)->get()
        );
        
        $this->assertSame(
            'prepended/foo/bar',
            (new UriPath('foo/bar'))->prependSegment('prepended', 1)->get()
        );   
        
        $this->assertSame(
            'foo/prepended/bar',
            (new UriPath('foo/bar'))->prependSegment('prepended', 2)->get()
        );
        
        $this->assertSame(
            '/prepended/foo/bar',
            (new UriPath('/foo/bar'))->prependSegment('prepended', 2)->get()
        );
        
        $this->assertSame(
            'prepended//foo/bar',
            (new UriPath('/foo/bar'))->prependSegment('prepended', 1)->get()
        ); 
        
        $this->assertSame(
            'foo/prepended/bar/',
            (new UriPath('foo/bar/'))->prependSegment('prepended', 2)->get()
        );
    }
    
    public function testAppendSegmentMethod()
    {        
        $this->assertSame(
            'foo/appended',
            (new UriPath('foo'))->appendSegment('appended', 1)->get()
        );
        
        $this->assertSame(
            'foo',
            (new UriPath('foo'))->appendSegment('appended', 2)->get()
        );
        
        $this->assertSame(
            'foo/appended/',
            (new UriPath('foo/'))->appendSegment('appended', 1)->get()
        );
        
        $this->assertSame(
            'foo//appended',
            (new UriPath('foo/'))->appendSegment('appended', 2)->get()
        );
    }
    
    public function testDeleteSegmentMethod()
    {        
        $this->assertSame(
            '',
            (new UriPath('foo'))->deleteSegment(1)->get()
        );
        
        $this->assertSame(
            'foo',
            (new UriPath('/foo'))->deleteSegment(1)->get()
        );
        
        $this->assertSame(
            '',
            (new UriPath('/foo'))->deleteSegment(2)->get()
        );
        
        $this->assertSame(
            'foo',
            (new UriPath('foo/bar'))->deleteSegment(2)->get()
        );
        
        $this->assertSame(
            'foo/',
            (new UriPath('foo/bar/'))->deleteSegment(2)->get()
        );        
    }    
}