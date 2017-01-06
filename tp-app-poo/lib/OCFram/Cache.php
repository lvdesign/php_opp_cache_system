<?php
namespace OCFram;

class Cache
{
  protected $dataFolder;
  protected $viewFolder;
  
  public function __construct($dataFolder, $viewFolder)
  {
    $this->setDataFolder($dataFolder);
    $this->setViewFolder($viewFolder);
  }
  
  public function delete($key)
  {
    unlink($this->dataFolder.'/'.$key);
  }
  
  public function deleteView($view)
  {
    unlink($this->viewFolder.'/'.$view);
  }
  
  public function read($key)
  {
    $content = $this->readFile($this->dataFolder.'/'.$key);
    
    return $content === null ? null : unserialize($content);
  }
  
  public function readFile($filename)
  {
    if (!file_exists($filename)) return null;
    
    $file = fopen($filename, 'r');
    $content = '';
    
    $expires = (int) fgets($file);
    
    if ($expires < time())
    {
      fclose($file);
      unlink($filename);
      return null;
    }
    
    while (($buffer = fgets($file)) !== false)
    {
      $content .= $buffer;
    }
    
    fclose($file);
    
    return $content;
  }
  
  public function readView($view)
  {
    return $this->readFile($this->viewFolder.'/'.$view);
  }
  
  public function write($key, $value, $duration)
  {
    $this->writeFile($this->dataFolder.'/'.$key, serialize($value), $duration);
  }
  
  public function writeFile($filename, $content, $duration)
  {
    $interval = \DateInterval::createFromDateString($duration);
    $expires = (new \DateTime)->add($interval)->getTimestamp();
    
    file_put_contents($filename, $expires."\n".$content);
  }
  
  public function writeView($name, $view, $duration)
  {
    $this->writeFile($this->viewFolder.'/'.$name, $view, $duration);
  }
  
  public function dataFolder()
  {
    return $this->dataFolder;
  }
  
  public function viewFolder()
  {
    return $this->viewFolder;
  }
  
  public function setDataFolder($dataFolder)
  {
    if (!is_dir($dataFolder))
    {
      throw new \InvalidArgumentException('Le dossier spécifié pour le cache des données est invalide');
    }
    
    $this->dataFolder = $dataFolder;
  }
  
  public function setViewFolder($viewFolder)
  {
    if (!is_dir($viewFolder))
    {
      throw new \InvalidArgumentException('Le dossier spécifié pour le cache des vues est invalide');
    }
    
    $this->viewFolder = $viewFolder;
  }
}