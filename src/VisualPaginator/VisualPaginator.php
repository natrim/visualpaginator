<?php

namespace VisualPaginator;


/**
 * Visual paginator control.
 * @author David Grudl
 * @author Filip Procházka
 */
class VisualPaginator extends ComponentPaginator
{

    /** @persistent */
    public $page;


    /**
     * Loads state informations.
     * @param  array
     * @return void
     */
    public function loadState(array $params)
    {
        parent::loadState($params);

        if (!$this->presenter->isAjax() && (int)$this->page === 1) {
            $this->redirect('this', array('page' => NULL));
            return;
        }

        $this->setPage($this->page);
    }
}