<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeachersRequest;
use App\Http\Requests\EditTeachersRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function create(CreateTeachersRequest $request)
    {

        $teacher = Teacher::create($request->toArray());
        if($request->image){
            $teacher->addMediaFromRequest('image')->toMediaCollection('teacher.image');
        }
        return response()->json($teacher);
    }


    public function index($id = null)
    {

        if ($id) {
            $teacher = Teacher::where('id', $id)->first();
        } else {
            $teacher = Teacher::orderby('id', 'desc')->paginate(12);
        }
        return response()->json($teacher);
    }

    public function edit(EditTeachersRequest $request, $id)
    {
        $teacher = Teacher::where('id', $id)->update($request->toArray());
        return response()->json('تغییرات با موفقیت انجام شد');
    }

    public function delete($id)
    {
        $teacher = Teacher::where('id', $id)->delete();
        return response()->json($teacher);
    }
}
