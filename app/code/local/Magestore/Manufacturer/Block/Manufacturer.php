<?php
class Magestore_Manufacturer_Block_Manufacturer extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getManufacturer()     
     { 
        if (!$this->hasData('manufacturer')) {
            $this->setData('manufacturer', Mage::registry('manufacturer'));
        }
        return $this->getData('manufacturer');        
    }
	
	public function getFeaturedManufacturer()
	{
		$featureManufacturers = Mage::getModel("manufacturer/manufacturer")->getFeaturedManufacturer();
		//$featureManufacturers->load();
		
		return $featureManufacturers;
	}
	
	public function getManufacturerDetailUrl($manufacturer)
	{
		$url = $this->getUrl($manufacturer->getUrlKey(), array());

		return $url;	
	}
	
	public function getManufacturerImage($manufacturer)
	{	
		if($manufacturer->getImage())
		{
			$url = Mage::helper('manufacturer')->getUrlImagePath($manufacturer->getName()) .'/'. $manufacturer->getImage();
		
			$img = "<img  src='". $url . "' title='". $manufacturer->getName()."' border='0'/>";
		
			return $img;
		} else {
		
			return null;
		}
	}
	
	public function generateListCharacter()
	{
		$lists = array('1','2','3','4','5','6','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','W','U','V','X','Y','Z');		
		
	    echo("<ul id='manufacturer_char_filter'>");
		
		echo("<li><a href='".$this->getCharSearchUrl("All") . "'>" . "All" . "</a></li>");
		
		for($i = 0; $i < count($lists); $i++)
		{
			echo("<li><a href='".$this->getCharSearchUrl($lists[$i]) . "'>" . $lists[$i] . "</a></li>");
		}
		
		echo("</ul>");
		
	}
	
	public function getCharSearchUrl($begin)
	{
		$lists = array('1','2','3','4','5','6','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','W','U','V','X','Y','Z');		
		if(!in_array($begin,$lists))
		{
			return $url = $this->getUrl("manufacturer/index/index", array());
		}
		
		return $this->getUrl("manufacturer/index/index", array("begin"=>$begin));

	}
	
	public function getManufacturers()
	{
		$begin = $this->getRequest()->getParam("begin");
		$manufacturers = Mage::getModel("manufacturer/manufacturer")->getManufacturers($begin);
		//$manufacturers->load();
		
		return $manufacturers;
	}
	
	
}