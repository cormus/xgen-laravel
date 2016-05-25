<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Description of XPage
 *
 * @author Alex
 */
class XPage {

    var $rout;
    var $title;
    var $ico = null;
	var $layout = 'adm.layouts.default';
	var $createcontrol = false;
	var $createModel   = false;
	var $createView    = false;
    var $modules = array();
    var $param = array();
    var $loginRequired = false;
    var $showInMenu = true;
    var $showInMenuIfLogged = false;

    public function setCreateControl($data)
    {
        $this->createcontrol = $data;
    }
    public function getCreateControl()
    {
        return $this->createcontrol;
    }
	
    public function setCreateModel($data)
    {
        $this->createModel = $data;
    }
    public function getCreateModel()
    {
        return $this->createModel;
    }
	
	public function setCreateView($data)
    {
        $this->createView = $data;
    }
    public function getCreateView()
    {
        return $this->createView;
    }
	
    public function setParam($data)
    {
        $this->param = $data;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function setRout($data)
    {

        $this->rout = $data;
    }

    public function getRout()
    {

        return $this->rout;
    }

    public function setTitle($data)
    {

        $this->title = $data;
    }

    public function getTitle()
    {

        return $this->title;
    }
	
    public function setIco($data)
    {

        $this->ico = $data;
    }

    public function getIco()
    {

        return $this->ico;
    }
	
    public function setLayout($data)
    {

        $this->layout = $data;
    }

    public function getLayout()
    {

        return $this->layout;
    }

    public function setLoginRequired($data)
    {

        $this->loginRequired = $data;
    }

    public function getLoginRequired()
    {

        return $this->loginRequired;
    }

    public function setShowInMenu($data)
    {

        $this->showInMenu = $data;
    }

    public function getShowInMenu()
    {

        return $this->showInMenu;
    }

    public function setShowInMenuIfLogged($data)
    {

        $this->showInMenuIfLogged = $data;
    }

    public function getShowInMenuIfLogged()
    {

        return $this->showInMenuIfLogged;
    }

    public function addModules(Array $modules)
    {

        foreach ($modules as $position => $module)
        {

            $this->modules[$position][] = $module;
        }
    }

    public function addModule($position, $module)
    {

        $this->modules[$position][] = $module;
    }

    public function render()
    {

        //coloca os módulos na estrutura do site

        $positions = array();

        //percorre todas as posições

        foreach ($this->modules as $position => $modules)
        {
            //percorre todos os módulos da posição
            foreach ($modules as $module)
            {
                if (!isset($positions[$position]))
                    $positions[$position] = '';

				if(is_object($module))
				{
					$reflectionClass = new ReflectionMethod(get_class($module), 'render');
                	$numParam = $reflectionClass->getNumberOfRequiredParameters();
					
					if($numParam)
					{
						$html = $module->render($this->getParam());
					}
					else
					{
						$html = $module->render();
					}
				}
				else
				{
					$html = $module;
				}
				
                $positions[$position] .= $html;
            }
        }
        return View::make($this->getLayout(), $positions);
    }
}

