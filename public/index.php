<?php
declare(strict_types=1);

error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

require '../app/kernel/autoloader.php';
require '../app/kernel/functions.php';

(new App\Kernel\Bootstrap());