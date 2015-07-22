<?php

/**
 * Class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Tabs
 *
 */
class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('newsletter_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('easytemplate_newsletter')->__('Newsletter Information'));
    }
}
