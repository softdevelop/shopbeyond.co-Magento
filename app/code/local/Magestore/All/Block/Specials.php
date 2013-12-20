<?php
class Magestore_All_Block_Specials extends Mage_Catalog_Block_Product_List {
	public function _getProductCollection() {
		$_productCollection = Mage::getResourceModel('catalogsearch/advanced_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addStoreFilter()
                ->addFieldToFilter('special_price',array('nin'=>array(null)));
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($_productCollection);
		$todayDate = date('m/d/y');
		$tomorrow = mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
		$tomorrowDate = date('m/d/y', $tomorrow);
		$_productCollection->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
						->addAttributeToFilter('special_to_date', array('or'=> array(
							0 => array('date' => true, 'from' => $tomorrowDate),
							1 => array('is' => new Zend_Db_Expr('null')))
							), 'left');
		return $_productCollection;
	}
	public function _prepareLayout()
	{
		$this->getLayout()->getBlock('head')->setTitle('Specials');
		return parent::_prepareLayout();
	}
}