<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file is used for shared hosting environments where the domain
 * points to the root directory instead of the public folder.
 *
 * @package  Laravel
 */

// Redirect all requests to the public folder
require __DIR__.'/public/index.php';
