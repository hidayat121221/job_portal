<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index(Request $request){    

        $categories = Category::where('status',1)->get();
        $jobtypes = JobType::where('status',1)->get();

        $jobs =Job::where('status',1);
        if(!empty($request->keyword)){
            $job = $jobs->where(function($query) use($request){
                $query->orWhere('title','like','%'.$request->keyword.'%');
            });
        }

        if(!empty($request->loacation)){
            $job = $jobs->where('location',$request->location);
        }

        if(!empty($request->category)){
            $job = $jobs->where('category_id',$request->category);
        }

        $jobs = $jobs->with('jobType')->orderBy('created_at','DESC')->paginate(9);

        return view('front.jobs',[
            'categories' => $categories,
            'jobtypes' => $jobtypes,
            'jobs' => $jobs
        ]);
    }
}
