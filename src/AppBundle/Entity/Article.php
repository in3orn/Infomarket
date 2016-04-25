<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\ImageEntity;

/**
 * Article
 */
class Article extends ImageEntity
{
	const DEFAULT_LEFT_LAYOUT 		= 0;
	const DEFAULT_RIGHT_LAYOUT 		= 1;
	const DEFAULT_ALTERNATE_LAYOUT 	= 2;
	
	const NARROW_LEFT_LAYOUT 		= 3;
	const NARROW_RIGHT_LAYOUT 		= 4;
	const NARROW_ALTERNATE_LAYOUT 	= 5;
	
	const WIDE_LAYOUT 				= 6;
	const WIDE_SMALL_IMAGE_LAYOUT 	= 7;
	
	const COLUMN_2_LAYOUT 			= 10;
	const COLUMN_3_LAYOUT 			= 11;
	const COLUMN_4_LAYOUT 			= 12;
	
	const GRID_2_LAYOUT 			= 20;
	const GRID_3_LAYOUT 			= 21;
	const GRID_4_LAYOUT 			= 22;
	
	/**
	 * {@inheritDoc}
	 */
	public function getUploadPath()
	{
		return '../web/uploads/articles';
	}
	
	/**
	 * @var string
	 */
	private $subname;
	
    /**
     * @var string
     */
    private $intro;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \AppBundle\Entity\Branch
     */
    private $branch;

    /**
     * @var \AppBundle\Entity\ArticleCategory
     */
    private $articleCategory;
    
    /**
     * @var boolean
     */
    private $featured;


    /**
     * Set intro
     *
     * @param string $intro
     *
     * @return Article
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
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
     * Set branch
     *
     * @param \AppBundle\Entity\Branch $branch
     *
     * @return Article
     */
    public function setBranch(\AppBundle\Entity\Branch $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return \AppBundle\Entity\Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Set articleCategory
     *
     * @param \AppBundle\Entity\ArticleCategory $articleCategory
     *
     * @return Article
     */
    public function setArticleCategory(\AppBundle\Entity\ArticleCategory $articleCategory = null)
    {
        $this->articleCategory = $articleCategory;

        return $this;
    }

    /**
     * Get articleCategory
     *
     * @return \AppBundle\Entity\ArticleCategory
     */
    public function getArticleCategory()
    {
        return $this->articleCategory;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     *
     * @return Article
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Set subname
     *
     * @param string $subname
     *
     * @return Article
     */
    public function setSubname($subname)
    {
        $this->subname = $subname;

        return $this;
    }

    /**
     * Get subname
     *
     * @return string
     */
    public function getSubname()
    {
        return $this->subname;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \AppBundle\Entity\Article
     */
    private $parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add child
     *
     * @param \AppBundle\Entity\Article $child
     *
     * @return Article
     */
    public function addChild(\AppBundle\Entity\Article $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\Article $child
     */
    public function removeChild(\AppBundle\Entity\Article $child)
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
     * @param \AppBundle\Entity\Article $parent
     *
     * @return Article
     */
    public function setParent(\AppBundle\Entity\Article $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Article
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * @var integer
     */
    private $layout;


    /**
     * Set layout
     *
     * @param integer $layout
     *
     * @return Article
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return integer
     */
    public function getLayout()
    {
        return $this->layout;
    }
    /**
     * @var boolean
     */
    private $displaySided;


    /**
     * Set displaySided
     *
     * @param boolean $displaySided
     *
     * @return Article
     */
    public function setDisplaySided($displaySided)
    {
        $this->displaySided = $displaySided;

        return $this;
    }

    /**
     * Get displaySided
     *
     * @return boolean
     */
    public function getDisplaySided()
    {
        return $this->displaySided;
    }
    /**
     * @var integer
     */
    private $orderNumber;


    /**
     * Set orderNumber
     *
     * @param integer $orderNumber
     *
     * @return Article
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return integer
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }
}
