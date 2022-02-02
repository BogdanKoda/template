<?php

namespace app\components\Auth;

class AccessData
{
    private array $roles;

    public function __construct(array $roles)
    {
        $this->setRoles($roles);
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

}