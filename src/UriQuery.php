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
 * The uri query.
 */
class UriQuery
{
    /**
     * @var string The uri query.
     */    
    protected string $query = '';

    /**
     * @var array The query parameters.
     */    
    protected array $parameters = [];
            
    /**
     * Create a new UriQuery
     *
     * @param string|array $query A uri query. 'arg=value&arg1=value1' or as array ['arg' => 'val']
     */    
    final public function __construct(
        string|array $query = ''
    ) {
        if (is_string($query)) {
            $this->query = $query;
            $this->parameters = $this->buildParameters($this->query);
        } else {
            $this->query = $this->build($query);
            $this->parameters = $query;
        }
    }

    /**
     * Set the path query.
     *
     * @param string|array $query A uri query. 'arg=value&arg1=value1' or as array ['arg' => 'val']
     * @return static A new instance with the specified query.
     */
    public function withQuery(string|array $query): static
    {
        return new static($query);
    }
        
    /**
     * Get the query component of the URI.
     *
     * @return string The URI query.
     */
    public function get(): string
    {
        return $this->query;
    }
    
    /**
     * Returns the string representation of the query.
     *
     * @return string
     */  
    public function __toString(): string
    {
        return $this->get();
    }

    /**
     * Get the query parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }    

    /**
     * Adds a parameter with its value.
     *
     * @param mixed $name A name.
     * @param mixed $value A value.
     * @return static A new instance with the added parameter.
     */
    public function add(mixed $name, mixed $value): static
    {
        $parameters = $this->parameters;
        $parameters[$name] = $value;
        
        return $this->withQuery($parameters);
    }

    /**
     * Deletes a parameter with its value.
     *
     * @param mixed $name A name.
     * @return static A new instance with the deleted parameter.
     */
    public function delete(mixed $name): static
    {
        $parameters = $this->parameters;
        
        if (array_key_exists($name, $parameters))
        {
            unset($parameters[$name]);
        }
        
        return $this->withQuery($parameters);
    }

    /**
     * Modifies the parameters.
     *
     * @param array $parameters The new parameters.
     * @return static A new instance with the modified parameters.
     */
    public function modify(array $parameters): static
    {    
        return $this->withQuery(
            $this->modifyParameters($this->parameters, $parameters)
        );
    }

    /**
     * Decode the query.
     *
     * @return static A new instance with the decoded query.
     */
    public function decode(): static
    {
        return $this->withQuery(rawurldecode($this->query));
    }

    /**
     * Encode the query.
     *
     * @return static A new instance with the encoded query.
     */
    public function encode(): static
    {
        $parameters = [];
            
        foreach($this->parameters as $key => $value) {
            $parameters[$key] = rawurlencode($value);
        }
        
        return $this->withQuery($parameters);
    }
    
    /**
     * Modifies the parameters.
     *
     * @param array $parameters The parameters.
     * @param array $newParameters The new parameters.
     * @return array The modified parameters.
     */
    protected function modifyParameters(array $parameters, array $newParameters): array
    {
        foreach($newParameters as $name => $value)
        {
            if (is_array($value) && array_key_exists($name, $parameters))
            {
                $value = $this->modifyParameters($parameters[$name], $value);
            }
            
            // do not set if null, so we remove it.
            if ($value === null && array_key_exists($name, $parameters)) {
                unset($parameters[$name]);
            } else {
                $parameters[$name] = $value;
            }
        }

        return $parameters;
    }    
            
    /**
     * Builds query based on the parameters.
     *
     * @param array $parameters The query parameters.
     * @return string The build query based on the parameters.
     */
    protected function build(array $parameters): string
    {        
        $query = http_build_query($parameters, '', '&');
        $query = urldecode($query);
        return preg_replace('/\\[([0-9]+)\\]/', '[]', $query);
    }

    /**
     * Builds the parameters based on the query.
     *
     * @param string $query The query.
     * @return array The query as parameters.
     */
    protected function buildParameters(string $query): array
    {
        parse_str($query, $parameters);
        return $parameters;
    }    
}