<?php
/**
 * SignalNotifier Controller
 *
 * Copyright (C) 2025 by Your Name <your.email@example.com>
 * All rights reserved.
 */

namespace OPNsense\SignalNotifier;

use OPNsense\Base\IndexController;
use OPNsense\Core\Config;

/**
 * Class IndexController
 * @package OPNsense\SignalNotifier
 */
class IndexController extends IndexController
{
    /**
     * Default action
     * @return view
     */
    public function indexAction()
    {
        $this->view->pick('OPNsense/SignalNotifier/index');
        $this->view->generalForm = $this->getForm("general");
        $this->view->triggersForm = $this->getForm("triggers");
        $this->view->urgencyForm = $this->getForm("urgency");
    }
}
