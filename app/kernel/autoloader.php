<?php
declare(strict_types=1);

spl_autoload_register( function( $className = '' )
{
    if( ! empty( $className ) && \is_string( $className ) )
    {
        if( \mb_strpos( $className, 'App\\' ) !== false )
        {
            $pos  = \mb_strrpos( $className, '\\' );
            $name = \mb_substr( $className, $pos + 1 );
            $path = \mb_strtolower(
                \str_replace(
                    [ $name, '\\' ],
                    [ ''   , '/'  ],
                    $className
                ));

            $filePath = '../'
                . $path
                . $name
                . '.php';
        }

        if( \stream_is_local( $filePath )
            && \is_readable( $filePath )
        ) {
            require $filePath;
        }
    }
});