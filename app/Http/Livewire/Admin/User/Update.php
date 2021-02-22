<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;

class Update extends Component
{
  public $user;

  public $name;
  public $email;
  
  protected $rules = [
    'name' => 'required',
    'email' => 'required|email',    
  ];

  public function mount(User $user)
  {
    $this->user = $user;
    $this->name = $this->user->name;
    $this->email = $this->user->email;    
  }

  public function updated($input)
  {
    $this->validateOnly($input);
  }

  public function update()
  {
    $this->validate();

    $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => __('UpdatedMessage', ['name' => __('User')])]);
    
    $this->user->update([
      'name' => $this->name,
      'email' => $this->email,
    ]);
  }

  public function render()
  {
    return view('livewire.admin.user.update', [
      'user' => $this->user
    ])->layout('admin::layouts.app');
  }
}
