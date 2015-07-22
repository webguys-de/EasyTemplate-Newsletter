<?php

class Webguys_EasytemplateNewsletter_Newsletter_PreviewController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->_forward('show');
    }

    public function showAction()
    {
        /** @var $template Mage_Newsletter_Model_Template */
        $template = Mage::getModel('newsletter/template');

        $id = $this->getRequest()->getParam('id', false);
        if ($id) {
            $template->load($id);
        }

        if ($template->getId() && $template->isEasyTemplate()) {

            $this->loadLayout();

            /** @var $previewBlock Webguys_EasytemplateNewsletter_Block_Preview */
            $previewBlock = $this->getLayout()->getBlock('root');
            $previewBlock->setTemplate($template);

            $this->renderLayout();
        }
        else {
            $this->_forward('noRoute');
        }
    }
}