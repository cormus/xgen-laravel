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
class Line extends Field
{
	var $showList = false;
	
	public function tableData($data)
	{
		
	}
	
    public function render($row)
    {
        $html = '<h1>'.$this->getTitle().'</h1>';
		if($this->getSubTitle())  
			$html .= '<span class="subtitle">'.$this->getSubTitle().'</span>';
        return '<div class="width-100-l">'. $html.'</div>';
    }
}
