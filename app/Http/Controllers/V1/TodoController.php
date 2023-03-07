<?php

namespace App\Http\Controllers\V1;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * API FOR List Todo
     * @param Request $request
     * @return json Data
     */
    public function list()
    {
        $todos = Todo::get();
        return response()->json([
            'message'   => 'Todo List',
            'todo'      => $todos
        ]);
    }
    /**
     * API FOR Create Todo
     * @param Request $request
     * @return json Data
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:40',
            'description' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'error'   => $validator->errors()
            ]);
        }
        $todo = Todo::create($request->only('title', 'description'));
        return response()->json([
            'message' => 'Todo Add Successfully',
            'todo'    => $todo
        ]);
    }
    /**
     * API FOR Update Todo
     * @param Request $request,$id
     * @return json Data
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:40',
            'description' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'error'   => $validator->errors()
            ]);
        }
        $todo = Todo::findOrFail($id);
        $todo->update($request->only('title', 'description'));
        return response()->json([
            'message' => 'Todo Updated Successfully',
            'todo'    => $todo
        ]);
    }
    /**
     * API FOR Delete Todo
     * @param $id
     * @return json Data
     */
    public function delete($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        return response()->json([
            'message' => 'Todo Deleted Successfully',
            'todo'    => $todo
        ]);
    }
    /**
     * API FOR Get Todo
     * @param $id
     * @return json Data
     */
    public function get($id)
    {
        $todo = Todo::findOrFail($id);
        return response()->json([
            'message' => 'Todo Get Successfully',
            'todo'    => $todo
        ]);
    }
}
