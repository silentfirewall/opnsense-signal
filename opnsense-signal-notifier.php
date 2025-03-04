<?php
/**
 * SignalNotifier - OPNsense plugin for sending notifications to Signal groups
 *
 * Copyright (C) 2025 by Your Name <your.email@example.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace OPNsense\SignalNotifier;

/**
 * Class SignalNotifierPlugin
 * @package OPNsense\SignalNotifier
 */
class SignalNotifierPlugin extends \OPNsense\Base\BaseModel
{
    /**
     * Send notification to Signal group
     * @param string $message Message to send
     * @param bool $urgent Whether this is an urgent notification
     * @return bool success status
     */
    public function sendNotification($message, $urgent = false)
    {
        $config = \OPNsense\Core\Config::getInstance()->object();
        
        // Get configuration options
        $signalCliPath = (string)$config->OPNsense->SignalNotifier->general->signalCliPath;
        $phoneNumber = (string)$config->OPNsense\SignalNotifier->general->phoneNumber;
        $groupId = (string)$config->OPNsense->SignalNotifier->general->groupId;
        
        if (empty($signalCliPath) || empty($phoneNumber) || empty($groupId)) {
            \OPNsense\Core\Config::getInstance()->getLogger("signalnotifier")->error("Missing configuration options for Signal notification");
            return false;
        }
        
        // Add prefix for urgent messages
        if ($urgent) {
            $message = "[URGENT] " . $message;
        }
        
        // Escape message for shell
        $escapedMessage = escapeshellarg($message);
        $escapedGroupId = escapeshellarg($groupId);
        
        // Execute signal-cli command
        $command = "{$signalCliPath} -u {$phoneNumber} send -g {$escapedGroupId} -m {$escapedMessage}";
        
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            \OPNsense\Core\Config::getInstance()->getLogger("signalnotifier")->error("Failed to send Signal notification: " . implode("\n", $output));
            return false;
        }
        
        return true;
    }
}
