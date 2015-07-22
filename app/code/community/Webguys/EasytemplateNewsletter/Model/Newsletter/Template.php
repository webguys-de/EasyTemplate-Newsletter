<?php

class Webguys_EasytemplateNewsletter_Model_Newsletter_Template extends Mage_Newsletter_Model_Template
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'newsletter_template';

    public function isEasyTemplate()
    {
        return ($this->getViewMode() == Webguys_Easytemplate_Model_Config_Source_Cms_Page_Viewmode::VIEWMODE_EASYTPL);
    }

    public function renderTemplate($storeId = false)
    {
        if ($this->isEasyTemplate()) {

            /** @var $helper Webguys_EasytemplateNewsletter_Helper_Newsletter */
            $helper = Mage::helper('easytemplate_newsletter/newsletter');
            $group = $helper->getGroupByNewsletterId($this->getId());

            // Replace original category content
            /** @var $renderer Webguys_Easytemplate_Block_Renderer */
            $renderer = Mage::app()->getLayout()->createBlock('easytemplate/renderer', 'easytemplate_newsletter');
            $renderer->setGroup($group);

            if($storeId == false) {
                $storeId = Mage::app()->getAnyStoreView()->getId();
            }

            $this->emulateDesign($storeId);
            $html = $renderer->toHtml();
            $this->revertDesign();

            return $html;
        }

        return false;
    }

    public function getAccessToken()
    {
        return sha1(join(PHP_EOL, $this->getData()));
    }

    public function validAccessToken($token)
    {
        return ($this->getAccessToken() === $token);
    }
}