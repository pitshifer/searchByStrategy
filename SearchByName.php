<?php
/**
 * Поиск по названию препарата
 * User: ps-home
 * Date: 01.08.14
 * Time: 15:44
 */
class SearchByName extends SearchBy
{
    public $strategyType = SearchDrugs::STRATEGY_NAME;

    /**
     * Иницилизация
     * Собираем CDbCriteria
     */
    protected function init()
    {
        $this->criteria = new CDbCriteria;
        $this->criteria->select = ['id_drugs', 'name', 'image', 'image_preview', 'short_desc', 'indications'];
        $this->criteria->addSearchCondition('name', $this->search->q);

        // TODO претендент на шаблон "Decorator"
        $this->criteria->scopes = ['orderByPosts'];
    }

    public function getCheck()
    {
        return !empty($this->search->drugs);
    }

    public function getFoundedBy()
    {
        return 'Найдено по имени препарата';
    }
}