# haukurh/uri

PHP library to parse URI into it's components, based on RFC 3986

## Description

The main idea of this library is to parse a URI into it's components. It does not validate the URI in any shape or form
only parse the URI, given some basic rules based on RFC 3986.

For now it acts very much like `parse_url()` but does not change invalid characters in the URL, like some utf8 characters
that may be a part of the path and/or query. 

Some validation and more functionality may be implemented in the future. 

## Basic usage 

Some basic getters have been made available to retrieve the desired component. 
The components are also available as public properties to read or write, but using the getters is encouraged because
some business logic maybe applied to the selected URI component, like scheme to lowercase.

```php
<?php

require_once 'vendor/autoload.php';

use Haukurh\Uri\Uri;

$uri = new Uri('HTTPS://john.doe@www.example.com:8000/some/path?q=term#result1');

echo $uri->getScheme() . PHP_EOL; // https
echo $uri->getAuthority() . PHP_EOL; // john.doe@www.example.com:8000
echo $uri->getPath() . PHP_EOL; // /some/path
echo $uri->getQuery() . PHP_EOL; // q=term
echo $uri->getFragment() . PHP_EOL; // result1

echo $uri . PHP_EOL; // https://john.doe@www.example.com:8000/some/path?q=term#result1

var_dump($uri->toArray());
// array(5) {
//   ["scheme"]=>
//   string(5) "https"
//   ["authority"]=>
//   string(29) "john.doe@www.example.com:8000"
//   ["path"]=>
//   string(10) "/some/path"
//   ["query"]=>
//   string(6) "q=term"
//   ["fragment"]=>
//   string(7) "result1"
// }

```

## Run tests

Some test have been created to ensure parsing of components are correct.

Run tests with phpunit

```bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
```

## ToDo

- Break down authority to it's subcomponents.
- Add some functionality based around URL based schemes like http, https
- Implement better tests with focus on some edge cases.
- Refactor tests
