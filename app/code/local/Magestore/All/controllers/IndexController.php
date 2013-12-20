<?php
class Magestore_All_IndexController extends Mage_Core_Controller_Front_Action
{
	public function specialsAction(){
		$this->loadLayout();     
		$this->renderLayout();
	}
}