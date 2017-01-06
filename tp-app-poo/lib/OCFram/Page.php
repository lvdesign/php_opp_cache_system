<?php
namespace OCFram;

class Page extends ApplicationComponent
{
  protected $content = null;
  protected $contentFile;
  protected $vars = [];

  public function addVar($var, $value)
  {
    if (!is_string($var) || is_numeric($var) || empty($var))
    {
      throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
    }

    $this->vars[$var] = $value;
  }

  public function getGeneratedPage()
  {
    if (!file_exists($this->contentFile))
    {
      throw new \RuntimeException('La vue spécifiée n\'existe pas');
    }
    
    $user = $this->app->user();

    extract($this->vars);

    if ($this->content === null)
    {
      ob_start();
        require $this->contentFile;
      $this->content = ob_get_clean();
    }

    $content = $this->content;
    
    ob_start();
      require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php';
    return ob_get_clean();
  }
  
  public function content()
  {
    return $this->content;
  }
  
  public function setContent($content)
  {
    if (!is_string($content))
    {
      throw new \InvalidArgumentException('Le contenu de la page doit être une chaine de caractères');
    }
    
    $this->content = $content;
  }

  public function setContentFile($contentFile)
  {
    if (!is_string($contentFile) || empty($contentFile))
    {
      throw new \InvalidArgumentException('La vue spécifiée est invalide');
    }

    $this->contentFile = $contentFile;
  }
}