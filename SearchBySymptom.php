<?php
/**
 * Поиск по симптому
 * User: ps-home
 * Date: 01.08.14
 * Time: 15:55
 */
class SearchBySymptom extends SearchBy
{
    public $strategyType = SearchDrugs::STRATEGY_SYMPTOM;

    private $founded = false;

    public static $idSymptom = null;

    public function init()
    {
        $symptom = null;

        // Если передаем модель симптома
        if($this->search->q instanceof Symptom) $symptom = $this->search->q;
        else {
            $criteriaSymptom = new CDbCriteria;
            $criteriaSymptom->compare('t.name', $this->search->q);
            $symptom = Symptom::model()->find($criteriaSymptom);
        }

        if($symptom === null) return null;

        $this->founded = true;
        self::$idSymptom = $symptom->id_symptom;

        $this->criteria = new CDbCriteria;
        $this->criteria->select = ['id_drugs', 'name', 'short_desc', 'image', 'image_preview', 'indications'];
        $this->criteria->with = 'symptoms';
        $this->criteria->together = true;
        $this->criteria->addCondition('symptoms.id_symptom = :symptom');
        $this->criteria->params = [':symptom'=>$symptom->id_symptom];
        $this->criteria->scopes = ['orderByPosts', 'symptomNoPromo'=>[$symptom->id_symptom]];
    }

    public function getCheck()
    {
        return $this->founded;
    }

    public function getFoundedBy()
    {
        return 'Найдено по названию симптома';
    }
}