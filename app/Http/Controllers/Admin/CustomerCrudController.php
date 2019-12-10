<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CustomerRequest as StoreRequest;
use App\Http\Requests\CustomerRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Models\Tag;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CustomerCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Customer');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/customer');
        $this->crud->setEntityNameStrings('customer', 'customers');
        $this->crud->enableExportButtons();
        $this->crud->addButtonFromView('line', 'mailto', 'mailto', 'beginning');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        $this->crud->addColumn([
            // 1-n relationship
            'label' => "Tag", // Table column heading
            'type' => "select",
            'name' => 'tag_id', // the column that contains the ID of that connected entity;
            'entity' => 'tag', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Models\Tag", // foreign key model
         ]);

        $this->crud->addField([  // Select2
            'label' => "Tag",
            'type' => 'select2',
            'name' => 'tag_id', // the db column for the foreign key
            'entity' => 'tag', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Tag", // foreign key model         
         ]);

         $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'name',
            'label'=> 'Name'
          ], 
          false, 
          function($value) { // if the filter is active
              $this->crud->addClause('where', 'name', 'LIKE', "%$value%");
          } );

          $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'email',
            'label'=> 'Email'
          ], 
          false, 
          function($value) { // if the filter is active
              $this->crud->addClause('where', 'email', 'LIKE', "%$value%");
          } );

          $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'phone',
            'label'=> 'Phone'
          ], 
          false, 
          function($value) { // if the filter is active
              $this->crud->addClause('where', 'phone', 'LIKE', "%$value%");
          } );

          $this->crud->addFilter([ // select2 filter
            'name' => 'tag',
            'type' => 'select2',
            'label'=> 'Tag'
          ], function() {
              return Tag::all()->keyBy('id')->pluck('name', 'id')->toArray();
          }, function($value) { // if the filter is active
              $this->crud->addClause('where', 'tag_id', $value);
          });


        // add asterisk for fields that are required in CustomerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
