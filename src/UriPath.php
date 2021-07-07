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

namespace Tobento\Service\Uri;

/**
 * The uri path.
 */
class UriPath
{
    /**
     * @var array The path segments array.
     */    
    protected array $segments = [];
            
    /**
     * Create a UriPath
     *
     * @param string $path A uri path. '/path/to'
     */    
    final public function __construct(
        protected string $path = ''
    ) {        
        $this->path = $this->normalize($path);
        $this->segments = $this->buildSegements($this->path);
    }

    /**
     * Return an instance with the specified path component of the URI.
     *
     * @param string $path A uri path. '/path/to'
     * @return static A new instance with the specified path.
     */
    public function withPath(string $path): static
    {
        return new static($path);
    }
        
    /**
     * Get the path component of the URI.
     *
     * @return string The URI path.
     */
    public function get(): string 
    {
        return $this->path;
    }

    /**
     * Returns the string representation of the path.
     *
     * @return string
     */  
    public function __toString(): string
    {
        return $this->get();
    }    

    /**
     * Subtract from beginning path.
     *
     * @param string $string The string to subtract.
     * @return static A new instance with the substracted string.
     */
    public function sub(string $string): static
    {
        $strLen = mb_strlen($string);
        
        if (mb_substr($this->path, 0, $strLen) === $string)
        {
            $this->path = mb_substr($this->path, $strLen);
        }
        
        return new static($this->path);
    }

    /**
     * Decode the path.
     *
     * @return static A new instance with the decoded path.
     */
    public function decode(): static
    {
        return new static(rawurldecode($this->path));
    }

    /**
     * Encode the path.
     *
     * @return static A new instance with the encoded path.
     */
    public function encode(): static
    {
        $segments = [];
        
        foreach($this->segments as $index => $segment)
        {
            $segments[$index] = rawurlencode($segment);
        }
        
        return $this->withSegments($segments);
    }
            
    /**
     * Return an instance with the specified path segments component of the URI.
     *
     * @param array $segments The segments. ['', 'path', 'to', '']
     * @return static A new instance with the specified path segments.
     */
    public function withSegments(array $segments): static
    {        
        $path = implode('/', $segments);
        
        return new static($path);
    }    
        
    /**
     * Get the path segments.
     *
     * @return array
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * Returns the path segment by key.
     *
     * @param int $index The segment index. Note: the index starts from 1 and not 0.
     * @param mixed $default
     * @return mixed
     */
    public function getSegment(int $index, mixed $default = null): mixed
    {
        $index = $index-1;
        
        return (array_key_exists($index, $this->segments)) ? $this->segments[$index] : $default;
    }

    /**
     * Prepends a segment on the index.
     *
     * @param string $segment The segment.
     * @param int $index The segment index. Note: the index starts from 1 and not 0.
     * @return static A new instance with the prepended segment.
     */
    public function prependSegment(string $segment, int $index = 1): static
    {
        $index = $index-1;
        
        $segments = $this->segments;
        
        if (isset($segments[$index]))
        { 
            array_splice($segments, $index, 0, $segment);
        }

        return $this->withSegments($segments);
    }
    
    /**
     * Appends a segment on the index.
     *
     * @param string $segment The segment.
     * @param int $index The segment index. Note: the index starts from 1 and not 0.
     * @return static A new instance with the apended segment.
     */
    public function appendSegment(string $segment, int $index = 1): static
    {
        $index = $index-1;
        
        $segments = $this->segments;
        
        if (isset($segments[$index]))
        { 
            array_splice($segments, $index+1, 0, $segment);
        }
        
        return $this->withSegments($segments);
    }
    
    /**
     * Deletes a segment.
     *
     * @param int $index The segment index. Note: the index starts from 1 and not 0.
     * @return static A new instance with the deleted segment.
     */
    public function deleteSegment(int $index): static
    {
        $index = $index-1;
        
        $segments = $this->segments;
        
        if (isset($segments[$index]))
        { 
            unset($segments[$index]);
            // Reindex the segments.
            $segments = array_values($segments);
        }
        
        return $this->withSegments($segments);
    }

    /**
     * Normalizes the path.
     *
     * @param string $path The path.
     * @return string The normalized path.
     */
    protected function normalize(string $path): string
    {
        // skip query if exist.
        if (strpos($path, '?') !== false) {
            $pathArr = explode('?', $path);
            return $pathArr[0];
        }
        
        return $path;
    }
                
    /**
     * Builds the segments from the path.
     *
     * @param string $path A uri path. '/path/to'
     * @return array
     */
    protected function buildSegements(string $path): array
    {
        // we do not trim slash. It's the task
        // of the user to handle this.
        
        return empty($path) ? [] : explode('/', $path);
    }
}