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
class Subdomain_Request extends Kohana_Request {

    /**
     * Request Subdomain
     * @var  string
     */
    public static $subdomain;

    /**
     * Creates a new request object for the given URI. New requests should be
     * Created using the [Request::factory] method.
     *
     *     $request = Request::factory($uri);
     *
     * If $cache parameter is set, the response for the request will attempt to
     * be retrieved from the cache.
     *
     * @param bool $uri                 URI of the request
     * @param array $client_params      An array of params to pass to the request client
     * @param bool $allow_external      Allow external requests? (deprecated in 3.3)
     * @param array $injected_routes    An array of routes to use, for testing
     * @return Request|void
     * @throws Request_Exception
     */
    public static function factory($uri = TRUE, $client_params = array(), $allow_external = TRUE, $injected_routes = array())
    {
        self::$subdomain = Request::catch_subdomain() ;
        return parent::factory($uri, $client_params, $allow_external, $injected_routes) ;
    }

    /**
     * Get Subdomain from URL or $_SERVER values
     * @param string $base_url  Base URL to the application
     * @param string $host      HOST
     * @return bool|string
     */
    public static function catch_subdomain(string $base_url = NULL, string $host = NULL)
    {
        if($base_url === NULL)
        {
            $base_url = parse_url(Kohana::$base_url, PHP_URL_HOST);
        }
        if($host === NULL)
        {
            if(php_sapi_name() == 'cli' AND empty($_SERVER['REMOTE_ADDR']))
            {
                return FALSE;
            }
            $host = $_SERVER['HTTP_HOST'];
        }
        if(empty($base_url) OR empty($host) OR in_array($host, Route::$localhosts) OR Valid::ip($host))
        {
            return FALSE;
        }
        $sub_pos = (int)strpos($host, $base_url) - 1;
        if($sub_pos > 0)
        {
            $subdomain = substr($host, 0, $sub_pos);
            if(! empty($subdomain)) {
                return $subdomain ;
            }
        }
        return Route::SUBDOMAIN_EMPTY;
    }
}