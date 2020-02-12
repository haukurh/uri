<?php

namespace Haukurh\Uri;

class Uri
{
    public $scheme;
    public $authority;
    public $path;
    public $query;
    public $fragment;

    public function __construct(string $uri)
    {
        $this->scheme = $this->matchScheme($uri);
        $this->authority = $this->matchAuthority($uri);
        $this->path = $this->matchPath($uri);
        $this->query = $this->matchQuery($uri);
        $this->fragment = $this->matchFragment($uri);
    }

    /**
     *
     * https://tools.ietf.org/html/rfc3986#section-3.1
     *
     * @param string $uri
     * @return string|null URI scheme, returns null if not found
     */
    protected function matchScheme(string $uri): ?string
    {
        preg_match('/^[a-zA-Z0-9\.\+-]+:/', $uri, $match);

        if (isset($match[0])) {
            return strtolower(rtrim($match[0], ':'));
        }
        return null;
    }

    /**
     * https://tools.ietf.org/html/rfc3986#section-3.2
     *
     * @param string $uri
     * @return string|null
     */
    protected function matchAuthority(string $uri): ?string
    {
        preg_match('/\/\/(?\'authority\'.*?)(\/|\?|$)/', $uri, $match);

        return $match['authority'] ?? null;
    }

    /**
     * Strips of the scheme off the URI
     * Helper function for matchPath() method
     *
     * @param string $uri
     * @return string URI without scheme
     */
    protected function stripScheme(string $uri): string
    {
        $scheme = $this->matchScheme($uri);
        $offset = !is_null($scheme) ? strlen($scheme) + 1 : 0;
        return substr($uri, $offset);
    }

    /**
     * Strips the authority section off the URI
     * Helper function for matchPath() method
     *
     * @param string $uri
     * @return string URI without authority section
     */
    protected function stripAuthority(string $uri): string
    {
        $authority = $this->matchAuthority($uri);
        $strippedUri = $uri;
        if (!is_null($authority)) {
            $pos = strpos($uri, "//{$authority}");
            $start = $pos + strlen($authority)+2;
            $strippedUri = substr($uri, 0, $pos) . substr($uri, $start);
        }

        return $strippedUri;
    }

    /**
     * https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @param string $uri
     * @return string|null returns URI path, returns null if not found
     */
    protected function matchPath(string $uri): ?string
    {
        // From RFC3986:
        //   If a URI contains an authority component, then the path component
        //   must either be empty or begin with a slash ("/") character.  If a URI
        //   does not contain an authority component, then the path cannot begin
        //   with two slash characters ("//").
        // Then to make it easier to match, we just strip off scheme and authority part off the URI
        $uri = $this->stripScheme($uri);
        $uri = $this->stripAuthority($uri);

        preg_match('/(?\'path\'.*?)(\?|\#|$)/', $uri, $match);

        return $match['path'] ?? null;
    }

    /**
     * https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @param string $uri
     * @return string|null
     */
    protected function matchQuery(string $uri): ?string
    {
        preg_match('/\?(?\'query\'.*?)(\#|$)/', $uri, $match);

        return $match['query'] ?? null;
    }

    /**
     * https://tools.ietf.org/html/rfc3986#section-3.5
     *
     * @param string $uri
     * @return string|null
     */
    protected function matchFragment(string $uri): ?string
    {
        preg_match('/\#(?\'fragment\'.+?)$/', $uri, $match);

        return $match['fragment'] ?? null;
    }

    public function getScheme(): ?string
    {
        return strtolower($this->scheme);
    }

    public function getAuthority(): ?string
    {
        return $this->authority;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    public function __toString()
    {
        $scheme = $this->getScheme();
        $query = $this->getQuery();
        $fragment = $this->getFragment();

        $uri = !is_null($scheme) ? "{$scheme}:" : '';
        $uri .= $this->getAuthority() ?? '';
        $uri .= $this->getPath() ?? '';
        $uri .= !is_null($query) ? "?{$query}" : '';
        $uri .= !is_null($fragment) ? "#{$fragment}" : '';

        return $uri;
    }

}
