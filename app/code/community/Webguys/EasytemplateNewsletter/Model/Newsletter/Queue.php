<?php

class Webguys_EasytemplateNewsletter_Model_Newsletter_Queue extends Mage_Newsletter_Model_Queue
{
    protected function isEasyTemplate()
    {
        return ($this->getTemplate()->getViewMode() == Webguys_Easytemplate_Model_Config_Source_Cms_Page_Viewmode::VIEWMODE_EASYTPL);
    }

    public function getNewsletterTextByStore($storeId)
    {
        if ($this->isEasyTemplate()) {

            /** @var $helper Webguys_EasytemplateNewsletter_Helper_Newsletter */
            $helper = Mage::helper('easytemplate_newsletter/newsletter');
            $group = $helper->getGroupByNewsletterId($this->getTemplate()->getId());

            // Replace original category content
            /** @var $renderer Webguys_Easytemplate_Block_Renderer */
            $renderer = Mage::app()->getLayout()->createBlock('easytemplate/renderer', 'easytemplate_newsletter');
            $renderer->setGroup($group);

            $this->getTemplate()->emulateDesign($storeId);
            $newsletterText = $renderer->toHtml();
            $this->getTemplate()->revertDesign();

            return $newsletterText;
        }
        else {
            return $this->getData('newsletter_text');
        }
    }

    /**
     * Send messages to subscribers for this queue
     *
     * @param   int     $count
     * @param   array   $additionalVariables
     * @return Mage_Newsletter_Model_Queue
     */
    public function sendPerSubscriber($count=20, array $additionalVariables=array())
    {
        if ($this->isEasyTemplate()) {
            if($this->getQueueStatus()!=self::STATUS_SENDING
                && ($this->getQueueStatus()!=self::STATUS_NEVER && $this->getQueueStartAt())
            ) {
                return $this;
            }

            if ($this->getSubscribersCollection()->getSize() == 0) {
                $this->_finishQueue();
                return $this;
            }

            $collection = $this->getSubscribersCollection()
                ->useOnlyUnsent()
                ->showCustomerInfo()
                ->setPageSize($count)
                ->setCurPage(1)
                ->load();

            $senders = array();

            /** @var $store Mage_Core_Model_Store */
            foreach (Mage::app()->getStores(false) as $store) {
                if (!$store->isAdmin()) {
                    // Load

                    /* @var $sender Mage_Core_Model_Email_Template */
                    $sender = Mage::getModel('core/email_template');
                    $sender->setSenderName($this->getNewsletterSenderName())
                        ->setSenderEmail($this->getNewsletterSenderEmail())
                        ->setTemplateType(self::TYPE_HTML)
                        ->setTemplateSubject($this->getNewsletterSubject())
                        ->setTemplateText($this->getNewsletterTextByStore($store->getId()))
                        ->setTemplateStyles($this->getNewsletterStyles())
                        ->setTemplateFilter(Mage::helper('newsletter')->getTemplateProcessor());

                    $senders[$store->getId()] = $sender;
                }
            }

            foreach($collection->getItems() as $item) {
                $email = $item->getSubscriberEmail();
                $name = $item->getSubscriberFullName();

                if (array_key_exists($item->getStoreId(), $senders)) {
                    /** @var $storeSender Mage_Core_Model_Email_Template */
                    $storeSender = $senders[$item->getStoreId()];

                    $storeSender->emulateDesign($item->getStoreId());
                    $successSend = $storeSender->send($email, $name, array('subscriber' => $item));
                    $storeSender->revertDesign();
                }
                else {
                    $successSend = false;
                }

                if($successSend) {
                    $item->received($this);
                } else {
                    $problem = Mage::getModel('newsletter/problem');
                    $notification = Mage::helper('newsletter')->__('Please refer to exeption.log');
                    $problem->addSubscriberData($item)
                        ->addQueueData($this)
                        ->addErrorData(new Exception($notification))
                        ->save();
                    $item->received($this);
                }
            }

            if(count($collection->getItems()) < $count-1 || count($collection->getItems()) == 0) {
                $this->_finishQueue();
            }
        }
        else {
            parent::sendPerSubscriber($count, $additionalVariables);
        }
        return $this;
    }
}