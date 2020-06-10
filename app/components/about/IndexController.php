<?php
declare(strict_types=1);

namespace App\Components\About;

use App\Kernel\BaseController;

/**-----------------------------------------------------------------------------
 *
 * Class IndexController
 *
 * -----------------------------------------------------------------------------
 *
 * About component index page
 *
 * @package App\Components\Home
 */
final class IndexController extends BaseController
{
    /**
     * IndexController constructor.
     *
     * @param $response
     */
    public function __construct( $response ){
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