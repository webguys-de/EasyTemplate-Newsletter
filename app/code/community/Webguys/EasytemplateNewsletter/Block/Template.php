<?php

class Webguys_EasytemplateNewsletter_Block_Template extends Webguys_Easytemplate_Block_Template
{
    /**
     * @return Webguys_EasytemplateNewsletter_Model_Newsletter_Template
     */
    public function getNewsletterTemplate()
    {
        /** @var $group Webguys_Easytemplate_Model_Group */
        $group = $this->getGroup();
        return Mage::getModel('newsletter/template')->load($group->getEntityId());
    }

    public function getNewsletterFrontendUrl()
    {
        $template = $this->getNewsletterTemplate();
        return Mage::getUrl('newsletter/preview/show', array('id' => $template->getId(), 'access_token' => $template->getAccessToken()));
    }

    public function getStoreName()
    {
        return Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
    }

    public function getContactAddress()
    {
        return join(', ', explode(PHP_EOL, Mage::getStoreConfig('general/store_information/address')));
    }
}