<?php

/**
 * Class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Tab_Templates
 *
 */
class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Tab_Templates
    extends Webguys_Easytemplate_Block_Adminhtml_Edit_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    /**
     * @return Webguys_Easytemplate_Model_Group
     */
    public function getGroup()
    {
        if ($newsletterTemplate = $this->getObjectOfType()) {
            /** @var $helper Webguys_EasytemplateNewsletter_Helper_Newsletter */
            $helper = Mage::helper('easytemplate_newsletter/newsletter');
            return $helper->getGroupByNewsletterId($newsletterTemplate->getId());
        }
        return Mage::getModel('easytemplate/group');
    }

    public function getType()
    {
        return 'newsletter';
    }

    public function getObjectOfType()
    {
        return Mage::registry('_current_template');
    }

}