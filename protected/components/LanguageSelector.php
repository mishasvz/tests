<?php

class LanguageSelector extends CWidget
{

    public function run()
    {
        $langs = explode(",",  Yii::app()->config->get('SYSTEM.TRANSLATE.LANG_AVAILABLE'));
        if (count($langs) <= 1)
            return false;
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . Yii::app()->config->get('SYSTEM.TRANSLATE.PATH_FLAG_CSS'));
        $this->render('languageselector', array(
            'langs'           => $langs,
            'currentLanguage' => Yii::app()->language,
            'cleanUrl'        => Yii::app()->urlManager->getCleanUrl(Yii::app()->request->url),
            'homeUrl'         => Yii::app()->homeUrl . (Yii::app()->homeUrl[strlen(Yii::app()->homeUrl) - 1] != "/" ? '/' : ''),
        ));
    }
}