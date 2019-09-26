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

        register_shutdown_function([ $this, 'shutdownHandler']);
        set_exception_handler([ $this, 'exceptionHandler']);
        set_error_handler([ $this, 'errorHandler']);


        //try{

            $this->getEnv();
            $this->getConfig();
            $this->getRequest();
            $this->getRoute();
            $this->dispatch();
            $this->getContent();
        //}
        //catch( \Throwable $e )
        //{
            //pre( $e->getMessage() );
            //pre( 'Line : ' . $e->getLine() );
            //pre( 'File : ' . $e->getFile() );
            //pre( 'Code : ' . $e->getCode() );
            //echo '<hr /><p>Trace</p>';
            //pre( $e->getTrace() );

        //}
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
        $component = \str_replace( ' ', '', \ucwords(
            \str_replace( '-', ' ' , $this->route['component'] )
        ));

        $namespace = "App\\Components\\$component\\";

        if( $this->route['controller'] !== 'index' ){
            $namespace .= 'Controllers\\';
        }

        $classname = \ucfirst( $this->route['controller'] ) . 'Controller'
            ?? 'IndexController';

        $action    = \ucfirst( $this->route['action'] );
        $method    = 'get' . $action;

        $this->route['dispatchHandler'] = [
            'component'   => $component,
            'controller'  => $classname,
            'action'      => $method,
            'param'       => $this->route['param'] ?? '',
        ];

        $this->response = [
            'baseUrl'  => $this->baseUrl,
            'basePath' => $this->basePath,
            'request'  => $this->request,
            'route'    => $this->route,
        ];

        $controllerClass = $namespace . $classname;

        if( ! \class_exists( $controllerClass ) )
        {
            echo 'Bootstrap Error :: dispatch() - Controller class not found';
            pre( $controllerClass );
            exit;
        }

        $controller = new $controllerClass( $this->response );

        ob_start();
        $controller->$method();
        $this->content = ob_get_clean();
    }


    private function getComponent()
    {
        $component = \str_replace( ' ', '', \ucwords(
            \str_replace( '-', ' ' , $this->route['component'] )
        ));

        $namespace = "App\\Components\\$component\\";
        $classname = ucfirst( $this->route['controller'] )
            . 'Controller' ?? 'IndexController';


        $action    = $this->route['action'] ?? 'Index';

        $method    = 'get' . $action;






        $this->route['dispatchHandler'] = [
            'namespace'   => $namespace,
            'component'   => $component,
            'controller'  => $classname,
            'action'      => $method,
            'param'       => '',
        ];



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

        if( ! \file_exists( $filePath ) )
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
        $this->request['method'] = \strtolower( $_SERVER['REQUEST_METHOD'] ) ?? '';
        $this->request['uri']    = \trim( $_SERVER['REQUEST_URI'], '/' )     ?? '';
        $this->request['query']  = $_SERVER['QUERY_STRING']                  ?? '';
    }

    /**
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

    /**
     * @param \Throwable $e
     */
    public function exceptionHandler( \Throwable $e )
    {
        echo '<h1>Exception</h1>';
        pre( $e->getMessage() );

        pre( 'Line : ' . $e->getLine() );
        pre( 'File : ' . $e->getFile() );
        pre( 'Code : ' . $e->getCode() );
        echo '<hr /><p>Trace</p>';
        pre( $e->getTrace() );

        $message = $e->getMessage();

        $logFile = $this->basePath
            . 'app/tmp/'
            . \date('Y-m-d')
            . '-exceptions.log';

        \error_log( $message, 3, $logFile );

    }

    /**
     *
     */
    public function shutdownHandler()
    {
        $error = error_get_last();

        if( isset( $error['type'] ) )
        {
            $message = \implode( ' ', $error );
            $logFile = $this->basePath
                . 'app/tmp/'
                . \date('Y-m-d')
                . '-shutdown-error.log';

            \error_log( $message, 3, $logFile );
        }
    }
}
