<?php
class Magestore_All_Block_Featurebrand extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    public function getFeatureBrand(){
		return Mage::getModel('manufacturer/manufacturer')->getFeaturedManufacturer();
	}
	public function getManufacturerImage($manufacturer)
	{
		if($manufacturer->getImage())
		{
			$url = Mage::helper('manufacturer')->getUrlImagePath($manufacturer->getName()) .'/'. $manufacturer->getImage();
			return $url;
		} else{
		
			return null;
		}
	}
}