<?php

namespace Ahmedjoda\JodaResources;

use LogicException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;

trait JodaResources
{
    final public function __construct()
    {
        $this->initAttributeNames();
    }



    public function index()
    {
        if (method_exists($this, 'query')) {
            ${$this->pluralName} = $this->query($this->model::query())->get();
        } else {
            ${$this->pluralName} = $this->model::all();
        }
        
        $index = ${$this->pluralName};
        $route = $this->route;
        return view("{$this->view}.index", compact($this->pluralName, 'index', 'route'));
    }


    public function create()
    {
        $route = $this->route;
        $title = trans('admin.create');
        return view("{$this->view}.create", compact('route', 'title'));
    }


    public function store()
    {
        $this->validateStoreRequest();
        
        $this->beforeStore();
        $data = $this->uploadFilesIfExist();
        $this->model::create($data);

        $this->afterStore();

        session()->flash('success', trans('admin.added'));

        return redirect(route("$this->route.index"));
    }


    public function show($id)
    {
        ${$this->name} = $this->model::find($id);
        $show = $this->model::find($id);
        $title = trans('admin.show');
        return view("$this->view.show", compact($this->name, 'show', 'title'));
    }


    public function edit($id)
    {
        ${$this->name} = $this->model::find($id);
        $edit = $this->model::find($id);
        $route = $this->route;
        $title = trans('admin.edit');
        return view("$this->view.edit", compact($this->name, 'edit', 'route', 'title'));
    }


    public function update($id)
    {
        $this->validateUpdateRequest();
        
        $this->beforeUpdate();

        $data = $this->uploadFilesIfExist();
        $this->model::find($id)->update($data);

        $this->afterUpdate();

        session()->flash('success', trans('admin.updated'));

        return redirect(route("$this->route.index"));
    }


    public function destroy($id)
    {
        $this->beforeDestroy();

        ${$this->name}  = $this->model::find($id);
        $this->deleteFilesIfExist(${$this->name});
        ${$this->name}->delete();

        $this->afterDestroy();

        session()->flash('success', trans('admin.deleted'));

        return redirect(route("$this->route.index"));
    }


    public function initAttributeNames()
    {
        if (!isset($this->model)) {
            throw new LogicException(get_class($this) . ' is using JodaResources it must have at least a $model value');
        }

        $array = explode('\\', $this->model);
        $name = lcfirst(end($array));
        $this->kebabName = $this->fromPascalCaseToKebabCase(end($array));
        
        if (!isset($this->name)) {
            $this->name = $name;
            $this->pluralName = Str::plural($this->name);
            $this->pluralKebabName = Str::kebab($this->pluralName);
            $this->pluralSnakeName = Str::snake($this->pluralName);
        }

        $reflector = new ReflectionClass($this);
        $namespace = Str::lower(str_replace('App\Http\Controllers\\', '', $reflector->getNamespaceName())) ;
        
        if (!isset($this->view)) {
            $this->view = "$namespace.$this->kebabName";
        }

        if (!isset($this->route)) {
            $this->route = "$namespace.$this->pluralSnakeName"  ;
        }
    }


    public function validateStoreRequest()
    {
        $rules = isset($this->model::$storeRules) ? $this->model::$storeRules :
            (isset($this->model::$rules) ? $this->model::$rules : null);
        if ($rules) {
            request()->validate($rules);
        }
    }
    
    public function validateUpdateRequest()
    {
        $rules = isset($this->model::$updateRules) ? $this->model::$updateRules :
            (isset($this->model::$rules) ? $this->model::$rules : null);
        if ($rules) {
            request()->validate($rules);
        }
    }

    public function uploadFilesIfExist()
    {
        $data = request()->except("_token", '_method');
        if (isset($this->files)) {
            foreach ($this->files as $file) {
                if (request()->hasFile($file) and request()->$file) {
                    $fileName =
                        (auth()->user() ? auth()->user()->id : '') . '-' .
                        time() . '.' .
                        request()->file($file)->getClientOriginalExtension();
                    $filePath = "$this->pluralName/$fileName";
                    $data[$file] = $filePath;
                    Storage::disk('local')->put($filePath, file_get_contents(request()->$file));
                }
            }
        }
        return $data;
    }
    public function deleteFilesIfExist($model)
    {
        if (isset($this->files)) {
            foreach ($this->files as $file) {
                Storage::delete($model->$file);
            }
        }
    }

    public function beforeStore()
    {
    }

    public function afterStore()
    {
    }
    public function beforeUpdate()
    {
    }

    public function afterUpdate()
    {
    }
    public function beforeDestroy()
    {
    }

    public function afterDestroy()
    {
    }
}
