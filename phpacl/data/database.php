<?php

class BD_main
{
    const TAULA_ROLES = 'main_roles';
    const TAULA_PERMISSIONS = 'main_permissions';
    const TAULA_ROLE_PERMISIONS = 'main_rolepermissions';
    const TAULA_USUARIS = 'users';
    const TAULA_USUARIS_ONLINE = 'users_online';
}

class KEYS
{

    const PK =
        [
            BD_main::TAULA_USUARIS => 'id',
            BD_main::TAULA_USUARIS_ONLINE => 'id',
            BD_main::TAULA_ROLES => 'id',
            BD_main::TAULA_ROLE_PERMISIONS => 'id',
            BD_main::TAULA_PERMISSIONS => 'tag',
            BD_main::TAULA_USUARIS => 'id',
        ];
}
