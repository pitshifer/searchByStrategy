<?php
/**
 * Поиск
 * User: ps-home
 * Date: 01.08.14
 * Time: 14:49
 *
 * @property CDataProvider $dataProvider Результаты поиска
 * @property Drugs[] $drugs Найденые препараты
 * @property CPagination $pages Постраничная разбивка результатов
 * @property CDbCriteria $criteria Настроена на поиск для нужных препаратов
 */
abstract class Search extends CComponent
{
    /**
     * @var string Искомое слово. Может быть несколько слов
     */
    public $q;

    /**
     * @var SearchBy
     */
    public $strategy;

    /**
     * @var CActiveDataProvider Если найдено по имени или симптому
     */
    protected $dataProvider;

    /**
     * @var string Если найдено по soundex или pspell
     */
    protected $suggest;

    /**
     * @var mixed Стратегии поиска
     */
    public $strategies = 'all';

    /**
     * @param $q string Слово или фраза (препарат или симптом)
     * @param $searchStrategy string
     */
    public function __construct($q, $searchStrategy = 'all')
    {
        $this->q = $q;
        if($searchStrategy !== 'all') {
            if(is_array($searchStrategy)) $this->strategies = $searchStrategy;
            else $this->strategies[] = $searchStrategy;
        }
    }

    /**
     * Сам процесс поиска
     * @return mixed
     */
    abstract function search();

    /**
     * Найдено по
     */
    public function getFoundedBy()
    {
        return $this->strategy->foundedBy;
    }

    protected function getCriteria()
    {
        return $this->strategy->criteria;
    }

    /**
     * @return CActiveDataProvider
     */
    public function getDataProvider()
    {
        return $this->strategy->dataProvider;
    }

    /**
     * @return Drugs[]
     */
    public function getDrugs()
    {
        return $this->strategy->drugs;
    }

    public function getPages()
    {
        return $this->strategy->pages;
    }

    /**
     * @return string
     */
    public function getSuggest()
    {
        return $this->strategy->suggest;
    }
}