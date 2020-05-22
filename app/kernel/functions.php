<?php
declare(strict_types=1);

/**-----------------------------------------------------------------------------
 *
 * Pre Code
 *
 * -----------------------------------------------------------------------------
 *
 * @param      $vars
 * @param bool $dump
 */
function pre( $vars, bool $dump = false ) : void
{
    ob_start();

    if( $dump ){
        var_dump( $vars );
    } else {
        print_r( $vars );
    }

    $contents = ob_get_clean();

    echo '<pre>';
    echo escaped( $contents );
    echo '</pre>';
}

/**-----------------------------------------------------------------------------
 *
 * Escaped String
 *
 * -----------------------------------------------------------------------------
 *
 * @param string $data
 *
 * @return string
 */
function escaped( string $data ) : string {
    return htmlspecialchars( $data, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
}
