<?php
class SearchPromoBySymptom extends SearchBy
{
    public $strategyType = SearchPromo::STRATEGY_SYMPTOM;

    private $idSymptom = null;

    public function init()
    {
        if(SearchBySymptom::$idSymptom === null) {
            $criteriaSymptom = new CDbCriteria;
            $criteriaSymptom->compare('t.name', $this->search->q);
            $symptom = Symptom::model()->find($criteriaSymptom);

            $this->idSymptom = $symptom !== null ? $symptom->id_symptom : null;
        } else {
            $this->idSymptom = SearchBySymptom::$idSymptom;
        }

        if($this->idSymptom !== null) {
            $this->criteria = new CDbCriteria;
            $this->criteria->select = ['id_drugs', 'name', 'short_desc', 'image', 'image_preview', 'indications'];
            $this->criteria->with = 'symptoms';
            $this->criteria->together = true;
            $this->criteria->addCondition('symptoms.id_symptom = :symptom');
            $this->criteria->params = [':symptom'=>$this->idSymptom];
            $this->criteria->scopes = ['orderByPosts', 'symptomPromo'=>$this->idSymptom];
        }
    }

    public function getFoundedBy()
    {
        return 'Найдено по названию симптома';
    }

    public function getCheck()
    {
        return $this->search->drugs !== null || !empty($this->drugs);
    }
}