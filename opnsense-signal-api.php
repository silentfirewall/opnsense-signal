<?php
/**
 * SignalNotifier API Controller
 *
 * Copyright (C) 2025 by Your Name <your.email@example.com>
 * All rights reserved.
 */

namespace OPNsense\SignalNotifier;

use OPNsense\Base\ApiControllerBase;
use OPNsense\Core\Backend;
use OPNsense\Core\Config;

/**
 * Class ApiController
 * @package OPNsense\SignalNotifier
 */
class ApiController extends ApiControllerBase
{
    /**
     * Get plugin settings
     * @return array
     */
    public function getSettingsAction()
    {
        $mdl = new \OPNsense\SignalNotifier\SignalNotifier();
        $result = array();
        
        $result['general'] = $mdl->general->getNodes();
        $result['triggers'] = $mdl->triggers->getNodes();
        $result['urgency'] = $mdl->urgency->getNodes();
        
        return $result;
    }
    
    /**
     * Update plugin settings
     * @return array
     */
    public function setSettingsAction()
    {
        $result = array("result" => "failed");
        
        if ($this->request->isPost()) {
            $mdl = new \OPNsense\SignalNotifier\SignalNotifier();
            
            // Update model with request data
            $mdl->setNodes($this->request->getPost("general"), "general");
            $mdl->setNodes($this->request->getPost("triggers"), "triggers");
            $mdl->setNodes($this->request->getPost("urgency"), "urgency");
            
            // Validate and save
            $validationMessages = $mdl->performValidation();
            
            if (count($validationMessages) == 0) {
                if ($mdl->serializeToConfig()) {
                    Config::getInstance()->save();
                    $result["result"] = "saved";
                }
            } else {
                $result["validationMessages"] = $validationMessages;
            }
        }
        
        return $result;
    }
    
    /**
     * Send test notification
     * @return array
     */
    public function sendTestAction()
    {
        $result = array("result" => "failed");
        
        if ($this->request->isPost()) {
            $message = $this->request->getPost("message", "string", "Test notification from OPNsense");
            $urgent = (bool)$this->request->getPost("urgent", "int", 0);
            
            $plugin = new SignalNotifierPlugin();
            $success = $plugin->sendNotification($message, $urgent);
            
            if ($success) {
                $result["result"] = "sent";
            } else {
                $result["result"] = "failed";
            }
        }
        
        return $result;
    }
}
