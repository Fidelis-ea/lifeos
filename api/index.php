<?php

// Temporarily reveal the FULL exception chain for debugging
set_error_handler(function ($severity, $message, $file, $line) {
    error_log("[PHP ERROR] $severity: $message in $file:$line");
});

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
