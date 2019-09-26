<?php
declare(strict_types=1);

namespace App\Kernel;

/**-----------------------------------------------------------------------------
 *
 * Class BaseController
 *
 * -----------------------------------------------------------------------------
 *
 * @package App\Kernel
 */
abstract class BaseController
{
    /**
     * @var array
     */
    public $response = [];

    /**
     * BaseController constructor.
     *
     * @param array $response
     */
    public function __construct( $response = [] ) {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    abstract public function getIndex();

    /**-------------------------------------------------------------------------
     *
     * Display
     *
     * -------------------------------------------------------------------------
     *
     * @throws \Exception
     */
    public function display()
    {
        $filePath = $this->getPath();

        if( ! \file_exists( $filePath ) )
        {
            throw new \Exception(
                'Base Controller Error :: display() - file not found '
                . $filePath
                , 404
            );
        }

        require $filePath;
    }

    /**-------------------------------------------------------------------------
     *
     * Get Path
     *
     * -------------------------------------------------------------------------
     *
     * @return string
     */
    private function getPath(): string
    {
        $route = $this->response['route'];

        $path = $this->response['basePath']
            . 'app/components/'
            . $route['component']
            . '/views/'
            . $route['controller'];

        if( ! empty( $route['action'] )
            && $route['action'] !== 'index'
        ){
            $path .= '/' . $route['action'];
        }

        $path .= '.tpl.php';

        pre( $path );

        return $path;
    }
}