<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class LectureRepository extends EntityRepository
{
    /**
     * @param Entity\Field $field
     * @param Entity\Tag $tag
     * @param Entity\Event $event
     * @param Entity\Lecturer $lecturer
     * @return \Doctrine\ORM\Query
     */
    public function findByFilters(
        Entity\Field $field = null,
        Entity\Tag $tag = null,
        Entity\Event $event = null,
        Entity\Lecturer $lecturer = null
    ) {
        $qb = $this
            ->createQueryBuilder('lecture')
            ->innerJoin('lecture.event', 'event')
            ->orderBy('event.date', 'DESC')
        ;

        if ($tag) {
            $qb->andWhere(':tag MEMBER OF lecture.tags')->setParameter('tag', $tag->getId());
        }
        if ($field) {
            $qb->andWhere($qb->expr()->eq('lecture.field', $field->getId()));
        }
        if ($event) {
            $qb->andWhere($qb->expr()->eq('lecture.event', $event->getId()));
        }
        if ($lecturer) {
            $qb->andWhere($qb->expr()->eq('lecture.lecturer', $lecturer->getId()));
        }

        return $qb->getQuery();
    }

    /** @return Entity\Lecture */
    public function getRandom()
    {
        $ids = array_column(
            $this->createQueryBuilder('lecture')->select('lecture.id')->getQuery()->getArrayResult(),
            'id'
        );

        return $this->find($ids[array_rand($ids)]);
    }
}
