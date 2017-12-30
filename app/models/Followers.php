<?php

namespace App\Models;

class Followers extends Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=20, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=false)
     */
    public $username;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=false)
     */
    public $login;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $avatar_url;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    public $uid;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $created_at;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $updated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("github");
        $this->setSource("followers");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'followers';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Followers[]|Followers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Followers|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
