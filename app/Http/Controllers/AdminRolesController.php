<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatesRoles;
use App\Http\Requests\EditsRoles;
use App\Http\Requests\GetsRoles;
use App\Http\Requests\ViewsRolesPage;
use App\Permission;
use App\Role;
use Illuminate\Support\Facades\DB;

class AdminRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  ViewsRolesPage  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ViewsRolesPage $request)
    {
        $roles = Role::all();

        $permissions = [
            [
                'name' => 'client_special',
                'title' => 'Clients',
                'description' => '',
                'col' => 'col-sm-6',
                'items' => Permission::group('client_special')
            ],
            [
                'name' => 'client_address',
                'title' => 'Client Addresses',
                'description' => '',
                'col' => 'col-sm-6',
                'items' => Permission::group('client_address')
            ],
            [
                'name' => 'client_phone',
                'title' => 'Client Phones',
                'description' => '',
                'col' => 'col-sm-6',
                'items' => Permission::group('client_phone')
            ],
            [
                'name' => 'client_email',
                'title' => 'Client Emails',
                'description' => '',
                'col' => 'col-sm-6',
                'items' => Permission::group('client_email')
            ],
            [
                'name' => 'client_service',
                'title' => 'Client Services',
                'description' => '',
                'col' => 'col-sm-6',
                'items' => Permission::group('client_service')
            ],
            [
                'name' => 'client_note',
                'title' => 'Client Case Notes',
                'description' => '',
                'col' => 'col-sm-6',
                'items' => Permission::group('client_note')
            ],
            [
                'name' => 'admin',
                'title' => 'Admin',
                'description' => '',
                'col' => 'col-12',
                'items' => Permission::group('admin')
            ],
        ];

        return view('admin.roles.index')
            ->with(['roles' => $roles, 'permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesRoles  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatesRoles $request)
    {
        $role = new Role();
        $role->name = str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9_]/', '', $request->input('roleId')));
        $role->display_name = $request->input('roleName');
        $role->description = $request->input('roleDescription');
        $role->save();

        foreach($request->all() as $key => $val)
        {
            if(substr($key, 0, 11) === 'permission_')
            {
                $permission = substr($key, 11);
                $permissionRecord = Permission::where(['name' => $permission])->get();

                if(count($permissionRecord))
                {
                    $role->attachPermission($permissionRecord[0]);
                }
            }
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Your changes were saved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditsRoles  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditsRoles $request)
    {
        $role = Role::findOrFail($request->input('id'));

        $role->update([
            'name'              => str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9_]/', '', $request->input('roleId'))),
            'display_name'      => $request->input('roleName'),
            'description'       => $request->input('roleDescription'),
        ]);

        DB::table('permission_role')->where(['role_id' => $role->id])->delete();

        foreach($request->all() as $key => $val)
        {
            if(substr($key, 0, 11) === 'permission_')
            {
                $permission = substr($key, 11);
                $permissionRecord = Permission::where(['name' => $permission])->get();

                if(count($permissionRecord))
                {
                    if($val)
                    $role->attachPermission($permissionRecord[0]);
                }
            }
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Your changes were saved');
    }

    /**
     * Display the specified resource from a ajax request.
     *
     * @param GetsRoles $request
     * @return \Illuminate\Http\Response
     */
    public function show(GetsRoles $request)
    {
        $role = Role::select('id', 'name', 'display_name', 'description')->findOrFail($request->input('id'));

        $permissionsArray = [];
        $permissions = DB::table('permission_role')->where(['role_id' => $role->id])->get();

        foreach($permissions as $item)
        {
            $permissionRecord = Permission::findOrFail($item->permission_id);
            array_push($permissionsArray, $permissionRecord->name);
        }

        return response()
            ->json([
            'role'          => $role,
            'permissions'   => $permissionsArray
        ]);
    }

    /**
     *
     */
    public function export()
    {
        die(); //TODO Export a csv showing the roles with permissions breakdown
    }
}
