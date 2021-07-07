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

namespace Tobento\Service\Sanitizer\Test\Mock;

use Tobento\Service\Sanitizer\FiltersParserInterface;
use Tobento\Service\Sanitizer\ParsedFilter;

/**
 * CustomFiltersParser
 */
class CustomFiltersParser implements FiltersParserInterface
{
    /**
     * Parses the filters.
     * 
     * @param string|array $filters
     * @return array The parsed filters [ParsedFilter, ...]
     */
    public function parse(string|array $filters): array
    {        
        if (empty($filters) || is_array($filters)) {
            return [];
        }
        
        $parsed = [];
        
        foreach(explode('|', $filters) as $filter)
        {
            if (str_contains($filter, ';'))
            {
                $params = explode(';', $filter);
                $filter = array_shift($params);

                $parsed[] = new ParsedFilter($filter, $params);
            }
            else
            {
                $parsed[] = new ParsedFilter($filter);
            }        
        }
        
        return $parsed; 
    }    
}