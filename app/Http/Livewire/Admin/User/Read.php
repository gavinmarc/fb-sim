<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Read extends Component
{
  use WithPagination;

  protected $paginationTheme = 'bootstrap';

  public $search;

  protected $queryString = ['search'];

  protected $listeners = ['userDeleted'];

  public $sortType;
  public $sortColumn;

  public function userDeleted() {}

  public function sort($column)
  {
    $sort = $this->sortType == 'desc' ? 'asc' : 'desc';

    $this->sortColumn = $column;
    $this->sortType = $sort;
  }

  public function render()
  {
    $data = User::query();

    if(config('easy_panel.crud.user.search')) {
      $data = $this->addSearchQuery($data, config('easy_panel.crud.user.search'));
    }

    if($this->sortColumn) {
      $data->orderBy($this->sortColumn, $this->sortType);
    } else {
      $data->latest('id');
    }

    $data = $data->paginate(config('easy_panel.pagination_count', 15));

    return view('livewire.admin.user.read', [
      'users' => $data
    ])->layout('admin::layouts.app');
  }

  public function addSearchQuery(Builder $query, $attributes)
  {
    $attributes = collect($attributes);

    $query->where(function (Builder $query) use ($attributes) {
      foreach ($attributes as $item) {
        if(!is_array($item)) {
          $query->orWhere($item, 'like', "%{$this->search}%");
        } else {
          $query->orWhereHas(array_key_first($item), function (Builder $query) use ($item) {
            $query->where($item[array_key_first($item)], 'like', "%{$this->search}%");
          });
        }
      }
    });

    return $query;
  }
}
