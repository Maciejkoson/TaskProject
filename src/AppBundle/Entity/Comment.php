<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CommentRepository")
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="recordTime", type="datetime")
     */
    private $recordTime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Task", inversedBy="comments")
     */
    private $task;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set recordTime
     *
     * @param \DateTime $recordTime
     * @return Comment
     */
    public function setRecordTime($recordTime)
    {
        $this->recordTime = $recordTime;

        return $this;
    }


    /**
     * Get recordTime
     *
     * @return \DateTime
     */
    public function getRecordTime()
    {
        return $this->recordTime;
    }

    /**
     * Set task
     *
     * @param \AppBundle\Entity\Task $task
     * @return Comment
     */
    public function setTask(\AppBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \AppBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }


}
