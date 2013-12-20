<?php
class Magestore_All_Block_Featuredproduct extends Mage_Catalog_Block_Product
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
	public function getFeaturedProduct(){
		return Mage::getModel('catalog/product')->getCollection()
				->addFieldToFilter('featured_product',1);
	}
	public function getManufacturerName($id){
		$manufacturerId = Mage::getModel('catalog/product')->load($id)->getManufacturer();
		$manufacturers = Mage::getModel("manufacturer/manufacturer")->getCollection()
					->addFieldToFilter('store_id',Mage::app()->getStore()->getId())
					->addFieldToFilter('option_id',$manufacturerId);
		$manuId = '';
		foreach($manufacturers as $manufacturer){
			$manuId = $manufacturer->getId();
		}
		return Mage::getModel('manufacturer/manufacturer')->load($manuId);
	}
}