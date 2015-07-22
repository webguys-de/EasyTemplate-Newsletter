<?php

/**
 * Class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Tab_Main
 *
 */
class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        /* @var $model Mage_Newsletter_Model_Template */
        $model = Mage::registry('_current_template');
        $identity = Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_UNSUBSCRIBE_EMAIL_IDENTITY);
        $identityName = Mage::getStoreConfig('trans_email/ident_'.$identity.'/name');
        $identityEmail = Mage::getStoreConfig('trans_email/ident_'.$identity.'/email');

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('template_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array('legend' => Mage::helper('newsletter')->__('Template Information'))
        );

        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                array(
                    'name' => 'id',
                    'value' => $model->getId(),
                )
            );
        }

        $fieldset->addField(
            'code',
            'text',
            array(
                'name' => 'code',
                'label' => Mage::helper('newsletter')->__('Template Name'),
                'title' => Mage::helper('newsletter')->__('Template Name'),
                'required' => true,
                'value' => $model->getTemplateCode(),
            )
        );

        $fieldset->addField(
            'subject',
            'text',
            array(
                'name' => 'subject',
                'label' => Mage::helper('newsletter')->__('Template Subject'),
                'title' => Mage::helper('newsletter')->__('Template Subject'),
                'required' => true,
                'value' => $model->getTemplateSubject(),
            )
        );

        $fieldset->addField(
            'sender_name',
            'text',
            array(
                'name' => 'sender_name',
                'label' => Mage::helper('newsletter')->__('Sender Name'),
                'title' => Mage::helper('newsletter')->__('Sender Name'),
                'required' => true,
                'value' => $model->getId() !== null
                        ? $model->getTemplateSenderName()
                        : $identityName,
            )
        );

        $fieldset->addField(
            'sender_email',
            'text',
            array(
                'name' => 'sender_email',
                'label' => Mage::helper('newsletter')->__('Sender Email'),
                'title' => Mage::helper('newsletter')->__('Sender Email'),
                'class' => 'validate-email',
                'required' => true,
                'value' => $model->getId() !== null
                        ? $model->getTemplateSenderEmail()
                        : $identityEmail
            )
        );

        /** @var $sourceModel Webguys_Easytemplate_Model_Config_Source_Cms_Page_Viewmode */
        $sourceModel = Mage::getModel('easytemplate/config_source_cms_page_viewmode');

        $fieldset->addField('view_mode', 'select', array(
            'label'     => Mage::helper('easytemplate')->__('Mode'),
            'title'     => Mage::helper('easytemplate')->__('View Mode'),
            'name'      => 'view_mode',
            'required'  => true,
            'options'   => $sourceModel->toArray(),
            'note'      => Mage::helper('easytemplate')->__('Use the template engine or default behavior'),
            'disabled'  => false,
            'value' => $model->getViewMode()
        ));

        Mage::dispatchEvent('easytemplate_newsletter_adminhtml_newsletter_template_edit_tab_main_prepare_form', array('form' => $form));

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('newsletter')->__('Template Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('newsletter')->__('Template Information');
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
        return Mage::getSingleton('admin/session')->isAllowed('newsletter/template/' . $action);
    }
}
