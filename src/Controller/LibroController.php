<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Entity\UsuarioLibros;
use App\Form\LibroType;
use App\Repository\UsuarioRepository;
use App\Repository\LibroRepository;
use App\Repository\UsuarioLibrosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Files;

class LibroController extends AbstractController
{


    private $entityManager;
    private $usuario;
    private $file;
    private $libro;
    private $usuarioLibros;

    public function __construct(UsuarioLibrosRepository $usuarioLibros, LibroRepository $libro, EntityManagerInterface $entityManager, UsuarioRepository $usuario, Files $file)
    {
      $this->usuarioLibros  = $usuarioLibros;
      $this->usuario        = $usuario;
      $this->libro          = $libro;
      $this->entityManager  = $entityManager;
      $this->file           = $file;
    }

    /**
     * @Route("/libro", name="libro")
     */
    public function index(): Response
    {
        return $this->render('libro/index.html.twig', [
            'controller_name' => 'LibroController',
        ]);
    }


    /**
     * @Route("/mis_libros/{currentPage}", name="mis_libros", defaults={"currentPage"=1})
     */
    public function misLibros($currentPage)
    {
      $limit = 1;
      $libro                       = $this->usuarioLibros->findLibro(1,$currentPage,$limit);
      $libroResultado              = $libro['paginator'];
      $libroQueryCompleta          = $libro['query'];
      $totalPorPagina              = count($libroQueryCompleta);
      $maxPages                    = ceil($libroResultado / $limit);
      $maxPages > 5 ? $numberPages = 5 : $numberPages = $maxPages;
      if ($currentPage > 5) {
          $first_page  = $currentPage - $numberPages;
          $numberPages = $currentPage;
      } else {
          $first_page = 1;
      }
      return $this->render('libro/ver_mis_libros.html.twig', [
         'libro'       => $libroQueryCompleta,
         'resultados'  => $libroResultado,
         'maxPages'    => $maxPages,
         'thisPage'    => $currentPage,
         'rowPage'     => $totalPorPagina,
         'numberPages' => $numberPages,
         'firstPage'   => $first_page,
      ]);
    }

    /**
     * @Route("/crear_libro", name="crear_libro")
     */
    public function crearLibro(Request $request): Response
    {

      $libro_objecto  = new Libro();
      $form  = $this->createForm(LibroType::class,$libro_objecto);
      $form->handleRequest($request);
      if($form->isSubmitted()) {
          try {
            $post              = $request->request->all();
            $files             = $request->files->all();
            $titulo            = $post['libro']['titulo'];
            $cantidad_paginas  = (int)$post['libro']['cantidad_paginas'];
            $descripcion       = $post['libro']['descripcion'];
            $fecha_publicacion = $post['libro']['fecha_publicacion'];
            $idioma            = $post['libro']['idioma'];
            $autor             = $post['libro']['autor'];
            $arrayImages       = ['jpg', 'jpeg', 'png', 'gif'];
            $continue          = false;
            $extension         = $files['libro']['image']->guessExtension();
            if(in_array($extension,$arrayImages)){
              $continue = true;
            }
            if($continue){
              $user  = $this->usuario->find(1);
              $image = $this->file->uploadFile($files['libro']['image'], 'libro_directory');
              $libro_objecto->setTitulo($titulo);
              $libro_objecto->setDescripcion($descripcion);
              $libro_objecto->setIdioma($idioma);
              $libro_objecto->setAutor($autor);
              $libro_objecto->setFechaPublicacion(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
              $libro_objecto->setCantidadPaginas($cantidad_paginas);
              $libro_objecto->setImagen($image);
              $this->entityManager->persist($libro_objecto);
              $this->entityManager->flush();
              if( null != $libro_objecto){
                $UsuarioLibros = new UsuarioLibros();
                $UsuarioLibros->setUsuario($user);
                $UsuarioLibros->setLibro($libro_objecto);
                $this->entityManager->persist($UsuarioLibros);
                $this->entityManager->flush();
                $this->addFlash('success', 'Se ha creado el registro correctamente.');
                return $this->redirectToRoute('crear_libro');
              }
            }else{
              $this->addFlash(
                  'warning',
                  'Ha ingresado una imagen con extensión invalida, formatos permitidos: jpg, jpeg, png, gif. '
              );
            }
          }catch (\Exception $e){
            $this->addFlash(
                'warning',
                'No se ha creado el registro con éxito. '.$e->getmessage()
            );
            return $this->redirectToRoute('crear_libro');
          }catch (\TypeError  $e){
            $this->addFlash(
                'warning',
                'No se ha creado el registro con éxito. '.$e->getmessage()
            );
            return $this->redirectToRoute('crear_libro');
          }
      }
      return $this->render('libro/crear.html.twig', [
          'form'  => $form->createView(),
      ]);
    }



    /**
     * @Route("/eliminar_libro", name="eliminar_libro")
     */
    public function eliminarLibro(Request $request): Response
    {

        if ($request->isXmlHttpRequest()){
        $id_libro         = $request->request->get('id');
        try {
          if($id_libro){
            $libro = $this->usuarioLibros->findBy(['usuario' => 1, 'libro' => $id_libro]);
            $this->entityManager->remove($libro[0]);
            $this->entityManager->flush();
            if(NULL != $libro){
              $status = true;
              return new JsonResponse(array('status' => $status));
            }
          }else{
            $status = false;
            return new JsonResponse(array('status' => $status));
          }
        }catch (\Exception $e){
          $status = false;
          return new JsonResponse(array('status' => $status,'message' => $e->getmessage()));
        }catch (\TypeError  $e){
          $status = false;
          return new JsonResponse(array('status' => $status,'message' => $e->getmessage()));
        }
      }else{
        $status = false;
        return new JsonResponse(array('status' => $status));
      }

    }
}
