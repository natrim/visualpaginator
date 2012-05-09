<?php

namespace VisualPaginator;

use Nette\Application\UI\Control;


/**
 * @author David Grudl
 * @author Filip Procházka
 * @author Natrim
 *
 * @abstract
 *
 * @property $paginator
 */
abstract class ComponentPaginator extends Control
{

    /** @var array */
    public $onChange;

    /** @var Paginator */
    private $paginator;

    /** @var array */
    private $class = array('paginator');

    /** @var string */
    private $ajaxClass = 'ajax';

    /**
     * @param $paginator Paginator
     * @return ComponentPaginator
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }

    /**
     * @return Paginator
     */
    public function getPaginator()
    {
        if (is_null($this->paginator)) {
            $this->paginator = new Paginator;
        }

        return $this->paginator;
    }


    /**
     * Renders paginator.
     * @return void
     */
    public function render()
    {
        echo $this->__toString();
    }


    /**
     * @return string
     */
    public function __toString()
    {
        $this->template->steps = $this->getPaginator()->getPagesListFriendly();
        $this->template->paginator = $this->getPaginator();
        $this->template->class = $this->getClass();

        if ($this->template->getFile() === NULL) {
            $this->template->setFile(dirname(__FILE__) . '/../../templates/paginator.phtml');
        }

        return (string)$this->template;
    }


    /**
     * @param string $page
     *
     * @return ComponentPaginator
     */
    public function setPage($page)
    {
        $this->getPaginator()->page = $page;

        if ($this->presenter->isAjax()) {
            $this->invalidateControl();
            if ($this->getParent() instanceof Control && get_class($this->getParent()) !== 'Nette\Application\UI\Presenter') {
                $this->getParent()->invalidateControl();
            }
        }

        $this->onChange($page, $this);

        return $this;
    }


    /**
     * Get ajax class
     * @return string
     */
    public function getAjaxClass()
    {
        return $this->ajaxClass;
    }


    /**
     * Set ajax class
     * @param string $ajaxClass ajax class
     * @return ComponentPaginator
     */
    public function setAjaxClass($ajaxClass)
    {
        $this->ajaxClass = $ajaxClass;
        return $this;
    }

    /**
     * @param string $file
     *
     * @return ComponentPaginator
     */
    public function setTemplateFile($file)
    {
        $this->getTemplate()->setFile($file);
        return $this;
    }

    /**
     * @param $class string|array
     */
    public function setClass($class)
    {
        $this->class = (array)$class;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return implode(' ', $this->class);
    }

    /**
     * @param $class string
     */
    public function addClass($class)
    {
        $this->class[] = $class;
        return $this;
    }

    /**
     * @param $class string
     */
    public function removeClass($class)
    {
        $newclass = array();
        foreach ($this->class as $cl) {
            if ($cl !== $class) {
                $newclass[] = $cl;
            }
        }

        $this->class = $newclass;

        return $this;
    }
}
