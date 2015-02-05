<?php
class SearchPromoByName extends SearchBy
{
    public $strategyType = SearchPromo::STRATEGY_NAME;

    private $founded = false;

    public function init()
    {
        $criteria = new CDbCriteria;
        $criteria->select = ['id_drugs', 'name', 'image', 'image_preview', 'short_desc', 'indications'];
        $criteria->compare('name', $this->search->q);

        $drugs = Drugs::model()->find($criteria);

        if($drugs !== null) $this->founded = true;
        else return;

        $this->criteria = new CDbCriteria;
        $this->criteria->select = ['id_drugs', 'name', 'image', 'image_preview', 'short_desc', 'indications'];
        $this->criteria->addInCondition('id_drugs', $drugs->idPromo);
    }

    public function getCheck()
    {
        return $this->founded;
    }

    public function getFoundedBy()
    {
        return 'Промо по названию препарата';
    }
}