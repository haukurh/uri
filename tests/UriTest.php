<?php
declare(strict_types=1);

use Haukurh\Uri\Uri;
use PHPUnit\Framework\TestCase;

final class UriTest extends TestCase
{
    public function testPathShouldBeParsedAndMatchedCorrectly(): void
    {
        $uris = [
            'ftp://ftp.is.co.za/rfc/rfc1808.txt' => '/rfc/rfc1808.txt',
            'http://www.ietf.org/rfc/rfc2396.txt' => '/rfc/rfc2396.txt',
            'ldap://[2001:db8::7]/c=GB?objectClass?one' => '/c=GB',
            'mailto:John.Doe@example.com' => 'John.Doe@example.com',
            'news:comp.infosystems.www.servers.unix' => 'comp.infosystems.www.servers.unix',
            'tel:+1-816-555-1212' => '+1-816-555-1212',
            'telnet://192.0.2.16:80/' => '/',
            'file:///Users/haukur/somefile.txt' => '/Users/haukur/somefile.txt',
        ];

        foreach ($uris as $uri => $path) {
            $this->assertEquals($path, (new Uri($uri))->getPath());
        }
    }

    public function testAuthorityShouldBeParsedAndMatchedCorrectly(): void
    {
        $uris = [
            'ftp://ftp.is.co.za/rfc/rfc1808.txt' => 'ftp.is.co.za',
            'http://www.ietf.org/rfc/rfc2396.txt' => 'www.ietf.org',
            'ldap://[2001:db8::7]/c=GB?objectClass?one' => '[2001:db8::7]',
            'mailto:John.Doe@example.com' => null,
            'news:comp.infosystems.www.servers.unix' => null,
            'tel:+1-816-555-1212' => null,
            'telnet://192.0.2.16:80/' => '192.0.2.16:80',
            'file:///Users/haukur/somefile.txt' => '',
        ];

        foreach ($uris as $uri => $path) {
            $this->assertEquals($path, (new Uri($uri))->getAuthority());
        }
    }

    public function testSchemeShouldBeParsedAndMatchedCorrectly(): void
    {
        $uris = [
            'fTp://ftp.is.co.za/rfc/rfc1808.txt' => 'ftp',
            'http://www.ietf.org/rfc/rfc2396.txt' => 'http',
            'HTTPS://www.ietf.org/rfc/rfc2396.txt' => 'https',
            'ldap://[2001:db8::7]/c=GB?objectClass?one' => 'ldap',
            'mailto:John.Doe@example.com' => 'mailto',
            'news:comp.infosystems.www.servers.unix' => 'news',
            'tel:+1-816-555-1212' => 'tel',
            'telnet://192.0.2.16:80/' => 'telnet',
            'file:///Users/haukur/somefile.txt' => 'file',
        ];

        foreach ($uris as $uri => $path) {
            $this->assertEquals($path, (new Uri($uri))->getScheme());
        }
    }

    public function testQueryShouldBeParsedAndMatchedCorrectly(): void
    {
        $uris = [
            'http://www.ietf.org/rfc/rfc2396.txt?q=term#anchor' => 'q=term',
            'ldap://[2001:db8::7]/c=GB?objectClass?one' => 'objectClass?one',
            'mailto:John.Doe@example.com' => null,
            'news:comp.infosystems.www.servers.unix?' => '',
            'tel:+1-816-555-1212?doesTelSchemeHaveQuery=false' => 'doesTelSchemeHaveQuery=false',
            'file:///Users/haukur/somefile.txt?something#fragment' => 'something',
        ];

        foreach ($uris as $uri => $path) {
            $this->assertEquals($path, (new Uri($uri))->getQuery());
        }
    }

    public function testFragmentShouldBeParsedAndMatchedCorrectly(): void
    {
        $uris = [
            'http://www.ietf.org/rfc/rfc2396.txt?q=term#anchor' => 'anchor',
            'ldap://[2001:db8::7]/c=GB?objectClass#one' => 'one',
            'mailto:John.Doe@example.com' => null,
            'news:comp.infosystems.www.servers.unix?' => null,
            'tel:+1-816-555-1212?doesTelSchemeHaveQuery=false#' => '',
            'file:///Users/haukur/somefile.txt?something#fragment#' => 'fragment#',
        ];

        foreach ($uris as $uri => $path) {
            $this->assertEquals($path, (new Uri($uri))->getFragment());
        }
    }

    public function testTypeCastToString(): void
    {
        $uri = new Uri('HTTPS://www.ietf.org/rfc/rfc2396.txt');

        $this->assertEquals('https://www.ietf.org/rfc/rfc2396.txt', (string) $uri);
    }
}
