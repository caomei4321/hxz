<?php


namespace App\Http\Controllers\Api;


use App\Models\Department;
use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserMessageController extends Controller
{
    public function UserMessageStore(Request $request, User $user)
    {
        if ($user->where('phone', $request->phone)->first()) {
            return $this->message('手机号已存在', 'error');
        }
        if ($request->department_id) {
            $data = $request->only(['name', 'phone', 'age',  'category_id', 'department_id']);
        } else {
            $department = new Department();
            $department->name = $request->department_name;
            $department->save();
            $data = $request->only(['name', 'phone', 'age',  'category_id']);
            $data['department_id'] = $department->id;
        }
        //$data = $request->only(['name', 'phone', 'age',  'category_id', 'department_id']);
        $data['password'] = Hash::make(substr($request->phone, 5));
        $user->fill($data);
        $user->save();

        return $this->message('录入成功');
    }

    public function getCategoryAndDepartment()
    {
        $category = $this->getCategories();
        $department = $this->getDepartments();
        $data['category'] = $category;
        $data['department'] = $department;

        return $this->message($data);
    }

    private function getCategories()
    {
        $categories = UserCategory::all(['id', 'name']);

        $categoryName = $categories->pluck('name');

        $data['categories'] = $categories;
        $data['categoryName'] = $categoryName;

        return $data;
    }

    private function getDepartments()
    {
        $departments = Department::all(['id', 'name']);

        $departmentName = $departments->pluck('name');

        $data['departments'] = $departments;
        $data['departmentName'] = $departmentName;

        return $data;
    }
}