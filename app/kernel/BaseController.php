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

    /**-------------------------------------------------------------------------
     *
     * BaseController constructor.
     *
     * -------------------------------------------------------------------------
     *
     * @param array $response
     */
    public function __construct( $response = [] ) {
        $this->response = $response;
    }

    /**-------------------------------------------------------------------------
     *
     * Get Index
     *
     * -------------------------------------------------------------------------
     *
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
            . $route['module']
            . '/views/'
            . $route['controller'];

        if( ! empty( $route['action'] )
            && $route['action'] !== 'index'
        ){
            $path .= '/' . $route['action'];
        }

        $path .= '.tpl.php';

        return $path;
    }

    /**-------------------------------------------------------------------------
     *
     * Content
     *
     * -------------------------------------------------------------------------
     *
     * Add response content
     *
     * @param string $name
     * @param array  $data
     */
    public function content( $name = '', $data = [] )
    {
        $this->response['content'] = [
            $name => $data
        ];
    }
}
