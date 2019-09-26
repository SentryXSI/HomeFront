<?php
declare(strict_types=1);

namespace App\Components\About\Controllers;

use App\Kernel\BaseController;

/**-----------------------------------------------------------------------------
 *
 * Class UsController
 *
 * -----------------------------------------------------------------------------
 *
 * @package App\Components\About\Controllers
 */
class UsController extends BaseController
{
    /**
     * UsController constructor.
     *
     * @param array $response
     */
    public function __construct( $response = [] ){
        parent::__construct( $response );
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function getIndex(){
        $this->display();
    }

    /**
     * @throws \Exception
     */
    public function getTerms(){
        $this->display();
    }
}
