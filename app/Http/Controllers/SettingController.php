<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Currency;
use App\Models\TimeZone;
use Illuminate\Http\Request;
use App\Traits\ImageSaveTrait;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

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
            $setting->save();   // Save the updated setting

            // Call the helper to update the app settings in .env and config files
            SettingsHelper::setEnvironmentValue('APP_NAME', $request->company_name);
            SettingsHelper::setEnvironmentValue('APP_DEBUG', $request->debug_mode);
            SettingsHelper::setEnvironmentValue('APP_URL', $request->app_url);

            return response()->json(['status' => 'success', 'message' => 'Settings updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function setting_reload()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
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
}
