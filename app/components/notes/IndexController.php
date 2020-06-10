<?php
declare(strict_types=1);

namespace App\Components\Notes;

use App\Kernel\BaseController;

/**-----------------------------------------------------------------------------
 *
 * Class IndexController
 *
 * -----------------------------------------------------------------------------
 *
 * Notes component index page
 *
 * @package App\Components\Notes
 */
final class IndexController extends BaseController
{
    /**-------------------------------------------------------------------------
     *
     * IndexController constructor.
     *
     * -------------------------------------------------------------------------
     *
     * @param $response
     */
    public function __construct( $response = [] ){
        parent::__construct( $response );
    }

    /**-------------------------------------------------------------------------
     *
     * Get Index
     *
     * -------------------------------------------------------------------------
     *
     * Notes demo page
     *
     * @return mixed|void
     * @throws \Exception
     */
    public function getIndex()
    {
        $filePath = $this->response['basePath']
            . 'app/content/notes/notes-test.txt';

        $data = [];

        if( \file_exists( $filePath ) ) {
            $data = \file_get_contents( $filePath );
            $data = \json_decode( $data, true );
        }

        $this->content( 'body', $data );
        $this->display();
    }

    /**-------------------------------------------------------------------------
     *
     * __call me
     *
     * -------------------------------------------------------------------------
     *
     * @param       $p
     * @param array $a
     */
    public function __call( $p, $a = [] )
    {
        pre( $p );
        pre( $a );
        pre( $_POST );
    }
}
