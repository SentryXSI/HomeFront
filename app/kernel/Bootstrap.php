<?php
declare(strict_types=1);

namespace App\Kernel;

/**-----------------------------------------------------------------------------
 *
 * Class Bootstrap
 *
 * -----------------------------------------------------------------------------
 *
 * NinjaSentry HomeFront
 *
 * @author NinjaSentry
 * @package App\Kernel
 */
final class Bootstrap
{
    private $basePath;
    private $baseUrl;

    private $component = [];
    private $request   = [];
    private $response  = [];
    private $route     = [];
    private $theme;

    /**-------------------------------------------------------------------------
     *
     * Bootstrap constructor.
     *
     * -------------------------------------------------------------------------
     *
     * @throws \Exception
     */
    public function __construct()
    {
        register_shutdown_function([ $this, 'shutdownHandler']);
        set_exception_handler([ $this, 'exceptionHandler']);
        set_error_handler([ $this, 'errorHandler']);

        $this->getEnv();
        $this->getConfig();
        $this->getRequest();
        $this->getRoute();
        $this->getComponent();
        $this->getResponse();
        $this->dispatch();
        $this->getContent();
    }

    /**-------------------------------------------------------------------------
     *
     * Get Env
     *
     * -------------------------------------------------------------------------
     *
     * Load .env file into $_ENV super global
     *
     * @throws \Exception
     */
    private function getEnv()
    {
        $filePath = '../.env';

        if( ! \file_exists( $filePath ) )
        {
            throw new \Exception(
                'Bootstrap Error :: getEnv() - '
                . '.env file not found'
                , 503
            );
        }

        $config = \file( $filePath );

        if( ! \is_array( $config ) )
        {
            throw new \Exception(
                'Bootstrap Error :: getEnv() - '
                . '$config array not found'
                , 503
            );
        }

        foreach( $config as $item )
        {
            if( empty( $item )
                || ! \is_string( $item )
                || \substr( $item, 0, 1 ) === '#'
                || \strpos( $item, '=' ) === false
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
        $this->theme    = $_ENV['THEME_NAME'] ?? 'sweet';
    }

    /**-------------------------------------------------------------------------
     *
     * Get Route
     *
     * -------------------------------------------------------------------------
     */
    private function getRoute()
    {
        $uri = $_GET['route'] ?? 'home';

        if( \strpos( $uri, '/' ) === false )
        {
            $this->route     = [
                'module'     => $uri,
                'controller' => 'index',
                'action'     => 'index',
                'param'      => '',
                'arg'        => '',
            ];

        } else {

            $tmp = \explode( '/', $uri );
            $tmp = \array_filter( $tmp );

            $this->route     = [
                'module'     => $tmp[0] ?? 'home',
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
     *
     * @throws \Exception
     */
    private function dispatch()
    {
        $namespace = $this->component['namespace'];
        $classname = $this->component['controller'];
        $method    = $this->component['action'];

        $controllerClass = $namespace . $classname;

        if( ! \class_exists( $controllerClass ) )
        {
            throw new \Exception(
                'Bootstrap Error :: dispatch() - '
                . 'Controller not found ( '
                . $controllerClass
                . ' )'
                , 404
            );
        }

        $controller = new $controllerClass( $this->response );

        if( ! \is_callable([ $controller, $method ]) )
        {
            throw new \Exception(
                'Bootstrap Error :: dispatch() - '
                . 'class method ( '
                . $method
                . ' ) not found in controller ( '
                . $controllerClass
                . ' )'
                , 404
            );
        }

        \ob_start();
        $controller->$method();
        $this->content = \ob_get_clean();
    }

    /**-------------------------------------------------------------------------
     *
     * Get Component
     *
     * -------------------------------------------------------------------------
     *
     * Prepare component parts
     *
     */
    private function getComponent()
    {
        $normalise = function( $input ) {
            return \str_replace( ' ', '', \ucwords(
                \str_replace( '-', ' ', $input )
            ));
        };

        $module     = $normalise( $this->route['module'] );
        $controller = $normalise( $this->route['controller'] ) . 'Controller'
            ?? 'IndexController';

        $this->component = [
            'namespace'  => $this->getNamespace( $module ),
            'module'     => $module,
            'controller' => $controller,
            'action'     => $this->getAction(),
            'param'      => $this->route['param'] ?? '',
            'arg'        => $this->route['arg']   ?? '',
        ];
    }

    /**-------------------------------------------------------------------------
     *
     * Get Namespace
     *
     * -------------------------------------------------------------------------
     *
     * Prepare component namespace
     *
     * @param $module
     *
     * @return string
     */
    private function getNamespace( $module ): string
    {
        $namespace = "App\\Components\\$module\\";

        if( $this->route['controller'] !== 'index' ){
            $namespace .= 'Controllers\\';
        }

        return $namespace;
    }

    /**-------------------------------------------------------------------------
     *
     * Get Content
     *
     * -------------------------------------------------------------------------
     *
     * @throws \Exception
     */
    private function getContent()
    {
        $filePath = $this->basePath
            . 'public/themes/'
            . $this->theme
            .'/index.html.php';

        if( ! \file_exists( $filePath ) )
        {
            throw new \Exception(
                'Bootstrap Error :: getContent() - '
                . 'File not found ( '
                . $filePath
                . ' )'
            );
        }

        require $filePath;
    }

    /**-------------------------------------------------------------------------
     *
     * Get Request
     *
     * -------------------------------------------------------------------------
     */
    private function getRequest()
    {
        $this->request = [
            'method'   => \strtolower( $_SERVER['REQUEST_METHOD'] ) ?? '',
            'uri'      => \ltrim( $_SERVER['REQUEST_URI'], '/' )    ?? '',
            'query'    => $_SERVER['QUERY_STRING']                  ?? '',
            'protocol' => $_SERVER['SERVER_PROTOCOL']               ?? '',
            'scheme'   => $_SERVER['REQUEST_SCHEME']                ?? '',
        ];
    }

    /**-------------------------------------------------------------------------
     *
     * Get Response
     *
     * -------------------------------------------------------------------------
     */
    private function getResponse()
    {
        $this->route['dispatchHandler'] = $this->component;

        $this->response = [
            'baseUrl'   => $this->baseUrl,
            'basePath'  => $this->basePath,
            'request'   => $this->request,
            'route'     => $this->route,
        ];
    }

    /**-------------------------------------------------------------------------
     *
     * Get Action
     *
     * -------------------------------------------------------------------------
     *
     * Return request method + route action segment
     *
     * @return string
     */
    private function getAction(): string
    {
        $action = \ucfirst( $this->route['action'] );
        $method = $this->request['method'] . $action;

        return $method;
    }

    /**-------------------------------------------------------------------------
     *
     * Log Info
     *
     * -------------------------------------------------------------------------
     *
     * Add client info to log file messages
     *
     * @return string
     */
    private function logInfo()
    {
        $ip  = $_SERVER['REMOTE_ADDR']     ?? '';
        $ua  = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $uri = $this->request['uri']       ?? '/';

        return " , $uri , $ip , $ua";
    }

    /**-------------------------------------------------------------------------
     *
     * Error Handler
     *
     * -------------------------------------------------------------------------
     *
     * @param $errNo
     * @param $errStr
     * @param $errFile
     * @param $errLine
     *
     * @throws \ErrorException
     */
    public function errorHandler( $errNo, $errStr, $errFile, $errLine )
    {
        throw new \ErrorException(
            $errStr, 503, $errNo, $errFile, $errLine
        );
    }

    /**-------------------------------------------------------------------------
     *
     * Exception Handler
     *
     * -------------------------------------------------------------------------
     *
     * @param \Throwable $e
     */
    public function exceptionHandler( \Throwable $e )
    {
        $message = $e->getMessage();

        echo '<h1>Bootstrap Exception</h1>';
        pre( $message );

        pre( 'Line : ' . $e->getLine() );
        pre( 'File : ' . $e->getFile() );
        pre( 'Code : ' . $e->getCode() );
        echo '<hr /><p>Trace</p>';
        pre( $e->getTrace() );

        $message .= $this->logInfo();
        $message .= "\n";

        $this->log( $message, 'exceptions' );
    }

    /**-------------------------------------------------------------------------
     *
     * Shutdown Handler
     *
     * -------------------------------------------------------------------------
     */
    public function shutdownHandler()
    {
        $error = \error_get_last();

        if( isset( $error['type'] ) ) {
            $message = \implode( ' ', $error ) . "\n";
            $this->log( $message, 'shutdown-error' );
        }
    }

    /**-------------------------------------------------------------------------
     *
     * Log
     *
     * -------------------------------------------------------------------------
     *
     * Create log file in app/tmp/
     *
     * @param $message
     * @param $filename
     */
    private function log( $message, $filename )
    {
        $name = $filename ?? 'error';

        $logFile = $this->basePath
            . 'app/tmp/'
            . \date('Y-m-d')
            . '-'
            . $name
            . '.log';

        \error_log( $message, 3, $logFile );
    }
}
