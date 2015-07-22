<?php

class Webguys_EasytemplateNewsletter_Model_Data_Form extends Varien_Data_Form
{
    public function getHtmlAttributes()
    {
        return array_merge(array('target'), parent::getHtmlAttributes());
    }

}