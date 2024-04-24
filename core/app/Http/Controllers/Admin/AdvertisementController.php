<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::paginate(getPaginate(10));
        $pageTitle = 'All Advertisements';
        return view('admin.advertisement.index', compact('advertisements', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $validation = [
            'type'               => 'required',
            'size'               => 'required',
            'image'              => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png', 'gif'])],
        ];

        $conditionalValidation = $this->advertisementValidation($request);


        $validation = array_merge($validation, $conditionalValidation);

        $request->validate($validation);

        if ($request->hasFile('image')) {
            $value = fileUploader($request->file('image'), getFilePath('advertisement'));
        } else {
            $value = $request->script;
        }

        $advertisement = new Advertisement();
        $advertisement->type = $request->type;
        $advertisement->value = $value;
        $advertisement->size = $request->size;
        $advertisement->redirect_url = $request->type == 'image' ? ($request->redirect_url ?? '#') : '#';
        $advertisement->save();

        $notify[] = ['success', 'Advertisement added successfully'];
        return redirect()->route('admin.advertisement.index')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $validation = [
            'type'               => 'required',
            'size'               => 'required',
            'image'              => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png', 'gif'])],
        ];

        $advertisement = Advertisement::findOrFail($id);

        $conditionalValidation = [];

        if ($request->type == "image"  && $request->hasFile('image') || $request->size != $advertisement->image_size || $advertisement->type = 'script' && $request->type == 'image'){
            $conditionalValidation = $this->advertisementValidation($request, 'nullable');
        }

        $validation = array_merge($validation, $conditionalValidation);
        $request->validate($validation);

        $value = $advertisement->value;
        if ($request->hasFile('image')) {
            $value = fileUploader($request->file('image'), getFilePath('advertisement'), null, $advertisement->value);
        }
        if ($request->type == "script") {
            $value = $request->script;
            if ($advertisement->type == 'image') {
                fileManager()->removeFile(getFilePath('advertisement').'/'.$advertisement->value);
            }
        }
        $advertisement->type = $request->type;
        $advertisement->value = $value;
        $advertisement->size = $request->size;
        $advertisement->redirect_url = $request->type == 'image' ? $request->redirect_url : '#';
        $advertisement->status = $request->status ? 1 : 0;
        $advertisement->save();
        $notify[] = ['success', 'Advertisement updated successfully'];
        return redirect()->route('admin.advertisement.index')->withNotify($notify);
    }

    public function delete(Request $request)
    {
        $advertisement = Advertisement::findOrFail($request->advertisement_id);
        if ($advertisement->type == 'image') {
            fileManager()->removeFile(getFilePath('advertisement').'/'.$advertisement->value);
        }
        $advertisement->delete();

        $notify[] = ['success', 'Advertisement deleted successfully'];
        return back()->withNotify($notify);
    }

    public function advertisementValidation($request, $imgValidation = 'required')
    {
        $validation = [];
        if ($request->type == "image") {
            $size = explode('x', $request->size);
            $validation = [
                'size'               => 'required',
                'image'              => [$imgValidation, 'image', 'dimensions:width=' . $size[0] . ',height=' . $size[1]],
            ];
        } else {
            $validation = [
                'script'              => 'required',
            ];
        }

        return $validation;
    }
}
