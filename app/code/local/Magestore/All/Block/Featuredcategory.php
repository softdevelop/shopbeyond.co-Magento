<?php
class Magestore_All_Block_Featuredcategory extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
	public function getFeaturedCategories()
	{
		$list = array();
		$collection = Mage::getResourceModel('catalog/category_collection')
						->addAttributeToSelect('name')
						->addAttributeToSelect('image');
		if(count($collection)){
			foreach($collection as $cat)
			{
				$featureddata = $this->getFeatured($cat);
				if($featureddata == 1){
					$list[] = $cat;
				}
			}
		}
		return $list;
	}
	
	public function getFeatured($cat)
	{
		if(!$cat)
			return ;
			
		if (!$cat->getId()) 
		{
			return "";
		}
		
		
		$entity_type = Mage::getSingleton("eav/entity_type")->loadByCode("catalog_category");
		
		$entity_type_id = $entity_type->getId();
		
		$attribute = Mage::getModel("eav/entity_attribute")->load("featured_category","attribute_code");
		
		$attribute_id = $attribute->getId();
		
		$pretable =  $this->getTablePrefix();
		
		$resource = Mage::getSingleton('core/resource');			
		
		$read = $resource->getConnection('core_read');
	
		$select = $read->select()
					   ->from( $pretable ."catalog_category_entity_int",array('value'))
					   ->where("entity_type_id=?",$entity_type_id)
					   ->where("attribute_id=?",$attribute_id)
					   ->where("entity_id=?",$cat->getId());
		
		$text = $read->fetchOne($select);		
		
		return 	$text;		
	}
}