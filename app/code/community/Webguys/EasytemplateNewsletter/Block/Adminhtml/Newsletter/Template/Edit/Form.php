<?php

/**
 * Class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Form
 *
 */
class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareLayout()
    {
        $previewForm = $this->getLayout()->createBlock('easytemplate_newsletter/adminhtml_newsletter_template_edit_previewform');
        $this->setChild('form_after', $previewForm);

        return parent::_prepareLayout();
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
