<?php

/**
 * Class Webguys_EasytemplateNewsletter_Block_Preview
 *
 * @method setTemplate
 * @method getTemplate
 */
class Webguys_EasytemplateNewsletter_Block_Preview extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        /** @var $template Webguys_EasytemplateNewsletter_Model_Newsletter_Template */
        if ($template = $this->getTemplate()) {
            if ($html = $template->renderTemplate()) {
                return $html;
            }
        }

        return '';
    }
}