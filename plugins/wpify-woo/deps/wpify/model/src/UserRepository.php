<?php

namespace WpifyWooDeps\Wpify\Model;

use WpifyWooDeps\Wpify\Model\Abstracts\AbstractUserRepository;
/**
 * Class BasePostRepository
 * @package Wpify\Model
 *
 * @method User[] all()
 * @method User[] find( array $args = array() )
 * @method User create()
 * @method User get( $object = null )
 * @method mixed save( $model )
 * @method mixed delete( $model )
 */
class UserRepository extends AbstractUserRepository
{
    public function model() : string
    {
        return User::class;
    }
}
