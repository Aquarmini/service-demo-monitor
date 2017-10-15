<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UserMigration_103
 */
class UserMigration_103 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('user', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'login',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 50,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                            'after' => 'login'
                        ]
                    ),
                    new Column(
                        'avatar_url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'avatar_url'
                        ]
                    ),
                    new Column(
                        'html_url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'url'
                        ]
                    ),
                    new Column(
                        'followers_url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'html_url'
                        ]
                    ),
                    new Column(
                        'following_url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'followers_url'
                        ]
                    ),
                    new Column(
                        'starred_url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'following_url'
                        ]
                    ),
                    new Column(
                        'organizations_url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'starred_url'
                        ]
                    ),
                    new Column(
                        'repos_url',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'organizations_url'
                        ]
                    ),
                    new Column(
                        'location',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 16,
                            'after' => 'repos_url'
                        ]
                    ),
                    new Column(
                        'email',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 64,
                            'after' => 'location'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'email'
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'created_at'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('LOGIN_UNIQUE', ['login'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_unicode_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
