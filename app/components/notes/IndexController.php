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
 *
 * @package App\Components\Notes
 */
class IndexController extends BaseController
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
     * @return mixed|void
     * @throws \Exception
     */
    public function getIndex(){
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