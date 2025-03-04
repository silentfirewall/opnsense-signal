<?php

/**
 * SignalNotifier service
 *
 * @copyright (C) 2025 by Your Name <your.email@example.com>
 */

namespace OPNsense\SignalNotifier;

use OPNsense\Core\Config;

/**
 * Class ServiceController
 * @package OPNsense\SignalNotifier
 */
class ServiceController extends \OPNsense\Base\ServiceController
{
    /**
     * Register event handlers
     */
    public function registerCallbacks()
    {
        // Register to system events
        $eventDispatcher = \OPNsense\Core\EventDispatcher::getInstance();
        
        // Interface status changes
        $eventDispatcher->registerListener(
            'interface.status.changed', 
            [$this, 'handleInterfaceEvent']
        );
        
        // Service status changes
        $eventDispatcher->registerListener(
            'service.status.changed', 
            [$this, 'handleServiceEvent']
        );
        
        // Firewall alerts
        $eventDispatcher->registerListener(
            'firewall.alert', 
            [$this, 'handleFirewallEvent']
        );
        
        // VPN status changes
        $eventDispatcher->registerListener(
            'vpn.status.changed', 
            [$this, 'handleVpnEvent']
        );
        
        // IDS/IPS alerts
        $eventDispatcher->registerListener(
            'ids.alert', 
            [$this, 'handleIdsEvent']
        );
        
        // System events
        $eventDispatcher->registerListener(
            'system.event', 
            [$this, 'handleSystemEvent']
        );
    }
    
    /**
     * Handle interface events
     * @param array $payload
     */
    public function handleInterfaceEvent($payload)
    {
        $mdl = new \OPNsense\SignalNotifier\SignalNotifier();
        
        if ((bool)$mdl->general->enabled != 1 || (bool)$mdl->triggers->interfaces != 1) {
            return;
        }
        
        $message = "Interface {$payload['interface']} status changed to {$payload['status']}";
        $urgent = (bool)$mdl->urgency->interfacesUrgent;
        
        $plugin = new SignalNotifierPlugin();
        $plugin->sendNotification($message, $urgent);
    }
    
    /**
     * Handle service events
     * @param array $payload
     */
    public function handleServiceEvent($payload)
    {
        $mdl = new \OPNsense\SignalNotifier\SignalNotifier();
        
        if ((bool)$mdl->general->enabled != 1 || (bool)$mdl->triggers->services != 1) {
            return;
        }
        
        $message = "Service {$payload['service']} status changed to {$payload['status']}";
        $urgent = (bool)$mdl->urgency->servicesUrgent;
        
        $plugin = new SignalNotifierPlugin();
        $plugin->sendNotification($message, $urgent);
    }
    
    /**
     * Handle firewall events
     * @param array $payload
     */
    public function handleFirewallEvent($payload)
    {
        $mdl = new \OPNsense\SignalNotifier\SignalNotifier();
        
        if ((bool)$mdl->general->enabled != 1 || (bool)$mdl->triggers->firewall != 1) {
            return;
        }
        
        $message = "Firewall alert: {$payload['message']}";
        $urgent = (bool)$mdl->urgency->firewallUrgent;
        
        $plugin = new SignalNotifierPlugin();
        $plugin->sendNotification($message, $urgent);
    }
    
    /**
     * Handle VPN events
     * @param array $payload
     */
    public function handleVpnEvent($payload)
    {
        $mdl = new \OPNsense\SignalNotifier\SignalNotifier();
        
        if ((bool)$mdl->general->enabled != 1 || (bool)$mdl->triggers->vpn != 1) {
            return;
        }
        
        $message = "VPN {$payload['type']} ({$payload['name']}) status changed to {$payload['status']}";
        $urgent = (bool)$mdl->urgency->vpnUrgent;
        
        $plugin = new SignalNotifierPlugin();
        $plugin->sendNotification($message, $urgent);
    }
    
    /**
     * Handle IDS/IPS events
     * @param array $payload
     */
    public function handleIdsEvent($payload)
    {
        $mdl = new \OPNsense\SignalNotifier\SignalNotifier();
        
        if ((bool)$mdl->general->enabled != 1 || (bool)$mdl->triggers->ids != 1) {
            return;
        }
        
        $message = "IDS/IPS alert: {$payload['message']} (severity: {$payload['severity']})";
        $urgent = (bool)$mdl->urgency->idsUrgent;
        
        $plugin = new SignalNotifierPlugin();
        $plugin->sendNotification($message, $urgent);
    }
    
    /**
     * Handle system events
     * @param array $payload
     */
    public function handleSystemEvent($payload)
    {
        $mdl = new \OPNsense\SignalNotifier\SignalNotifier();
        
        if ((bool)$mdl->general->enabled != 1 || (bool)$mdl->triggers->system != 1) {
            return;
        }
        
        $message = "System event: {$payload['message']}";
        $urgent = (bool)$mdl->urgency->systemUrgent;
        
        $plugin = new SignalNotifierPlugin();
        $plugin->sendNotification($message, $urgent);
    }
}
