<?php

class Webguys_EasytemplateNewsletter_Model_Observer extends Mage_Core_Model_Abstract
{
    public function modelSaveBefore($observer)
    {
        $object = $observer->getObject();

        if ($object instanceof Mage_Newsletter_Model_Template) {

            $request = Mage::app()->getRequest();
            if ($request->isPost()) {
                $object->setViewMode($request->getPost('view_mode'));
            }
        }
    }

    public function modelSaveCommitAfter($observer)
    {
        $object = $observer->getObject();

        if ($object instanceof Mage_Newsletter_Model_Template) {
            /** @var $group Webguys_Easytemplate_Model_Group */
            $group = Mage::helper('easytemplate_newsletter/newsletter')->getGroupByNewsletterId($object->getId());

            /** @var $helper Webguys_Easytemplate_Helper_Data */
            $helper = Mage::helper('easytemplate');
            $helper->saveTemplateInformation($group);
        }
    }

    public function adminhtmlBlockHtmlBefore($observer)
    {
        $block = $observer->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Newsletter_Template_Grid) {

            /** @var $sourceModel Webguys_Easytemplate_Model_Config_Source_Cms_Page_Viewmode */
            $sourceModel = Mage::getModel('easytemplate/config_source_cms_page_viewmode');

            $block->addColumnAfter(
                'view_mode',
                array(
                    'header' => Mage::helper('easytemplate')->__('Mode'),
                    'index' => 'view_mode',
                    'width' => '100px',
                    'header_css_class' => 'view_mode',
                    'sortable' => true,
                    'type' => 'options',
                    'options'  => $sourceModel->toArray(false),
                ),
                'type'
            );

        }
        elseif ($block instanceof Mage_Adminhtml_Block_Newsletter_Queue_Edit_Form)
        {
            /* @var $queue Mage_Newsletter_Model_Queue */
            $queue = Mage::getSingleton('newsletter/queue');

            if ($queue->getTemplate()->getViewMode() == Webguys_Easytemplate_Model_Config_Source_Cms_Page_Viewmode::VIEWMODE_EASYTPL) {
                $form = $block->getForm();
                $fieldset = $form->getElement('base_fieldset');
                $fieldset->removeField('text');
                $fieldset->removeField('styles');
            }
        }
    }
}