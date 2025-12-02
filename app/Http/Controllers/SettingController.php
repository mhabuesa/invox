<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Currency;
use App\Models\TimeZone;
use Illuminate\Http\Request;
use App\Traits\ImageSaveTrait;
use App\Helpers\SettingsHelper;
use App\Models\InvoiceSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    // Permissions Method
    public function __construct()
    {
        $this->setPermissions([
            'index'   => 'setting',
        ]);
    }
    // Include the ImageSaveTrait to gain access to image saving methods
    use ImageSaveTrait;


    public function setting()
    {
        $time_zones = TimeZone::all();
        $setting = Setting::first();
        return view('setting.index', [
            'time_zones' => $time_zones,
            'setting' => $setting
        ]);
    }
    public function setting_update(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'company_name' => 'required',    // Company name is required
                'phone' => 'required',           // Phone number is required
                'email' => 'required',           // Email is required
                'address' => 'required',         // Address is required
                'logo' => 'nullable|image',      // Logo is optional but must be an image if provided
                'favicon' => 'nullable|image',   // Favicon is optional but must be an image if provided
            ]);


            // Retrieve the first or create a new setting record
            $setting = Setting::firstOrNew([]);

            // If a logo is uploaded, save the image and get its name
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if (!empty($setting->logo)) {
                    $this->deleteImage(public_path($setting->logo));
                }
                $logo_name = $this->saveImage('web', $request->file('logo'));
            }

            // If a favicon is uploaded, save the image and get its name
            if ($request->hasFile('favicon')) {
                // Delete old favicon if exists
                if (!empty($setting->favicon)) {
                    $this->deleteImage(public_path($setting->favicon));
                }
                $favicon_name = $this->saveImage('web', $request->file('favicon'));
            }

            // Update the setting model with the new values from the request
            $setting->company_name = $request->company_name;
            $setting->phone = $request->phone;
            $setting->email = $request->email;
            $setting->address = $request->address;
            $setting->logo = $logo_name ?? $setting->logo;   // Update logo if provided, otherwise keep the existing value
            $setting->favicon = $favicon_name ?? $setting->favicon;   // Update favicon if provided, otherwise keep the existing value
            $setting->app_url = $request->app_url;
            $setting->debug_mode = $request->debug_mode;
            $setting->time_zone = $request->time_zone;
            $setting->email_userName = $request->email_userName;
            $setting->app_password = $request->app_password;
            $setting->save();   // Save the updated setting

            // Call the helper to update the app settings in .env and config files
            SettingsHelper::setEnvironmentValue('APP_NAME', $request->company_name);
            SettingsHelper::setEnvironmentValue('APP_DEBUG', $request->debug_mode);
            SettingsHelper::setEnvironmentValue('APP_URL', $request->app_url);
            SettingsHelper::setEnvironmentValue('MAIL_USERNAME', $request->email_userName);
            SettingsHelper::setEnvironmentValue('MAIL_FROM_ADDRESS', $request->email_userName);
            SettingsHelper::setEnvironmentValue('MAIL_PASSWORD', $request->app_password);


            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function setting_reload()
    {
        // Clear cache and config
        Artisan::call('optimize:clear');

        return redirect()->route('setting.index')->with('success', 'Settings Updated Successfully');
    }


    // Currency
    public function currencies()
    {
        $currencies = Currency::orderBy('status', 'desc')->get();
        return view('setting.currencies', [
            'currencies' => $currencies,
        ]);
    }

    public function currency_store(Request $request)
    {

        $request->validate([
            'country' => 'required|unique:currencies,country',
            'symbol' => 'required',
        ]);

        Currency::create([
            'country' => $request->country,
            'currency' => $request->currency,
            'code' => $request->code,
            'symbol' => $request->symbol,
        ]);

        return redirect()->route('setting.currencies')->with('success', 'Currency Created Successfully');
    }

    public function currency_update(Request $request, Currency $currency)
    {

        $request->validate([
            'country' => 'required',
            'symbol' => 'required',
        ]);

        $currency->update([
            'country' => $request->country,
            'currency' => $request->currency,
            'code' => $request->code,
            'symbol' => $request->symbol,
        ]);

        return redirect()->route('setting.currencies')->with('success', 'Currency Updated Successfully');
    }

    public function currency_status_update($id)
    {
        $currency = Currency::findOrFail($id);

        try {
            // If the current currency is being activated, deactivate all others
            if ($currency->status == 0) {
                Currency::where('id', '!=', $currency->id)->update(['status' => 0]);
                $currency->update(['status' => 1]);
            } else {
                // Otherwise, just deactivate the current one
                $currency->update(['status' => 0]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Currency status updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating currency status.'
            ], 500);
        }
    }


    public function currency_destroy(Currency $currency)
    {
        try {
            // Delete Tax
            $currency->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Currency Deleted Successfully'], 200);
    }
    public function invoice_setting()
    {
        return view('setting.invoice_setting', [
            'info' => InvoiceSetting::first(),
        ]);
    }
    public function invoice_setting_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'designation' => 'required',
        ]);

        $previous_info = InvoiceSetting::first();

        $authorized_status = $request->authorized_status ? 1 : 0;
        $terms_status = $request->terms_status ? 1 : 0;

        if ($request->hasFile('signature')) {
            // only delete if previous exists
            if ($previous_info && $previous_info->signature != null) {
                $this->deleteImage('invoice_setting', $previous_info->signature);
            }
            $signature_name = $this->saveImage('invoice_setting', $request->file('signature'));
        }

        InvoiceSetting::updateOrCreate(
            ['id' => 1],
            [
                'name' => $request->name,
                'designation' => $request->designation,
                'signature' => $signature_name ?? ($previous_info->signature ?? null),
                'authorized_status' => $authorized_status,
                'terms' => $request->terms,
                'terms_status' => $terms_status,
            ]
        );

        return redirect()->route('setting.invoice')->with('success', 'Invoice Setting Updated Successfully');
    }


    public function remove_signature()
    {
        // Remove signature logic
        $invoiceSetting = InvoiceSetting::first();
        if ($invoiceSetting && $invoiceSetting->signature) {
            $this->deleteImage('invoice_setting', $invoiceSetting->signature);
            $invoiceSetting->signature = null;
            $invoiceSetting->save();
            return response()->json([
                'success' => true,
                'message' => 'Signature removed successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No signature found to remove.'
            ], 404);
        }
    }
}
