<?php
/**
 * Created by Claudio Eterno.
 * Date: 17/01/16
 * Time: 8.15
 */ 
class elFinderSessionNamespace  {

    /**
     * @var array the volumes key 
     */
    private $start;

    /**
     * @var string the actual path
     */
    private $actualPath;

    /**
     * elFinderSession constructor.
     */
    /**
     * elFinderSessionNamespace constructor.
     * @param $cache_prefix
     * @param $id
     */
    public function __construct($path)
    {
        $this->actualPath = $path;
        $x = explode('/',$path);
        $z = array_pop($x);
        $start = &$_SESSION;
        if( count($x) == 0 ){
            if( isset($start[$z])){
                $this->start = &$start[$z];
                return;
            }
        }
        else {
            foreach($x as $sub){
                if( !isset($start[$sub])){
                    $start[$sub] = array();
                }
                $start = &$start[$sub];
            }
            
        }
        $start[$z] = array();
        $this->start = &$start[$z];
         
    }
    
    public function __unset($key){
        unset($this->start[$key]);
    }
    
    public function __isset($name)
    {
        return isset($this->start[$name]);
    }
    
    public function __get($name)
    {
        if( isset($this->start[$name]) ){
            return $this->start[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $this->start[$name] = $value;
    }
    
    public function getData(){
        return $this->start;
    }
    
    public function setData(array $data){
        $this->clean();
        foreach( $data  as $key => $value){
            $this->start[$key] = $value;
        }        
        return $this;
    }
    
    public function clean(){
        foreach( $this->start  as $key => $value){
            unset($this->start[$key]);
        }
    }


}
