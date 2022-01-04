<?php

namespace App\Controllers;

use App\Core\Request;

class SettingsController
{
    public function permissionIndex()
    {
        abort_if(gate_denies('settings_access'), '403 Forbidden');

        $pageTitle = "Permission";
        $branch = $_SESSION['system']['branch_id'];

        $permissions = DB()->selectLoop("*", "permissions", "id > 0 ORDER BY id DESC")->get();

        return view('/settings/permission/index', compact('pageTitle', 'permissions'));
    }

    public function permissionStore()
    {
        $request = Request::validate('/settings/permission', [
            'title' => ['required']
        ]);

        DB()->insert("permissions", [
            "title" => $request['title'],
            "date" => date('Y-m-d')
        ]);

        return redirect('/settings/permission', ["message" => "Added successfully."]);
    }

    public function permissionEdit($id)
    {
        $permission = DB()->select("*", "permissions", "id = '$id'")->get();

        $data = [
            "id" => $permission['id'],
            "title" => $permission['title'],
            "date" => $permission['date']
        ];

        echo json_encode($data);
    }

    public function permissionUpdate()
    {
        $request = Request::validate('/settings/permission', [
            'u_permission_id' => ['required'],
            'u_title' => ['required']
        ]);

        DB()->update("permissions", [
            "title" => $request['u_title']
        ], "id = '$request[u_permission_id]'");

        return redirect('/settings/permission', ["message" => "Updated successfully."]);
    }

    public function permissionDestroy()
    {
        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("permissions", "id = '$id'");
        }

        return redirect('/settings/permission', ["message" => "deleted successfully."]);
    }

    public function rolesIndex()
    {
        abort_if(gate_denies('settings_access'), '403 Forbidden');

        $pageTitle = "Roles";
        $branch = $_SESSION['system']['branch_id'];

        $roles = DB()->selectLoop("*", "roles", "id > 0 ORDER BY id DESC")->get();
        $permissions = DB()->selectLoop("*", "permissions", "id > 0 ORDER BY title ASC")->get();

        return view('/settings/roles/index', compact('pageTitle', 'roles', 'permissions'));
    }

    public function rolesStore()
    {
        sort($_REQUEST['permissions']);
        $permissions = implode(",", $_REQUEST['permissions']);
        $request = Request::validate('/settings/roles', [
            'title' => ['required'],
            'permissions' => ['required']
        ]);

        DB()->insert("roles", [
            "role" => $request['title'],
            "permission" => $permissions,
            "created_at" => date('Y-m-d')
        ]);

        return redirect('/settings/roles', ["message" => "Added successfully."]);
    }

    public function rolesEdit($id)
    {
        $role = DB()->select("*", "roles", "id = '$id'")->get();
        $permissions = DB()->selectLoop("*", "permissions", "id > 0 ORDER BY id DESC")->get();

        $permissionData = [];
        $permission_explod = explode(",", $role['permission']);
        foreach ($permissions as $permission) {
            $selected = (in_array($permission['id'], $permission_explod)) ? 'selected' : '';
            $permissionData[] = [
                "id" => $permission['id'],
                "title" => $permission['title'],
                "is_selected" => $selected
            ];
        }

        $data = [
            "id" => $role['id'],
            "role" => $role['role'],
            "created_at" => $role['created_at'],
            "permissions" => $permissionData
        ];

        echo json_encode($data);
    }

    public function rolesUpdate()
    {
        sort($_REQUEST['u_permissions']);
        $permissions = implode(",", $_REQUEST['u_permissions']);
        $request = Request::validate('/settings/roles', [
            'u_roles_id' => ['required'],
            'u_title' => ['required'],
            'u_permissions' => ['required']
        ]);

        DB()->update("roles", [
            "role" => $request['u_title'],
            "permission" => $permissions
        ], "id = '$request[u_roles_id]'");

        return redirect('/settings/roles', ["message" => "Updated successfully."]);
    }

    public function rolesDestroy()
    {
        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("roles", "id = '$id'");
        }

        return redirect('/settings/roles', ["message" => "deleted successfully."]);
    }

    public function userIndex()
    {
        abort_if(gate_denies('settings_access'), '403 Forbidden');

        $pageTitle = "Users";
        $branch = $_SESSION['system']['branch_id'];

        $users = DB()->selectLoop("*", "users", "id > 0 ORDER BY id DESC")
            ->with([
                "roles" => ['role_id', 'id']
            ])->get();
        $roles = DB()->selectLoop("*", "roles", "id > 0 ORDER BY role ASC")->get();
        $employees = DB()->selectLoop("*", "employee", "id > 0 ORDER BY fullname ASC")->get();

        return view('/settings/user/index', compact('pageTitle', 'users', 'roles', 'employees'));
    }

    public function userStore()
    {
        $request = Request::validate('/settings/users', [
            'email' => ['required', 'email'],
            'name' => ['required'],
            'roles' => ['required'],
            'username' => ['required', 'unique:users'],
            'password' => ['required']
        ]);

        DB()->insert("users", [
            'email' => $request['email'],
            'fullname' => $request['name'],
            'username' => $request['username'],
            'password' => bcrypt($request['password']),
            'role_id' => $request['roles'],
            'updated_at' => date("Y-m-d H:i:s"),
            'created_at' => date("Y-m-d H:i:s")
        ]);

        return redirect('/settings/users', ["message" => "Added successfully."]);
    }

    public function userEdit($id)
    {
        $user = DB()->select("*", "users", "id = '$id'")->get();

        $data = [
            "id" => $user['id'],
            "email" => $user['email'],
            "fullname" => $user['fullname'],
            "username" => $user['username'],
            "role_id" => $user['role_id'],
            "created_at" => $user['created_at']
        ];

        echo json_encode($data);
    }

    public function userUpdate()
    {
        $request = Request::validate('/settings/users', [
            'u_email' => ['required', 'email'],
            'u_name' => ['required'],
            'u_roles' => ['required'],
            'u_username' => ['required', 'unique:users,username,id,' . $_REQUEST['u_users_id']]
        ]);

        DB()->update("users", [
            'email' => $request['u_email'],
            'fullname' => $request['u_name'],
            'username' => $request['u_username'],
            'role_id' => $request['u_roles'],
            'updated_at' => date("Y-m-d H:i:s")
        ], "id = '$request[u_users_id]'");

        return redirect('/settings/users', ["message" => "Updated successfully."]);
    }

    public function userDestroy()
    {
        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("users", "id = '$id'");
        }

        return redirect('/settings/users', ["message" => "deleted successfully."]);
    }
}
