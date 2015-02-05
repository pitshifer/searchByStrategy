<?php
class SearchPromoByLetter extends SearchBy
{
    public $strategyType = SearchPromo::STRATEGY_LETTER;

    private $_letter = null;

    public function init()
    {
        // Если передаем модель Letter
        if($this->search->q instanceof Letter) $this->_letter = $this->search->q;
        // Если ID буквы то загружаем модель
        if($this->_letter === null) $this->_letter = Letter::model()->findAllByPk($this->search->q);
    }

    public function getDrugs()
    {
        return $this->_letter->drugs;
    }

    public function getCheck()
    {
        return !empty($this->search->drugs);
    }

    public function getFoundedBy()
    {
        return 'Промо по названию букве';
    }
}