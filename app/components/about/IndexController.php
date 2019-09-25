<?php
declare(strict_types=1);

namespace App\Components\About;

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
            . 'app/components/about/views/index.tpl.php';

        if( ! \file_exists( $filePath ) )
        {
            echo 'About - IndexController Error :: getIndex() - '
                . 'file not found';
            pre( $filePath );
            exit;
        }

        return require $filePath;
    }
}