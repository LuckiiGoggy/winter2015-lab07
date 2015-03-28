<?php

/**
 * This is a "CMS" model for quotes, but with bogus hard-coded data.
 * This would be considered a "mock database" model.
 *
 */
class Orders extends CI_Model {

    protected $xml = null;
    
    protected $orderName;
    protected $orderType;
    protected $customer;
    protected $orderTotal = 0.00;
    protected $burgers = array();
    protected $burgerCount = 0;
    

    // Constructor
    public function __construct() {
        parent::__construct();

    }
    
    function init($filename){
        $this->xml = simplexml_load_file(DATAPATH . $filename);
        $this->load->model("Menu", "menu");

        $this->orderName = substr($filename, 0, strlen($filename) - strlen('.xml'));
        $this->orderType = (string)$this->xml['type'];
        $this->customer = (string)$this->xml->customer;
        
        //build a full list of burgers - approach 2
        foreach ($this->xml->burger as $burger) {
            $record = new stdClass();
            
            $record->id         = ++$this->burgerCount;
            $record->patties    = $this->getContents($burger->patty, 'type');
            $record->topCheeses = $this->getContents($burger->cheeses, 'top');
            $record->botCheeses = $this->getContents($burger->cheeses, 'bottom');
            $record->toppings   = $this->getContents($burger->topping, 'type');
            $record->sauces     = $this->getContents($burger->sauce, 'type');
            
            $record->price      = $this->calculateTotalPrice($record);
            
            $this->orderTotal += $record->price;
            
            array_push($this->burgers, $record);
        }
        
    }

    function getContents($data, $tag){
        $output = array();
        foreach($data as $dat){
            array_push($output, (string)$dat[$tag]);
        }
        return $output;
    }
    
    function calculateTotalPrice($burger){
        $price = 0;
        
        $price += $this->calculateContentPrice($burger->patties,    'getPatty');
        $price += $this->calculateContentPrice($burger->topCheeses, 'getCheese');
        $price += $this->calculateContentPrice($burger->botCheeses, 'getCheese');
        $price += $this->calculateContentPrice($burger->toppings,   'getTopping');
        $price += $this->calculateContentPrice($burger->sauces,     'getSauce');
        
        return $price;
    }
    
    function calculateContentPrice($type, $func){    
        $price = 0;
        foreach($type as $content){
            if(($val = $this->menu->$func($content)) != null) 
                $price += $val->price;
        }
        return $price;
    }
    
    
    
    // retrieve a list of burgers, to populate a dropdown, for instance
    function burgers() {
        return $this->burgers;
    }
    
    // retrieve a list of burgers, to populate a dropdown, for instance
    function burgersAsStrings() {
        $burgers_str = array();
        
        foreach($this->burgers as $burger){
            $burger_str = new stdClass();
            $cheeses = array_merge($burger->topCheeses, $burger->botCheeses);

            
            $burger_str->id = $burger->id;
            $burger_str->patty = $this->buildList($burger->patties, "getPatty");
            $burger_str->topCheese = $this->buildList($burger->topCheeses, "getCheese", false, "(top)");
            $burger_str->botCheese = $this->buildList($burger->botCheeses, "getCheese", true, "(bottom)");
            $burger_str->topping = $this->buildList($burger->toppings, "getTopping");
            $burger_str->sauce = $this->buildList($burger->sauces, "getSauce");
            
            $burger_str->pricelabel = "Burger Total: ";
            $burger_str->price = "$" . $burger->price;
            
            array_push($burgers_str, $burger_str);
        }
        
        
        return $burgers_str;
    }
    
    function buildList($list, $func, $snip = true, $extras = ""){
        $string = "";
        
        if(count($list) < 1) return "none";
        
        foreach($list as $obj){
            if(($val = $this->menu->$func($obj)) != null) 
                $string .= "" . $val->name . $extras . ", ";
        }
        
        return substr($string, 0, $snip ? strlen($string) - 2 : strlen($string));
    }
    
    function getOrderInfo(){
        return array(
            'orderName'     => $this->orderName,
            'orderType'     => $this->orderType,
            'orderTotal'     => $this->orderTotal,
            'customerName'  => $this->customer,
            'burgers'       => $this->burgersAsStrings()
        );
    }

}
