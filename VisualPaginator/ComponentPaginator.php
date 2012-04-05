<?php

namespace VisualPaginator;

use Nette\Application\UI\Control;


/**
 * @author David Grudl
 * @author Filip Procházka
 */
class ComponentPaginator extends Control
{

    /** @var Paginator */
    private $paginator;

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
     * @throw \InvalidArgumentException if paginator not set
     */
    public function getPaginator()
    {
        if ($this->paginator === NULL) {
            throw new \InvalidArgumentException("The paginator was not set! Use \'setPaginator\' to set paginator first!");
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

        if ($this->template->getFile() === NULL) {
            $this->template->setFile(dirname(__FILE__) . '/templates/paginator.phtml');
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

}
