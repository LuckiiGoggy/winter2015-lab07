<?php

/**
 * This is a "CMS" model for quotes, but with bogus hard-coded data.
 * This would be considered a "mock database" model.
 *
 * @author jim
 */
class Menu extends CI_Model {

    protected $xml = null;
    protected $patty_names   = array();
    protected $patties       = array();
    protected $cheese_names  = array();
    protected $cheeses       = array();
    protected $topping_names = array();
    protected $toppings      = array();
    protected $sauce_names   = array();
    protected $sauces        = array();
    

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->xml = simplexml_load_file(DATAPATH . 'menu.xml');

        // build the list of patties - approach 1
        foreach ($this->xml->patties->patty as $patty) {
            $this->patty_names[(string) $patty['code']] = (string) $patty;
        }

        // build a full list of patties - approach 2
        foreach ($this->xml->patties->patty as $patty) {
            $record = new stdClass();
            $record->code = (string) $patty['code'];
            $record->name = (string) $patty;
            $record->price = (float) $patty['price'];
            $this->patties[$record->code] = $record;
        }

        // build the list of cheeses - approach 1
        foreach ($this->xml->cheeses->cheese as $cheese) {
            $this->cheese_names[(string) $cheese['code']] = (string) $cheese;
        }

        // build a full list of cheeses - approach 2
        foreach ($this->xml->cheeses->cheese as $cheese) {
            $record = new stdClass();
            $record->code = (string) $cheese['code'];
            $record->name = (string) $cheese;
            $record->price = (float) $cheese['price'];
            $this->cheeses[$record->code] = $record;
        }

        // build the list of cheeses - approach 1
        foreach ($this->xml->toppings->topping as $topping) {
            $this->topping_names[(string) $topping['code']] = (string) $topping;
        }

        // build a full list of cheeses - approach 2
        foreach ($this->xml->toppings->topping as $topping) {
            $record = new stdClass();
            $record->code = (string) $topping['code'];
            $record->name = (string) $topping;
            $record->price = (float) $topping['price'];
            $this->toppings[$record->code] = $record;
        }

        // build the list of cheeses - approach 1
        foreach ($this->xml->sauces->sauce as $sauce) {
            $this->sauce_names[(string) $sauce['code']] = (string) $sauce;
        }

        // build a full list of cheeses - approach 2
        foreach ($this->xml->sauces->sauce as $sauce) {
            $record = new stdClass();
            $record->code = (string) $sauce['code'];
            $record->name = (string) $sauce;
            $record->price = (float) $sauce['price'];
            $this->sauces[$record->code] = $record;
        }
    }

    // retrieve a list of patties, to populate a dropdown, for instance
    function patties() {
        return $this->patty_names;
    }

    // retrieve a patty record, perhaps for pricing
    function getPatty($code) {
        if (isset($this->patties[$code]))
            return $this->patties[$code];
        else
            return null;
    }

    // retrieve a list of cheeses, to populate a dropdown, for instance
    function cheeses() {
        return $this->cheese_names;
    }

    // retrieve a cheese record, perhaps for pricing
    function getCheese($code) {
        if (isset($this->cheeses[$code]))
            return $this->cheeses[$code];
        else
            return null;
    }

    // retrieve a list of toppings, to populate a dropdown, for instance
    function toppings() {
        return $this->topping_names;
    }

    // retrieve a topping record, perhaps for pricing
    function getTopping($code) {
        if (isset($this->toppings[$code]))
            return $this->toppings[$code];
        else
            return null;
    }

    // retrieve a list of sauces, to populate a dropdown, for instance
    function sauces() {
        return $this->sauce_names;
    }

    // retrieve a sauce record, perhaps for pricing
    function getSauce($code) {
        if (isset($this->sauces[$code]))
            return $this->sauces[$code];
        else
            return null;
    }

}
