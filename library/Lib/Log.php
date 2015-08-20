<?php 

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Lib_Log {
  protected $log;
  function __construct()
  {
    $this->log = new Logger('beiquan');
    $file = '/var/log/beiquan.log.'.date('Ymd');
    $handler = new StreamHandler($file, Logger::DEBUG);
    $this->log->pushHandler($handler);
  }
  private function process($level, $data)
  {
    $caller = next(debug_backtrace());
    $funcName = 'add'.ucfirst($level);
    $data[1]['file'] = $caller['file'];
    $data[1]['line'] = $caller['line'];
    $this->log->$funcName($data[0], $data[1]);
  }
  public static function __callStatic($method, $parameters)
  {
    if ( !in_array($method, ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency']) ) {
      throw new \UnexpectedValueException("Log level [$method] does not exist!");
    } else {
      $log = new Lib_Log;
      $log->process($method, $parameters);
    }
  }
}