<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.dashboard');
    }

    public function AdminUsers()
    {
        return view('admin.users.index');
    }

    public function AdminGetUsers(Request $request)
    {
        $columns_list = array(
            0 => 'id',
            1 => 'name',
            2 => 'lastname',
            3 => 'email',
            4 => 'mobile',
            5 => 'is_admin',
            6 => 'user_code',
            7 => 'created_at',
            8 => 'updated_at'
        );

        $query = User::query();

        // Total records BEFORE search
        $totalData = $query->count();

        // 🔍 Search filter
        if ($request->has('search') && $request->search['value'] != '') {
            $searchValue = $request->search['value'];

            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%")
                    ->orWhere('lastname', 'LIKE', "%$searchValue%")
                    ->orWhere('email', 'LIKE', "%$searchValue%")
                    ->orWhere('mobile', 'LIKE', "%$searchValue%");
            });
        }

        // Total AFTER search
        $totalFiltered = $query->count();

        // Pagination
        $limit = $request->input('length');
        $start = $request->input('start');
        $draw = $request->input('draw');

        // Sorting
        $columnIndex = $request->order[0]['column'];

        if (isset($columns_list[$columnIndex])) {
            $columnName = $columns_list[$columnIndex];
            $columnSortOrder = $request->order[0]['dir'];

            $query->orderBy($columnName, $columnSortOrder);
        } else {
            // fallback
            $query->orderBy('id', 'desc');
        }

        $data = $query->offset($start)->limit($limit)->get();

        $data_val = array();

        foreach ($data as $post_val) {

            $productData = array();

            // ✅ EDIT BUTTON (FIRST COLUMN)
            $productData['edit'] = '
                <a href="' . route('updateUser', ['id' => $post_val->id]) . '"
                class="btn btn-sm btn-primary">
                Edit
                </a>
            ';

            $productData['id'] = $post_val->id;
            $productData['name'] = $post_val->name;
            $productData['lastname'] = $post_val->lastname;
            $productData['email'] = $post_val->email;
            $productData['mobile'] = $post_val->mobile;
            $productData['is_admin'] = $post_val->is_admin ? 'Admin' : 'User';
            $productData['user_code'] = $post_val->user_code;
            $productData['created_at'] = date('Y-m-d', strtotime($post_val->created_at));
            $productData['updated_at'] = date('Y-m-d', strtotime($post_val->updated_at));

            // ✅ DELETE BUTTON (LAST COLUMN)
            $productData['delete'] = '
                <button onclick="deleteuserList(' . $post_val->id . ')"
                    class="btn btn-sm btn-danger">
                    Delete
                </button>
            ';

            $data_val[] = $productData;
        }

        $get_json_data = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered), // ✅ FIXED
            "data" => $data_val
        );

        return response()->json($get_json_data);
    }

    public function updateUser($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.users.edit', compact('user'));
    }


    public function adminUpdateData(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $request->id,
            'phone'  => 'required|digits:10',
        ]);

        $user = User::findOrFail($request->id);

        $user->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'mobile' => $request->phone,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully'
        ]);
    }

    public function adminDestroyUser(Request $request)
    {

        $user = User::findOrFail($request->id)->first();
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
