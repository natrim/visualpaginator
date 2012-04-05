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
    public $page = 1;


    /**
     * Loads state informations.
     * @param  array
     * @return void
     */
    public function loadState(array $params)
    {
        parent::loadState($params);
        $this->setPage($this->page);
    }

    /**
     * Creates new instance
     * @return VisualPaginator
     */
    public function createPaginator()
    {
        return new VisualPaginator;
    }
}