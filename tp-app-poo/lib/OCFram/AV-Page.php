<?php
namespace OCFram;

class Page extends ApplicationComponent
{
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

    ob_start();
      require $this->contentFile;
    $content = ob_get_clean();

    ob_start();
      require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php';
    return ob_get_clean();
  }
  /*
  //https://openclassrooms.com/forum/sujet/mooc-programmez-en-oriente-objet-en-php?page=5
  public function getGeneratedPage()
   {
     if (!file_exists($this->contentFile))
     {
       throw new \RuntimeException('La vue spécifiée n\'existe pas');
     }
   
     extract($this->vars);
   
     ob_start();
   
     // appelle à la methode cacheView
     $c = new Cache($this->view);
     // arrange toi pour que si la page expire tu renvoie null
     if(null === $c->cacheView())
         require $this->contentFile;
     $content = ob_get_clean();
     ob_start();
       require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php';
     return ob_get_clean();
   }
  */

  public function setContentFile($contentFile)
  {
    if (!is_string($contentFile) || empty($contentFile))
    {
      throw new \InvalidArgumentException('La vue spécifiée est invalide');
    }

    $this->contentFile = $contentFile;
  }
}