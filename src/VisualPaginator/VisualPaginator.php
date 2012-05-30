<?php

namespace VisualPaginator;


/**
 * Visual paginator control.
 * @author David Grudl
 * @author Filip Procházka
 * @author Natrim
 *
 * @property $rememberState bool
 * @property $stateTimeout string|int
 */
class VisualPaginator extends ComponentPaginator
{

    /** @var int */
    public $page;

    /** @var bool */
    private $remember = FALSE;

    /** @var int|string session timeout (default: until is browser closed) */
    private $timeout = 0;

    /** @var \Nette\Http\Session */
    private $session;

    /**
     * Returns array of classes persistent parameters. They have public visibility and are non-static.
     * @return array
     */
    public static function getPersistentParams()
    {
        return array('page');
    }

    /**
     * Save the state to session?
     * @param bool $remember
     * @return VisualPaginator
     */
    public function setRememberState($remember = TRUE)
    {
        $this->remember = (bool)$remember;
        return $this;
    }

    /**
     * Is the state saving in session
     * @return bool
     */
    public function getRememberState()
    {
        return $this->remember;
    }

    /**
     * Loads state informations.
     * @param  array
     * @return NULL
     */
    public function loadState(array $params)
    {
        if ($this->rememberState) {
            $session = $this->getStateSession();
            foreach ($this->getPersistentParams() as $name) {
                if (isset($session[$name]) && !isset($params[$name])) {
                    if ($name === 'page' && ($session[$name] < $this->paginator->getFirstPage() || $session[$name] > $this->paginator->getLastPage())) {
                        continue;
                    }

                    $params[$name] = $session[$name];
                }
            }
        }

        parent::loadState($params);

        if (!$this->presenter->isAjax() && (int)$this->page === 1) {
            $this->redirect('this', array('page' => NULL));
            return;
        }

        $this->setPage($this->page);
    }

    /**
     * Saves state informations for next request.
     * @param  array
     * @param  PresenterComponentReflection (internal, used by Presenter)
     * @return void
     */
    public function saveState(array & $params, $reflection = NULL)
    {
        parent::saveState($params, $reflection);

        if ($this->rememberState) {
            $session = $this->getStateSession();

            foreach ($this->getPersistentParams() as $name) {
                $session[$name] = $this->{$name};
            }

            $session->setExpiration($this->timeout);
        }
    }

    /**
     * @return \Nette\Http\SessionSection
     */
    protected function getStateSession()
    {
        if (is_null($this->session)) {
            throw new \Nette\InvalidStateException('Session is not set! Use \'setSession\' to set session!');
        }

        return $this->session->getSection('VisualPaginator/' . $this->lookupPath('Nette\ComponentModel\IComponent', FALSE) ? : $this->getName() . '/states');
    }

    /**
     * @inject
     * @param \Nette\Http\Session $session
     * @return VisualPaginator
     */
    public function setSession(\Nette\Http\Session $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Sets the timeout for the saved state in session
     * @param int|string $timeout
     * @return \VisualPaginator\VisualPaginator
     */
    public function setStateTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getStateTimeout()
    {
        return $this->timeout;
    }

}