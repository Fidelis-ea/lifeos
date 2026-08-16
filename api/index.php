<?php

// On Vercel, /tmp is the only writable directory.
// Create the compiled views directory if it doesn't exist.
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0777, true);
}

// Forward Vercel requests to the normal Laravel bootstrap
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // Walk the full exception chain to find root cause
    $chain = [];
    $current = $e;
    while ($current !== null) {
        $chain[] = [
            'class'   => get_class($current),
            'message' => $current->getMessage(),
            'file'    => str_replace('/var/task/user/', '', $current->getFile()),
            'line'    => $current->getLine(),
        ];
        $current = $current->getPrevious();
    }
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['debug_exception_chain' => $chain], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
