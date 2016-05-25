<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Description of Form
 *
 * @author Alex
 */
class XForm extends Eloquent
{
    /**
     * Quanto é para editar um dado
     *
     * @var int 
     */
    var $id = null;
    
    
    /**
     * Variável que vai receber o nome da tablela do formulário
     *
     * @var string 
     */
    public $table = '';
    
    /**
     * Variável que vai guardar os fields desse form
     *
     * @var array 
     */
    var $fields = array();
    
    /**
     * Variável que vai receber o título do formulário
     *
     * @var string 
     */
    var $title = '';
    
    /**
     * Variável que vai receber o título do formulário
     *
     * @var string 
     */
    var $subTitle = '';
	
    /**
     * Variável que vai receber o título do formulário
     *
     * @var string 
     */
    var $description = '';
    
    /**
     * Quantidade de ítens que será mostrado por página
     *
     * @var int 
     */
    var $itemsPerPage = 15;
    
    /**
     * Controla a ativação do botão de apagar registros
     *
     * @var boolean
     */
    var $showBtnDelete = true;
    
    /**
     * Controla a ativação do botão Novo Cadastro
     *
     * @var type 
     */
    var $showBtnNewCadastre = true;
    
    /**
     * Controla a ativação do botão de edição dos registros
     *
     * @var booblean
     */
    var $showBtnEdit = true;
    
    /**
     * Configura se é para mostrar as caixas de seleção na listagem
     *
     * @var booblean 
     */
    var $showSelectBox = true;
    
    var $order = array('camp' => 'id', 'order' => 'desc');
	
	var $queryList = null;
	
	 var $runAfterSaving = null;
	 
    /**
     * Condições extras para a listagem de dados
     *
     * @var array
     */
    var $extraWhere = array();
    
    public function __construct() {
        $this->setId(Input::get('id', null));
    }


    /**
     * Seta a id da linha que será editada
     * 
     * @param int $date
     */
    public function setId($date)
    {
        $this->id = $date;
    }
    
    /**
     * Retorna a linha da id que esta sendo editada
     * 
     * @return id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * seta o nome ta tabela desse form
     * 
     * @param string $data
     */
    public function setTable($data)
    {
        $this->table = $data;
    }
    /**
     * Retorna o nome da tabela desse form
     * 
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
     * Seta o título do formulário
     * 
     * @param string$data
     */
    public function setTitle($data)
    {
        $this->title = $data;
    }
    
    /**
     * Método que retorna o título do formulário
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setSubTitle($data)
    {
        $this->subTitle = $data;
    }

    public function getSubTitle()
    {
        return $this->subTitle;
    }
    
    public function setDescription($data)
    {
        $this->description = $data;
    }

    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Seta a quantidade de ítens que será mostrado por página
     * 
     * @param string$data
     */
    public function setItemsPerPage($data)
    {
        $this->itemsPerPage = $data;
    }
    
    /**
     * Método que retorna a quantidade de ítens que será mostrado por página
     * 
     * @return string
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
    
    
    /**
     * Configura se é para mostrar o botão de deletar
     * 
     * @param boolean $data
     */
    public function setShowBtnDelete($data)
    {
        $this->showBtnDelete = $data;
    }
    public function getShowBtnDelete()
    {
        return $this->showBtnDelete;
    }
    
    /**
     * Configura se é para mostrar o botão de novo cadastro
     * 
     * @param boolean $data
     */
    public function setShowBtnNewCadastre($data)
    {
        $this->showBtnNewCadastre = $data;
    }
    public function getShowBtnNewCadastre()
    {
        return $this->showBtnNewCadastre;
    }
    
    /**
     * Configura se é para mostrar o botão de editar
     * 
     * @param booblean $data
     */
    public function setShowBtnEdit($data)
    {
        $this->showBtnEdit = $data;
    }
    public function getShowBtnEdit()
    {
        return $this->showBtnEdit;
    }
    
    /**
     * Configura se é para mostrar a caixa de seleção
     * 
     * @param booblean $data
     */
    public function setShowSelectBox($data)
    {
        $this->showSelectBox = $data;
    }
    public function getShowSelectBox()
    {
        return $this->showSelectBox;
    }

	
    public function addExtraWhere($colum, $expression, $value, $type = 'and')
    {
            $this->extraWhere[] = array('type' => $type, 'colum' => $colum, 'expression' => $expression, 'value' => $value);
    }

    public function getExtraWhere()
    {
            return $this->extraWhere;
    }
    
    public function setOrder($camp, $order)
    {
        $this->order = array('camp' => $camp, 'order' => $order);
    }
    public function getOrder()
    {
        return $this->order;
    }
    
