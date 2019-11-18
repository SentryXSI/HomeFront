<?php
declare(strict_types=1);

function pre( $vars, bool $dump = false ): void
{
    ob_start();

    if( $dump ){
        var_dump( $vars );
    } else {
        print_r( $vars );
    }

    $contents = ob_get_clean();

    echo '<pre>';
    echo \htmlspecialchars( $contents, \ENT_QUOTES, 'UTF-8' );
    echo '</pre>';
}

function escaped( string $data ): string {
    return \htmlspecialchars( $data, \ENT_QUOTES, 'UTF-8' );
}