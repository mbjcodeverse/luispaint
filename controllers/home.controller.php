<?php
class ControllerHome{
	static public function ctrLotAllList(){
		$answer = (new ModelHome)->mdlLotAllList();
		return $answer;
	}

	static public function ctrLotCategoryList($categorycode){
		$answer = (new ModelHome)->mdlLotCategoryList($categorycode);
		return $answer;
	}			

	// Update EXISTING RECORD
	static public function ctrPostLotLocation($data){
		$answer = (new ModelHome)->mdlPostLotLocation($data);
		return $answer;
	}

	static public function ctrGetNearestLot($data){
		$answer = (new ModelHome)->mdlGetNearestLot($data);
		return $answer;
	}
	
	static public function ctrShowDecedentList($saleid){
		$deceased = (new ModelHome)->mdlShowDecedentList($saleid);
		return $deceased;
	}
}