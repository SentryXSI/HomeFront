<?php
declare(strict_types=1);

namespace App\Components\Home;

use App\Kernel\BaseController;

/**-----------------------------------------------------------------------------
 *
 * Class IndexController
 *
 * -----------------------------------------------------------------------------
 *
 * @package App\Components\Home
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
}