<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Text
 *
 * @author Alex
 */
class Select  extends Field{
    
    var $table = '';
    var $option;
    var $column;
    var $loadList = true;
    var $options = array('Selecione uma opção');
    var $relationship = array();
    var $query = null;
    
    public function setTable($data) 
    {
        $this->table = $data;
        $this->query = DB::table($this->gettable());
    }
    public function getTable() 
    {
        return $this->table;
    }
    
    public function setLoadList($data)
    {
        $this->loadList = $data;
    }
    public function getLoadList()
    {
        return $this->loadList;
    }
    
    public function setOptions(Array $data)
    {
        $this->options = array_merge($this->getOptions(), $data);
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function tableData($table)
    {
        $table->integer($this->getName());
        return $table;
    }
    
    /**
     * Campo para configurar os campos de value e texto do fild select
     * 
     * @param type $option coluna da tabela que será o campo value
     * @param type $column coluna da tabela que sera o texto visual
     */
    public function selectOptions($option, $column)
    {
        $this->option = $option;
        $this->column = $column;
    }
    
    /**
     * Esse método faz relação entre dois selects. Quando esse select tem o 
     * valor trocado dispara o evento ajax que carrega os dados da tabela informada
     * em outro select
     * 
     * @param string $table
     * @param string $id_camp
     * @param string $camp
     * @param string $relationship
     */
    public function relationship($table, $id_camp, $camp, $relationship)
    {
        $this->relationsphip = array('table' => $table, 'id_camp' => $id_camp, 'camp' => $camp, 'relationship' => $relationship);
    }
    
    
    public function setQuery($data)
    {
        $this->query = $data;
    }
    public function getQuery()
    {
        if(!$this->query)
            $this->query = DB::table($this->gettable());
        return $this->query;
    }
    
    public function render($row)
    {
        $option = $this->option;
        $column = $this->column;
        $name   = $this->getName();
        
        if($this->gettable() && ($this->getLoadList() || $row))
        {
            $query  = $this->getQuery();
            $values = $query->select($option, $column)->get();
            //organiza os dados no formato do select laravel
            foreach($values as $value)
            {
                $this->options[$value->$option] = $value->$column;
            }
        }
        
        //quando faz a renderização o valo é setado diretamente no select
        //isso facilita pois cada field tem suas particularidades
        if($row)
        {
            $this->setValue($row->$name);
        }
        
        $control = array('class' => 'form-control', 'id' => $name);
        
        //se esse select tem relação com outro select
        if(!empty($this->relationsphip))
        {
            $control          = array_merge($control, $this->relationsphip);
            $control['class'] = 'form-control onchange';
        }
        
        $required = '';
        if($this->getRequired())
           $required = '<span class="required">*</span>';
        
        $subTitle = '';
        if($this->getSubTitle())
            $subTitle = '<span class="subtitle">'.$this->getSubTitle().'</span>';
        
        return '<div class="form-group">
                    '.Form::label($name, $this->getTitle())
                     .$required
                     .$subTitle
                     .Form::select($name, $this->getOptions(), $this->getValue(), $control).'
                </div>';
    }
    
    public function treatmentValue($row)
    {
        $options = $this->getOptions();
        $name    = $this->getName();
        //verifica se não foi add valors diretamente
        if(count($options) == 1)
        {
            $query = DB::table($this->gettable()); 
            $row   = $query->select($this->column)->where($this->option, '=', $row->$name)->first();
            if($row)
            {
                $name = $this->column;
                return $row->$name;
            }
        }
        else
        {
            if($row->$name && isset($options[$row->$name]))
            {
                return $options[$row->$name];
            }
        }
        
        return '-';
    }
	
    public function filter()
    {
        $options = $this->getOptions();
        $option  = $this->option;
        $column  = $this->column;
        $name    = $this->getName();
        
        $valueDefault = Input::get($name, 0);
        
        if($this->gettable())
        {
            $query  = $this->getQuery();
            $values = $query->select($option, $column)->get();
            //organiza os dados no formato do select laravel
            foreach($values as $value)
            {
                $options[$value->$option] = $value->$column;
            }
        }
        
        $control = array('class' => 'form-control form-filter', 'id' => $name);
        
        return '<div class="form-group">
                    '.Form::label($name, $this->getTitle())
                     .Form::select($name, $options, $valueDefault, $control).'
                </div>';
    }
}
