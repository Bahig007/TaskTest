<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTaskRequest;
use App\Models\Task;
use App\Repository\Facades\TaskFacade;
use Illuminate\Http\Request;    

class TaskController extends Controller
{
    public function index(Request $request){
 
        $filters = $request->input('filter', []);
        $filters['user_id'] = auth('user')->user()->id;
        $tasks = TaskFacade::getTasks($filters);
        if(!$tasks['success']){
            return response()->json(['success' => false, 'message' => $tasks['message']]);
        }
        return response()->json(['success' => true, 'data' => $tasks['data']]);
        
    }

    public function store(UserTaskRequest $request){

       $validatedData = $request->validated();
        $validatedData['user_id'] = auth('user')->user()->id;
        $task = TaskFacade::store($validatedData);
        if(!$task['success']){
            return response()->json(['success' => false, 'message' => $task['message']]);
        }
        return response()->json(['success' => true, 'data' => $task['data']]);
    }

    public function show($id){
        $task = Task::find($id);
        $this->authorize('show', $task);
        $task = TaskFacade::show($id);
        if(!$task['success']){
            return response()->json(['success' => false, 'message' => $task['message']]);
        }
        return response()->json(['success' => true, 'data' => $task['data']]);
    }

    public function update(UserTaskRequest $request, $id){
        $task = Task::find($id);
        $this->authorize('update', $task);
        $validatedData = $request->validated();
        $task = TaskFacade::update($validatedData, $id);
        if(!$task['success']){
            return response()->json(['success' => false, 'message' => $task['message']]);
        }
        return response()->json(['success' => true, 'data' => $task['data']]);
    }

    public function destroy($id){
        $task = Task::find($id);
        $this->authorize('delete', $task);
        $task = TaskFacade::destroy($id);
        if(!$task['success']){
            return response()->json(['success' => false, 'message' => $task['message']]);
        }
        return response()->json(['success' => true, 'message' => $task['message']]);
    }
}