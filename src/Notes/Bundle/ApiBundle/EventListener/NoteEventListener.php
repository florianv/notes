<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\EventListener;

use Notes\Bundle\ApiBundle\Entity\Note;

/**
 * Updates the creation and modification dates of a {@link Note}.
 *
 * @author Florian Voutzinos <florian@voutzinos.com>
 */
class NoteEventListener
{
    public function prePersist(Note $note)
    {
        $now = new \DateTime();

        if (null === $note->getCreatedDate()) {
            $note->setCreatedDate($now);
        }

        if (null === $note->getModifiedDate()) {
            $note->setModifiedDate($now);
        }
    }

    public function preUpdate(Note $note)
    {
        $note->setModifiedDate(new \DateTime());
    }
}
