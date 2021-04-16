<?php

namespace App\Repository;

use App\Entity\UsuarioLibros;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsuarioLibros|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsuarioLibros|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsuarioLibros[]    findAll()
 * @method UsuarioLibros[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioLibrosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioLibros::class);
    }

    public function findLibro($usuario = null, $currentPage, $limit)
    {
        $qb    = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select("l.id,l.titulo,l.descripcion,l.autor,l.cantidad_paginas")
            ->from("App\Entity\UsuarioLibros", "ul")
            ->leftJoin('ul.libro', 'l');
        $query = $qb->where("ul.usuario =:usuario")->setParameter('usuario', $usuario);
        $query    = $qb->orderBy("l.titulo","ASC");
        $queryPag = $query->getQuery()->getResult();
        $query    = $qb->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $paginator = count($queryPag);

        return array('paginator' => $paginator, 'query' => $query);
    }
}
