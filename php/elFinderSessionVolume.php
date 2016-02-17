<?php
/**
 * Created by Claudio Eterno.
 * Date: 17/01/16
 * Time: 8.15
 */ 
class elFinderSessionVolume  {

    /**
     * @var string the cache key 
     */
    private $cacheKey;
    
    /**
     * @var string the volumes key 
     */
    private $volKey;

    /**
     * elFinderSession constructor.
     */
    /**
     * elFinderSessionVolume constructor.
     * @param $cache_prefix
     * @param $id
     */
    public function __construct($cache_prefix, $id)
    {
        $this->cacheKey = $cache_prefix;
        $this->volKey = $id;
        if( !isset($_SESSION[$this->cacheKey]) ){
            $_SESSION[$this->cacheKey] = array();
        }
        if( !isset($_SESSION[$this->cacheKey][$id]) ){
            $_SESSION[$this->cacheKey][$id] = array();
        }
    }
    
    public function __unset($key){
        unset($_SESSION[$this->cacheKey][$this->volKey][$key]);
    }
    
    public function __isset($name)
    {
        return isset($_SESSION[$this->cacheKey][$this->volKey][$name]);
    }
    
    public function __get($name)
    {
        if( isset($_SESSION[$this->cacheKey][$this->volKey][$name]) ){
            return $_SESSION[$this->cacheKey][$this->volKey][$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $_SESSION[$this->cacheKey][$this->volKey][$name] = $value;
    }
    
    public function clean(){
        $_SESSION[$this->cacheKey][$this->volKey] = array();
    }


}
