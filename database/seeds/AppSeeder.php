<?php

use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'name'      => 'Global Administration',
            'email'     => 'global@admin.com',
            'password'  => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        App\User::create([
            'name'      => 'Ian',
            'email'     => 'ianconway@protonmail.com',
            'password'  => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        auth()->loginUsingId(1);

        $lists = [

            [
                'list_name' => 'email_types',
                'system'    => 1,
                'items'     => [
                    'Home',
                    'Work',
                    'School',
                    'Other',
                ]
            ],

            [
                'list_name' => 'phone_types',
                'system'    => 1,
                'items'     => [
                    'Home',
                    'Cell',
                    'Pager',
                    'Work',
                    'School',
                    'Other',
                ]
            ],

            [
                'list_name' => 'address_types',
                'system'    => 1,
                'items'     => [
                    'Home',
                    'Work',
                    'School',
                    'Mailing',
                    'Shipping',
                    'Other',
                ]
            ],

            [
                'list_name' => 'contact_times',
                'system'    => 1,
                'items'     => [
                    'Morning',
                    'Afternoon',
                    'Evening',
                ]
            ],

            [
                'list_name' => 'genders',
                'system'    => 1,
                'items'     => [
                    'Male',
                    'Female',
                    'Other',
                ]
            ],

            [
                'list_name' => 'client_services',
                'system'    => 1,
                'items'     => [
                    'Psychotherapy',
                    'Community Mental Health Services',
                    'Emergency Services',
                    'Consumer Driven Services',
                ]
            ]

        ];

        foreach($lists as $list)
        {
            foreach($list['items'] as $list_item)
            {
                $new_list = new \App\AppList();
                $new_list->system = $list['system'];
                $new_list->list_name = $list['list_name'];
                $new_list->item_title = $list_item;
                $new_list->save();
            }
        }

        $roles = [
            [
                'name'          => 'global_admin',
                'display_name'  => 'Global Administrator',
                'description'   => 'Granted all permissions'
            ],
            [
                'name'          => 'intake',
                'display_name'  => 'Intake',
                'description'   => 'Can create clients, client services'
            ],
            [
                'name'          => 'case_manager',
                'display_name'  => 'Case Manager',
                'description'   => 'Can manage client record, case notes'
            ],
            [
                'name'          => 'provider',
                'display_name'  => 'External Provider',
                'description'   => 'Can create client services'
            ],
            [
                'name'          => 'clerk',
                'display_name'  => 'Clerk, Reception',
                'description'   => 'Can view client records, scheduling'
            ],
        ];

        foreach($roles as $role)
        {
            $createRole = new \App\Role();
            $createRole->name = $role['name'];
            $createRole->display_name = $role['display_name'];
            $createRole->description = $role['description'];
            $createRole->save();
        }

        $permissions = [
            [
                'name'          => 'search_clients',
                'display_name'  => 'Search',
                'description'   => 'Search for an existing client',
                'roles'         => '1,2,3,4,5',
                'groupName'     => 'client_special'
            ],
            [
                'name'          => 'create_clients',
                'display_name'  => 'Create',
                'description'   => 'Create a new client',
                'roles'         => '1,2',
                'groupName'     => 'client_special'
            ],
            [
                'name'          => 'create_client_phone',
                'display_name'  => 'Create',
                'description'   => 'Create a client phone number',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_phone'
            ],
            [
                'name'          => 'create_client_email',
                'display_name'  => 'Create',
                'description'   => 'Create a client email',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_email'
            ],
            [
                'name'          => 'create_client_address',
                'display_name'  => 'Create',
                'description'   => 'Create a client address',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_address'
            ],
            [
                'name'          => 'create_client_service',
                'display_name'  => 'Create',
                'description'   => 'Create a client service',
                'roles'         => '1,2,4',
                'groupName'     => 'client_service'
            ],
            [
                'name'          => 'create_client_notes',
                'display_name'  => 'Create',
                'description'   => 'Create a client note',
                'roles'         => '1,2,3',
                'groupName'     => 'client_note'
            ],

            [
                'name'          => 'need_to_know',
                'display_name'  => 'View Some',
                'description'   => 'Access only clients assigned to you',
                'roles'         => '3',
                'groupName'     => 'client_special'
            ],
            [
                'name'          => 'full_access',
                'display_name'  => 'View All',
                'description'   => 'Access all clients',
                'roles'         => '1,2,3,4,5',
                'groupName'     => 'client_special'
            ],
            [
                'name'          => 'view_client_phones',
                'display_name'  => 'View Active',
                'description'   => 'Views a clients active phone numbers',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_phone'
            ],
            [
                'name'          => 'view_client_emails',
                'display_name'  => 'View Active',
                'description'   => 'View a clients active emails',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_email'
            ],
            [
                'name'          => 'view_client_addresses',
                'display_name'  => 'View Active',
                'description'   => 'View a clients active addresses',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_address'
            ],
            [
                'name'          => 'view_client_services',
                'display_name'  => 'View Active',
                'description'   => 'View a clients active services',
                'roles'         => '1,2,3,4,5',
                'groupName'     => 'client_service'
            ],
            [
                'name'          => 'view_client_notes',
                'display_name'  => 'View',
                'description'   => 'View a clients note',
                'roles'         => '1,2,3',
                'groupName'     => 'client_note'
            ],

            [
                'name'          => 'view_expired_client_phones',
                'display_name'  => 'View Expired',
                'description'   => 'View a clients expired phone numbers',
                'roles'         => '1,2,3',
                'groupName'     => 'client_phone'
            ],
            [
                'name'          => 'view_expired_client_emails',
                'display_name'  => 'View Expired',
                'description'   => 'View a clients expired emails',
                'roles'         => '1,2,3',
                'groupName'     => 'client_email'
            ],
            [
                'name'          => 'view_expired_client_addresses',
                'display_name'  => 'View Expired',
                'description'   => 'View a clients expired address',
                'roles'         => '1,2,3',
                'groupName'     => 'client_address'
            ],
            [
                'name'          => 'view_expired_client_services',
                'display_name'  => 'View Expired',
                'description'   => 'View a clients expired services',
                'roles'         => '1,2,3',
                'groupName'     => 'client_service'
            ],

            [
                'name'          => 'edit_client_demographics',
                'display_name'  => 'Edit',
                'description'   => 'Edit a clients demographics',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_special'
            ],
            [
                'name'          => 'edit_client_phone',
                'display_name'  => 'Edit',
                'description'   => 'Edit a client phone number',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_phone'
            ],
            [
                'name'          => 'edit_client_email',
                'display_name'  => 'Edit',
                'description'   => 'Edit a client email',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_email'
            ],
            [
                'name'          => 'edit_client_address',
                'display_name'  => 'Edit',
                'description'   => 'Edit a client address',
                'roles'         => '1,2,3,5',
                'groupName'     => 'client_address'
            ],
            [
                'name'          => 'edit_client_service',
                'display_name'  => 'Edit',
                'description'   => 'Edit a client service',
                'roles'         => '1,2,4',
                'groupName'     => 'client_service'
            ],

            [
                'name'          => 'admin_settings',
                'display_name'  => 'Settings Page',
                'description'   => 'Allows access to the admin settings page',
                'roles'         => '1',
                'groupName'     => 'admin'
            ],

            [
                'name'          => 'admin_add_roles',
                'display_name'  => 'Create Roles & Permissions',
                'description'   => 'Create user roles and permissions',
                'roles'         => '1',
                'groupName'     => 'admin'
            ],

            [
                'name'          => 'admin_edit_roles',
                'display_name'  => 'Edit Roles & Permissions',
                'description'   => 'Edit user roles and permissions',
                'roles'         => '1',
                'groupName'     => 'admin'
            ],
        ];

        foreach($permissions as $permission)
        {
            $createPermission = new \App\Permission();
            $createPermission->name = $permission['name'];
            $createPermission->display_name = $permission['display_name'];
            $createPermission->group_name = $permission['groupName'];
            $createPermission->description = $permission['description'];
            $createPermission->save();

            foreach(explode(',', $permission['roles']) as $roleItem)
            {
                $role = App\Role::findOrFail($roleItem);
                $role->attachPermission($createPermission);
            }
        }

        $settings = [
            [
                'code'      => 'login_self_registration',
                'type'      => 'BOOLEAN',
                'label'     => 'Allow Self Registration',
                'value'     => true,
                'hidden'    => 0,
            ],
            [
                'code'      => 'login_invitation_code',
                'type'      => 'TEXT',
                'label'     => 'Registration Invitation Code',
                'value'     => 'CL' . rand(1,99999999999),
                'hidden'    => 0,
            ],
            [
                'code'      => 'theme_mm_bg_color',
                'type'      => 'TEXT',
                'label'     => 'Main Menu Background Color',
                'value'     => '#6C757D',
                'hidden'    => 0,
            ],
            [
                'code'      => 'theme_mm_fg_color',
                'type'      => 'TEXT',
                'label'     => 'Main Menu Font Color',
                'value'     => '#FFFFFF',
                'hidden'    => 0,
            ],
            [
                'code'      => 'theme_primary_btn_fg_color',
                'type'      => 'TEXT',
                'label'     => 'Primary Button Font Color',
                'value'     => '#007BFF',
                'hidden'    => 0,
            ],
            [
                'code'      => 'smtp_mail_server',
                'type'      => 'TEXT',
                'label'     => 'Outgoing Mail Server',
                'value'     => '',
                'hidden'    => 0,
            ],
            [
                'code'      => 'smtp_mail_port',
                'type'      => 'TEXT',
                'label'     => 'Outgoing Mail Port',
                'value'     => '',
                'hidden'    => 0,
            ],
            [
                'code'      => 'smtp_mail_username',
                'type'      => 'TEXT',
                'label'     => 'Mail Username',
                'value'     => '',
                'hidden'    => 0,
            ],
            [
                'code'      => 'smtp_mail_password',
                'type'      => 'TEXT',
                'label'     => 'Mail Password',
                'value'     => '',
                'hidden'    => 0,
            ],
            [
                'code'      => 'smtp_mail_from_name',
                'type'      => 'TEXT',
                'label'     => 'Mail From Name',
                'value'     => '',
                'hidden'    => 0,
            ],
            [
                'code'      => 'smtp_mail_from_address',
                'type'      => 'TEXT',
                'label'     => 'Mail From Address',
                'value'     => '',
                'hidden'    => 0,
            ],
        ];

        $role = \App\Role::findOrFail(1);
        $user = auth()->user()->attachRole($role);

        foreach($settings as $setting)
        {
            \Illuminate\Support\Facades\DB::table('settings')->insert($setting);
        }

        $fake = \Faker\Factory::create();

        for($x = 0; $x < 50; $x++)
        {
            $item = [
                'author_id'         => 1,
                'gender'            => array_random(\App\AppList::items('genders')),
                'chart_number'      => $x,
                'legal_first_name'  => $fake->firstName,
                'legal_middle_name' => $fake->firstName,
                'legal_last_name'   => $fake->lastName,
                'legal_suffix'      => $fake->suffix,
                'legal_prefix'      => array_random(['Dr', 'Mr', 'Mrs', 'Miss']),
                'nickname'          => $fake->firstName,
                'birth_first_name'  => $fake->firstName,
                'birth_middle_name' => $fake->firstName,
                'birth_last_name'   => $fake->lastName,
                'birth_suffix'      => $fake->suffix,
                'birth_prefix'      => array_random(['Dr', 'Mr', 'Mrs', 'Miss']),
                'date_of_birth'     => strtotime('365 days ago'),
                'ssn'               => rand(100000000, 999999999),
                'comments'          => array_random(['Updated Address IC 9-5-17', 'Scheduled evaluation JH 12-18-18', '', '', 'Intake performed in field']),
            ];

            $client = new \App\Client();

            foreach($item as $key => $val)
            {
                $client->$key = $val;
            }

            $client->save();

            $client->assign(App\User::findOrFail(2), 'Primary');

            for($y = 0; $y < random_int(1,5); $y++)
            {
                $client->services()->create([
                    'author_id'      => 1,
                    'service_id'     => rand(23,26),
                    'client_id'      => $client->id,
                    'active_at'      => $fake->date('Y-m-d H:i:s'),
                    'expires_at'     => date('Y-m-d H:i:s', strtotime('+ 90 days')),
                ]);
            }

            for($y = 0; $y < random_int(1,15); $y++)
            {
                $client->caseNotes()->create([
                    'author_id'      => 1,
                    'comments'       => $fake->text(rand(100,1000)),
                ]);
            }

            for($y = 0; $y < random_int(1,3); $y++)
            {
                $client->addresses()->create([
                    'author_id'      => 1,
                    'type'           => array_random(\App\AppList::items('address_types')),
                    'primary'        => array_random([0,1]),
                    'address_line_1' => $fake->streetAddress,
                    'address_line_2' => '',
                    'address_line_3' => '',
                    'city'           => $fake->city,
                    'state'          => $fake->state,
                    'zip_code'       => $fake->postcode,
                    'comments'       => array_random(['', '', '', '', 'Uncles house']),
                    'active_at'      => $fake->date('Y-m-d H:i:s'),
                    'expires_at'     => $fake->date('Y-m-d H:i:s'),
                ]);
            }

            for($y = 0; $y < random_int(1,3); $y++)
            {
                $client->phones()->create([
                    'author_id'      => 1,
                    'type'           => array_random(\App\AppList::items('phone_types')),
                    'contact_time'   => array_random(\App\AppList::items('contact_times')),
                    'primary'        => array_random([0,1]),
                    'phone'          => $fake->phoneNumber,
                    'comments'       => array_random(['', '', '', '', 'Call mom if no answer on this line']),
                    'active_at'      => $fake->date('Y-m-d H:i:s'),
                    'expires_at'     => $fake->date('Y-m-d H:i:s'),
                ]);
            }

            for($y = 0; $y < random_int(1,3); $y++)
            {
                $client->emails()->create([
                    'author_id'      => 1,
                    'type'           => array_random(\App\AppList::items('email_types')),
                    'primary'        => array_random([0,1]),
                    'verified'       => array_random([0,1]),
                    'email'          => $fake->email,
                    'comments'       => array_random(['', '', '', '', 'Messages sometimes go to spam']),
                    'active_at'      => $fake->date('Y-m-d H:i:s'),
                    'expires_at'     => $fake->date('Y-m-d H:i:s'),
                ]);
            }

        }
    }
}
