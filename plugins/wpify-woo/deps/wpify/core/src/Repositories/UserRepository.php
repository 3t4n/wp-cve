<?php

namespace WpifyWooDeps\Wpify\Core\Repositories;

use WpifyWooDeps\Doctrine\Common\Collections\ArrayCollection;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;
use WpifyWooDeps\Wpify\Core\Interfaces\RepositoryInterface;
use WpifyWooDeps\Wpify\Core\Models\UserModel;
class UserRepository extends AbstractComponent implements RepositoryInterface
{
    public function all() : ArrayCollection
    {
        $collection = new ArrayCollection();
        $users = get_users();
        foreach ($users as $user) {
            $collection->add($this->get($user));
        }
        return $collection;
    }
    public function get($user) : UserModel
    {
        $model = $this->plugin->create_component(UserModel::class, ['user' => $user]);
        $model->init();
        return $model;
    }
    public function get_current_user()
    {
        $user = wp_get_current_user();
        return $this->get($user);
    }
}
