<?php
/**
 * Created by Claudio Eterno.
 * Date: 17/01/16
 * Time: 8.15
 */ 

include_once 'elFinderSessionVolume.php';

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
     * @var bool the init flag
     */
    private $_init = false;

    /**
     * @var array volumes created
     */
    private $volumes = array();

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
    
    public function init() {
        if( $this->_init )
            return;
        
        if( !isset($this->volKey) || !isset($this->cacheKey)){
            throw new Exception("Session invalid");
        }
        if( !isset($_SESSION[$this->cacheKey]) )  {
            $_SESSION[$this->cacheKey] = array();
        }
        if( !isset($_SESSION[$this->volKey]) )  {
            $_SESSION[$this->volKey] = array();
        }
        
        $this->_init = true;
    }



    /**
     * Call session_write_close() if session is restarted
     *
     * @return $this
     */
    public function write() {
        if ($this->sessionClosed) {
            session_write_close();
        }
        return $this;
    }

    /**
     * Session Start default method
     */
    public function start(){
        $start = @session_start(); 
        $this->init();
        return $start;
    }

    /**
     * Session Close default method
     * @return $this
     */
    public function writeClose(){
        session_write_close();
        return $this;
    }
    
    public function close(){
        $this->sessionClosed = true;
        return $this;
    }

    /**
     * Check if parameter is present on session
     * @param $key
     * @return bool
     */
    public function __set($key,$value){
        $_SESSION[$this->cacheKey][$key]=$value;
    }

    public function get_id(){
        return session_id();
    }
    
    public function is_closed(){
        return $this->sessionClosed;
    }
    
    public function __unset($name){
        unset($_SESSION[$this->cacheKey][$name]);
    }
    
    public function __isset($name)
    {
        return isset($_SESSION[$this->cacheKey][$name]);
    }
    
    public function __get($name)
    {
        if( isset($_SESSION[$this->cacheKey][$name]) ){
            return $_SESSION[$this->cacheKey][$name];
        }
        return null;
    }

    /**
     * @param $key
     * @return elFinderSession
     */    
    public function setCachePrefix($key){
        $this->cacheKey = $key;
        return $this;
    }

    /**
     * @param $key
     * @return elFinderSession
     */
    public function setVolumePrefix($key){
        $this->volKey = $key;
        return $this;
    }
    
    public function session_expires($timeout){
        $keyLast = 'LAST_ACTIVITY';
        if( !isset( $_SESSION[$this->cacheKey][$keyLast] ) ) {
            $_SESSION[$this->cacheKey][$keyLast] = time();
            return false;
        }

        if ( ($timeout > 0) && (time() - $_SESSION[$this->cacheKey][$keyLast] > $timeout) ) {
            return true;
        }
        $_SESSION[$this->cacheKey][$keyLast] = time();
        return false;
    }
    
    public function getNetVolumesData(){
        if( isset($_SESSION[$this->volKey])){
            return $_SESSION[$this->volKey];
        }
        return null;        
    }
    
    public function saveNetVolumesData($data){
        $_SESSION[$this->volKey] = $data;
        return $this;        
    }
    
    public function &getVolume($id, $default = array()) {
        if( !isset($_SESSION[$this->cacheKey][$id]) ){
            $_SESSION[$this->cacheKey][$id] = $default;
        }
        return $_SESSION[$this->cacheKey][$id];        
    }
    
    public function &sessionCache(){
        return $_SESSION[$this->cacheKey];
    }
    
    public function getVolumeSession($id) {
        if( !isset($this->volumes[$id])) {
            $this->volumes[$id] = new elFinderSessionVolume($this->volKey,$id);
        }
        return $this->volumes[$id];
    }
    
    public function hasData(){
        return isset($_SESSION[$this->cacheKey]);
    }
    
    public function clean(){
        $this->volumes = array();
        $_SESSION[$this->cacheKey] = array();
        $_SESSION[$this->volKey] = array();
    }


}
