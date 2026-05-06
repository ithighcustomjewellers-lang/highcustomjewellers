<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function data(Request $request)
    {
        $columns_list = [
            0 => 'edit',
            1 => 'user_code',
            2 => 'fullname',
            3 => 'mobile',
            4 => 'email',
            5 => 'is_admin',
            6 => 'app_rights',
            7 => 'access_rights',
            8 => 'status',
            9 => 'delete'
        ];

        $query = User::query();

        // Filter inactive
        if ($request->has('filter_inactive') && $request->filter_inactive == 1) {
            $query->where('status', 'inactive');
        }

        // Global search
        if ($request->has('global_search') && !empty($request->global_search)) {
            $search = $request->global_search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('lastname', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile', 'LIKE', "%{$search}%")
                    ->orWhere('user_code', 'LIKE', "%{$search}%");
            });
        }
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('lastname', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile', 'LIKE', "%{$search}%")
                    ->orWhere('user_code', 'LIKE', "%{$search}%");
            });
        }

        $totalData = $query->count();
        $totalFiltered = $totalData;
        $query->orderBy('is_admin', 'DESC');
        // Sorting
        $orderCol = $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'asc');
        switch ($orderCol) {
            case 1:
                $query->orderBy('user_code', $orderDir);
                break;
            case 2:
                $query->orderBy('name', $orderDir)->orderBy('lastname', $orderDir);
                break;
            case 3:
                $query->orderBy('mobile', $orderDir);
                break;
            case 4:
                $query->orderBy('email', $orderDir);
                break;
            case 8:
                $query->orderBy('status', $orderDir);
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $users = $query->offset($start)->limit($length)->get();

        $data_val = [];
        foreach ($users as $user) {
            $appRights = json_decode($user->app_rights ?? '[]', true);
            $accessRights = json_decode($user->access_rights ?? '[]', true);
            $isSuperAdmin = ($user->is_admin == 1);

            $rightsHtml = function ($rights, $type) use ($user, $isSuperAdmin) {
                $rights = is_array($rights) ? $rights : [];
                $badges = '';
                foreach (array_slice($rights, 0, 2) as $r) {
                    $badges .= '<span class="rights-badge">' . e($r) . '</span> ';
                }
                if (count($rights) > 2) {
                    $badges .= '<span class="rights-badge">+' . (count($rights) - 2) . '</span>';
                }
                if (!$isSuperAdmin) {
                    $badges .= '<i class="fas fa-pen edit-rights-icon ms-1" data-userid="' . $user->id . '" data-type="' . $type . '" data-rights=\'' . json_encode($rights) . '\' title="Edit ' . ucfirst($type) . ' Rights"></i>';
                }
                return $badges ?: '<span class="text-muted">—</span>';
            };

            $row = [];
            $row['edit'] = $isSuperAdmin
                ? '<button class="btn btn-sm btn-secondary" disabled><i class="fas fa-edit"></i> Edit</button>'
                : '<button class="btn btn-sm btn-outline-primary edit-user-btn" data-id="' . $user->id . '"><i class="fas fa-edit"></i></button>';

            $row['user_code'] = $user->user_code ?? 'E' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
            $row['fullname'] = e($user->name . ' ' . $user->lastname);
            $row['mobile'] = e($user->mobile);
            $row['email'] = e($user->email);
            $row['is_admin'] = $isSuperAdmin ? '<span class="badge bg-danger">Admin</span>' : '<span class="badge bg-secondary">User</span>';
            $row['app_rights'] = $rightsHtml($appRights, 'app');
            $row['access_rights'] = $rightsHtml($accessRights, 'access');

            $row['status'] = $isSuperAdmin
                ? '<label class="switch"><input type="checkbox" disabled ' . ($user->status == 'active' ? 'checked' : '') . '><span class="slider"></span></label>'
                : '<label class="switch"><input type="checkbox" class="status-toggle" data-id="' . $user->id . '" ' . ($user->status == 'active' ? 'checked' : '') . ' id="statusSwitch' . $user->id . '"><span class="slider"></span></label>';

            $row['delete'] = $isSuperAdmin
                ? '<button class="btn btn-sm btn-secondary" disabled><i class="fas fa-trash"></i> Delete</button>'
                : '<button class="btn btn-sm btn-outline-danger" onclick="deleteUser(' . $user->id . ')"><i class="fas fa-trash"></i></button>';

            $data_val[] = $row;
        }

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data'            => $data_val,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'user_code' => 'nullable|string|unique:users,user_code',
            'password' => 'nullable|min:6',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->user_code = $request->user_code ?? 'EC' . rand(10000, 99999);
        if ($request->password) $user->password = Hash::make($request->password);
        $user->app_rights = $request->app_rights;
        $user->access_rights = $request->access_rights;
        $user->is_active = $request->is_active ?? 1;

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->save();
        return response()->json(['message' => 'User created successfully']);
    }

    public function edit(Request $request)
    {
        $user = User::findOrFail($request->id);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'mobile' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_code' => 'nullable|unique:users,user_code,' . $user->id,
            'password' => 'nullable|min:6',
            'profile_image' => 'nullable|image|max:2048'
        ]);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        if ($request->user_code) $user->user_code = $request->user_code;
        if ($request->password) $user->password = Hash::make($request->password);
        $user->app_rights = $request->app_rights;
        $user->access_rights = $request->access_rights;
        $user->is_active = $request->is_active;

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) Storage::disk('public')->delete($user->profile_image);
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }
        $user->save();
        return response()->json(['message' => 'User updated']);
    }

    public function toggleStatus(Request $request)
    {
        User::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated']);
    }

    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function totalUsers()
    {
        return response()->json(['total' => User::count()]);
    }

    public function export(Request $request)
    {
        $query = User::query();
        if ($request->global_search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->global_search . '%')
                    ->orWhere('email', 'like', '%' . $request->global_search . '%');
            });
        }
        if ($request->filter_inactive) $query->where('is_active', 0);
        $users = $query->get();
        $csv = fopen('php://temp', 'w+');
        fputcsv($csv, ['User Code', 'Full Name', 'Mobile', 'Email', 'App Rights', 'Access Rights', 'Status']);
        foreach ($users as $user) {
            fputcsv($csv, [
                $user->user_code,
                $user->name . ' ' . $user->lastname,
                $user->mobile,
                $user->email,
                implode(',', json_decode($user->app_rights ?? '[]')),
                implode(',', json_decode($user->access_rights ?? '[]')),
                $user->is_active ? 'Active' : 'Inactive'
            ]);
        }
        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);
        return response($csvContent, 200)
            ->header('Content-Type', 'application/csv')
            ->header('Content-Disposition', 'attachment; filename="users_export.csv"');
    }

    public function printCard()
    {
        $users = User::all();
        return view('admin.users.print_cards', compact('users'));
    }
}
