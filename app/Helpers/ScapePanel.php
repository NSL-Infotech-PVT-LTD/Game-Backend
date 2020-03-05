<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ScapePanel {

    /**
     * default Parameters
     * @param Class $table Object of class name Blueprint
     * @param Bool $userParent Set <b>TRUE</b> to set users id as a foreign key
     * @author Gaurav Sethi <gaurav@netscapelabs.com>
     * @return default Parameters
     */
    public static function getUserRoles($roleID = null, $type = 'key') {
        $roles = config('ScapePanel.user_roles');
        if ($type == 'key'):
            return ($roleID != null) ? $roles[$roleID] : $roles;
        else:
            $rolesM = [];
            foreach ($roles as $key => $role):
                if ((strpos($role, $roleID) !== false)):
                    $rolesM[$key] = $role;
                endif;
            endforeach;
            return $rolesM;
        endif;
    }

}
