<?php

namespace App\Livewire;
use App\Models\EmployeeDetails;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Livewire\Component;

class Home extends Component
{   
    public $currentDate;
    public $currentDay;
    public $showAlertDialog=false;
    public $signIn = true;
    public $swipeDetails;

    public $employeeDetails;
    public function toggleSignState()
    {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
        $this->signIn = !$this->signIn;
        SwipeRecord::create([
            'emp_id'=>$this->employeeDetails->emp_id,
            'swipe_time' =>now()->format('H:i:s'),
            'in_or_out' => $this->signIn ? "Sign In" : "Sign Out",
        ]);
        $flashMessage = $this->signIn ? "You Have Successfully Signed In." : "You Have Successfully Signed Out.";
        session()->flash('success', $flashMessage);
    }

    public function open(){
     $this->showAlertDialog=true;
    }
    public function close()
    {
        $this->showAlertDialog = false;
    }
    
    
    public function render()
    {
        $this->currentDay = now()->format('l');
        $this->currentDate = now()->format('d M Y');
        $today = Carbon::now()->format('Y-m-d');

        $this->swipeDetails = SwipeRecord::whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('livewire.home');
    }
}
