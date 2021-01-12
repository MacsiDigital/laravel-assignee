<?php

namespace Roles\Contracts;

interface Role
{

    /**
     * Find a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Roles\Contracts\Role
     *
     * @throws \Roles\Exceptions\RoleDoesNotExist
     */
    public static function findByName(string $name, $guardName = null): Role;

    /**
     * Find a role by its id and guard name.
     *
     * @param int $id
     * @param string|null $guardName
     *
     * @return \Roles\Contracts\Role
     *
     * @throws \Roles\Exceptions\RoleDoesNotExist
     */
    public static function findById(int $id, $guardName): Role;

    /**
     * Find or create a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Roles\Contracts\Role
     */
    public static function findOrCreate(string $name, $guardName): Role;
}
