<?php
abstract class controllerBase {
    
    protected $view;
    
    function __construct()
    {
        $this->view = new View();
    }
}
?>