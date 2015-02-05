<?php
/**
 * Поиск препарата по soundex
 * User: ps-home
 * Date: 03.08.14
 * Time: 14:04
 */
class SearchBySoundex extends SearchBy
{
    public function init()
    {
        $hash = Soundex::string($this->search->q);

        if($hash === null) return $this->suggest = false;

        $hashString = implode(' ', $hash);

        $sql = 'SELECT drugs_id FROM drugs_search WHERE MATCH(soundex) AGAINST("'.$hashString.'")';
        $id_drugs = Yii::app()->db->createCommand($sql)->queryColumn();

        $drugs = Yii::app()->db->createCommand()
            ->from('drugs')
            ->select('name')
            ->where(['in', 'id_drugs', $id_drugs])
            ->queryColumn();

        $this->suggest = implode(' / ', $drugs);
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
        return is_string($this->suggest);
    }

    public function getFoundedBy()
    {
        return 'Найдено через soundex';
    }
}