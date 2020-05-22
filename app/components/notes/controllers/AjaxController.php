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
       // $this->display();
    }

    /**-------------------------------------------------------------------------
     *
     * Post Add
     *
     * -------------------------------------------------------------------------
     *
     * Add note
     *
     * @throws \Exception
     */
    public function postAdd()
    {
        try{
            $this->validatePost();
        }
        catch( \Throwable $e )
        {
            pre( $e->getMessage() );

            exit('AjaxController :: postAdd() - Post validation error');
        }

        try{

            $path = $this->getPath() . 'notes-test.txt';
            $data = $this->prepare();

            $this->save( $path, $data );
        }
        catch( \Throwable $e )
        {
            pre( $e->getMessage() );
        }

        // temp debug
        // TODO :: add debug mode toggle switch
        pre( $_POST );
    }

    /**-------------------------------------------------------------------------
     *
     * Prepare
     *
     * -------------------------------------------------------------------------
     *
     * @return array|false|string
     */
    private function prepare()
    {
        try{

            $data = [
                'id'      => 1,
                'title'   => $_POST['title'],
                'content' => $_POST['content'],
                'created' => \date('Y-m-d H:i:s'),
                'updated' => \date('Y-m-d H:i:s'),
            ];

            $data = \json_encode(
                $data, JSON_THROW_ON_ERROR
            );

            return $data;
        }
        catch( \Throwable $e ){

            pre( $e->getMessage() );

            exit;
        }
    }

    /**-------------------------------------------------------------------------
     *
     * Save
     *
     * -------------------------------------------------------------------------
     *
     * @param $path
     * @param $data
     *
     * @throws \Exception
     */
    private function save( $path, $data )
    {
        if( empty( $data ) )
        {
            throw new \Exception(
                'Notes Error :: save() - data not found'
            );
        }

        if( empty( $path ) )
        {
            throw new \Exception(
                'Notes Error :: save() - path not found'
            );
        }

        $spl = new \SplFileObject( $path, 'w+b' );

        if( null === ( $spl->fwrite( $data ) ) )
        {
            echo '<p class="alert alert-success">Notes Error :: save() - Unable to save file</p>';

        } else {

            echo '<p class="alert alert-success">Notes :: Success - note created</p>';
        }

        $spl = null;
    }

    /**-------------------------------------------------------------------------
     *
     * Validate Post
     *
     * -------------------------------------------------------------------------
     *
     * TODO :: add input filter
     *
     * @throws \Exception
     */
    private function validatePost()
    {
        if( empty( $_POST ) ) {

            throw new \Exception(
                'Notes Error :: POST not found'
            );
        }

        if( empty( $_POST['token'] ) )
        {
            throw new \Exception(
                'Notes Error :: Form token not found'
            );
        }

        if( empty( $_POST['title'] ) )
        {
            throw new \Exception(
                'Notes Error :: title not found'
            );
        }

        if( empty( $_POST['content'] ) )
        {
            throw new \Exception(
                'Notes Error :: content not found'
            );
        }

        // $data['title'] = $input
        //       ->post('title')
        //       ->strLen('65')
        //       ->notEmpty()
        //       ->acceptOnly('text')
        //       ->trim()
        //       ->validate();

        // return $data;
    }

    /**-------------------------------------------------------------------------
     *
     * Get Path
     *
     * -------------------------------------------------------------------------
     *
     * Prepare absolute path to notes directory
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
                echo 'Notes/AjaxController Error :: Setup() - '
                    . 'Unable to create directory';
                pre( $path );
                exit;
            }
        }
    }
}