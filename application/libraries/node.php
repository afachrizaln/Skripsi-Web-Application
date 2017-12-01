<?php 

class Node
{
    public $name;
    public $linked = array();
    public function __construct()
    {

    }

    public function set_name($name){
        $this->name = $name;
    }
    public function link_to(Node $node, $also = true)
    {
        if (!$this->linked($node)) $this->linked[] = $node;
        if ($also) $node->link_to($this, false);
        return $this;
    }
    private function linked(Node $node)
    {
        foreach ($this->linked as $l) { if ($l->name === $node->name) return true; }
        return false;
    }
    public function not_visited_nodes($visited_names)
    {
        $ret = array();
        foreach ($this->linked as $l) {
            if (!in_array($l->name, $visited_names)) $ret[] = $l;
        }
        return $ret;
    }
}

?>