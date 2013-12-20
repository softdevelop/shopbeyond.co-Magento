<?php

class Magestore_Faq_Helper_Data extends Mage_Core_Helper_Abstract
{
	public static function getCategoryOptions1()
	{
		$options = array();
		$collection = Mage::getModel('faq/category')->getCollection();	
		foreach($collection as $category)
		{
			$options[$category->getCategoryId()] = $category->getName();
		}
		return $options;
	}	
	
	public static function getCategoryOptions2($store_id = null)
	{
		$options = array();
		$collection = Mage::getModel('faq/category')
								->setStoreId($store_id)
								->getCollection();	
								
		foreach($collection as $category)
		{
			$option = array();
			$option['label'] = $category->getName();
			$option['value'] = $category->getCategoryId();
			$options[] = $option;
		}
		
		return $options;
	}

	public function getOptionApplied()
	{
		return array(				
				array('value'=>1,'label'=>$this->__('Yes')),
				array('value'=>0,'label'=>$this->__('No')),
				
			);
	}
	
	public function getTablePrefix()
	{
		$tableName = Mage::getResourceModel('faq/faq')->getTable('faq');

		
		$prefix = str_replace('_faq','_',$tableName);
		
		return $prefix;
	}
	
	public function normalizeUrlKey($urlKey)
	{
		for($i=0;$i<5;$i++)
		{
			$urlKey = str_replace("  "," ",$urlKey);
		}
		$urlKey = trim($urlKey);
		$urlKey = str_replace(" ","-",$urlKey);
		$urlKey = str_replace(",","",$urlKey);
		$urlKey = str_replace("?","",$urlKey);
		$urlKey = str_replace(":","-",$urlKey);
		$urlKey = str_replace("!","-",$urlKey);
		
		$urlKey = str_replace("'","-",$urlKey);
		$urlKey = strtolower($urlKey);
		
		return $urlKey;		
	}
	
	public function getFaqUrl()
	{
		$url = $this->_getUrl("faq", array());

		return $url;			
	}
	
	public function getStoreId()
	{		
		$store_id = Mage::app()->getStore()->getId();		
		
		return $store_id;
	}
}