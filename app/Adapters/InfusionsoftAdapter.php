<?php

namespace App\Adapters;

use Infusionsoft\Infusionsoft as SDK;
use Infusionsoft;

class InfusionsoftAdapter
{
    /**
     * @var Infusionsoft
     */
    private $infusionsoft;

    /**
     * InfusionsoftAdapter constructor. DI doesn't work properly, facades are used for now. @todo
     *
     * InfusionsoftAdapter constructor.
     * @param SDK $infusionsoft
     */
    public function __construct(SDK $infusionsoft)
    {
        $this->infusionsoft = $infusionsoft;
    }

    /**
     * @return \Infusionsoft\InfusionsoftCollection
     */
    public function tags()
    {
        return Infusionsoft::tags()->all();
    }

    /**
     * @param $email
     * @param $fields
     * @return mixed
     */
    public function contacts($email, $fields)
    {
        return Infusionsoft::contacts('xml')->findByEmail($email, $fields)[0];
    }

    /**
     * @param $contactId
     * @param $tagId
     * @return bool
     */
    public function addTag($contactId, $tagId)
    {
        return Infusionsoft::contacts('xml')->addToGroup($contactId, $tagId);
    }

}