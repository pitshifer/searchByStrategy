<?php
/**
 * Стратегия поиска
 * User: ps-home
 * Date: 01.08.14
 * Time: 15:44
 */
abstract class SearchBy extends CComponent
{
    /**
     * @var integer Тип стратегии
     */
    public $strategyType;

    /**
     * @var CDbCriteria
     */
    public $criteria = false;

    /**
     * @var Search
     */
    protected $search;

    /**
     * @var CDataProvider
     */
    private $_dataProvider = null;

    /**
     * @var Drugs[]
     */
    private $_drugs = null;

    /**
     * @var CPagination
     */
    private $_pages;

    /**
     * @var string
     */
    public $suggest = null;

    public function __construct(Search $search) {
        $this->search = $search;

        $this->init();

        // Пагинатор
        // У промо отсутствует
        if($this->search instanceof SearchDrugs && $this->criteria !== false) {
            $size = Yii::app()->request->getQuery('size', null);
            if ($size !== null) $this->pages = $size;
            else $this->pages = 10;
        }
    }

    /**
     * @return CActiveDataProvider
     */
    public function getDataProvider()
    {
        if($this->_dataProvider === null)
            $this->_dataProvider = new CActiveDataProvider('Drugs', ['criteria'=>$this->criteria]);

        return $this->_dataProvider;
    }

    /**
     * @return Drugs[]
     */
    public function getDrugs()
    {
        if($this->_drugs === null && $this->criteria)
            $this->_drugs = Drugs::model()->findAll($this->criteria);

        return $this->_drugs;
    }

    /**
     * Пагинация
     * @param $size int
     */
    public function setPages($size)
    {
        // Показать все
        if($size <= 0) {
            $this->_pages = false;
        }  else {
            $count = Drugs::model()->count($this->criteria);
            $this->_pages = new CPagination($count);
            $this->_pages->pageSize = $size;
            $this->_pages->applyLimit($this->criteria);
        }
    }

    public function getPages()
    {
        return $this->_pages;
    }

    /**
     * Иницилизация
     *
     * @return mixed
     */
    protected abstract function init();

    /**
     * Найдено по
     */
    abstract function getFoundedBy();
}