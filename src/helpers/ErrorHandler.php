<?php
namespace App\Helpers;

use App\Helpers\LogHelper;

class ErrorHandler {
    private static $errorViews = [
        400 => '/views/codepages/codes/400.php',
        401 => '/views/codepages/codes/401.php', 
        403 => '/views/codepages/codes/403.php',
        404 => '/views/codepages/codes/404.php',
        500 => '/views/codepages/codes/500.php'
    ];
    
    private static $errorTitles = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden', 
        404 => 'Not Found',
        500 => 'Internal Server Error'
    ];

    public static function register() {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleFatalError']);
    }

    public static function handleError($level, $message, $file = '', $line = 0) {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function handleException(\Throwable $exception) {
        $code = $exception->getCode();
        if (!array_key_exists($code, self::$errorViews)) {
            $code = 500;
        }
        
        self::logError($exception);
        self::displayError($code, $exception->getMessage());
    }

    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handleException(new \ErrorException(
                $error['message'], 
                0, 
                $error['type'], 
                $error['file'], 
                $error['line']
            ));
        }
    }

    private static function logError(\Throwable $exception) {
        $message = sprintf(
            "Error: %s\nFile: %s\nLine: %d\nTrace:\n%s",
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
        
        LogHelper::log($message, 'ERROR');
    }

    private static function displayError($code, $message = '') {
        http_response_code($code);
        
        $basePath = $_SERVER['DOCUMENT_ROOT'] . '/traceritesa/tracer';
        $errorData = [
            'code' => $code,
            'title' => self::$errorTitles[$code] ?? 'Error',
            'message' => $message ?: self::$errorTitles[$code]
        ];
        
        extract($errorData);
        
        if (isset(self::$errorViews[$code])) {
            require_once $basePath . self::$errorViews[$code];
        } else {
            require_once $basePath . self::$errorViews[500];
        }
        exit();
    }
}