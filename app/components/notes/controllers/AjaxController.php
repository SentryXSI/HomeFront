<?php
declare(strict_types=1);

namespace App\Components\Notes\Controllers;

use App\Kernel\BaseController;

/**-----------------------------------------------------------------------------
 *
 * Class IndexController
 *
 * -----------------------------------------------------------------------------
 *
 * Class IndexController
 *
 * @package App\Components\Notes\Controllers
 */
class AjaxController extends BaseController
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
        $this->setup();
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
     * Post Add
     *
     * -------------------------------------------------------------------------
     *
     * Add note
     *
     */
    public function postAdd()
    {
        if( empty( $_POST ) ) {
            echo 'Notes/Add Error :: POST not found';
        }

        if( empty( $_POST['token'] ) ) {
            echo 'Notes/Add Error :: Form token not found';
        }

        if( empty( $_POST['title'] ) ) {
            echo 'Notes/Add Error :: title not found';
        }

        if( empty( $_POST['content'] ) ) {
            echo 'Notes/Add Error :: content not found';
        }

        // temp debug

        pre( $_POST );

        // prepare data
        // todo :: input filter

        $data = [
            'title'   => $_POST['title'],
            'content' => $_POST['content'],
            'created' => \date('Y-m-d H:i:s'),
        ];

        $data = \json_encode( $data );
        $path = $this->getPath() . 'notes-test.txt';

        //
        // save to file
        //

        $spl = new \SplFileObject( $path, 'w+b' );

        if( null === ( $spl->fwrite( $data ) ) )
        {
            echo 'Notes :: Error - Unable to save file';
            pre( $path );

        } else {

            echo 'Notes :: Success - note created';
        }

        $spl = null;
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
        return $this->response['basePath']
            . 'app/content/notes/';
    }

    /**-------------------------------------------------------------------------
     *
     * Setup
     *
     * -------------------------------------------------------------------------
     *
     * Background operations
     *
     * SELinux Policy :
     * sudo semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/html/homefront/app/content(/.*)?'
     * sudo restorecon -rv '/var/www/html/homefront/app/content/'
     *
     */
    private function setup()
    {
        $path = $this->getPath();

        if( ! \is_dir( $path ) )
        {
            if( ! @\mkdir( $path ) )
            {
                echo 'Notes/AjaxController :: Setup() - '
                    . 'Unable to create directory';
                pre( $path );
                exit;
            }
        }
    }
}