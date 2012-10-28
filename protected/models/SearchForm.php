<?php 

class SearchForm extends CFormModel {
	public $s;
	
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('s', 'required'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			's'=>'Keywords',
		);
	}
	
	public function search() {
		$terms = explode(' ', $this->s);
		$qs = array();
		$params = array();
		foreach($terms as $k => $term) {
			$term = trim($term);
			$qs[] = "description LIKE :term{$k}";
			$params[":term{$k}"] = "%{$term}%";
		}
		$qs = implode(' OR ', $qs);
		
		$criteria = new CDbCriteria;
		$criteria->condition = $qs;
		$criteria->params = $params;
		$results = Photo::model()->findAll($criteria);
		
		return $results;
	}
}