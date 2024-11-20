<?php
namespace App\Helpers;

/**
 * Class QuestionLogger
 * Mengelola log khusus untuk modul pertanyaan/kuesioner
 */
class QuestionLogger extends LogHelper {
    /**
     * Log aktivitas pengisian kuesioner
     */
    public static function logQuestionActivity($userId, $action, array $context = []) {
        $baseContext = [
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $context = array_merge($baseContext, $context);
        parent::log($action, 'INFO', $context);
    }

    /**
     * Log error yang terjadi saat pengisian kuesioner
     */
    public static function logQuestionError($userId, $error, array $context = []) {
        $baseContext = [
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $context = array_merge($baseContext, $context);
        parent::log($error, 'ERROR', $context);
    }
} 