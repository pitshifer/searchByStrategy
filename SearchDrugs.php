<?php
/**
 * Поиск препаратов
 * User: ps-home
 * Date: 01.08.14
 * Time: 14:52
 */
class SearchDrugs extends Search
{
    const STRATEGY_NAME = 1;
    const STRATEGY_SYMPTOM = 2;
    const STRATEGY_LETTER = 3;

//    const STRATEGY_PSPELL = 3;

    /**
     * @return array Стратегии поиска
     */
    public $strategies = ['name', 'symptom', 'soundex', 'pspell'];

    /**
     * @return mixed Поиск
     */
    public function search()
    {
        // Проходимся по порядку, по всем стратегиям поиска пока не полуим результат
        foreach($this->strategies as $strategy) {
            $strategyName = 'SearchBy'.ucfirst($strategy);

            /* @var SearchBy */
            $this->strategy = new $strategyName($this);
            if($this->strategy->check) return $this;
        }

        return false;
    }

    public static function getStrategyNames()
    {
        return [
            0 => 'Не найдено',
            self::STRATEGY_NAME => 'По имени',
            self::STRATEGY_SYMPTOM => 'По симптому',
            self::STRATEGY_LETTER => 'По букве',
        ];
    }

    public static function getStrategyName($strategyType)
    {
        return self::getStrategyNames()[$strategyType];
    }
}