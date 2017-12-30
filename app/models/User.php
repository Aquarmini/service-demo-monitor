<?php

namespace App\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class User extends Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $login;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $avatar_url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $html_url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $followers_url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $following_url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $starred_url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $organizations_url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $repos_url;

    /**
     *
     * @var string
     * @Column(type="string", length=16, nullable=true)
     */
    public $location;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    public $email;

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
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("github");
        $this->setSource("user");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user';
    }
}
