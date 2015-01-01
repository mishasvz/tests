<?php
class LanguageBehavior extends CBehavior
{
    public function attach($owner)
    {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleLanguageBehavior'));
    }
    public function handleLanguageBehavior($event)
    {
        $app  = Yii::app();
        $home = $app->homeUrl . ($app->homeUrl[strlen($app->homeUrl) - 1] != "/" ? '/' : '');
        $path = $app->request->getPathInfo();
        $lm   = $app->urlManager;
        $l = false;

        if (!is_array($lm->languages))
            return;
        if ((
            isset($_GET[$lm->langParam])                    &&
            in_array($_GET[$lm->langParam], $lm->languages) &&
            $l = $_GET[$lm->langParam]
        ) || (
            $l = substr($path, 0, 2)     &&
            strlen($l) == 2              &&
            in_array($l, $lm->languages) &&
            isset($path[3])              &&
            $path[3] == '/'
        ))
        {
            // Если текущий язык у нас не тот же, что указан - поставим куку и все дела
            if ($app->language != $l || $l == $app->sourceLanguage)
                $this->setLanguage($l);

            if (//язык в URL в виде пути или параметра - нативный для приложения
                $l == $app->sourceLanguage    ||
                // Язык установлен на вывод в GET-парамметре, но обращение было через путь
                !isset($_GET[$lm->langParam]) ||
                // Язык установлен на вывод в пути, но обращение было через GET-парамметр
                ($lm->languageInPath && substr($path, 0, 2) != $l)
            )
            {
                // Редирект на URL без указания языка
                if (!$app->request->isAjaxRequest)
                    $app->request->redirect($home . $lm->getCleanUrl($app->request->url));
            }
        }
        else
        {
            $user = $app->user;
            if ($user->hasState($lm->langParam))
                $l = $user->getState($lm->langParam);
            // Пробуем получить код языка из кук
            else if (isset($app->request->cookies[$lm->langParam]) && in_array($app->request->cookies[$lm->langParam]->value, $lm->languages))
                $l = $app->request->cookies[$lm->langParam]->value;
            else if ($lm->preferredLanguage && $l = $app->request->getPreferredLanguage())
                $l = $app->locale->getLanguageID($l);
            else
                $l = false;
            if (!$l || !in_array($l, $lm->languages))
                $l = $app->language = $app->sourceLanguage;
            if ($l != $app->sourceLanguage)
            {
                $this->setLanguage($l);
                if (!$app->request->isAjaxRequest)
                    $app->request->redirect($home . $lm->replaceLangUrl($lm->getCleanUrl($app->request->url), $l));
            }
            else
                $app->language = $l;
        }
    }

    protected function setLanguage($language)
    {
        $app = Yii::app();
        $lp  = $app->urlManager->langParam;

        $app->user->setState($lp, $language);
        $app->request->cookies[$lp] = new CHttpCookie($lp, $language, array('expire' => time() + (60 * 60 * 24 * 365)));
        $app->language = $language;
    }
}