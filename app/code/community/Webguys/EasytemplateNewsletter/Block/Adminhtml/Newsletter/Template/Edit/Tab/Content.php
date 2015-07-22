<?php

/**
 * Class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Tab_Content
 *
 */
class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Tab_Content
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    protected function _prepareForm()
    {
        /** @var $model Mage_Newsletter_Model_Template */
        $model = Mage::registry('_current_template');

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('template_');

        $fieldset = $form->addFieldset(
            'content_fieldset',
            array('legend' => Mage::helper('newsletter')->__('Content'), 'class' => 'fieldset-wide')
        );

        $widgetFilters = array('is_email_compatible' => 1);
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('widget_filters' => $widgetFilters));
        if ($model->isPlain()) {
            $wysiwygConfig->setEnabled(false);
        }
        $fieldset->addField('text', 'editor', array(
            'name'      => 'text',
            'label'     => Mage::helper('newsletter')->__('Template Content'),
            'title'     => Mage::helper('newsletter')->__('Template Content'),
            'required'  => true,
            'state'     => 'html',
            'style'     => 'height:36em;',
            'value'     => $model->getTemplateText(),
            'config'    => $wysiwygConfig
        ));

        if (!$model->isPlain()) {
            $fieldset->addField('styles', 'textarea', array(
                'name'          =>'styles',
                'label'         => Mage::helper('newsletter')->__('Template Styles'),
                'container_id'  => 'field_template_styles',
                'value'         => $model->getTemplateStyles()
            ));
        }

        $this->setForm($form);

        Mage::dispatchEvent('easytemplate_newsletter_adminhtml_newsletter_template_edit_tab_content_prepare_form', array('form' => $form));

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('newsletter')->__('Content');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('newsletter')->__('Content');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/block/' . $action);
    }
}
