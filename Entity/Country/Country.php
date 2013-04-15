<?php

namespace Qwer\LottoFrontendBundle\Entity\Country;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * 
 * @ORM\Entity(repositoryClass="Qwer\LottoFrontendBundle\Repository\CountryRepository")
 * @ORM\Table("country")
 * @Vich\Uploadable
 */
class Country
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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


}
