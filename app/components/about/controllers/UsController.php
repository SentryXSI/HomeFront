<?php
declare(strict_types=1);

namespace App\Components\About\Controllers;

use App\Kernel\BaseController;

class UsController extends BaseController
{
    public function __construct( $response = [] ){
        parent::__construct( $response );
    }

    public function getIndex(){
        $this->display();
    }

    public function getTerms()
    {
        $this->display();
    }
}