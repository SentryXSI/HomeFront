<?php
declare(strict_types=1);

namespace App\Components\Home;

/**-----------------------------------------------------------------------------
 *
 * Class IndexController
 *
 * -----------------------------------------------------------------------------
 *
 * @package App\Components\Home
 */
class IndexController
{
    /**
     * IndexController constructor.
     *
     * @param $response
     */
    public function __construct( $response ) {
        $this->response = $response;
    }

    /**-------------------------------------------------------------------------
     *
     * Get Index
     *
     * -------------------------------------------------------------------------
     */
    public function getIndex()
    {
        $filePath = $this->response['basePath']
            . 'app/components/home/views/index.tpl.php';

        if( ! \file_exists( $filePath ) ) {
            echo 'IndexController Error :: getIndex() - file not found ( ';
            echo $filePath;
            echo ' ) ';
            exit;
        }

        return require $filePath;
    }
}