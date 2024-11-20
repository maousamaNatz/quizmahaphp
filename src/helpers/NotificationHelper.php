<?php

namespace App\Helpers;

class NotificationHelper {
    public static function show($message, $type = 'info') {
        $_SESSION['notification'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public static function clear() {
        unset($_SESSION['notification']);
    }

    public static function get() {
        $notification = $_SESSION['notification'] ?? null;
        self::clear();
        return $notification;
    }
} 