<?php

class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Previewform
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Return preview action url for form
     *
     * @return string
     */
    public function getPreviewUrl()
    {
        return $this->getUrl('*/*/preview');
    }

    protected function _prepareForm()
    {
        /* @var $model Mage_Newsletter_Model_Template */
        $model = Mage::registry('_current_template');

        $form = new Webguys_EasytemplateNewsletter_Model_Data_Form(array('id' => 'newsletter_template_preview_form', 'action' => $this->getPreviewUrl(), 'method' => 'post', 'target' => '_blank'));

        $form->addField('preview_type', 'hidden', array(
            'name' => 'type',
            'value' => $model->isPlain() ? 1 : 2
        ));

        $form->addField('preview_text', 'hidden', array(
            'name' => 'text',
        ));

        $form->addField('preview_styles', 'hidden', array(
            'name' => 'styles',
        ));

        $form->addField('preview_id', 'hidden', array(
            'name' => 'id',
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}