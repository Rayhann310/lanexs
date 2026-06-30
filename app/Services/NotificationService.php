<?php

namespace App\Services;

/**
 * Class NotificationService
 * Framework-agnostic service for sending notifications via WhatsApp or Email.
 * Can be easily integrated with providers like Fonnte, Wablas, or SMTP.
 */
class NotificationService
{
    /**
     * Send a notification when a new package is created
     */
    public static function sendResiCreated(array $package)
    {
        $phone = $package['sender_phone'];
        $resi = $package['resi'];
        $receiver = $package['receiver_name'];
        $url = BASE_URL . "/tracking?resi=" . urlencode($resi);

        $message = "Halo {$package['sender_name']},\n\n";
        $message .= "Paket Anda dengan No. Resi *{$resi}* (Tujuan: {$receiver}) telah berhasil dibuat di sistem LANEX.\n\n";
        $message .= "Lacak paket Anda secara realtime di:\n{$url}\n\n";
        $message .= "Terima kasih telah menggunakan LANEX Express.";

        // For now we log to file instead of actual API call to save costs/avoid setup
        self::logNotification('whatsapp', $phone, $message);
    }

    /**
     * Send a notification when package status is updated to DELIVERED/SELESAI
     */
    public static function sendStatusUpdated(array $package, string $status)
    {
        if ($status !== 'SELESAI') {
            return; // Only notify on completion for now to avoid spam
        }

        $phone = $package['sender_phone'];
        $resi = $package['resi'];
        $receiver = $package['receiver_name'];

        $message = "Halo {$package['sender_name']},\n\n";
        $message .= "Kabar baik! Paket Anda dengan No. Resi *{$resi}* telah BERHASIL DIKIRIM (Diterima oleh: {$receiver}).\n\n";
        $message .= "Terima kasih telah menggunakan LANEX Express.";

        self::logNotification('whatsapp', $phone, $message);
    }

    /**
     * Internal method to process the sending (cURL to provider)
     * Using a log file as a stub for this boilerplate.
     */
    private static function logNotification(string $channel, string $target, string $message)
    {
        $logFile = BASE_PATH . '/storage/logs/notifications.log';
        
        // Create directory if not exists
        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $date = date('Y-m-d H:i:s');
        $logEntry = "[$date] [CHANNEL: $channel] [TO: $target]\n$message\n----------------------------------------\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}
