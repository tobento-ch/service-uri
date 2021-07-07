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
use Tobento\Service\Uri\UriQuery;

/**
 * UriQueryTest tests
 */
class UriQueryTest extends TestCase
{
    public function testCreateQueryFromString()
    {        
        $this->assertSame(
            'arg=value&arg1=value1',
            (new UriQuery('arg=value&arg1=value1'))->get()
        );
    }
    
    public function testCreateQueryFromStringWithArray()
    {        
        $this->assertSame(
            'arg=value&arg1[]=1',
            (new UriQuery('arg=value&arg1[]=1'))->get()
        );
    }    
    
    public function testCreateQueryFromArray()
    {        
        $this->assertSame(
            'arg=value&arg1=value1',
            (new UriQuery(['arg' => 'value', 'arg1' => 'value1']))->get()
        );
        
        $this->assertSame(
            'arg=5',
            (new UriQuery(['arg' => 5]))->get()
        );
    }
    
    public function testCreateQueryFromArrayWithArray()
    {        
        $this->assertSame(
            'arg=value&arg1[]=foo&arg1[]=bar',
            (new UriQuery(['arg' => 'value', 'arg1' => ['foo', 'bar']]))->get()
        );
    }    
    
    public function testWithQueryMethodFromString()
    {        
        $this->assertSame(
            'arg=value&arg1=value1',
            (new UriQuery(''))->withQuery('arg=value&arg1=value1')->get()
        );
    }

    public function testWithQueryMethodFromArray()
    {        
        $this->assertSame(
            'arg=value&arg1=value1',
            (new UriQuery(''))->withQuery(['arg' => 'value', 'arg1' => 'value1'])->get()
        );
    }
    
    public function testGetParametersMethod()
    {
        $this->assertSame(
            ['arg' => 'value', 'arg1' => 'value1'],
            (new UriQuery(['arg' => 'value', 'arg1' => 'value1']))->getParameters()
        );
        
        $this->assertSame(
            ['arg' => 'value', 'arg1' => ['foo']],
            (new UriQuery(['arg' => 'value', 'arg1' => ['foo']]))->getParameters()
        );
    }
    
    public function testAddMethod()
    {        
        $this->assertSame(
            'arg=value&arg1=value1&foo=1',
            (new UriQuery('arg=value&arg1=value1'))->add('foo', '1')->get()
        );
        
        $this->assertSame(
            'arg=value&arg1=value1&foo=&bar=1',
            (new UriQuery('arg=value&arg1=value1'))->add('foo', '')->add('bar', '1')->get()
        );
    }
    
    public function testDeleteMethod()
    {        
        $this->assertSame(
            'arg1=value1',
            (new UriQuery('arg=value&arg1=value1'))->delete('arg')->get()
        );
        
        $this->assertSame(
            'arg=value&arg1=value1',
            (new UriQuery('arg=value&arg1=value1'))->delete('invalid')->get()
        );        
    }
    
    public function testModifyMethod()
    {        
        $this->assertSame(
            'arg=new&arg1=value1&foo=1',
            (new UriQuery('arg=value&arg1=value1'))->modify([
                'arg' => 'new',
                'foo' => '1'
            ])->get()
        );
        
        $this->assertSame(
            'arg=new&arg1[]=foo&arg1[]=new',
            (new UriQuery('arg=old&arg1[]=foo&arg1[]=bar'))->modify([
                'arg' => 'new',
                'arg1' => [1 => 'new'],
            ])->get()
        );
    }
    
    public function testDecodeMethod()
    {        
        $this->assertSame(
            'arg=foo bar&arg1=value1&foo=1',
            (new UriQuery('arg=foo%20bar&arg1=value1&foo=1'))->decode()->get()
        );
    }
    
    public function testEncodeMethod()
    {        
        $this->assertSame(
            'arg=foo%20bar&arg1=value1&foo=1',
            (new UriQuery('arg=foo bar&arg1=value1&foo=1'))->encode()->get()
        );
    }    
}