<?php
/**
 * Поиск по словарю pspell
 * User: ps-home
 * Date: 01.08.14
 * Time: 16:14
 */
class SearchByPspell extends SearchBy
{
    public function init()
    {
        // Сначала ищем по симптомам
        $pspells = Pspell::factoryMethod(Pspell::DICTIONARY_SYMPTOMS, $this->search->q);
        $pspellComposite = new PspellComposite(Pspell::DICTIONARY_SYMPTOMS, $pspells);

        $against = '';
        foreach($pspellComposite->suggest() as $suggest) {
            if(!empty($suggest)) $against .= '+(' . implode(' ', $suggest) . ') ';
        }
        $against = trim($against);

        $sql = 'SELECT name, MATCH (name) AGAINST (\''.$against.'\' IN BOOLEAN MODE) as rel FROM symptom WHERE MATCH (name) AGAINST (\''.$against.'\' IN BOOLEAN MODE) ORDER BY rel';
        $symptomNames = Yii::app()->db->createCommand($sql)->queryColumn();

        if(!empty($symptomNames)) return ($this->suggest = $symptomNames);

        // Ищем по препаратам
        $pspells = Pspell::factoryMethod(Pspell::DICTIONARY_DRUGS, $this->search->q);
        $pspellComposite = new PspellComposite(Pspell::DICTIONARY_DRUGS, $pspells);

        $suggests = $pspellComposite->suggest();
        $against = '';
        foreach($suggests as $suggest) {
            if(!empty($suggest)) $against .= '+(' . implode(' ', $suggest) . ') ';
        }
        $against = trim($against);

        // Ищем по именам препаратов
        $sql = 'SELECT name, MATCH (name) AGAINST (\''.$against.'\' IN BOOLEAN MODE) as rel FROM drugs WHERE MATCH (name) AGAINST (\''.$against.'\' IN BOOLEAN MODE) ORDER BY rel';
        $drugsNames = Yii::app()->db->createCommand($sql)->queryColumn();

        $this->suggest = $drugsNames;
    }

    public function getDataProvider()
    {
        return false;
    }

    public function getDrugs()
    {
        return false;
    }

    public function getCheck()
    {
        return !empty($this->suggest);
    }

    public function getFoundedBy()
    {
        return 'Найдено через pspell';
    }
}