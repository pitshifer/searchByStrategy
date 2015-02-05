<?php
class SearchByLetter extends SearchBy
{
    public $strategyType = SearchDrugs::STRATEGY_LETTER;
    /**
     * @return CActiveDataProvider
     */
    protected function init()
    {
        /* @var Letter $letter */
        $letter = null;
        // Если передаем модель Letter
        if($this->search->q instanceof Letter) $letter = $this->search->q;
        // Если ID буквы то загружаем модель
        if($letter === null) $letter = Letter::model()->findAllByPk($this->search->q);

        // Вытаскиваем все id промо препаратов к этой букве
        // Их не нужно подтягивать в результатах поиска
        // Они будут в позициях промо
        $drugsIDS = [];

        foreach($letter->drugsArray as $id=>$name) $drugsIDS[] = $id;

        $this->criteria = new CDbCriteria;
        $this->criteria->select = ['id_drugs', 'name', 'short_desc', 'image', 'image_preview', 'indications'];
        // Если искомая буква не A-Z или 0-9
        if($letter->id_letter != Letter::ID_AZ && $letter->id_letter != Letter::ID_09)
            $this->criteria->addSearchCondition('t.name', "^$letter->letter", false, 'AND', 'RLIKE');
        else
            $this->criteria->addSearchCondition('t.name', "^[$letter->letter]", false, 'AND', 'RLIKE');
        $this->criteria->addNotInCondition('id_drugs', $drugsIDS);
        $this->criteria->scopes = ['orderByPosts'];
    }

    public function getCheck()
    {
        return count($this->search->drugs);
    }

    public function getFoundedBy()
    {
        return 'Найдено по букве';
    }
}