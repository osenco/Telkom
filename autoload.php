<?php

/**
 * Register autoloader for classes under the Osen namespace
 * @param class $class Full namespaced class e.g Osen\Telkom\STK
 */
spl_autoload_register(function ($class)
{
    if (substr($class, 0, 10) == 'Osen\Telkom') {
        $class  = str_replace('Osen\Telkom', '', $class);
        $path   = str_replace('\\', '/', $class);

        require_once("src/{$path}.php");
    }
});

/**
 * Load helper functions for more concise code
 */
require_once('src/helpers.php');
