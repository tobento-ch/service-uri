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

use Psr\Http\Message\ServerRequestInterface;

/**
 * BasePathResolver
 */
class BasePathResolver implements BasePathResolverInterface
{        
    /**
     * Create a new BasePathResolver.
     *
     * @param ServerRequestInterface $request
     */    
    public function __construct(
        protected ServerRequestInterface $request
    ) {}

    /**
     * Get the resolved base path
     *
     * @return string
     */
    public function resolve(): string
    {
        $server = $this->request->getServerParams();
        $requestScriptName = parse_url($server['SCRIPT_NAME'] ?? '', PHP_URL_PATH);
        $requestScriptDir = dirname($requestScriptName);
        
        return $requestScriptDir !== DIRECTORY_SEPARATOR ? $requestScriptDir : '';
    }
}