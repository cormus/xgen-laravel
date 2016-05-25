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
class Int extends Field{
    
    var $mask = null;
    
    public function setMask($data)
    {
        $this->mask =  $data;
    }
    
    public function tableData($table)
    {
        $table->integer($this->getName());
        return $table;
    }
    
    public function render($row)
    {
        $value = '';
        if($row)
        {
            $name = $this->getName();
            $value = $row->$name;
        }
        else if($this->getValue())
        {
            $value = $this->getValue();
        }
        
        $required = '';
        if($this->getRequired())
           $required = '<span class="required">*</span>';
        
        $subTitle = '';
        if($this->getSubTitle())
            $subTitle = '<span class="subtitle">'.$this->getSubTitle().'</span>';
        
        $data = array
        (
            'id' => $this->getName(), 
            'placeholder' => ''
        );
        
        $class = 'form-control';
        if($this->mask)
        {
            switch($this->mask)
            {
                case 'money':
                    $class .= ' money';
                    break;
                default:
                    $class .= ' mask';
                    $data['mask'] = $this->mask;
            }
        }
        $data['class']     = $class;
        $data['onkeydown'] = 'javascript:return numeros(event)';
        
        return '<div class="form-group">
                    '.Form::label($this->getName(), $this->getTitle())
                     .$required
                     .$subTitle
                     .Form::text($this->getName(), $value,  $data).'
                </div>';
    }
}