<?php
declare(strict_types=1);

namespace App\Kernel;

/**-----------------------------------------------------------------------------
 *
 * Class Bootstrap
 *
 * -----------------------------------------------------------------------------
 *
 * @package App\Kernel
 */
final class Bootstrap
{
    private $basePath;
    private $baseUrl;

    private $request  = [];
    private $response = [];
    private $route    = [];

    /**-------------------------------------------------------------------------
     *
     * Bootstrap constructor.
     *
     * -------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->getEnv();
        $this->getConfig();
        $this->getRequest();
        $this->getRoute();
        $this->dispatch();
        $this->getContent();
    }

    /**-------------------------------------------------------------------------
     *
     * Get Env
     *
     * -------------------------------------------------------------------------
     */
    private function getEnv()
    {
        $filePath = '../.env';

        if( ! \file_exists( $filePath ) )
        {
            exit( 'Bootstrap Error :: getEnv() - '
                . '.env file not found' );
        }

        $config = \file( $filePath );

        if( ! \is_array( $config ) )
        {
            exit( 'Bootstrap Error :: getEnv() - '
                . '$config array not found' );
        }

        foreach( $config as $item )
        {
            if( empty( $item )
                || ! \is_string( $item )
                || \substr( $item, 0, 1 ) === '#'
            ){
                continue;
            }

            [ $key, $value ] = \explode( '=', $item );

            $key   = \strtoupper( \trim ( $key ) );
            $value = \trim( trim( $value ), '\'' );

            if( ! isset( $_ENV[ $key ] ) ) {
                $_ENV[ $key ] = $value;
            }
        }
    }

    /**-------------------------------------------------------------------------
     *
     * Get Config
     *
     * -------------------------------------------------------------------------
     */
    private function getConfig()
    {
        $this->basePath = $_ENV['BASE_PATH'];
        $this->baseUrl  = $_ENV['BASE_URL'];
    }

    /**-------------------------------------------------------------------------
     *
     * Get Route
     *
     * -------------------------------------------------------------------------
     */
    public function getRoute()
    {
        $uri = $_GET['route'] ?? 'home';

        if( \strpos( $uri, '/' ) === false )
        {
            $this->route     = [
                'component'  => $uri,
                'controller' => 'index',
                'action'     => 'index',
                'param'      => '',
                'arg'        => '',
            ];

        } else {

            $tmp = \explode( '/', $uri );
            $tmp = \array_filter( $tmp );

            $this->route     = [
                'component'  => $tmp[0] ?? 'home',
                'controller' => $tmp[1] ?? 'index',
                'action'     => $tmp[2] ?? 'index',
                'param'      => $tmp[3] ?? '',
                'arg'        => $tmp[4] ?? '',
            ];
        }
    }

    /**-------------------------------------------------------------------------
     *
     * Dispatch
     *
     * -------------------------------------------------------------------------
     */
    private function dispatch()
    {
        $component = \ucfirst( $this->route['component'] );

        $namespace = "App\\Components\\$component\\";
        $classname = 'IndexController';
        $action    = 'Index';
        $method    = 'get' . $action;

        $this->route['dispatchHandler'] = [
            'component'   => $component,
            'controller'  => $classname,
            'action'      => $method,
            'param'       => '',
        ];

        $this->response = [
            'baseUrl'  => $this->baseUrl,
            'basePath' => $this->basePath,
            'request'  => $this->request,
            'route'    => $this->route,
        ];

        $controllerName = $namespace . $classname;

        if( ! \class_exists( $controllerName ) )
        {
            echo 'Bootstrap Error :: dispatch() - Controller file not found';
            pre( $controllerName );
            exit;
        }

        $controller = new $controllerName( $this->response );

        ob_start();
        $controller->$method();
        $this->content = ob_get_clean();
    }

    /**-------------------------------------------------------------------------
     *
     * Get Content
     *
     * -------------------------------------------------------------------------
     *
     */
    private function getContent()
    {
        $filePath = $this->basePath
            . 'public/themes/sweet/index.html.php';

        if( ! file_exists( $filePath ) )
        {
            echo 'Bootstrap Error :: getContent() - File not found';
            pre( $filePath );
            exit;
        }

        require $filePath;
    }

    /**-------------------------------------------------------------------------
     *
     * Request method
     *
     * -------------------------------------------------------------------------
     */
    public function getRequest()
    {
        $this->request['method'] = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->request['uri']    = $_SERVER['REQUEST_URI']    ?? '';
    }
}