    public function setQueryList($data)
    {
        $this->queryList = $data;
    }
    public function getQueryList()
    {
        if(!$this->queryList)
            $this->queryList = DB::table($this->gettable());
        
        return $this->queryList ;
    }
	
	public function runAfterSaving($data)
    {
    	$this->runAfterSaving = $data;
    }

	
    /**
     * Faz o carregamento de um field próprio do sistema ou que foi criado pelo usuário
     * 
     * @param string $name nome do fieald a ser carregado
     * @return object retorna o objeteo field
     */
    public function field($name)
    {
        $link = '/field';
        //carrega o field padrão
        require_once(__DIR__.$link.'/Field.php');
        //deixa apenas a primeira letra maiúscula
        $name = ucfirst(strtolower($name));
        //verifica se o desenvolvedor criou um field personalizado
        //se não carrega o field do sistema
        if(file_exists(__DIR__.$link.'/fields/my/'.$name.'.php'))
        {
            require_once(__DIR__.$link.'/fields/my/'.$name.'.php');
        }
        else
        {
            require_once(__DIR__.$link.'/fields/system/'.$name.'.php');
        }
        return new $name;
    }
    
    /**
     * Método para add os filds que esse formulário vai ter
     * 
     * @param object $data
     */
    public function addField($data)
    {
        $this->fields[] = $data;
    }
    
    /**
     * Retorna o HTML do formulário
     * 
     * @return string
     */
       public function renderForm()
    {
        //header('Location:'.Request::url());
        $id = $this->getId();
        //dados que vão compor o formulário
        $row = null;
        //status do salve
        $return = Request::get('save', -1);
        
        //se exisstir uma id carrega os dados para preencher o formulário
        if($id)
        {
            $row = DB::table($this->table)->where('id', $id)->first();
        }
        
        //verifica se existou um submit
        if(Input::has('_token'))
        {
            $updates = array();
            foreach($this->fields as $field)
			{
                    if($field->getName())
					{
					$field->setRow($row);
					$name  = $field->getName();
					$value = $field->loadValue();

					//verifica se esse campo não admite valores duplicados
					if($field->getUnique())
					{
						$unique = $field->getUnique();
						$dbUnique = DB::table($this->table);
						//condição padrão é o valor que o usuãrio cadastrou
						$dbUnique->where($name, '=', $value);
						//condições extram para saber se o campo é único. Muito usado para verificar se o usuário esta logado
						foreach ($unique['condition'] as $condition)
						{
							//$dbUnique->where('id', '=', 1);
							$dbUnique->where($condition[0], $condition[1], $condition[2]);
						}
						$numb = $dbUnique->count();
						if($numb)
						{
							$return  = 3;
							$message = $field->getTitle();
						}
					}
					//verifica se o campo é obrigatório e foi preenchido
					if($field->getRequired() && !$field->requiredFieldIsValid())
					{
						$return = 2;
					}

					//verifica se existe o método run, esse método é executado de forma automática caso ele exista
					if(method_exists($field,'run'))
					{
                        $value = $field->run();
						//caso o método run retorne alguma coisa, salva no banco de dados
						if($value)
							$updates[$name] = $value;
					}
					//alguns fields não podem ser atualizados na tabela
					else if(!in_array(get_class($field), array('Image', 'Checkbox')))
					{
						$updates[$name] = $value;
					}
				}
            }

            //só salva se todos os campos obrigatórios foram preenchidos
            if($return < 2)
            {
                $db = DB::table($this->table);
                //se exitir uma ID atualiza
                //se não realiza um novo cadastro
                if($id)
                {
                    $updates['updated_at'] = date('Y-m-d H:i:s');
                    $return = $db->where('id', $id)->update($updates);

                    if($this->runAfterSaving != null)
                    {
                        $runAfterSaving = $this->runAfterSaving;
                        $runAfterSaving($id);
                    }
                }
                else
                {
                    $updates['created_at'] = date('Y-m-d H:i:s');
                    $updates['updated_at'] = date('Y-m-d H:i:s');


                    $return = $db->insertGetId($updates);
                    //quando salva um novo registro muda a URL para a id inserida
                    if($return)
                    {
                        if($this->runAfterSaving != null)
                        {
                            $runAfterSaving = $this->runAfterSaving;
                            $runAfterSaving($id);
                        }
                         header("Location:".URL::to(Request::url().'/?id='.$return.'&save=1'));
                         exit();
                    }
                }
            }

            $row = (object) $updates;
        }
        
        //mensagem de status
        if($return > -1)
        {
            switch($return)
            {
                case 0:
                    $error   = 1;
                    $message = "Erro ao tentar salvar os dados";
                    break;
                case 1:
                    $error   = 0;
                    $message = "Dados salvos com sucesso";
                    break;
                case 2:
                    $error   = 2;
                    $message = "Informe todos os campos obrigatórios";
                    break;
                case 3:
                    $error   = 3;
                    $message = "Esse \"{$message}\" já esta em uso, escolha outro";
                    break;
            }
        }
		
        if(substr($_SERVER['REDIRECT_URL'], -1) == '/')
            $url = Request::url().'/?id='.$id;
        else
            $url = Request::url().'?id='.$id;
        
        $html  = '<div class="width-100-l marketing">';
        $html .= '<div class="page-title pull-left width-100">
                    <h1 class="pull-left">'.$this->title.'<br /><small>'.$this->getSubTitle().'</small></h1>
                  </div>';
                        $html .= Form::open(array('url' => $url, 'name' => 'consoles-form', 'files' => true, 'role' => 'form'));
                        foreach($this->fields as $field)
                        {
                            if($field->getShowForm())
                                $html .= $field->render($row);
                        }
                        if(isset($message))
                        {
                            $class = ($error == 0)? 'alert-success': 'alert-danger';
                            $html .= '<div class="alert '.$class.' width-100-l">
                                            <a href="#" class="alert-link">
                                                '.$message.'
                                            </a>
                                      </div>';  
                        }
               $html .= Form::button('Enviar', array('class' => 'btn btn-primary', 'type' => 'submith'));
               $html .= '<a href="'.Request::url().'" class="btn btn-danger">Cancelar</a>';
            $html .= Form::close();
        $html .= '</div><!--/container-->';
        
        return $html;
    }
    
