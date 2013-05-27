<?php

namespace Qwer\LottoFrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Qwer\LottoBundle\Entity\Resource;

/**
 * @Vich\Uploadable
 */
class Country extends Resource
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;
    
    /**
     * @var string
     */
    protected $title;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="image", fileNameProperty="imageName")
     *
     * @var File $image
     */
    protected $image;

    /**
     *
     * @var string $imageName
     */
    protected $imageName;
    
    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $lottoTypes;
    
     /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $translations;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    public function __toString()
    {
        return $this->title;
    }
    
    public function getLottoTypes()
    {
        return $this->lottoTypes;
    }

    public function setLottoTypes($lottoTypes)
    {
        $this->lottoTypes = $lottoTypes;
    }

}
