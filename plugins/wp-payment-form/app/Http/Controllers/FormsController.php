<?php


namespace WPPayForm\App\Http\Controllers;

use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\DemoForms;
use WPPayForm\App\Services\GlobalTools;
use WPPayForm\Framework\Support\Arr;

class FormsController extends Controller
{
    public function index()
    {
        try {
            return Form::index($this->request->all());
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }
    }

    public function migrateOrderItems()
    {
        return Form::migrate();
    }

    public function store()
    {
        try {
            $formId = Form::storeData($this->request->all());
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }

        return array(
            'message' => __('Form successfully created.', 'wp-payment-form'),
            'form_id' => $formId
        );
    }

    public function demo()
    {
        try {
            $forms  = DemoForms::getDemoForms();
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }

        return array(
            'demo_forms' => Arr::get($forms, 'forms', []),
            'categories' => array_unique(Arr::get($forms, 'categories'))
        );
    }

    public function formatted()
    {
        try {
            $forms  = Form::getAllAvailableForms();
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => __('Form query error', 'wp-payment-form')
            ], 423);
        }

        return array(
            'available_forms' => $forms
        );
    }

    public function import()
    {
        try {
           return (new GlobalTools())->handleImportForm();
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }
    }
}
