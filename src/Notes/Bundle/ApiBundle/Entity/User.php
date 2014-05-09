<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notes_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $notes;

    /**
     * Creates a new user.
     */
    public function __construct()
    {
        parent::__construct();
        $this->notes = new ArrayCollection();
    }

    /**
     * Sets the id.
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Sets the notes.
     *
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = new ArrayCollection();
        foreach ($notes as $note) {
            $this->addNote($note);
        }
    }

    /**
     * Adds a note.
     *
     * @param Note $note
     */
    public function addNote(Note $note)
    {
        $this->notes[] = $note;
    }

    /**
     * Removes a note.
     *
     * @param Note $note
     */
    public function removeNote(Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Gets the notes.
     *
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
