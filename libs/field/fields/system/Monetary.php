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
class Monetary extends Field{
    
    var $mask = null;
    
    public function loadValue()
    {
        //se existir um submit no formulário retorna o valor do forulário
        $value = Input::get($this->getName(), 0);
        $value = str_replace(array('R$ ','.', ' ', ','), array('', '', '', '.'), $value);
        return floatval($value);
    }
    
    public function setMask($data)
    {
        $this->mask =  $data;
    }
    
    public function tableData($table)
    {
        $table->double($this->getName());
        return $table;
    }
    
    public function render($row)
    {
        $value = 0;
        if($row)
        {
            $name = $this->getName();
            $value = $row->$name;
        }
        else if($this->getValue())
        {
            $value = $this->getValue();
        }
        
        $value = 'R$ '.number_format($value, 2, ',', '.');
        
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
        
        $class = 'form-control maskMoney';
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
        //$data['onkeydown'] = 'javascript:return numeros(event)';
        
        $data['data-affixes-stay'] = 'true';
        $data['data-prefix']       = 'R$ ';
        $data['data-thousands']    = '.';
        $data['data-decimal']      = ',';
        
        
        return '<div class="form-group">
                    '.Form::label($this->getName(), $this->getTitle())
                     .$required
                     .$subTitle
                     .Form::text($this->getName(), $value,  $data).'
                </div>';
    }
    
     /**
     * Faz o tratamento dos dados para serem mostrados na listagem
     * 
     * @param object $row
     * @return string
     */
    public function treatmentValue($row)
    {
        $name = $this->getName();
        return 'R$ '.number_format($row->$name, 2, ',', '.');
    }
}