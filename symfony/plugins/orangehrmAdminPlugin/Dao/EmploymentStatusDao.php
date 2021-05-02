<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace OrangeHRM\Admin\Dao;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use OrangeHRM\Admin\Dto\EmploymentStatusSearchFilterParams;
use OrangeHRM\Entity\EmploymentStatus;
use OrangeHRM\ORM\Doctrine;
use \DaoException;
use \Exception;

class EmploymentStatusDao
{
    /**
     * @param $id
     * @return object|null
     * @throws DaoException
     */
    public function getEmploymentStatusById(int $id): EmploymentStatus
    {
        try {
            return Doctrine::getEntityManager()->getRepository(EmploymentStatus::class)->find($id);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param EmploymentStatus $employmentStatus
     * @return EmploymentStatus
     * @throws DaoException
     */
    public function saveEmploymentStatus(EmploymentStatus $employmentStatus): EmploymentStatus
    {
        try {
            Doctrine::getEntityManager()->persist($employmentStatus);
            Doctrine::getEntityManager()->flush();
            return $employmentStatus;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * @param array $toBeDeletedEmploymentStatusIds
     * @return int
     * @throws DaoException
     */
    public function deleteEmploymentStatus(array $toBeDeletedEmploymentStatusIds): int
    {
        try {
            $q = Doctrine::getEntityManager()->createQueryBuilder();
            $q->delete(EmploymentStatus::class, 'es')
                ->where($q->expr()->in('es.id', ':ids'))
                ->setParameter('ids', $toBeDeletedEmploymentStatusIds);
            return $q->getQuery()->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Search Employment Statuses
     *
     * @param EmploymentStatusSearchFilterParams $employmentStatusSearchParams
     * @return array
     * @throws DaoException
     */
    public function searchEmploymentStatus(EmploymentStatusSearchFilterParams $employmentStatusSearchParams): array
    {
        try {
            $q = $this->_buildSearchQuery($employmentStatusSearchParams);
            return $q->getQuery()->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param EmploymentStatusSearchFilterParams $employmentStatusSearchParams
     * @return QueryBuilder
     */
    private function _buildSearchQuery(EmploymentStatusSearchFilterParams $employmentStatusSearchParams): QueryBuilder
    {
        $q = Doctrine::getEntityManager()->getRepository(
            EmploymentStatus::class
        )->createQueryBuilder('es');

        if (!is_null($employmentStatusSearchParams->getSortField())) {
            $q->addOrderBy($employmentStatusSearchParams->getSortField(), $employmentStatusSearchParams->getSortOrder());
        }
        if (!empty($employmentStatusSearchParams->getLimit())) {
            $q->setFirstResult($employmentStatusSearchParams->getOffset())
                ->setMaxResults($employmentStatusSearchParams->getLimit());
        }

        if (!empty($employmentStatusSearchParams->getName())) {
            $q->andWhere('es.name = :name');
            $q->setParameter('name', $employmentStatusSearchParams->getName());
        }
        return $q;
    }

    /**
     * Get Employment Statuses
     *
     * @return EmploymentStatus[]
     * @throws \OrangeHRM\Core\Exception\DaoException
     */
    public function getEmploymentStatuses(): array
    {
        try {
            return Doctrine::getEntityManager()->getRepository(
                EmploymentStatus::class
            )->findAll();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get Count of Search Query
     *
     * @param EmploymentStatusSearchFilterParams $employmentStatusSearchParams
     * @return int
     * @throws DaoException
     */
    public function getSearchEmploymentStatusesCount(EmploymentStatusSearchFilterParams $employmentStatusSearchParams): int
    {
        try {
            $q = $this->_buildSearchQuery($employmentStatusSearchParams);
            $paginator = new \OrangeHRM\ORM\Paginator($q);
            return $paginator->count();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
