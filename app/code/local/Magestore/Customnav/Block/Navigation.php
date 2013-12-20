<?php
	
class Magestore_Customnav_Block_Navigation extends Mage_Catalog_Block_Navigation
{
	public function drawItem($category, $level=0, $last=false, $first=false)
	{
		$html = '';
        if (!$category->getIsActive()) {
            return $html;
        }
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;				
        $html.= '<li';
        if ($hasChildren && $level == 0) {
             $html.= ' onmouseover="toggleMenu(this,1)" onmouseout="toggleMenu(this,0)"';
        }else{
			 $html.= ' onmouseover="hoverMenu(this,1)" onmouseout="hoverMenu(this,0)"';
		
		}
		
		
		
        $html.= ' class="level'.$level;
        $html.= ' nav-'.str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath()));
        
				if ($this->isCategoryActive($category)) {
            $html.= ' active';
        }
				
				if ($category->getName() == 'Brands') {
					if ($this->_getRequestPath() == 'manufacturer') {
						$html.= ' active';
					}
				}
	
        if ($last) {
            $html .= ' last';
        }
				
				if ($first) {
					$html .= ' first';
				}
        if ($hasChildren) {
            $cnt = 0;
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $cnt++;
                }
            }
            if ($cnt > 0) {
                $html .= ' parent';
            }
        }
        $html.= '">'."\n";
		
        $html.= '<a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a>'."\n";

        if ($hasChildren){

            $j = 0;
            $htmlChildren = '';
			//Begin sort children category
			$indexs=array();
			foreach($children as $index=>$childr){
				$indexs[]=$index;
			}
			for($i=0;$i<count($indexs)-1;$i++){
				for($j=$i+1;$j<count($indexs);$j++){
					if(strcmp(trim($children[$indexs[$i]]->getName()),trim($children[$indexs[$j]]->getName()))>0){
					$temp=$children[$indexs[$i]];
					$children[$indexs[$i]]=$children[$indexs[$j]];
					$children[$indexs[$j]]=$temp;
				}
				}
			}
			//end sort
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $htmlChildren.= $this->drawItem($child, $level+1, ++$j >= $cnt);
                }
            }

            if (!empty($htmlChildren)) {
				if($level ==0){
					$html.= '<ul class="menu-show">'."\n"
                        //.'<li class="bg-top-menu-children"><div style="width:150px;" class="block-top-menu"></div><div class="block-top-menu2"></div></li>'
                        .$htmlChildren
						//.'<li class="bg-bottom-menu-children"><span><span></span></span></li>'
                        .'</ul>';
				}else{
					//$html.= '<ul class="level' . $level . '">'."\n"
					//		.$htmlChildren
					//		.'</ul>';				
				}
                
            }

        }
        $html.= '</li>'."\n";
        return $html;
	}
	
	public function getCountSubCategories() {
		$_subCount = 0;
		$currentCategory = $this->getCurrentCategory();
		$parentCategory = Mage::getModel('catalog/category')->load($currentCategory->getParentId());
		$subCatCollection = $parentCategory->getChildrenCategories();
		$_subCount = is_array($subCatCollection)?count($subCatCollection):$subCatCollection->count();	
		return $_subCount;
	}
	
	protected function _getRequestPath() {
		$request = $this->getRequest();
		return $request->getPathInfo();
	}
}