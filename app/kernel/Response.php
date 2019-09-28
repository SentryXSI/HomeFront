<?php
declare(strict_types=1);

namespace App\Kernel;

/**-----------------------------------------------------------------------------
 *
 * Class Response
 *
 * -----------------------------------------------------------------------------
 *
 * new Response( )
 *
 * @package App\Kernel
 */
class Response
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * Response constructor.
     *
     * @param array $config
     */
    public function __construct( $config = [] )
    {
        $this->config = $config;
    }
}