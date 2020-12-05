<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

     /**
      * Requete qui récupère produit en fonction de la saisie de l'utilisateur
      * @return Product[]
    */

    public function findWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('p') /* Tu me créer une query et tu fait mapping avec la table 'p' product*/
            ->select('c','p')/* Je veux que tu me sélectionne la categorie et le produit associé*/
            ->join('p.category','c');/* Fais moi la jointure entre champ categoryy de product et la table category*/

            //SI une categorie est coché j'ai besoin que son ID soit le parametre where de ma requete
            if(!empty($search->categories)){
                $query = $query
                    ->andWhere('c.id IN (:categories)')/* (:categories) est un FLLAG, la categories cochez de l'objet search*/
                    ->setParameter('categories', $search->categories );/* ici je lui dit a quoi corresond le FLAG ligne 38*/
            }

            //SI du texte est tapé dans la barre search
            if(!empty($search->string)){
                $query = $query
                    ->andWhere('p.name LIKE :string')/* (:string est un flag) de l'objet search*/
                    ->setParameter('string', "%{$search->string}%" );/* ici je lui dit a quoi corresond categories le flag et avec
            les "%{}%" je lui demande une recherche partielle et non exacte*/
            }

            //Donne moi la query et execute là.
            return $query->getQuery()->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
