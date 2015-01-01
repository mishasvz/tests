<?php
class DbMessage extends CDbMessageSource
{
	public $sourceMessageTable = "{{sourcemessage}}";
	public $cachingDuration;
    
    public function __construct(){
        $this->cachingDuration = Yii::app()->config->get('SYSTEM.CACHE.CACHE_DURATION');
    }

	protected function loadMessages($category,$language)
	{
		if($this->cachingDuration>0 && $this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
		{
			$key=self::CACHE_KEY_PREFIX.'.messages.'.$category.'.'.$language;
			if(($data=$cache->get($key))!==false)
				return unserialize($data);
		}

		$messages=$this->loadMessagesFromDb($category,$language);

		if(isset($cache))
			$cache->set($key,serialize($messages),$this->cachingDuration);

		return $messages;
	}
	protected function loadMessagesFromDb($category,$language)
	{
	   $languageTable = "{{translation_".$language."}}";
	   $sql=<<<EOD
SELECT t1.message AS message, t2.translation AS translation
FROM {$this->sourceMessageTable} t1, {$languageTable} t2
WHERE t1.id=t2.id AND t1.category=:category
EOD;
        $command=$this->getDbConnection()->createCommand($sql);
		$command->bindValue(':category',$category);
		$messages=array();
		foreach($command->queryAll() as $row){
			$messages[$row['message']]=$row['translation'];
        }

		return $messages;
	}
}