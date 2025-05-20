<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TimeZone;
use Illuminate\Http\Request;
use App\Traits\ImageSaveTrait;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\SettingsHelper;
use App\Models\Currency;

class SettingController extends Controller
{
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
        $setting->save();   // Save the updated setting

        // Call the helper to update the app settings in .env and config files
        SettingsHelper::updateAppSettings($request);

        return redirect()->route('setting.index')->with('success', 'Setting Updated Successfully');
    }

    public function currencies()
    {

        $currencies = Currency::all();
        return view('setting.currencies', [
            'currencies' => $currencies,
        ]);
    }
}
