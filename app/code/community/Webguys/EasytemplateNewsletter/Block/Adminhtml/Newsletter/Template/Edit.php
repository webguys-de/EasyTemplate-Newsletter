<?php

/**
 * Class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit
 *
 */
class Webguys_EasytemplateNewsletter_Block_Adminhtml_Newsletter_Template_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize cms block edit block
     *
     * @return void
     */
    public function __construct()
    {
        $this->_objectId   = 'template_id';
        $this->_controller = 'newsletter_template';
        $this->_blockGroup = null; // Workaround to avoid automatic block creation by parent

        parent::__construct();

        $this->_addButton(
            'convert_plain',
            array(
                'label' => Mage::helper('newsletter')->__('Convert to Plain Text'),
                'onclick' => 'templateControl.stripTags();',
                'id' => 'convert_button',
                'class' => 'task'
            )
        );

        $this->_addButton(
            'convert html',
            array(
                'label' => Mage::helper('newsletter')->__('Return HTML Version'),
                'onclick' => 'templateControl.unStripTags();',
                'id' => 'convert_button_back',
                'style' => 'display:none',
                'class' => 'task'
            )
        );

        $this->_addButton(
            'preview',
            array(
                'label' => Mage::helper('newsletter')->__('Preview Template'),
                'onclick' => 'templateControl.preview();',
                'class' => 'task'
            )
        );

        if ($this->_isAllowedAction('delete')) {
            $this->_updateButton('delete', 'label', Mage::helper('newsletter')->__('Delete Template'));
        } else {
            $this->_removeButton('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded block
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('_current_template')->getId()) {
            return Mage::helper('newsletter')->__("Edit Template '%s'", $this->escapeHtml(Mage::registry('_current_template')->getTemplateCode()));
        }
        else {
            return Mage::helper('newsletter')->__('New Template');
        }
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

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'   => true,
            'back'       => 'edit',
            'active_tab' => '{{tab_id}}'
        ));
    }

    /**
     * Prepare layout
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $tabsBlock = $this->getLayout()->getBlock('easytemplate_newsletter_template_edit_tabs');
        if ($tabsBlock) {
            $tabsBlockJsObject = $tabsBlock->getJsObjectName();
            $tabsBlockPrefix   = $tabsBlock->getId() . '_';
        } else {
            $tabsBlockJsObject = 'template_tabsJsTabs';
            $tabsBlockPrefix   = 'template_tabs_';
        }

        $this->_formScripts[] = "
            var templateForm = new varienForm('newsletter_template_edit_form');
            var templatePreviewForm = new varienForm('newsletter_template_preview_form');
            var templateControl = {
                unconvertedText: '',
                typeChange: false,
                templateName: false,
                id: 'template_text',

                init: function () {
                    if ($('convert_button_back')) {
                        $('convert_button_back').hide();
                    }
                },

                stripTags: function () {
                    if(!window.confirm('".Mage::helper('newsletter')->__('Are you sure that you want to strip all tags?')."')) {
                        return false;
                    }
                    if(this.isEditor()) {
                        this.getEditor().turnOff();
                        this.getEditor().getToggleButton().hide();
                    }
                    this.unconvertedText = $(this.id).value;
                    $('convert_button').hide();
                    $('convert_button_back').show();
                    $(this.id).value =  $(this.id).value.stripScripts().stripTags();
                    $('field_template_styles').hide();
                    this.typeChange = true;
                    return false;
                },

                unStripTags: function () {
                    $('convert_button').show();
                    $('convert_button_back').hide();
                    $(this.id).value =  this.unconvertedText;
                    if(this.isEditor()) {
                        this.getEditor().turnOn();
                        this.getEditor().getToggleButton().show();
                    }
                    this.typeChange = false;
                    $('field_template_styles').show();
                    return false;
                },

                save: function() {
                    if (this.typeChange) {
                        $('change_flag_element').value = '1';
                    }
                    if(this.isEditor()) {
                        tinyMCE.triggerSave();
                    }
                    templateForm.submit();
                    return false;
                },

                saveAs: function() {
                    if (this.typeChange) {
                        $('change_flag_element').value = '1';
                    }

                    if(\$F('code').blank() || \$F('code')==templateControl.templateName) {
                       value = prompt('".Mage::helper('newsletter')->__('Please enter new template name')."', templateControl.templateName + '".Mage::helper('newsletter')->__(' Copy')."');
                       if(!value) {
                           if(value !== null) {
                               $('code').value = '';
                               templateForm.submit();
                           }
                           return false;
                       } else {
                           $('code').value = value;
                       }
                    }

                    $('save_as_flag').value = '1';

                    if(this.isEditor()) {
                        tinyMCE.triggerSave();
                    }
                    templateForm.submit();
                    return false;
                },

                preview: function() {
                    if (this.typeChange) {
                        $('preview_type').value = 1;
                    } else {
                        $('preview_type').value = 2;
                    }
                    if (this.isEditor() && tinyMCE.get(this.id)) {
                        tinyMCE.triggerSave();
                        $('preview_text').value = $(this.id).value;
                        tinyMCE.triggerSave();
                    } else {
                        $('preview_text').value = $(this.id).value;
                    }
                    if ($('template_styles') != undefined) {
                        $('preview_styles').value = $('template_styles').value;
                    }
                    if ($('template_id') != undefined) {
                        $('preview_id').value = $('template_id').value;
                    }
                    templatePreviewForm.submit();
                    return false;
                },

                deleteTemplate: function() {
                    if(window.confirm('".Mage::helper('newsletter')->__('Are you sure that you want to delete this template?')."')) {
                        window.location.href = '".$this->getDeleteUrl()."';
                    }
                },

                isEditor: function() {
                    return (typeof tinyMceEditors != 'undefined' && tinyMceEditors.get(this.id) != undefined)
                },

                getEditor: function() {
                    return tinyMceEditors.get(this.id);
                }
            };

            templateControl.init();
            templateControl.templateName = '".$this->getJsTemplateName()."';
        ";

        $this->setChild('form', $this->getLayout()->createBlock('easytemplate_newsletter/adminhtml_newsletter_template_edit_form'));

        return parent::_prepareLayout();
    }
}
