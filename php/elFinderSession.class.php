<?php
/**
 * Created by Claudio Eterno.
 * Date: 17/01/16
 * Time: 8.15
 */ 

include_once 'elFinderSessionNamespace.class.php';

class elFinderSession  {

    /**
     * Singleton instance
     *
     * @var elFinderSession
     */
    protected static $_instance = null;

    /**
     * Is session closed
     *
     * @var bool
     */
    private $sessionClosed = false;

    /**
     * @var string the cache key 
     */
    private $cacheKey;
    
    /**
     * @var string the volumes key 
     */
    private $volKey;
    
    /**
     * @var array volumes created
     */
    private $volumes = array();

    /**
     * @var array static namespaces requested
     */
    private $namespaces = array();

    /**
     * @var array namespaces alias
     */
    private $alias = array();

    /**
     * elFinderSession constructor.
     */
    protected function __construct()
    {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {}



    /**
     * Returns an instance of Zend_Auth
     *
     * Singleton pattern implementation
     *
     * @return elFinderSession Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @param $ns
     * @return elFinderSessionNamespace
     */
    public function getNamespace($ns){
        if( isset($this->alias[$ns])){
            $ns = $this->alias[$ns];
        }
        if( isset($this->namespaces[$ns]) ){
            return $this->namespaces[$ns];
        }
        $this->namespaces[$ns] = new elFinderSessionNamespace($ns);
        return $this->namespaces[$ns];        
    }

    /**
     * Session Start default method
     */
    public function start(){
        return @session_start(); 
    }

    /**
     * array of alias
     * @param array $alias
     * @return $this
     */
    public function setAlias(array $alias){
        $this->alias = $alias;
        return $this;
    }

    /**
     * Session Close default method
     * @return $this
     */
    public function writeClose(){
        session_write_close();
        return $this;
    }

    
    /**
     * session_id
     * @return string
     */

    public function session_id(){
        return session_id();
    }
}
