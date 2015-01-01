<?php
class DbModules extends CApplicationComponent
{
	public $cache = 108000;
	public $dependency = null;

	protected $data = array(
		'import'=>'',
		'rules'=>'',
		'preload'=>''
	);

	public function init()
	{
		$db = $this->getDbConnection();

		$items = $db->createCommand('SELECT * FROM {{modules}}')->queryAll();

		foreach($items as $item){
			$this->data['modules'][$item['name']] = array(
				'class'=>$item['class'],
				'param'=>explode(',',$item['param'])
			);
			$this->data['import']=CMap::mergeArray($this->data['import'],explode(',',$item['import']));
			$this->data['rules']=CMap::mergeArray($this->data['rules'],explode(',',$item['rules']));
			$this->data['preload']=CMap::mergeArray($this->data['preload'],explode(',',$item['preload']));
		}
		
		parent::init();
	}

	public function get($key)
	{
		return $this->data[$key];
	}

	protected function getDbConnection()
	{
		if ($this->cache)
			$db = Yii::app()->db->cache($this->cache, $this->dependency);
		else
			$db = Yii::app()->db;

		return $db;
	}
}