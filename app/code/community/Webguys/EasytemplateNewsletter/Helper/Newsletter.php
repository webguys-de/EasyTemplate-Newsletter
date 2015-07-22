<?php

class Webguys_EasytemplateNewsletter_Helper_Newsletter extends Mage_Core_Helper_Abstract
{
    const ENTITY_TYPE_NEWSLETTER = 'newsletter_template';

    public function isEasyTemplateNewsletter($templateId)
    {
        $template = Mage::getModel('newsletter/template')->load($templateId);
        return ($template->getId() && $template->isEasyTemplate());
    }

    /**
     * Returns an existing newsletter entry or creates a new one
     *
     * @param $id TemplateId
     * @return Webguys_Easytemplate_Model_Group
     */
    public function getGroupByNewsletterId($id)
    {
        /** @var $collection Webguys_Easytemplate_Model_Resource_Group_Collection */
        $collection = Mage::getModel('easytemplate/group')->getCollection()
            ->addFieldToFilter('entity_type', self::ENTITY_TYPE_NEWSLETTER)
            ->addFieldToFilter('entity_id', $id)
            ->load();

        if ($collection->getSize() >= 1) {
            return $collection->getFirstItem();
        }
        else {
            // Return new item
            /** @var $newItem Webguys_Easytemplate_Model_Group */
            $newItem = Mage::getModel('easytemplate/group');
            $newItem->setEntityType(self::ENTITY_TYPE_NEWSLETTER);
            $newItem->setEntityId($id);
            $newItem->save();

            return $newItem;
        }
    }
}