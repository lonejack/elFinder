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
        $this->rebase();
    }
    
    public function rebase(){
        $x = explode('/',$this->actualPath);
        //$z = array_pop($x);
        $start = &$_SESSION;
        foreach($x as $sub){
            if( !isset($start[$sub])){
                $start[$sub] = array();
            }
            $start = &$start[$sub];
        }
        $this->start = &$start;
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

    /**
     * Get all data of the namespace
     * @return array
     */
    public function getArray(){
        $tempData = $this->start;
        return $tempData;
    }

    /**
     * Exchange all data on namespace
     * @param array $data
     * @return $this
     */
    public function exchangeArray(array $data){
        $this->clean();
        foreach($data as $key => $value ){
            $this->start[$key] = $value;
        }        
        return $this;
    }

    /**
     * Clean the namespace
     */
    public function clean(){
        foreach($this->start as $key => $value ){
            unset($this->start[$key]);
        }
    }


}
