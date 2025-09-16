<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CustomProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomProductCategory>
 */
class CustomProductCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomProductCategory::class);
    }

    /**
     * @return CustomProductCategory[] Returns an array of CustomProductCategory objects
     */
    public function findActiveCategories(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CustomProductCategory[] Returns an array of root categories
     */
    public function findRootCategories(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.parent IS NULL')
            ->andWhere('c.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CustomProductCategory[] Returns an array of child categories
     */
    public function findChildCategories(CustomProductCategory $parent): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.parent = :parent')
            ->andWhere('c.isActive = :active')
            ->setParameter('parent', $parent)
            ->setParameter('active', true)
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneBySlug(string $slug): ?CustomProductCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return CustomProductCategory[] Returns a hierarchical tree of categories
     */
    public function findHierarchicalTree(): array
    {
        $rootCategories = $this->findRootCategories();
        $tree = [];

        foreach ($rootCategories as $rootCategory) {
            $tree[] = $this->buildCategoryTree($rootCategory);
        }

        return $tree;
    }

    private function buildCategoryTree(CustomProductCategory $category): array
    {
        $children = $this->findChildCategories($category);
        $categoryData = [
            'category' => $category,
            'children' => []
        ];

        foreach ($children as $child) {
            $categoryData['children'][] = $this->buildCategoryTree($child);
        }

        return $categoryData;
    }
}