    /**
     * Retorna o HTML da lista de dadados do cadastro desse form
     * 
     * @return string
     */
    public function renderList() 
    {
        //verifica se é para deletar uns dados
        if(Input::has('select'))
        {
            $ids    = Input::get('select');
            $rows   = DB::table($this->table)->whereIn('id', $ids)->get();
            $return = DB::table($this->table)->whereIn('id', $ids)->delete();
            //verifica se os dados foram apagados da tabela
            if($return)
            {
                //percorre os fields verificando se algum tem o método delet a ser executado
                foreach($this->fields as $field)
                {
                    if(method_exists($field,'delet'))
                        $field->delet($rows);
                }

                $error = 0;
                $message = 'Dados apagados com sucesso !';
            }
            else
            {
                $error = 1;
                $message = 'Não foi possível deletar os dados, tente novamente !';
            }
        }
        
       $table = $this->getQueryList();
		
        //quando o usuário add mais condições para filtrar a listagem
        foreach($this->getExtraWhere() as $condition)
        {
            if($condition['type'] == 'and')
                $table->where($condition['colum'], $condition['expression'], $condition['value']);
            else
                $table->orWhere($condition['colum'], $condition['expression'], $condition['value']);
        }
		
        //filtros
        $htmlFilter     = '';
        $htmlFilterText = '';
        foreach($this->fields as $field)
        {
            if($field->getFilter())
            {
                $value_filter = 0;
                //filtro para campo de texto ELSE campo select
                if(in_array(get_class($field), array('Text', 'Textarea')))
                {
                    $value_filter = Input::get('search', '');
                    $htmlFilterText =  '<td>';
                        $htmlFilterText .= Form::label('search', 'Busca', array('class' => 'width-100 pull-left'));
                        $htmlFilterText .= Form::text('search', $value_filter, array('class' => 'form-control pull-left input-search'));
                        $htmlFilterText .= Form::button('Filtrar', array('id' => 'btn-search', 'class' => ' btn btn-info pull-right'));
                    $htmlFilterText .= '</td>';
                    
                    if($value_filter)
                    {
                        $table->where($field->getName(), 'LIKE', "%$value_filter%");
                    }
                }
                else
                {
                    $value_filter = Input::get($field->getName(), 0);
                    if(method_exists($field,'filter'))
                    {
                        $htmlFilter .= '<td>'.$field->filter().'</td>';
                    }
                    
                    if($value_filter)
                    {
                        $table->where($field->getName(), '=', $value_filter);
                    }
                }
            }
        }
        
        $order = $this->getOrder();
        $table->orderBy($order['camp'], $order['order']);
        
        $rows  = $table->paginate($this->itemsPerPage);
        $links = $rows->appends(Input::except('page'))->links();
        
        
        if(substr($_SERVER['REDIRECT_URL'], -1) == '/')
            $url = Request::url().'/';
        else
            $url = Request::url();
		
        $html  = Form::open(array('url' => $url, 'name' => 'select-form'));
        $html .= '<div class="width-100-l marketing">
                    <div class="page-title pull-left width-100">
                        <div class="row">
                            <div class="col-md-8"> 
                                <h1 class="pull-left">'.$this->title.' <br /><small>'.$this->getSubTitle().'</small></h1>
                            </div>';
                           
                            $html .= '<div class="col-md-4">';
                                if($this->getShowBtnDelete())
                                    $html .= '<button type="submit" class="btn btn-danger pull-right">Delete</button>';
                                if($this->getShowBtnNewCadastre())
                                    $html .= '<a href="'.Request::url().'/?id=0" class="btn btn-success pull-right">Novo cadastro</a>';
                            $html .= '</div>';
                            
         $html .=       '</div>';
                        if(isset($message))
                        {
                            $class = ($error == 0)? 'alert-success': 'alert-danger';
                            $html .= '<div class="alert '.$class.'">
                                            <a href="#" class="alert-link">
                                                '.$message.'
                                            </a>
                                      </div>';  
                        }
          $html .= '</div>';
          
          
          //add os filtros
          if($htmlFilterText || $htmlFilter)
          {
            $html .= '<table class="table table-filter">
                          <tbody>
                              <tr>'.$htmlFilterText.$htmlFilter.'</tr>
                          </tbody>
                      </table>';
          }
          
          $html .= '<table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>';
          
                                if($this->getShowSelectBox())
                                {
                                    $html .= '<th class="text-center">Selecionar</th>';
                                }
                                
                                foreach($this->fields as $field)
                                {
                                    if($field->getShowList())
                                        $html .= '<th>'.$field->getTitle().'</th>';
                                }
                                
                                if($this->getShowBtnEdit())
                                {
                                    $html .= '<th>Editar</th>';
                                }
                                    
                    $html .= '</tr>
                        </thead>
                        <tbody>';
                        foreach($rows as $row)
                        {
                            $html .= '<tr>
                                        <td>'.$row->id.'</td>';
                            
                                        if($this->getShowSelectBox())
                                        {
                                            $html .= '<td class="text-center">'.Form::checkbox('select[]', $row->id,  in_array($row->id, array())).'</td>';
                                        }
                                        
                                        foreach($this->fields as $field)
                                        {
                                            if($field->getShowList())
                                                $html .= '<td>'.$field->treatmentValue($row).'</td>';
                                        }
                                        
                                        if($this->getShowBtnEdit())
                                        {
                                            $html .= '<td> 
                                                        <button type="button" class="btn btn-default btn-xs" onclick="location.href=\''.Request::url().'/?id='.$row->id.'\'">
                                                            <span class="glyphicon glyphicon-pencil"></span> Editar
                                                        </button>
                                                    </td>';
                                        }
                                            
                            $html .= '</tr>';
                        }
                $html .= '
                    </tbody>
            </table>';
            
            if($links)
                $html .= $links;
                
        $html .= '</div><!--/container-->';
        $html .= Form::close(); 
        
        return $html;
    }
    
