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

$uri = new Uri('HTTPS://www.example.com/some/path?q=term#result1');

echo $uri->getScheme(); // https
echo $uri->getAuthority(); // www.example.com
echo $uri->getPath(); // /some/path
echo $uri->getQuery(); // q=term
echo $uri->getFragment(); // result1

echo $uri; // https://www.example.com/some/path?q=term#result1

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
