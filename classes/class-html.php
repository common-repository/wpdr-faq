<?php

class HTMLBuilder
{

    private $start;
    private $end;
    //private $temp;

    /**
     * Create the element
     * @param array $arguments It can contain the css styling or normal HTML arguments like cellpadding, cellspacing,
     * border, margin, padding, width etc
     * @return void
     */

    public function create_element($arguments)
    {
        if(!empty($arguments)):
            $this->start = '<' . $arguments['element'];
            if(!empty($arguments['attributes'])):
                foreach($arguments['attributes'] as $name => $value):
                    $this->start .= ' ' . $name . '="' . $value . '"';
                endforeach;
            endif;
            $this->start .= '>';
            $this->end .= '</' . $arguments['element'] . '>';
        endif;
    }

    /**
     * Add a new child to the parent
     * @param string $to_add Name of the element
     * @param array $arguments
     * @param bool $content
     */

    public function add_child($to_add, $arguments = array(), $content = false)
    {
        $this->start .= '<' . $to_add;
        if(!empty($arguments)):
            foreach($arguments as $name => $value):
                $this->start .= ' ' . $name . '="' . $value . '"';
            endforeach;
        endif;
        $this->start .= '>';
        if($content) $this->start .= $content;
    }

    /**
     * Close a child element
     * @param $to_add Name of the element
     */

    public function close_child($to_add)
    {
        $this->start .= '</' . $to_add . '>';
    }

    /**
     * Return the constructed element by mergin the $start and the $end variables
     * @return string
     */

    public function return_element()
    {
        return $this->start . $this->end;
    }

}

?>