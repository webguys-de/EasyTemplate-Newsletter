<?php

require_once 'abstract.php';


class Webguys_EasytemplateNewsletter_Shell extends Mage_Shell_Abstract
{
    /**
     * Run script
     *
     */
    public function run()
    {
        if ($this->getArg('send')) {

            /** @var $observer Mage_Newsletter_Model_Observer */
            $observer = Mage::getSingleton('newsletter/observer');
            $observer->scheduledSend(null);

        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f easytemplate_newsletter.php -- [options]

  --send            Send pending newsletters
  help                          This help

USAGE;
    }
}

$shell = new Webguys_EasytemplateNewsletter_Shell();
$shell->run();
