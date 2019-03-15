<?php
/**
 * This implements ability to catch subdomains and set routes specifically by sub-domain.
 *
 * e.G.:
 *  Route::set('default', '(<controller>(/<action>(/<id>)))')
 *     ->subdomains(array(Route::SUBDOMAIN_EMPTY, 'www1', 'foo', 'bar'))
 *     ->defaults(array(
 *         'controller' => 'welcome',
 *     ));
 *
 * @package    Subdomain
 * @category   Base
 * @copyright  (c) 2007-2014  jeanmask
 * @copyright  (c) since 2018 Koseven Team
 * @license    https://koseven.ga/LICENSE.md
 */
class Subdomain_Route extends KO7_Route {

    /**
     * Wildcard e.g *.example.com
     */
    const SUBDOMAIN_WILDCARD = '*';

    /**
     * Empty Subdomain
     */
    const SUBDOMAIN_EMPTY = '' ;

    /**
     * Default Subdomains (empty/none and www)
     * @var array
     */
    public static $default_subdomains = array
    (
        self::SUBDOMAIN_EMPTY,
        'www'
    );

    /**
     * Rout Subdomain
     * @var  string
     */
    protected $_subdomain;

    /**
     * Creates a new route.
     * Sets the URI and regular expressions for keys.
     * Sets default subdomains in this route
     * Routes should always be created with [Route::set] or they will not
     * be properly stored.
     *
     * The $uri parameter should be a string for basic regex matching.
     *
     * @param   string  $uri    route URI pattern
     * @param   array   $regex  key patterns
     * @return  void
     * @uses    Route::_compile
     */
    public function __construct(string $uri = NULL, array $regex = NULL)
    {
        parent::__construct($uri, $regex);
        $this->_subdomain = self::$default_subdomains;
    }

    /**
     * Set one or more subdomains to execute this route
     *
     * @param array $name         name(s) of subdomain(s) to apply in route
     * @return Subdomain_Route
     */
    public function subdomains(array $name) : Subdomain_Route
    {
        $this->_subdomain = $name;
        return $this;
    }

    /**
     * Tests if the route matches a given Request. A successful match will return
     * all of the routed parameters as an array. A failed match will return
     * boolean FALSE.
     *
     * @param Request $request    Request object to match
     * @param string $subdomain   Subdomain
     * @return array|FALSE        on success|on failure
     */
    public function matches(Request $request, string $subdomain = NULL)
    {
        $subdomain = (!isset($subdomain) OR $subdomain === NULL) ? Request::$subdomain : $subdomain;
        if($subdomain === FALSE)
        {
            $subdomain = self::SUBDOMAIN_EMPTY;
        }
        if(in_array(self::SUBDOMAIN_WILDCARD, $this->_subdomain) OR in_array($subdomain, $this->_subdomain))
        {
            return parent::matches($request) ;
        }
        return FALSE;
    }

    /**
     * Create a URL from a route name, with subdomain support
     *
     * @param   string  $name       route name
     * @param   array   $params     URI parameters
     * @param   mixed   $protocol   protocol string or boolean, adds protocol and domain
     * @return  string
     * @throws KO7_Exception
     */
    public static function url($name, array $params = NULL, $protocol = NULL)
    {
        $route = Route::get($name);

        // Create a URI with the route and convert it to a URL
        if ($route->is_external())
            return $route->uri($params);

        $subdomain = $route->_subdomain[0] ?? FALSE;
        if (!$subdomain) {
            return URL::site($route->uri($params), $protocol);
        }
        return URL::site($route->uri($params), $protocol, TRUE, $subdomain);
    }
}
