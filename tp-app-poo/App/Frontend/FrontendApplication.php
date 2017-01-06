<?php
namespace App\Frontend;

use \OCFram\Application;

class FrontendApplication extends Application
{
  public function __construct()
  {
    parent::__construct();

    $this->name = 'Frontend';
  }

  public function run()
  {
    $controller = $this->getController();
    $page = $controller->page();
    
    $this->httpResponse->setPage($page);
    
    if (($content = $controller->cache()->readView($this->name.'_'.$controller->module().'_'.$controller->action())) !== null)
    {
      $page->setContent($content);
      $wholePage = $page->getGeneratedPage();
    }
    else
    {
      $controller->execute();
      $wholePage = $page->getGeneratedPage();
      
      if (is_callable([$controller, 'createCache']))
      {
        $cache = $controller->createCache();
        
        if (array_key_exists($controller->action(), $cache))
        {
          $controller->cache()->writeView($this->name.'_'.$controller->module().'_'.$controller->action(), $page->content(), $cache[$controller->action()]);
        }
      }
    }
    
    $this->httpResponse->send($wholePage);
  }
}