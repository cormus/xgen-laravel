<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * ,
 * Description of App
 *
 * @author Alex
 */
class XApp {
    
    var $title;
    var $pages = array();
    var $defullModules = array();
    var $menuStructure = array();
    
    public function setTitle($data)
    {
        $this->title = $data;
    }
    public function getTitle()
    {
        return $this->title;
    }
	
    public function getPages()
    {
        return $this->pages;
    }
    
    public function addPage($page)
    {
        $this->pages[] = $page;
    }
    
    public function addDefullModules($layout, Array $data)
    {
        $this->defullModules[$layout] = $data;
    }
    
    /**
     * Seta a estrutura de menu e sub-menus
     * 
     * @param array $data
     */
    public function setMenuStructure(Array $data)
    {
        $this->menuStructure = $data;
    }
    public function getMenuStructure()
    {
        return $this->menuStructure;
    }

    public function run() 
    {
        foreach($this->getPages() as $page)
        {
            //coloca os módulos comuns a todas as páginas página
			if(isset($this->defullModules[$page->getLayout()]) && !empty($this->defullModules))
			{
				$page->addModules($this->defullModules[$page->getLayout()]);
			}
			
			
			if($page->getCreateControl() || $page->getCreateModel() || $page->getCreateView())
			{
				$rout = $page->getRout();
				//remove os caracteres de separação da rota
				$rout = str_replace(array('-', '_'), ' ', $rout);
				//coloca as primeiras letras de cada palavra em maiúscula
				$rout = ucwords($rout);
				//remove os espaços
				$rout = str_replace(' ', '', $rout);
				//monta o nome do controller
				$controlName = $rout.'Controller';
				$controlPath = '../app/controllers/'.$controlName.'.php';
				$modelName   = $rout.'Model';
				$modelPath   = '../app/models/'.$modelName.'.php';
				$viewName    = $rout;
				$viewPath    = '../app/views/app/'.$viewName.'.blade.php';
				
				if($page->getCreateControl() && !file_exists($controlPath))
				{
					$controller  = '<?php'."\n";
					$controller .= 'class '.$controlName.' extends BaseController'."\n";
					$controller .= '{'."\n";
					$controller .= '		public function  render()'."\n";
					$controller .= '		{'."\n";
					$controller .= '			$'.$modelName.' =  new '.$modelName.'();'."\n";
					$controller .= '			$data = $'.$modelName.'->render();'."\n";
					$controller .= '			return View::make(\'app.'.$rout.'\', $data);'."\n";
					$controller .= '		}'."\n";
					$controller .= '}'."\n";
																				  
					// Abre ou cria o arquivo
					// "a" representa que o arquivo é aberto para ser escrito
					$fp = fopen($controlPath, "a");
					// Escreve "exemplo de escrita" no bloco1.txt
					$escreve = fwrite($fp, $controller);
					// Fecha o arquivo
					fclose($fp);
				}
				
				if($page->getCreateModel() && !file_exists($modelPath))
				{
					$model  = '<?php'."\n";
					$model .= 'class '.$modelName.' extends Eloquent '."\n";
					$model .= '{'."\n";
					$model .= '		public function render()'."\n";
					$model .= '		{'."\n";
					$model .= '			return array();'."\n";
					$model .= '		}'."\n";
					$model .= '}'."\n";
																				  
					// Abre ou cria o arquivo
					// "a" representa que o arquivo é aberto para ser escrito
					$fp = fopen($modelPath, "a");
					// Escreve "exemplo de escrita" no bloco1.txt
					$escreve = fwrite($fp, $model);
					// Fecha o arquivo
					fclose($fp);
				}
				
				if($page->getCreateView() && !file_exists($viewPath))
				{
					$view  = $viewName;
																				  
					// Abre ou cria o arquivo
					// "a" representa que o arquivo é aberto para ser escrito
					$fp = fopen($viewPath, "a");
					// Escreve "exemplo de escrita" no bloco1.txt
					$escreve = fwrite($fp, $view);
					// Fecha o arquivo
					fclose($fp);
				}
			}
            
            //verifica se é necessário estar logado para ter acesso a a essa página
            if($page->getLoginRequired())
            {
                Route::any($page->getRout(), array('before' => 'auth', function() use ($page){
                    //se for passado algum parametro na url ele é passado como parametro para a página
                    $page->setParam(func_get_args());
                    return $page->render();
                }));
            }
            else
            {
                //página inicial do site caso esteja logado
                Route::any($page->getRout(), function() use ($page){
                    //se for passado algum parametro na url ele é passado como parametro para a página
                    $page->setParam(func_get_args());
                    return $page->render();
                });
            }

        }
    }
    
    /**
     * Método que organiza as páginas em estrutura de menu e submenus
     * 
     * @return array
     */
    public function menuStructure()
    {
        $pages = $this->getPages();
        $structure = $this->getMenuStructure();

        $return = array();
        foreach($structure as $value)
        {
            if(is_array($value))
            {
                $routes = array();
                foreach($value['routes'] as $rota)
                {
                    foreach($pages as $page)
                    {
                        if($rota == $page->getRout())
                        {
                            //mostar no menu
                            //mostrar no menu se estiver logado
                            if($page->getShowInMenu())
                            {
                                if($page->getShowInMenuIfLogged())
                                {
                                    $logged = Sentry::check();
                                }
                                else
                                {
                                    $logged = true;
                                }

                                if($logged)
                                {
                                    $routes[$page->getRout()] = $page;
                                }
                            }
                        }
                    }
                }
                $value['routes'] = $routes;
                $return[] = $value;
            }
            else
            {
                foreach($pages as $page)
                {
                    if($value == $page->getRout())
                    {
                        //mostar no menu
                        //mostrar no menu se estiver logado
                        if($page->getShowInMenu())
                        {
                            if($page->getShowInMenuIfLogged())
                            {
                                $logged = Sentry::check();
                            }
                            else
                            {
                                $logged = true;
                            }

                            if($logged)
                            {
                                $routes[$page->getRout()] = $page;
                            }
                        }
                        $return[] = $value;
                    }
                }
            }
        }

        return $return;
    }
}