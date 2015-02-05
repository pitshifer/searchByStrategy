<?php
class SearchPromoByKeyword extends SearchBy
{
    public $strategyType = SearchPromo::STRATEGY_KEYWORD;

    private $_drugs = null;
    private $_keyword = null;

    public function init()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.name', $this->search->q);

        $this->_keyword = Keywords::model()->with('drugs')->find($criteria);
    }

    public function getDrugs()
    {
        if(!$this->_drugs && $this->_keyword)
            $this->_drugs = $this->_keyword->drugs;

        return $this->_drugs;
    }

    public function getFoundedBy()
    {
        return 'Найдено по ключевым словам';
    }

    public function getCheck()
    {
        return $this->drugs !== null || !empty($this->drugs);
    }
}