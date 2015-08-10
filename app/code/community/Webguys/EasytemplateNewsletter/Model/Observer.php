<?php

class Webguys_EasytemplateNewsletter_Model_Observer extends Mage_Core_Model_Abstract
{
    public function newsletterTemplateSaveBefore($observer)
    {
        $object = $observer->getDataObject();

        $request = Mage::app()->getRequest();
        if ($request->isPost()) {
            $object->setViewMode($request->getPost('view_mode'));
        }
    }

    public function newsletterTemplateSaveCommitAfter($observer)
    {
        $object = $observer->getDataObject();

        /** @var $group Webguys_Easytemplate_Model_Group */
        $group = Mage::helper('easytemplate_newsletter/newsletter')->getGroupByNewsletterId($object->getId());

        /** @var $helper Webguys_Easytemplate_Helper_Data */
        $helper = Mage::helper('easytemplate');
        $helper->saveTemplateInformation($group);
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
        elseif ($block instanceof Mage_Adminhtml_Block_Newsletter_Queue_Edit) {

            /* @var $queue Mage_Newsletter_Model_Queue */
            $queue = Mage::getSingleton('newsletter/queue');

            if ($queue->getTemplate()->isEasyTemplate()) {
                $block->unsetChild('preview_button');
            }
        }
        elseif ($block instanceof Mage_Adminhtml_Block_Newsletter_Queue_Edit_Form)
        {
            /* @var $queue Mage_Newsletter_Model_Queue */
            $queue = Mage::getSingleton('newsletter/queue');

            if ($queue->getTemplate()->isEasyTemplate()) {
                $form = $block->getForm();
                $fieldset = $form->getElement('base_fieldset');
                $fieldset->removeField('text');
                $fieldset->removeField('styles');
            }
        }
    }

    public function coreBlockAbstractToHtmlAfter($observer)
    {
        /** @var $block Mage_Core_Block_Abstract */
        $block = $observer->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Newsletter_Queue_Preview) {

            if ($id = (int)Mage::app()->getRequest()->getParam('id')) {
                /** @var $queue Webguys_EasytemplateNewsletter_Model_Newsletter_Queue */
                $queue = Mage::getModel('newsletter/queue');
                $queue->load($id);

                $storeId = (int)Mage::app()->getRequest()->getParam('store_id', false);

                if ($html = $queue->getTemplate()->renderTemplate($storeId)) {
                    $transport = $observer->getTransport();
                    $transport->setHtml($html);
                }
            }

        }
        elseif ($block instanceof Mage_Adminhtml_Block_Newsletter_Template_Preview) {

            if ($id = (int)Mage::app()->getRequest()->getParam('id')) {
                /* @var $template Mage_Newsletter_Model_Template */
                $template = Mage::getModel('newsletter/template');
                $template->load($id);

                $storeId = (int)Mage::app()->getRequest()->getParam('store_id', false);

                if ($html = $template->renderTemplate($storeId)) {
                    $transport = $observer->getTransport();
                    $transport->setHtml($html);
                }
            }

        }
    }
}