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
}