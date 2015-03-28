<?php

/**
 * Our homepage. Show the most recently added quote.
 * 
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct()
    {
	parent::__construct();
    }

    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
        $this->load->model('Orders', 'orders');
	// Build a list of orders
        $this->load->helper('directory');
        $this->load->model('Menu');
	$map = directory_map('./data/', FALSE, TRUE);
        $orderlist = array();
        $test = "order";
        
        
        foreach($map as $file){
            if((substr_compare($file, $test, 0, strlen($test)) === 0)){
                $this->orders->init($file);
                $temp = $this->orders->getOrderInfo();
                array_push($orderlist, $temp);
            }
        }
        $this->data['orders'] = $orderlist;
        
        
	// Present the list to choose from
	$this->data['pagebody'] = 'homepage';
        
        
	$this->render();
    }
    
    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename)
    {
	// Build a receipt for the chosen order
        $this->load->model('Orders', 'orders');
        $this->orders->init($filename);
        
        $orderInfo = $this->orders->getOrderInfo();
        
        $this->data['orderTotal'] = $orderInfo['orderTotal'];
        $this->data['orderName'] = $orderInfo['orderName'];
        $this->data['ordertype'] = $orderInfo['orderType'];
        $this->data['customer'] = $orderInfo['customerName'];
        
        $this->data['burgerlist'] = $this->parser->parse('_burger', $orderInfo, true);
        
        
	// Present the list to choose from
	$this->data['pagebody'] = 'justone';
	$this->render();
    }
    

}
