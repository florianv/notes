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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="Notes\Bundle\ApiBundle\Repository\NoteRepository")
 * @ORM\Table(name="notes_note")
 * @ORM\EntityListeners({"Notes\Bundle\ApiBundle\EventListener\NoteEventListener"})
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", columnDefinition="TEXT NOT NULL")
     */
    private $content;

    /**
     * @Assert\NotNull
     * @JMS\Exclude
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $modifiedDate;

    /**
     * Sets the content.
     *
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Gets the content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the user.
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Gets the user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the title.
     *
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Gets the title.
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
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
     * Gets the id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the modified date.
     *
     * @param \DateTime $modifiedDate
     */
    public function setModifiedDate(\DateTime $modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
    }

    /**
     * Gets the modified date.
     *
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * Sets the creation date.
     *
     * @param \DateTime $createdDate
     */
    public function setCreatedDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Gets the creation date.
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }
}
