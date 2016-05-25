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
class Checkbox extends Field
{
    var $table = '';
    var $option;
    var $column;
    var $options;
    
    public function setTable($data) 
    {
        $this->table = $data;
    }
    public function getTable() 
    {
        return $this->table;
    }
    
    public function setOptions($data)
    {
        $this->options = $data;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Campo para configurar os campos de value e texto do checkboxes
     * 
     * @param type $option coluna da tabela que será o campo value
     * @param type $column coluna da tabela que sera o texto visual
     */
    public function selectOptions($option, $column)
    {
        $this->option = $option;
        $this->column = $column;
    }
    
    
    public function tableData($table)
    {
        $table->text($this->getName());
        return $table;
    }
    
    public function render($row)
    {
        $option = $this->option;
        $column = $this->column;
        
        $name = $this->getName();
        //quando faz a renderização o valor é setado diretamente no checkbox
        //isso facilita pois cada field tem suas particularidades
        if($row && $row->$name)
        {
            $chacked = json_decode($row->$name);
        }
        else
        {
            $chacked = array();
        }
        
        $required = '';
        if($this->getRequired())
           $required = '<span class="required">*</span>';
        
        $subTitle = '';
        if($this->getSubTitle())
            $subTitle = '<span class="subtitle">'.$this->getSubTitle().'</span>';
        
        $html = '<div class="form-group">';
           $html .= Form::label($name, $this->getTitle()); 
           $html .= $required; 
           $html .= $subTitle; 
           $html .= '<div class="container marketing">';
           
                $i = 0;
                if($this->gettable())
                {
                    $values  = DB::table($this->gettable())->select($option, $column)->get();
                    foreach($values as $value)
                    {
                        $html .=  '<div class="col-md-4" style="padding-left: 0px">
                                      <div class="checkbox">
                                          '.Form::label($name.'_'.$i, $value->$column)
                                           .Form::checkbox($name.'[]', $value->$option,  in_array($value->$option, $chacked), array('id' => $name.'_'.$i)).'
                                      </div>
                                   </div>';
                        $i++;
                    }
                }
                else
                {
                    foreach($this->options as $option => $column)
                    {
                        $html .=  '<div class="col-md-4" style="padding-left: 0px">
                                      <div class="checkbox">
                                          '.Form::label($name.'_'.$i, $column)
                                           .Form::checkbox($name.'[]', $option,  in_array($option, $chacked), array('id' => $name.'_'.$i)).'
                                      </div>
                                   </div>';
                        $i++;
                    }
                }
           $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Método que é executado quando tem um submit
     * 
     */
    public function run()
    {
        $post = Input::get($this->getName());
        if(is_array($post))
            return json_encode($post);
        else
            return '[]';
    }
    
    /**
     * Faz o tratamento dos dados para serem mostrados na listagem
     * 
     * @param object $row
     * @return string
     */
    public function treatmentValue($row)
    {
        $html    = '';
        $options = $this->getOptions();
        $name    = $this->getName();
        $ids     =  json_decode($row->$name);
        
        if(!empty($ids))
        {
            if(empty($options))
            {
                $colum  = $this->column;
                $values = DB::table($this->gettable())->select($colum)->whereIn('id', $ids)->get();
                foreach($values as $value)
                {
                    $html .= $value->$colum.', ';
                }
            }
            else
            {
                foreach($options as $key => $value)
                {
                    if(in_array($key, $ids))
                        $html .= $value.', ';
                }
            }
            return substr($html, 0, -2);
        }
        
        return $html;
    }
    
    /**
     * funçao que verifica se o campo foi preenchido
     * 
     * @param type $value
     * @return boolean
     */
    public function requiredFieldIsValid()
    {
         //verifica se o campo é obrigatório e foi preenchido
         $value = $this->loadValue();
        if($value && json_decode($value))
        {
            return true;
        }
        return false;
    }
}
