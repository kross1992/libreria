<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Files
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
      $this->container = $container;
    }

    //Retorna el nombre del archivo guardado en la ruta
    public function uploadFile($file, $folder)
    {
      $fileName = md5(uniqid()) . '.' . $file->guessExtension();
      $file->move($this->container->getParameter($folder), $fileName);
      return $fileName;
    }

    //Funcion para convertir el nombre del archivo y enviarlo a la carpeta destino
    public function uploadFileUpdate($new_file, $old_file, $folder)
    {
        $fileName = md5(uniqid()) . '.' . $new_file->guessExtension();
        $new_file->move($this->container->getParameter($folder), $fileName);
        if ($old_file != null) {
            // Everything for owner, read and execute for others
            chmod($this->container->getParameter($folder) . '/' . $old_file, 0755);
            unlink($this->container->getParameter($folder) . '/' . $old_file);
        }
        return $fileName;
    }

}
