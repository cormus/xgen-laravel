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
class Editor extends Field{
    
    public function tableData($table)
    {
        $table->text($this->getName());
        return $table;
    }
    
    public function render($row)
    {
        $value = '';
        if($row)
        {
            $name = $this->getName();
            $this->setValue($row->$name);
        }
        
        $required = '';
        if($this->getRequired())
           $required = '<span class="required">*</span>';
        
        $subTitle = '';
        if($this->getSubTitle())
            $subTitle = '<span class="subtitle">'.$this->getSubTitle().'</span>';
        
         $html = '<div class="form-group">
                    '.Form::label($this->getName(), $this->getTitle())
                     .$required
                     .$subTitle
                     .Form::textarea($this->getName(), $this->getValue(),  array('class' => 'form-control tinyMCE tinymce-editor', 'id' => $this->getName(), 'placeholder' => '')).'
                 </div>';
		
		$html .= '<!-- Place inside the <head> of your HTML -->
					<script type="text/javascript" src="bawer/tinymce_4.1.9/tinymce.min.js"></script>
					<script type="text/javascript">
						 tinymce.init({
								selector: ".tinymce-editor",
								theme: "modern",
								width: "100%",
								height: "300",
								link_list: [
									{title: "My page 1", value: "http://www.tinymce.com"},
									{title: "My page 2", value: "http://www.tecrail.com"}
								],
								plugins: [
									 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
									 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking spellchecker",
									 "table contextmenu directionality emoticons paste textcolor responsivefilemanager"
							   ],
								relative_urls: false,
								browser_spellcheck : true ,
								filemanager_title:"Responsive Filemanager",
								external_filemanager_path:"bawer/tinymce_4.1.9/plugins/filemanager/",
								external_plugins: { "filemanager" : "plugins/filemanager/plugin.min.js"},

							   image_advtab: true,
							   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
							   toolbar2: "| responsivefilemanager | image | media | link unlink anchor | print preview code  | forecolor backcolor"
						 });
					</script>';
		
		return $html;
    }
	
	public function treatmentValue($row)
    {
        $name  = $this->getName();
		$value = $this->applyStringLimits(strip_tags($row->$name), 100);
        if($value)
        {
			return $value;
        }
		else
		{
			return '-';
		}
    }
	
	function applyStringLimits($string, $max_size)
	{
			$string = trim($string);

			if ($max_size != 0) {
				if (strlen($string) > $max_size) {
					$words = explode(' ', $string);
					array_pop($words);

					while (strlen(implode(' ', $words)) > $max_size) {
						array_pop($words);
					}

					$string = implode(' ', $words) . '...';
				}
			}

		return $string;
	}
}
