<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\Job;
use illuminate\Support\Facades\Auth;
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
    public function detail($id){
        $job = Job::where([
                'id'=> $id,
                'status'=> 1
        ])->with('jobType','category')->first();

        if($job == null){
            abort(404);
        }

        return view('front.jobDetail',['job'=>$job]);
    }

    public function applyJob(Request $request){
        $id = $request->id;
        $job = Job::where('id','$id')->first();

        if($job == null){
            session()->flash('error','Job does not Exits'); 
            return response()->json([
            'status' => false,  
            'message' => 'Job does not Exits'
            ]);
        }

        $employer_id = $job->user_id;
        if($employer_id == Auth::user()->id){
            session()->flash('error','You can not Applay on your own job'); 
            return response()->json([
            'status' => false,  
            'message' => 'YOu can not applay in your own job'
            ]);
        }

        $jobApplicationCount = JobApplication::where([
            'user_id'=> Auth::user()->id,
            'job_id' => $id
        ])->count();
        
        if($jobApplicationCount > 0){
            session()->flash('error','You alreaday applied on this job'); 
            return response()->json([
            'status' => false,  
            'message' => 'You alreaday applied on this job'
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id  =$employer_id;
        $application->applied_date = now();
        $application->save();

        session()->flash('success','You have successfuly applied'); 
            return response()->json([
            'status' => true,  
            'message' => 'You have successfuly applied'
            ]);
    }
}
