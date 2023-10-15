# Uri Service

The uri service provides useful classes to deal with URLs in your applications.

## Table of Contents

- [Getting started](#getting-started)
    - [Requirements](#requirements)
    - [Highlights](#highlights)
- [Documentation](#documentation)
    - [Urls](#urls)
    - [Uri Path](#uri-path)
    - [Uri Query](#uri-query)
    - [Uri Request](#uri-request)    
    - [Base Uri](#base-uri)
    - [Current Uri](#current-uri)
    - [Previous Uri](#previous-uri)
    - [Asset Uri](#asset-uri)
    - [Base Path Resolver](#base-path-resolver)
- [Credits](#credits)
___

# Getting started

Add the latest version of the uri service running this command.

```
composer require tobento/service-uri
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design
- Extendable

# Documentation

## Urls

A place to store your applications urls for later usage.

```php
use Tobento\Service\Uri\Urls;
use Psr\Http\Message\UriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

$urls = new Urls(uriFactory: new Psr17Factory());

// set urls
$urls->set('home', 'https://www.example.com');
$urls->set('backend', 'https://www.example.com/backend/');

// get urls
var_dump($urls->get('home'));
// string(23) "https://www.example.com"

// get urls with a default url
var_dump($urls->get('home.de', 'https://www.example.com/de'));
// string(26) "https://www.example.com/de"

// get uri
var_dump($urls->getUri('home') instanceof UriInterface);
// bool(true)

// get uri with default url
$uri = $urls->getUri('home.de', 'https://www.example.com/de');

// build url
var_dump($urls->build('home', path: 'foo/bar'));
// string(31) "https://www.example.com/foo/bar"

// get all urls
foreach($urls->all() as $key => $url) {
    //
}
```

You might define a custom builder:

```php
use Tobento\Service\Uri\Urls;
use Nyholm\Psr7\Factory\Psr17Factory;

$urls = new Urls(uriFactory: new Psr17Factory());

$urls->set(
    'home',
    'https://www.example.com',
    function(string $uri, ?string $path): string {
        // do custom building
        return $uri;
    }
);

// only on the build method your custom builder gets called.
var_dump($urls->build('home', path: 'foo/bar'));
// string(23) "https://www.example.com"
```

## Uri Path

The UriPath class is immutable, all transform methods return a new instance.

```php
use Tobento\Service\Uri\UriPath;

$path = new UriPath('/foo/bar');

// creating a new path
$newPath = $path->withPath('/foo/bar/new/');

// get the path string
$pathString = $path->get();
$pathString = (string)$path;

// Subtract a string from the beginning path
var_dump($path->sub('/foo')->get());
// string(4) "/bar"

// Decode path
var_dump($path->withPath('foo%20bar')->decode()->get());
// string(7) "foo bar"

// Encode path
var_dump($path->withPath('foo bar')->encode()->get());
// string(9) "foo%20bar"
```

Path segments:

```php
use Tobento\Service\Uri\UriPath;

$path = new UriPath('/foo/bar');

// creating a new path with segments
var_dump($path->withSegments(['foo', 'bar'])->get());
// string(7) "foo/bar"

var_dump($path->withSegments(['', 'foo', 'bar'])->get());
// string(8) "/foo/bar"

// get all segments
foreach($path->getSegments() as $segment) {
    var_dump($segment);
    // string(0) ""
    // string(3) "foo"
    // string(3) "bar"
}

// get segment, starting from 1.
var_dump($path->getSegment(1));
// string(0) ""

var_dump($path->getSegment(2));
// string(3) "foo"

var_dump($path->getSegment(4, default: 'value'));
// string(5) "value"

// prepend a segment from the index specified
var_dump($path->prependSegment('prepended', index: 2)->get());
// string(18) "/prepended/foo/bar"

// append a segment from the index specified
var_dump($path->appendSegment('appended', index: 2)->get());
// string(17) "/foo/appended/bar"

// delete a segment from the index specified
var_dump($path->deleteSegment(index: 2)->get());
// string(4) "/bar"
```

## Uri Query

The UriQuery class is immutable, all transform methods return a new instance.

```php
use Tobento\Service\Uri\UriQuery;

// from string
$query = new UriQuery('arg=value&arg1=value1');

// from array
$query = new UriQuery(['arg' => 'value', 'arg1' => 'value1']);

// creating a new query
$newQuery = $query->withQuery('arg=value&arg1=new');

// get the query string
$queryString = $query->get();
$queryString = (string)$query;

// add a parameter
var_dump($query->add('name', 'value')->get());
// string(32) "arg=value&arg1=value1&name=value"

// delete a parameter
var_dump($query->delete('arg')->get());
// string(11) "arg1=value1"

// modify parameters
var_dump(
    $query->modify([
        'arg' => 'new',
        'foo' => '1',
    ])->get()
);
// string(25) "arg=new&arg1=value1&foo=1"

// decode query
var_dump($query->withQuery('arg=foo%20bar&arg1=value1&foo=1')->decode()->get());
// string(29) "arg=foo bar&arg1=value1&foo=1"

// encode query
var_dump($query->withQuery('arg=foo bar&arg1=value1&foo=1')->encode()->get());
// string(31) "arg=foo%20bar&arg1=value1&foo=1"
```

## Uri Request

The UriRequest class is immutable, all with methods return a new instance.

```php
use Tobento\Service\Uri\UriRequest;
use Tobento\Service\Uri\UriPath;
use Tobento\Service\Uri\UriQuery;

$uri = new UriRequest('foo?arg=value');

// get the uri string
$uriString = $uri->get();
$uriString = (string)$uri;

// with a new path
var_dump($uri->withPath('bar')->get());
// string(13) "bar?arg=value"

var_dump($uri->withPath(new UriPath('bar'))->get());
// string(13) "bar?arg=value"

// with a new query
var_dump($uri->withQuery('arg=new')->get());
// string(11) "foo?arg=new"

var_dump($uri->withQuery(['arg' => 'new'])->get());
// string(11) "foo?arg=new"

var_dump($uri->withQuery(new UriQuery('arg=new'))->get());
// string(11) "foo?arg=new"

// get the path
var_dump($uri->path() instanceof UriPath);
// bool(true)

// check if uri has a query
var_dump($uri->hasQuery());
// bool(true)

// get the query, this returns null if no query exists.
var_dump($uri->query() instanceof UriQuery);
// bool(true)
```

## Base Uri

The BaseUri class might be useful for your application.

```php
use Tobento\Service\Uri\BaseUri;
use Tobento\Service\Uri\BaseUriInterface;
use Psr\Http\Message\UriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
        
$uri = (new Psr17Factory())->createUri('https://example.com/base/path/');

$baseUri = new BaseUri($uri);

var_dump($baseUri instanceof UriInterface);
// bool(true)

var_dump($baseUri instanceof BaseUriInterface);
// bool(true)
```

## Current Uri

The CurrentUri class might be useful for your application.

```php
use Tobento\Service\Uri\CurrentUri;
use Tobento\Service\Uri\CurrentUriInterface;
use Psr\Http\Message\UriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
        
$uri = (new Psr17Factory())->createUri('https://example.com/current/path/');

$currentUri = new CurrentUri($uri, isHome: true);

var_dump($currentUri instanceof UriInterface);
// bool(true)

var_dump($currentUri instanceof CurrentUriInterface);
// bool(true)

var_dump($currentUri->isHome());
// bool(true)
```

## Previous Uri

The PreviousUri class might be useful for your application.

```php
use Tobento\Service\Uri\PreviousUri;
use Tobento\Service\Uri\PreviousUriInterface;
use Psr\Http\Message\UriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
        
$uri = (new Psr17Factory())->createUri('https://example.com/previous/path/');

$previousUri = new PreviousUri($uri);

var_dump($previousUri instanceof UriInterface);
// bool(true)

var_dump($previousUri instanceof PreviousUriInterface);
// bool(true)
```

## Asset Uri

The AssetUri class might be useful for your application.

```php
use Tobento\Service\Uri\AssetUri;
use Tobento\Service\Uri\AssetUriInterface;
use Psr\Http\Message\UriInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
        
$uri = (new Psr17Factory())->createUri('https://example.com/asset/path/');

$assetUri = new AssetUri($uri);

var_dump($assetUri instanceof UriInterface);
// bool(true)

var_dump($assetUri instanceof BaseUriInterface);
// bool(true)
```

## Base Path Resolver

```php
use Tobento\Service\Uri\BasePathResolver;
use Tobento\Service\Uri\BasePathResolverInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

$serverRequest = (new Psr17Factory())->createServerRequest(
    'GET',
    'https://example.com',
    ['SCRIPT_NAME' => '/foo/uri.php']
);

var_dump((new BasePathResolver($serverRequest))->resolve());
// string(4) "/foo"

var_dump(new BasePathResolver($serverRequest) instanceof BasePathResolverInterface);
// bool(true)
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)