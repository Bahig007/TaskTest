<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminTaskRequest;
use App\Models\Task;
use App\Repository\Facades\TaskFacade;
use Illuminate\Http\Request;    

class TaskController extends Controller
{
    public function index(Request $request){
 
        $filters = $request->input('filter', []);
        $tasks = TaskFacade::getTasks($filters);
        if(!$tasks['success']){
            return response()->json(['success' => false, 'message' => $tasks['message']]);
        }
        return response()->json(['success' => true, 'data' => $tasks['data']]);
        
    }

    public function store(AdminTaskRequest $request){

       $validatedData = $request->validated();
  
        $task = TaskFacade::store($validatedData);
        if(!$task['success']){
            return response()->json(['success' => false, 'message' => $task['message']]);
        }
        return response()->json(['success' => true, 'data' => $task['data']]);
    }

    public function show($id){
        $task = TaskFacade::show($id);
        if(!$task['success']){
            return response()->json(['success' => false, 'message' => $task['message']]);
        }
        return response()->json(['success' => true, 'data' => $task['data']]);
    }

    public function update(AdminTaskRequest $request, $id){
        $validatedData = $request->validated();
        $task = TaskFacade::update($validatedData, $id);
        if(!$task['success']){
            return response()->json(['success' => false, 'message' => $task['message']]);
        }
        return response()->json(['success' => true, 'data' => $task['data']]);
    }

    public function destroy($id){
        $task = TaskFacade::destroy($id);
        if(!$task['success']){
            return response()->json(['success' => false, 'message' => $task['message']]);
        }
        return response()->json(['success' => true, 'message' => $task['message']]);
    }
}