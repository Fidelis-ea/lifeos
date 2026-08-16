<?php

// On Vercel, /tmp is the only writable directory.
// Create the compiled views directory if it doesn't exist.
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0777, true);
}

// Forward Vercel requests to the normal Laravel bootstrap
require __DIR__ . '/../public/index.php';
