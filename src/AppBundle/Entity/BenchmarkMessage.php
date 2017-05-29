<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\SimpleEntity;

/**
 * BenchmarkMessage
 */
class BenchmarkMessage extends SimpleEntity
{
	const REPORTED_STATE = 0;
	
	const IN_PROCESS_STATE = 10;
	
	const INFORMATION_REQUIRED_STATE = 20;
	const INFORMATION_SUPPLIED_STATE = 21;
	
	const COMPLETED_STATE = 30;
	
	
	
	public static function getStateName($state) {
		switch($state) {
			case self::REPORTED_STATE:
				return 'label.benchmarkMessage.state.reported';
			case self::IN_PROCESS_STATE:
				return 'label.benchmarkMessage.state.inProcess';
			case self::INFORMATION_REQUIRED_STATE:
				return 'label.benchmarkMessage.state.informationRequired';
			case self::INFORMATION_SUPPLIED_STATE:
				return 'label.benchmarkMessage.state.informationSupplied';
			case self::COMPLETED_STATE:
				return 'label.benchmarkMessage.state.completed';
			default:
				return null;
		}
	}
	
	
	
    /**
     * @var integer
     */
    private $state;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $readByAuthor;

    /**
     * @var boolean
     */
    private $readByAdmin;

    /**
     * @var \AppBundle\Entity\User
     */
    private $author;


    /**
     * Set state
     *
     * @param integer $state
     *
     * @return BenchmarkMessage
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return BenchmarkMessage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set readByAuthor
     *
     * @param boolean $readByAuthor
     *
     * @return BenchmarkMessage
     */
    public function setReadByAuthor($readByAuthor)
    {
        $this->readByAuthor = $readByAuthor;

        return $this;
    }

    /**
     * Get readByAuthor
     *
     * @return boolean
     */
    public function getReadByAuthor()
    {
        return $this->readByAuthor;
    }

    /**
     * Set readByAdmin
     *
     * @param boolean $readByAdmin
     *
     * @return BenchmarkMessage
     */
    public function setReadByAdmin($readByAdmin)
    {
        $this->readByAdmin = $readByAdmin;

        return $this;
    }

    /**
     * Get readByAdmin
     *
     * @return boolean
     */
    public function getReadByAdmin()
    {
        return $this->readByAdmin;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return BenchmarkMessage
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * @var \AppBundle\Entity\Product
     */
    private $product;


    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return BenchmarkMessage
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \AppBundle\Entity\BenchmarkMessage
     */
    private $parent;


    /**
     * Add child
     *
     * @param \AppBundle\Entity\BenchmarkMessage $child
     *
     * @return BenchmarkMessage
     */
    public function addChild(\AppBundle\Entity\BenchmarkMessage $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\BenchmarkMessage $child
     */
    public function removeChild(\AppBundle\Entity\BenchmarkMessage $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\BenchmarkMessage $parent
     *
     * @return BenchmarkMessage
     */
    public function setParent(\AppBundle\Entity\BenchmarkMessage $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\BenchmarkMessage
     */
    public function getParent()
    {
        return $this->parent;
    }
}