    /**
     * Faz o controle entre retornar o HTML formulário ou a lista de dados cadastrado pelo formulário
     * 
     * @return type
     */
    public function render()
    {
        $this->checkTable();
        
        //se existir id na rl do site é para retornar o formulário
        //se não retorna a listagem
        if($this->getId() !== null)
        {
            return $this->renderForm();
        }
        else
        {
            return $this->renderList();
        }
    }
    
    /**
     * Método que verifica se uma determinada tabela existe
     * 
     * 
     */
    public function checkTable()
    {
		//http://laravel.com/docs/schema
        $fields = $this->fields;
        //verifica se a tabela não existe
        if(!Schema::hasTable($this->table))
        {
			//se a tabela não existir ela é criada
            Schema::create($this->table, function($table) use ($fields)
            {
                    //$table->integer('chapter_id');
                
                    $table->increments('id');
                    foreach($fields as $field)
                    {
                        //campos que são criados automaticamente
                        if(!in_array($field->getName(), array('id', 'created_at', 'updated_at')))
                        {
                            $table = $field->tableData($table);
                        }
                    }
                    $table->timestamps();
            });
        }
        else
        {
            //se a tabela existir é feita a verificação se todos os campos existem na tabela
            foreach($fields as $field)
			{
				//verifica se a coluna existe na tabela
                if(!Schema::hasColumn(Config::get('database.connections.mysql.prefix').$this->table, $field->getName()))
                {
					//se não existir a coluna é criada
                    Schema::table($this->table, function($table) use ($field)
                    {
                        $table = $field->tableData($table);
                    });
                }
            }
        }
    }
}
