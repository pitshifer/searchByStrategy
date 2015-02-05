<?php
class SearchPromo extends Search
{
    const STRATEGY_NAME = 1;
    const STRATEGY_SYMPTOM = 2;
    const STRATEGY_LETTER = 3;
    const STRATEGY_KEYWORD = 4;

    /**
     * @var array Стратегии поиска
     */
    public $strategies = ['name', 'symptom', 'keyword'];

    public function search()
    {
        // Проходимся по порядку, по всем стратегиям поиска пока не полуим результат
        foreach($this->strategies as $strategy) {
            $strategyName = 'SearchPromoBy'.ucfirst($strategy);

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
            self::STRATEGY_KEYWORD => 'По Ключевому слову',
        ];
    }

    public static function getStrategyName($strategyType)
    {
        return self::getStrategyNames()[$strategyType];
    }
}