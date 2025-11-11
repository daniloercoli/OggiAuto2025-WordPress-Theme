<?php

namespace EC;

if (!defined('ABSPATH')) {
    exit;
}

class Utils
{
    /**
     * Log a message to the debug log (only if WP_DEBUG is enabled)
     *
     * @param string $message Message to log
     * @param string $level Log level: info, warning, error, debug
     * @param array $context Additional context data
     */
    public static function log(string $message, string $level = 'info', array $context = []): void
    {
        if (!defined('WP_DEBUG') || !WP_DEBUG || !defined('WP_DEBUG_LOG') || !WP_DEBUG_LOG) {
            return;
        }

        $level = strtoupper($level);
        $timestamp = current_time('Y-m-d H:i:s');
        $user_id = get_current_user_id();
        $user_info = $user_id ? "user:{$user_id}" : 'guest';

        $log_message = sprintf(
            '[%s] [EC-%s] [%s] %s',
            $timestamp,
            $level,
            $user_info,
            $message
        );

        if (!empty($context)) {
            $log_message .= ' | Context: ' . json_encode($context);
        }

        error_log($log_message);
    }

    /**
     * Log info message
     *
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function log_info(string $message, array $context = []): void
    {
        self::log($message, 'info', $context);
    }

    /**
     * Log warning message
     *
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function log_warning(string $message, array $context = []): void
    {
        self::log($message, 'warning', $context);
    }

    /**
     * Log error message
     *
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function log_error(string $message, array $context = []): void
    {
        self::log($message, 'error', $context);
    }

    /**
     * Log debug message
     *
     * @param string $message Message to log
     * @param array $context Additional context
     */
    public static function log_debug(string $message, array $context = []): void
    {
        self::log($message, 'debug', $context);
    }


    public static function log_backtrace(bool $withArgs = false, string $label = 'Stack trace'): void
    {

        if (!defined('WP_DEBUG') || !WP_DEBUG || !defined('WP_DEBUG_LOG') || !WP_DEBUG_LOG) {
            return;
        }

        $opts = $withArgs ? 0 : DEBUG_BACKTRACE_IGNORE_ARGS;
        $trace = debug_backtrace($opts);
        $lines = [];
        foreach ($trace as $i => $f) {
            $func = ($f['class'] ?? '') . ($f['type'] ?? '') . ($f['function'] ?? '{main}');
            $file = $f['file'] ?? 'unknown';
            $line = $f['line'] ?? '?';
            $lines[] = sprintf("#%d %s called at [%s:%s]", $i, $func, $file, $line);
        }
        error_log("$label:\n" . implode("\n", $lines));
    }
}
