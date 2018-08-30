<?php
/**
* Virtual Employee Ajaxify 
* 
* @category     VE
* @package      Ve_Ajaxify
* @copyright    Copyright (c) 2015 VE (http://www.virtualemployee.com/)
* @author       VE (Magento Team)
* @version      Release: 1.0.0
* @Class        Ve_Ajaxify_Model_Observer   
*/ 
class Ve_Ajaxify_Model_Observer {

    public function addToCartEvent($observer) {
        if (Mage::getStoreConfig('ajaxify/ajaxifycart/ajaxify_cart') == 0) {
            return $observer;
        }
        $request = Mage::app()->getFrontController()->getRequest();
        if (!$request->getParam('in_cart') && !$request->getParam('is_checkout')) {

            Mage::getSingleton('checkout/session')->setNoCartRedirect(true);

            $_response = Mage::getModel('ajaxify/response')
                    ->setProductName($observer->getProduct()->getName())
                    ->setMessage(Mage::helper('checkout')->__('%s was added into cart.', $observer->getProduct()->getName()));

            //append updated blocks
            $_response->addUpdatedBlocks($_response);

            $_response->send();
        }
        if ($request->getParam('is_checkout')) {

            Mage::getSingleton('checkout/session')->setNoCartRedirect(true);

            $_response = Mage::getModel('ajaxify/response')
                    ->setProductName($observer->getProduct()->getName())
                    ->setMessage(Mage::helper('checkout')->__('%s was added into cart.', $observer->getProduct()->getName()));
            $_response->send();
        }
    }

    public function updateItemEvent($observer) {
        if (Mage::getStoreConfig('ajaxify/ajaxifycart/ajaxify_cart') == 0) {
            return $observer;
        }

        $request = Mage::app()->getFrontController()->getRequest();

        if (!$request->getParam('in_cart') && !$request->getParam('is_checkout')) {

            Mage::getSingleton('checkout/session')->setNoCartRedirect(true);

            $_response = Mage::getModel('ajaxify/response')
                    ->setMessage(Mage::helper('checkout')->__('Item was updated.'));

            //append updated blocks
            $_response->addUpdatedBlocks($_response);

            $_response->send();
        }
        if ($request->getParam('is_checkout')) {

            Mage::getSingleton('checkout/session')->setNoCartRedirect(true);

            $_response = Mage::getModel('ajaxify/response')
                    ->setMessage(Mage::helper('checkout')->__('Item was updated.'));
            $_response->send();
        }
    }

    public function getConfigurableOptions($observer) {
        if (Mage::getStoreConfig('ajaxify/ajaxifycart/ajaxify_cart') == 0) {
            return $observer;
        }
        $is_ajax = Mage::app()->getFrontController()->getRequest()->getParam('ajax');

        if ($is_ajax) {
            $_response = Mage::getModel('ajaxify/response');

            $product = Mage::registry('current_product');
            if (!$product->isConfigurable() && !$product->getTypeId() == 'bundle') {
                return false;
                exit;
            }

            //append configurable options block
            $_response->addConfigurableOptionsBlock($_response);
            $_response->send();
        }
        return;
    }

    public function getGroupProductOptions() {
        if (Mage::getStoreConfig('ajaxify/ajaxifycart/ajaxify_cart') == 0) {
            return $observer;
        }
        $id = Mage::app()->getFrontController()->getRequest()->getParam('product');
        $options = Mage::app()->getFrontController()->getRequest()->getParam('super_group');

        if ($id) {
            $product = Mage::getModel('catalog/product')->load($id);
            if ($product->getData()) {
                if ($product->getTypeId() == 'grouped' && !$options) {
                    $_response = Mage::getModel('ajaxify/response');
                    Mage::register('product', $product);
                    Mage::register('current_product', $product);

                    //add group product's items block
                    $_response->addGroupProductItemsBlock($_response);
                    $_response->send();
                }
            }
        }
    }

}
