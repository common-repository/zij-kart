<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class zijkartHTML {

        public function label($text,$for,$attr = array()){
            $html = '<label for="'.$for.'"';
            $this->setAttr($attr,$html);
            $html .= '>'.$text.'</label>';
            return $html;
        }

        public function text($id,$value,$attr = array()){
            $html = '<input type="text" name="'.$id.'" id="'.$id.'"';
            $this->setAttr($attr,$html);
            if(isset($value->$id)){
                $html .= ' value="'.$value->$id.'"';
            }
            $html .= ' />';
            return $html;
        }

        public function textarea($id,$value,$attr = array()){
            $html = '<textarea type="text" name="'.$id.'" id="'.$id.'"';
            $this->setAttr($attr,$html);
            $html .= ' >';
            if(isset($value->$id)){
                $html .= $value->$id;
            }
            $html .= '</textarea>';            
            return $html;
        }

        public function file($id,$attr = array()){
            $html = '<input type="file" name="'.$id.'" id="'.$id.'"';
            $this->setAttr($attr,$html);
            $html .= ' />';
            return $html;
        }

        public function hidden($id,$value,$attr = array()){
            $html = '<input type="hidden" name="'.$id.'" id="'.$id.'"';
            $this->setAttr($attr,$html);
            if(isset($value->$id))            
                $html .= ' value="'.$value->$id.'" />';
            else
                $html .= ' value="'.$value.'" />';
            return $html;
        }

        public function select($id,$optarray,$value,$attr = array()){
            $html = '<select id="'.$id.'" name="'.$id.'" ';
            $this->setAttr($attr,$html);
            $html .= '>';
            foreach($optarray AS $opt){
                $selected = ((isset($value->$id) ? $value->$id : '') == $opt->value) ? 'selected' : '';
                $html .= '<option value="'.$opt->value.'" '.$selected.'>'.$opt->text.'</option>';
            }
            $html .= '</select>';
            return $html;
        }

        public function statusarray(){
            return array(
                    (object)array('value'=>'','text'=>__('Select Status','zijkart')),
                    (object)array('value'=>'1','text'=>__('Published','zijkart')),
                    (object)array('value'=>'2','text'=>__('Un-published','zijkart')),
                    );

        }

        public function paymentverifiedarray(){
            return array(
                    (object)array('value'=>'','text'=>__('Select payment status','zijkart')),
                    (object)array('value'=>'1','text'=>__('Verified','zijkart')),
                    (object)array('value'=>'2','text'=>__('Not verified','zijkart')),
                    );

        }

        public function yesnoarray(){
            return array(
                    (object)array('value'=>'1','text'=>__('Yes','zijkart')),
                    (object)array('value'=>'0','text'=>__('No','zijkart')),
                    );

        }

        public function discounttypearray(){
            return array(
                    (object)array('value'=>'1','text'=>__('Percentage','zijkart')),
                    (object)array('value'=>'2','text'=>__('Amount','zijkart')),
                    );

        }

        public function submit($title,$attr = array()){
            $html = '<input type="submit" name="submit" value="'.$title.'"';
            $this->setAttr($attr,$html);
            $html .= ' />';
            return $html;
        }

        public function editor($description,$form){
            $value = isset($form->$description) ? $form->$description : '';
            $html = wp_editor($value,$description);
            return $html;
        }

        private function setAttr($attr,&$html){
            if(!empty($attr)){
                foreach($attr AS $k => $v){
                    $html .= " $k=\"$v\"";
                }
            }
        }
    }

?>