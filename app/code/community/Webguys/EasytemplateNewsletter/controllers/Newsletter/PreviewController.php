<?php

class Webguys_EasytemplateNewsletter_Newsletter_PreviewController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->_forward('show');
    }

    public function showAction()
    {
        /** @var $template Webguys_EasytemplateNewsletter_Model_Newsletter_Template */
        $template = Mage::getModel('newsletter/template');

        $id = $this->getRequest()->getParam('id', false);
        $accessToken = $this->getRequest()->getParam('access_token', false);
        if ($id && $accessToken) {
            $template->load($id);
        }

        if ($template->getId() && $template->isEasyTemplate() && $template->validAccessToken($accessToken)) {
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