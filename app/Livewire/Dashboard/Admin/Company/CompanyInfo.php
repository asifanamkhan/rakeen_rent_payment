<?php

namespace App\Livewire\Dashboard\Admin\Company;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class CompanyInfo extends Component
{
    public $update = false;
    public $isTure = false;
    public $company;
    public $photos = [];
    public $editPhotos = [];
    public $state = [];

    public function render()
    {
        return view('livewire.dashboard.admin.company.company-info');
    }

    public function mount()
    {
        $this->state = (array)DB::table('HRM_COMPANY_INFO')
            ->first();

        if (@$this->state['logo']) {
            $this->editPhotos = json_decode($this->state['logo']);
        }
    }

    // public function updatedIsTure(){
    //     if($this->isTure){
    //         $this->update = true;
    //     }else{
    //         $this->update = false;
    //     }
    // }


    public function save()
    {

        Validator::make($this->state, [
            'comp_name' => 'required',
            'comp_add' => 'required',
            'comp_phone' => 'required',
            'comp_email' => 'required',
        ])->validate();

        if (count($this->photos)) {
            foreach ($this->photos as $photo) {
                $file_name = time() . '-' . mt_rand() . '.' . $photo['extension'];
                Storage::putFileAs('upload/company', new File($photo['path']), $file_name);
                $allFile[] = $file_name;
            }
            $this->state['logo'] = json_encode($allFile);
        }

        $this->editPhotos = json_decode($this->state['logo']);
        $this->photos = [];

        DB::table('HRM_COMPANY_INFO')->where('com_id', $this->state['com_id'])
            ->update($this->state);

        session()->flash('status', 'Company information updated successfully.');
    }
}
