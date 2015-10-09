<?php

namespace Kamran\UserBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;




class AccessOptionsTransformer implements DataTransformerInterface
{

    private $aceString;

    public function __construct($_aceString){
        $this->aceString = $_aceString;
    }

    //PersistentCollection
    public function transform($object)
    {

        $aceArray = array();

        if('' != $this->aceString ){
            $aceArray = json_decode($this->aceString , true);
        }

        if(count($aceArray) > 0){
            return $aceArray;
        }

    }

    // $values Array
    public function reverseTransform($arrayValues)
    {

        //return $arrayValues;

        $filterArray = array();
        foreach($arrayValues as $bundle => $list){
            $filterArray[$bundle] = array_values($list);
        }

        return json_encode($filterArray);
    }

}