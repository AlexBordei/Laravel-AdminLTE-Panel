<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all();

        foreach ($students as $key => $student) {
            $phones = array_filter([$student->phone, $student->phone2], function($e) {
                return !empty($e);
            });

            $students[$key]->phones = implode('/', $phones);
        }
        return $this->buildResponse('student.list', $students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->buildResponse('student.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required_without:phone2|max:255',
            'phone2' => 'required_without:phone|max:255',
            'email' => 'required|email|max:255',
        ]);

        $data = $request->all();

        if(!empty($request->get('birth_date'))) {
            $date = Carbon::createFromFormat('d/m/Y', $request->get('birth_date'));
            $data['birth_date'] = $date->format('Y-m-d');
        }

        Student::create($data);

        return redirect('/student')->with('success', 'Student has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        if(isset($student->birth_date)) {
            $date = strtotime($student->birth_date);
            $student->birth_date = Date('d/m/Y', $date);
        }

        return $this->buildResponse('student.edit', $student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required_without:phone2|max:255',
            'phone2' => 'required_without:phone|max:255',
            'email' => 'required|email|max:255',
        ]);

        $data = $request->all();

        if(!empty($request->get('birth_date'))) {
            $date = Carbon::createFromFormat('d/m/Y', $request->get('birth_date'));
            $data['birth_date'] = $date->format('Y-m-d');
        }

        $student->fill($data)->save();

        return back()->with('success', 'Student successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Student $student)
    {
        try {
            $student->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Student deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error deleting the student!']);
        }
    }
}
