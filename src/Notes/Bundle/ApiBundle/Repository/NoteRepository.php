<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Notes\Bundle\ApiBundle\Entity\User;
use Notes\Bundle\ApiBundle\Entity\Note;
use Doctrine\ORM\Query\Expr;

/**
 * Repository to manage {@link Note}.
 *
 * @author Florian Voutzinos <florian@voutzinos.com>
 */
class NoteRepository extends EntityRepository
{
    /**
     * Finds notes by user (sorted by modification date).
     *
     * @param User   $user       The user
     * @param string $search     A search string to search by note title
     * @param mixed  $maxResults The maximum number of results
     *
     * @return Note[] An array of notes
     */
    public function findByUser(User $user, $search = null, $maxResults = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('n')
            ->from('NotesApiBundle:Note', 'n')
            ->innerJoin('n.user', 'u', Expr\Join::WITH, 'u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('n.modifiedDate', 'DESC');

        if (null !== $search) {
            $qb->where($qb->expr()->like('n.title', ':search'));
            $qb->setParameter('search', $search . '%');
        }

        if (null !== $maxResults) {
            $qb->setMaxResults($maxResults);
        }

        return $qb->getQuery()->getResult();
    }
}
